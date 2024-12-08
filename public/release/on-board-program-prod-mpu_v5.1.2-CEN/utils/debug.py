from common.component import Component
from common.event import *
import time
from threading import Thread
from monitor import osutils
import os


# note: used to redirect to stdout, in principle, all the events traffic should be listened by Debug class
class Debug(Component):
    def __init__(self):
        super().__init__()
        # self.add_event_listener(SensorEvent._event_type, self.print_data)  # from SensorManager
        self.add_event_listener(LogEvent._event_type, self.print_data)  # todo: from all components, except for router?
        # self.add_event_listener("data_backup", self.print_data)  # from Network
        # self.add_event_listener("resend_success", self.print_data)  # from Network
        # self.add_event_listener("resend_data", self.print_data)  # from BackUpSystem
        # self.add_event_listener(ResultEvent._event_type, self.print_data)  # from Estimator
        # self.add_event_listener(SystemStatusEvent._event_type, self.print_data)  # from SystemMonitor
        # self.add_event_listener(HCIStatusEvent._event_type, self.print_data)  # from SystemMonitor
        # self.add_event_listener("sensor_pause", self.print_data)  # todo: from Network or hci
        # self.add_event_listener(RemoteRequestIdEvent._event_type, self.print_data)
        # self.add_event_listener(StepEvent._event_type, self.print_data)  # from StepEstimator
        # self.add_event_listener(PositionEvent._event_type, self.print_data)  # from StepEstimator
        # self.add_event_listener(PositionVelocityEvent._event_type, self.print_data)  # from StepEstimator
        self.t_gap = 0

    def start(self):
        Thread(target=self.system_self_check).start()

    def print_data(self, e: Event):
        b_200 = 'fb:62:79:4f:2b:81'
        b_500 = 'df:e4:87:44:43:8c'
        print(f"{time.time()} {e} debugmodule recvtime")
        # if e._sensor_type == '3':
        #     # print(e)
        #     if e._value._value._beacon_mac == b_200:
        #     #     print(e)
        #         print(f"{e._value._value._beacon_mac, e._value._value._rssi, round(e._timestamp-self.t_gap, 2)} ")
        #         self.t_gap = e._timestamp
        # print(e._sensor_type)
        # if e._sensor_type == '1':
        #     print(e)

    def system_self_check(self):
        while True:
            print(f"system_self_check: {self.check_program_memory()} mb, {osutils.get_CPU_temperature()} degree")
            time.sleep(10)

    def check_program_memory(self):
        # note: just for checking memory leakage and other system thing stuff, but depends on psutil
        # import psutil
        # process = psutil.Process(os.getpid())
        # return process.memory_info().rss / (1024 ** 2)  # in mega bytes
        pass
