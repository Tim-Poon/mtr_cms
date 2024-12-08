import time
import sys
from sensors.sensor import Sensor
from threading import Thread, Lock
from config.common import SensorType, SENSOR_SETTING, SENSOR_LIST  # todo: config cross too many layer
import traceback
from functools import partial
from config.common import PI_BLE_MAC_ADDR

from multiprocessing import Process, Queue
import os,time,random
import numpy as np


import sys, getopt
sys.path.append('.')
# import RTIMU
import sensors.RTIMU as RTIMU
import os.path
import time
import math
import socket
import threading
import json
import zlib

from utils.logger import logger_gen
logger = logger_gen('MPU')

class IMU(Sensor):
    def __init__(self, *args, **kwargs):

        super().__init__(*args, **kwargs)
        self.send = partial(self.send, sensor_type=SensorType.SENSOR_IMU)  # note: all inputs should use keyword args
        self.log = partial(self.log, sensor_type=SensorType.SENSOR_IMU)
        self.__sensor_handles = self.__init_sensors()  # todo
        self.__thread_dict = {}
        self.__active_flag_list = {sensor_name: False for sensor_name in self.__sensor_handles.keys()}

    def __init_sensors(self):

        if "test.mock.mock_sensors.mock_imu" not in SENSOR_LIST:
            self.log(level='info',
                     msg="Please ensure that the SENSOR_LIST variable in your config file has 'test.mock.mock_sensors.mock_imu'")

        if "sensors.imu" not in SENSOR_LIST:
            self.log(level='info',
                     msg="Please ensure that the SENSOR_LIST variable in your config file has 'sensors.imu'")

        sensors = {}
        try:
            sensors["sensors.imu"] = 'imu'
        except Exception as e:
            self.log(level='warning',
                     msg=f"Unable to init 'sensors.imu': {traceback.format_exc()}")
        else:
            self.log(level='info',
                     msg="init sensor.imu successfully")

        try:
            sensors["test.mock.mock_sensors.mock_imu"] = MOCKIMU()
        except Exception as e:
            self.log(level='warning',
                     msg=f"Unable to init 'test.mock.mock_sensors.mock_imu': {traceback.format_exc()}")
        else:
            self.log(level='info',
                     msg="init test.mock.mock_sensors.mock_imu success")

        return sensors

    def get_sensors_list(self):
        return self.__sensor_handles.keys()

    def activate(self, sensor: str, config: SENSOR_SETTING):
        self.log(level='info', msg=f"activate {sensor}")
        if sensor in self.__sensor_handles and self.__sensor_handles[sensor] is not None:
            sensor_name = sensor
        else:
            self.log(level="warning",
                     msg=f"unable to activate {sensor}")
            return

        if sensor_name not in self.__thread_dict:  # note: need to guarantee all these threads won't crash, because these threads will only start once.
            self.__thread_dict[sensor_name] = Thread(target=self.__read_sensor_data, args=(sensor_name, config))
            self.__thread_dict[sensor_name].start()

        self.__active_flag_list[sensor_name] = True  # note: gurantee that __active_flag is only changed by sensor_manager which is only one thread exist in this process

    def deactivate(self):
        self.__active_flag_list = {sensor_name: False for sensor_name, activate_status in self.__active_flag_list}

    def configure(self, config):
        pass

    @staticmethod
    def iimu(q, feq):
        SETTINGS_FILE = "RTIMULib"
        s = RTIMU.Settings(SETTINGS_FILE)
        imu = RTIMU.RTIMU(s)

        # T_SRV = 'mtr-uat-internal.smartsensing.biz'
        # T_SRV = '143.89.49.63'
        # T_PORT = 4003

        # try:
        #     sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        #     sock.settimeout(2)
        #     sock.connect((T_SRV, T_PORT))
        # except Exception as e:
        #     logger.error(f"Lost IMU server: {e}")

        start_flag = 0
        while not imu.IMUInit():
            time.sleep(3)
            s = RTIMU.Settings(SETTINGS_FILE)
            imu = RTIMU.RTIMU(s)
            start_flag += 1
            if start_flag > 10:
                break

        if (not imu.IMUInit()):
            logger.error(f'IMU Init Failed: {imu.IMUName()}')
            sys.exit(1)
        else:
            logger.info(f'IMU Init Succeeded: {imu.IMUName()}')

        idx = 0
        rate = 250*2
        coll_sec = 0
        ll = []

        decrease = 8 # / 500Hz
        decrease_start = 0
        decrease_ll = []
        decrease_rate = 250*2.5
        decrease_idx = 0

        imu.setSlerpPower(0.02)
        imu.setGyroEnable(True)
        imu.setAccelEnable(True)
        imu.setCompassEnable(False)
        poll_interval = imu.IMUGetPollInterval()
        try:
            while True:
                if imu.IMURead():
                    data = imu.getIMUData()
                    accel = data['accel']
                    gyro = data['gyro']
                    mag = data['compass']
                    f_post = data['fusionPose']
                    accl = imu.getAccelResiduals()
                    # self.send(values=accel + gyro + mag + f_post + accl)
                    recv_ts = round(time.time(),3)
                    ll.append([PI_BLE_MAC_ADDR,
                               round(accel[0], 3),
                               round(accel[1], 3),
                               round(accel[2], 3),
                               round(gyro[0], 3),
                               round(gyro[1], 3),
                               round(gyro[2], 3),
                               0,
                               0,
                               0,
                               round(f_post[0], 3),
                               round(f_post[1], 3),
                               round(f_post[2], 3),
                               round(accl[0], 3),
                               round(accl[1], 3),
                               round(accl[2], 3),
                               recv_ts
                              ])
                    decrease_start += 1
                    if decrease_start == decrease:
                        g = np.sqrt(np.square(np.array([accel[0],accel[1],accel[2]])).sum())
                        decrease_ll.append(round(g,3))
                        decrease_start = 0

                    decrease_idx += 1
                    if decrease_idx > decrease_rate:
                        q.put(decrease_ll)
                        decrease_ll = []
                        decrease_idx = 0

                    idx += 1
                    if idx > rate:
                        coll_sec += 1
                        # print('coll pkg =', rate, 'count=', coll_sec, 'seconds')
                        # 
                        pkg = json.dumps(ll).encode('utf-8')
                        compressed_data = zlib.compress(pkg)
                        compressed_data += b'/'
                        # print('org pkg =', len(pkg), 'compressed =', len(compressed_data))
                        # try:
                        #     sock.sendall(compressed_data)
                        # except Exception as e:
                        #     logger.error(f'Lost MPU server when SENDING: {e}')
                        #     try:
                        #         logger.warning(f'Reconnecting to MPU server')
                        #         sock.close()
                        #         sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
                        #         sock.connect((T_SRV, T_PORT))
                        #     except Exception as e:
                        #         logger.warning(f'Reconnecting to MPU server FAIL: {e}')
                        idx = 0
                        ll = []
                    time.sleep(poll_interval * 1.0 / 1000.0)
        except KeyboardInterrupt:
            pass

    def __read_sensor_data(self, sensor_name: str, config: SENSOR_SETTING):
        from multiprocessing import Process, Queue
        import os, time, random
        q = Queue()
        proc_write1 = Process(target=self.iimu, args=(q, 0))
        proc_write1.start()
        # print('_'*20)

        while True:
            idx = 0
            while not q.empty():
                # result_list.append(queue.get())
                qq = q.get()
                # print('qq', idx, len(qq))
                # print('='*50, q.qsize(), len(qq))
                idx += 1
                self.send(values=qq)
        
            time.sleep(2.5)
            # print(time.time())