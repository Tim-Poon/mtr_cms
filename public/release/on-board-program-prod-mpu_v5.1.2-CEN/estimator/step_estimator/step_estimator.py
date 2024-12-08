from typing import cast
from common.component import Component
from threading import Thread
from common.event import *
import time
from config.common import PI_BLE_MAC_ADDR
from common.data_type import *
from collections import defaultdict
from multiprocessing import Pool
from ._estimate_step import estimate_num_of_step
from collections import deque
from copy import deepcopy
from scipy.fftpack import fft, ifft, fftfreq
import numpy as np
from scipy.signal import filtfilt, butter
from random import uniform

# todo: bind it to site?
STEP_ESTIMATOR_MODULE_PARAMS = {
    'STEP_ESTIMATOR_CAL_PERIOD': 2,
    'WINDOW_SIZE': 5,
}


class StepEstimator(Component):
    def __init__(self):
        super().__init__()
        self.add_event_publisher(StepEvent._event_type)
        self.add_event_listener(SensorEvent._event_type, self.on_receive_sensor_data)  # note: don't know why it warns
        self._acc_records = defaultdict(list)  # {identifier: List[Tuple[TS, ACC_X, ACC_Y, ACC_Z]]]}
        # self._step_estimator_pool = Pool(processes=2)
        self.last_g = []
        self.r_time = time.time()

    def start(self):
        pass
        # Thread(target=self.poll_imu_data).start()

    def poll_imu_data(self):
        cal_period = STEP_ESTIMATOR_MODULE_PARAMS['STEP_ESTIMATOR_CAL_PERIOD']
        time.sleep(cal_period)
        while True:
            for target_identifier, data in self._acc_records.items():
                acce_datas = deepcopy(data)
                s_ts = time.time()
                try:
                    self._step_estimator_pool.apply_async(estimate_num_of_step,
                                                          (acce_datas,),
                                                          callback=self.publish_step_event)
                except:
                    print('error')
                e_ts = time.time()
                # print(f'-------------------# imu_pack = {len(acce_datas)}')
                # print(f'oldest ts = {acce_datas[0][0]}, latest ts = {acce_datas[-1][0]}, ')
            time.sleep(cal_period)

    def on_receive_sensor_data(self, event: SensorEvent):
        if isinstance(event.value, IMUDataPackage):
            self._on_receive_imu_data(cast(IMUDataPackage, event.value))

    def _on_receive_imu_data(self, imu_data_package: IMUDataPackage):


        target_identifier = imu_data_package.target_identifier
        # todo: need lock
        # window_size = STEP_ESTIMATOR_MODULE_PARAMS['WINDOW_SIZE']

        # acce_datas = self._acc_records[target_identifier]
        # print(f"step ---- {(imu_data_package._value.mpu[0])}")
        raw_data = np.array(imu_data_package._value.mpu)
        # print(imu_data_package._value.mpu)
        
        # print(len(raw_data),len(self.last_g))
        if len(self.last_g) == 0:
            self.last_g = raw_data
        
        raw_data_full = np.append(raw_data, self.last_g)
        self.last_g = raw_data
        # raw_data_full = raw_data
        feq = len(raw_data_full)/5

        # rr_t = time.time()
        # print(len(raw_data_full), rr_t-self.r_time)
        # self.r_time = rr_t
        

        b, a = butter(3, 8/feq, 'low') # 0.04=0.2*2/10, magic num 0.2
        raw_data_lp = filtfilt(b, a, raw_data_full-np.mean(raw_data_full))
        # print(imu_data_package._value.mpu)
        gg = []
        for g in raw_data_full:
            if g <0.8 or g>1.2:
                gg.append(0)
            else:
                gg.append(1)

        ARG_STEP_G = 0.15 # g
        step_flag = 0
        step_v1 = 0
        init_idx = 0
        for idx, g in enumerate(raw_data_lp):
            if g > ARG_STEP_G and step_flag == 0:
                step_flag = 1
                if idx - init_idx < feq * 1.5:
                    step_v1 += 1
                init_idx = idx
            if idx - init_idx > feq * 0.4 and step_flag == 1:
                step_flag = 0
            elif idx - init_idx > feq * 2:
                step_flag = 0



        fft_y = fft(raw_data_full)
        # print(fft_y)
        data_len = len(raw_data_full)
        fft_y = np.abs(fft_y)
        nfft_y = fft_y/data_len
        nfft_y = nfft_y[range(int(data_len/2))]

        y = fftfreq(data_len, 1/feq)[:data_len//2]

        y_limit = np.where((y < 3) & (y > 0.2))
        nfft_y_limit = nfft_y[y_limit[0][0]:y_limit[0][-1]]
        max_id = np.argwhere(nfft_y_limit==max(nfft_y_limit))[0][0] + y_limit[0][0]
        
        step = 0
        # print(nfft_y)
        st = np.mean(nfft_y[1:])/nfft_y[max_id]
        # st = raw_data_full.var()
        # if nfft_y[max_id] > 0.01 and y[max_id] < 3 and st > 0.001:
        if nfft_y[max_id] > 0.01 and st < 0.25:
            step = int(y[max_id]*5)

        # print('-'*60, f"step={step}/{step_v1}, st={int(st * 100)}% feq={feq} ")

        vel_step = ((step_v1))/5*0.7
        vel_acc = np.mean(gg)
        general = 0
        r_seed = uniform(-0.1, 0.1)
        if vel_acc > 0.9 and vel_step < 0.3:
            general = 0.2 + r_seed
        elif vel_step >  0.8:
            general = vel_step
        else:
            general = -1

        # print(f'---------------------------------------------------------------general={general}, vel_acc={vel_acc}, vel_step={vel_step:.2f}, step={step}/{step_v1}')
        self.publish_step_event(round(general,2))
        # public imu speed
        # self.publish_step_event(int(step+step_v1)/2)



        # cur_ts = imu_data_package.timestamp * 1000
        # if acce_datas:
        #     valid_idx = 0
        #     for acc_data in acce_datas:
        #         if cur_ts - acc_data[0] > window_size * 1000:
        #             valid_idx += 1
        #         else:
        #             break
        #     # print(valid_idx)
        #     # print('before', self._acc_records[target_identifier])
        #     acce_datas = acce_datas[valid_idx:]
        #     # print('after', self._acc_records[target_identifier])
        # acce_datas.append((cur_ts,
        #                    imu_data_package.value.acc_x * 9.81,
        #                    imu_data_package.value.acc_y * 9.81,
        #                    imu_data_package.value.acc_z * 9.81,
        #                    ))
        # self._acc_records[target_identifier] = acce_datas

    def publish_step_event(self, step):
        # print('-'*10, step)
        self.publish(StepEvent(identifier=PI_BLE_MAC_ADDR,
                               value=step))
