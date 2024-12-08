# import sys
# sys.path = ['/Users/kfl/on-board-program'] + sys.path
from typing import cast
from common.component import Component
from threading import Thread
from multiprocessing import Process, Lock, Queue
from common.event import *
import time
from config.common import SENSOR_SITE, PI_BLE_MAC_ADDR
from utils.beacon_parser import MTRBeaconParser
from common.data_type import *
import importlib
from estimator.preprocessor import SensorDataPreprocessor
from estimator.localization_pf import LocalizationModule
from utils.utils import kill_thread
import traceback

from utils.logger import logger_gen
logger = logger_gen('estimator')


class Estimator(Component):
    def __init__(self):
        super().__init__()
        self.add_event_listener(RemoteNotifyIdEvent._event_type, self.on_receive_remote_notification)
        self.add_event_listener("remote_request", self.on_receive_remote_request)  # todo: for future remote control
        self.add_event_listener(SensorEvent._event_type, self.on_receive_sensor_data)  # note: don't know why it 
        self.mpu_vel_q = Queue()
        self.add_event_listener(StepEvent._event_type, self.on_receive_step_event)

        self.add_event_publisher(LogEvent._event_type)
        self.add_event_publisher(PositionEvent._event_type)
        self.add_event_publisher(RemoteRequestIdEvent._event_type)

        self.tracking_records = {}  # {target_identifier: {process_id, preprocessor_thread_handle, target_data_input_q}}
        self.process_records = {}  # {process_id: {target_nums, target_q, batch_input_q, result_q, log_q, terminate_q}}
        self.estimator_process_log_q = Queue()
        self.output_result_q = Queue()  # use the same one for all processes and threads

        self.site = SENSOR_SITE

        self.__site_config = None
        

    def on_receive_step_event(self, step_event: StepEvent):
        step = step_event.value
        self.mpu_vel_q.put(step)

    def start(self):
        # note: core would initialize its position based on the first few BLE inputs, if no BLE inputs, then no init pos
        Thread(target=self._poll_result_q).start()
        self._try_init_core(target_identifier=PI_BLE_MAC_ADDR)

    def restart(self, target_identifier):
        self._terminate_core(target_identifier)
        print(f'[estimator] try to restart')

        self._try_init_core(target_identifier)
        print("[estimator] successfully restart")

    def on_receive_sensor_data(self, event: SensorEvent):
        # note: define strategy to start a process or ask a process to start a thread
        # print(f"[estimator]: receive sensor data {event}")
        if self.__site_config:  # no estimation before knowing where the target is
            target_identifier = event.value.target_identifier
            if target_identifier not in self.tracking_records:  # new target
                self._register_tracking_target(target_identifier)

            if isinstance(event.value, BeaconDataPackage):
                self._on_receive_beacon_data(cast(BeaconDataPackage, event.value))
            # elif isinstance(event.value, IMUDataPackage):
            #     self._on_receive_imu_data(cast(IMUDataPackage, event.value))
            # elif isinstance(event.value, WIFIDataPackage):
            #     self._on_receive_wifi_data(cast(WIFIDataPackage, event.value))

    def _on_receive_beacon_data(self, beacon_data_package: BeaconDataPackage):
        target_identifier = beacon_data_package.target_identifier
        if target_identifier not in self.tracking_records:
            return

        major, minor = beacon_data_package.value.major, beacon_data_package.value.minor
        if not major or not minor:
            return

        try:
            major_minor = str(major) + str(minor)
        except Exception:
            return

        if major_minor not in self.__site_config.SOURCE_INFO_DICT:
            return

        source_info = self.__site_config.SOURCE_INFO_DICT[major_minor]
        if not source_info.activated:
            return

        # if not hasattr(beacon_data_package.value, 'beacon_type'):
        #     return
        # else:
        #     beacon_type = getattr(beacon_data_package.value, 'beacon_type')
        #     if beacon_type != MTRBeaconParser.BEACON_TYPE.BEACON_LOCALIZATION_TYPE.value:
        #         return

        # todo: retrieving source pos here is bad
        if isinstance(beacon_data_package.source.source_pos, Position):
            source_pos = beacon_data_package.source.source_pos
            x, y, z = source_pos.x, source_pos.y, source_pos.z
        else:
            x, y, z = source_info.x, source_info.y, source_info.z

        d = {'target_identifier': beacon_data_package.target_identifier,
             'data_type': beacon_data_package.data_type,
             'value':  {'rssi': beacon_data_package.value.rssi},
             'source': {'source_identifier': beacon_data_package.source.source_identifier,
                        'source_pos': {'x': x,
                                       'y': y,
                                       'z': z,
                                       'pos_type': 'non-lon-lat', },
                        },
             'timestamp': beacon_data_package.timestamp
             }

        sensor_data_input_q = self.tracking_records[target_identifier]['target_data_input_q']
        sensor_data_input_q.put(d)

    def _on_receive_imu_data(self, imu_data_package: IMUDataPackage):
        target_identifier = imu_data_package.target_identifier
        d = {'target_identifier': target_identifier,
             'data_type': imu_data_package.data_type,
             'value':  {'acc_x': imu_data_package.value.acc_x,
                        'acc_y': imu_data_package.value.acc_y,
                        'acc_z': imu_data_package.value.acc_z,
                        'gyro_x': imu_data_package.value.gyro_x,
                        'gyro_y': imu_data_package.value.gyro_y,
                        'gyro_z': imu_data_package.value.gyro_z,
                        'mag_x': imu_data_package.value.mag_x,
                        'mag_y': imu_data_package.value.mag_y,
                        'mag_z': imu_data_package.value.mag_z,
                        },
             'timestamp': imu_data_package.timestamp,
             }
        if target_identifier in self.tracking_records:
            sensor_data_input_q = self.tracking_records[target_identifier]['target_data_input_q']
            sensor_data_input_q.put(d)

    def _on_receive_wifi_data(self, wifi_data: WIFIDataPackage):
        pass

    def on_receive_remote_notification(self, event: RemoteNotifyIdEvent):
        print(f"[estimator]: receive remote request: {event}")
        if isinstance(event, RemoteNotifyIdEvent):
            if event.value == 'None' or event.value is None:
                self.site = None
                self.__site_config = None
                self._terminate_core(event.identifier)
            elif self.site != event.value:
                self.site = event.value
                self.restart(event.identifier)

    def on_receive_remote_request(self, event: Event):
        pass

    def _try_init_core(self, target_identifier):
        if self.site:
            self.__site_config = self._load_config(self.site)

        if self.__site_config:
            self.publish(LogEvent(identifier=target_identifier,
                                  value=LogMessage(level='info',
                                                   msg=f'load config = {self.__site_config}')))
            self._register_tracking_target(target_identifier)

    def _terminate_core(self, target_identifier):
        if target_identifier in self.tracking_records:
            tracking_process_id = self.tracking_records[target_identifier]['process_id']
            terminate_q = self.process_records[tracking_process_id]['terminate_q']
            terminate_q.put(target_identifier)
            self.process_records[tracking_process_id]['target_nums'] -= 1
        print(f'[estimator] trying to terminate {target_identifier}')

    def _poll_result_q(self):
        while True:
            # {identifier, x, y, z, timestamp}
            position = self.output_result_q.get()  # xyz
            # note: if you need different alarm strategy or velocity strategy for different target,
            #       you could still implement them in a separate module,
            #       because the PositionEvent has target_identifier that help you identify data
            # print(position['raw_p'])
            self.publish(PositionEvent(identifier=position['target_identifier'],
                                       value=Position(x=position['x'],
                                                      y=position['y'],
                                                      z=position['z'],
                                                      timestamp=position['timestamp'],
                                                      pos_type=0,
                                                      # raw_p=position['raw_p'],
                                                      raw_p=[]
                                                      )))

    def _register_tracking_target(self, target_identifier):
        pid = self._find_a_free_process()

        if not pid:
            pid = self._spawn_localization_estimator_process()
        self.process_records[pid]['target_nums'] += 1
        batch_q = self.process_records[pid]['batch_input_q']  # todo

        sensor_data_input_q = Queue()
        preprocess_thread = SensorDataPreprocessor(input_q=sensor_data_input_q,
                                                   batch_output_q=batch_q,
                                                   cal_period_sec=self.__site_config.CAL_PERIOD_SEC,
                                                   beacon_preprocessor_config={'rssi_threshold': self.__site_config.FILTER_MODULE_PARAMS['RSSI_THRESHOLD'],
                                                                               'activated_source_identifier_set': set([source_info.source_identifier for source_info in self.__site_config.SOURCE_INFO_DICT.values() if source_info.activated]),
                                                                               'source_identifier_validation_func_list': None,
                                                                               'equivalent_source_identifier_set': None,
                                                                               'avg_rssi_flag': False, },  # todo
                                                   imu_preprocessor_config={},
                                                   wifi_preprocessor_config={}, )
        t = Thread(target=preprocess_thread.run)
        t.start()

        self.tracking_records[target_identifier] = {'process_id': pid,
                                                    'preprocessor_thread': t,
                                                    'target_data_input_q': sensor_data_input_q}

        target_q = self.process_records[pid]['target_q']
        target_q.put({'target_identifier': target_identifier,
                      'algo_params': self.__site_config.LOCALIZATION_MODULE_PARAMS,
                      'map_constraints': self.__site_config.MAPS})

    def _spawn_localization_estimator_process(self):
        target_q, batch_q, terminate_q = Queue(), Queue(), Queue()

        estimator_process = EstimatorProcess(target_q=target_q,
                                             batch_input_q=batch_q,
                                             result_q=self.output_result_q,
                                             log_q=self.estimator_process_log_q,
                                             terminate_q=terminate_q,
                                             mpu_vel_q=self.mpu_vel_q)

        # todo: using fork so that it could estimator_process.run can be used?
        p = Process(target=estimator_process.run, args=(False,))
        p.start()

        pid = p.pid
        self.process_records[pid] = {'target_nums': 0,
                                     'target_q': target_q,
                                     'batch_input_q': batch_q,
                                     'result_q': self.output_result_q,
                                     'log_q': self.estimator_process_log_q,
                                     'terminate_q': terminate_q}

        return pid

    def _load_config(self, site):
        try:
            site_config = importlib.import_module(name=f'config.{site}')
        except Exception as err:
            self.publish(LogEvent(identifier=PI_BLE_MAC_ADDR,
                                  value=LogMessage(level='error',
                                                   msg=f"unable to load config for core: {traceback.format_exc()}")))
            return None
        return site_config

    def _find_a_free_process(self) -> Union[int, None]:
        min_target_nums = 1000
        suitable_pid = None
        for pid, process_info in self.process_records.items():
            if process_info['target_nums'] < 150 and process_info['target_nums'] < min_target_nums:
                min_target_nums = process_info['target_nums']
                suitable_pid = pid
        return suitable_pid

    def _get_source_pos(self, source_identifier):
        pass


class EstimatorProcess:
    # note: 1. responsible for creating core threads
    # note: 2. handle multiple targets
    # note: 3. each process has one batch_input_q
    def __init__(self, target_q: Queue, batch_input_q: Queue, result_q: Queue, log_q: Queue = None, terminate_q: Queue = None, mpu_vel_q:Queue= None):
        # note: every element is of form {'target_identifier': str, 'pos': {'x', 'y', 'z'}, 'timestamp': }
        self.result_q = result_q
        # note: every element is of form {'target_identifier', 'algo_params', 'map_constraints'}
        self.target_q = target_q
        # note: every element is of form [{'target_identifier': {'beacon_batch':[], 'imu_batch':[], 'wifi_batch':[]}}]
        self.batch_input_q = batch_input_q
        # note: every element is of form {'target_identifier', 'msg', 'timestamp'}
        self.log_q = log_q
        # note: every element is of form {'target_identifier'}
        self.terminate_q = terminate_q

        self._target_batch_input_q = {}

        self._register_target_lock = Lock()
        self._register_target = {}

        self.mpu_vel_q = mpu_vel_q
        self.vel_estimate = -1

    def run(self, daemon=True):
        # print("start a new process")
        t1 = Thread(target=self.redirect_batch_input_q)
        t2 = Thread(target=self.spawn_core_thread)
        t1.start()
        t2.start()
        if self.terminate_q:
            Thread(target=self.terminate_core_thread).start()
        if not daemon:
            t1.join()
            t2.join()

        # print("end a new process")

    def spawn_core_thread(self):
        while True:
            target_info = self.target_q.get()  # block
            target_ident = target_info['target_identifier']
            target_algo_params = target_info['algo_params']
            target_map_constraints = target_info['map_constraints']

            with self._register_target_lock:
                if target_ident not in self._register_target:
                    localization_core = LocalizationModule(algo_params=target_algo_params,
                                                           map_constraints=list(target_map_constraints.values()))

                    self._target_batch_input_q[target_ident] = Queue()
                    self._register_target[target_ident] = localization_core
                    # t = Thread(target=self.calculate, args=(target_ident, ))  # note: pay attention to the order
                    # t.start()
                    proc_write1 = Process(target=self.calculate, args=(target_ident,))
                    proc_write1.start()
            logger.info(f'Spawn_core_thread for target={target_ident}')

    def terminate_core_thread(self):
        while True:
            terminate_target = self.terminate_q.get()  # block
            target_ident = terminate_target['target_identifier']

            with self._register_target_lock:
                if target_ident in self._register_target:
                    # hack, modify the output format from list to dict and also add tmac info
                    t = self._register_target[target_ident]
                    try:
                        kill_thread(t)
                    except Exception:
                        pass
                    finally:
                        del self._register_target[target_ident]
            print(f'terminate_core_thread for target={target_ident}')

    def redirect_batch_input_q(self):
        while True:
            batches = self.batch_input_q.get()
            target_ident = batches['target_identifier']
            self._target_batch_input_q[target_ident].put(batches)

    def calculate(self, target_identifier):
        # todo: may conflict with terminate for now
        # todo: handle multiple sensor
        q = self._target_batch_input_q[target_identifier]
        core = self._register_target[target_identifier]
        while True:
            # cal_start_ts = time.time()
            batch_sensor_data = q.get()  # block
            beacon_batch = batch_sensor_data['beacon_batch']
            
            if self.mpu_vel_q.qsize() > 0:
                self.vel_estimate = self.mpu_vel_q.get()
            result = core.estimate_position(beacon_data_batch=beacon_batch, imu_data_batch=None, wifi_data_batch=None, mpu_vel_q=self.vel_estimate)
            result['target_identifier'] = target_identifier
            self.result_q.put(result)
            # cal_end_ts = time.time()
            assert 'x' in result and 'y' in result and 'z' in result and 'timestamp' in result
            # print(f'calc time = {cal_end_ts - cal_start_ts}s, end_ts = {cal_end_ts}')


if __name__ == '__main__':
    m = importlib.import_module(name=f'config.KOB')
    print(dir(m))
