import time
from config.common import SensorType, SENSOR_LIST
from common.component import Component
from threading import Thread, Lock
from common.event import *
from queue import Queue
import sys
from .imu import IMU
from .ble import BLE
from .wifi import WIFI


class SensorManager(Component):
    def __init__(self):
        super().__init__()
        from config.common import SENSOR_LIST
        self.sensors_dict = SENSOR_LIST
        self.sensors = {SensorType.SENSOR_IMU: IMU(self.publish),
                        SensorType.SENSOR_BLE: BLE(self.publish),
                        SensorType.SENSOR_WIFI: WIFI(self.publish)
                        }
        self.max_freq = min([setting.time_period_sec if setting.active else sys.maxsize for setting in self.sensors_dict.values()])
        self.sensors_data_q_lock = Lock()

        self.add_event_listener("sensor_pause", self.router_callback)
        self.add_event_publisher("sensor_data")
        self.add_event_publisher("log_msg")

    def start(self):
        for sensor_type, sensor_handle in self.sensors.items():
            sensor_list = sensor_handle.get_sensors_list()
            for sensor_name, sensor_setting in SENSOR_LIST.items():
                if sensor_name in sensor_list and sensor_setting.active:
                    # print(sensor_name)
                    sensor_handle.activate(sensor_name, config=sensor_setting)

    def publish(self, event: Event):
        if isinstance(event, SensorEvent) or isinstance(event, LogEvent):
            super().publish(event)
        # todo: maybe do some buffer or non block design?

    def router_callback(self, router_event: Event):
        # todo: pause sensor
        if router_event.event_type == "sensor_pause" and hasattr(router_event, 'sensor_name'):
            if router_event.sensor_name in self.sensors:
                sensor = self.sensors[router_event.sensor_name]
                sensor.deactivate()

            print(f"in sensor manager on_receive_sensor_data {router_event}")

    def sensor_callback(self, sensor_event: Event):
        pass


