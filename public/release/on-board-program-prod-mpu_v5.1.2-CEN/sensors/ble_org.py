import time
from config.common import SensorType, SENSOR_SETTING
from test.mock.mock_sensors.mock_ble import MOCKBLE
from sensors.sensor import Sensor
from threading import Thread, Lock
from config.common import SENSOR_LIST
import traceback
from functools import partial


class BLE(Sensor):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.send = partial(self.send, sensor_type=SensorType.SENSOR_BLE)  # note: all inputs should use keyword args
        self.log = partial(self.log, sensor_type=SensorType.SENSOR_BLE)
        self.__sensor_handles = self.__init_sensors()  # todo
        self.__thread_dict = {}
        self.__active_flag_list = {sensor_name: False for sensor_name in self.__sensor_handles.keys()}

    def __init_sensors(self):
        if "test.mock.mock_sensors.mock_ble" not in SENSOR_LIST:
            self.log(level='info',
                     msg="Please ensure that the SENSOR_LIST variable in your config file has 'test.mock.mock_sensors.mock_beacon'")

        if "sensors.beacon" not in SENSOR_LIST:
            self.log(level='info',
                     msg="Please ensure that the SENSOR_LIST variable in your config file has 'sensors.beacon'")

        sensors = {}
        try:
            sensors["sensors.beacon"] = BLUEPYBLE(self.send)
            self.log(level='info',
                     msg="init sensor.ble successfully")
        except Exception as err:
            self.log(level='warning',
                     msg=f"Unable to init 'sensors.beacon': {traceback.format_exc()}")

        try:
            sensors["test.mock.mock_sensors.mock_ble"] = MOCKBLE(self.send)
            self.log(level='info',
                     msg="init test.mock.mock_sensors.mock_ble successfully")
        except Exception as err:
            self.log(level='warning',
                     msg=f"Unable to init 'sensors.beacon': {traceback.format_exc()}")

        return sensors

    def get_sensors_list(self):
        return self.__sensor_handles.keys()

    def activate(self, sensor: str, config: SENSOR_SETTING):
        if sensor in self.__sensor_handles and self.__sensor_handles[sensor] is not None:
            sensor_name = sensor
        else:
            return

        if sensor_name not in self.__thread_dict:  # note: need to guarantee all these threads won't crash, because these threads will only start once.
            self.__thread_dict[sensor_name] = Thread(target=self.__read_sensor_data, args=(sensor_name, config))
            self.__thread_dict[sensor_name].start()

        self.__active_flag_list[sensor_name] = True  # note: gurantee that __active_flag is only changed by sensor_manager which is only one thread exist in this process

    def deactivate(self):
        self.__active_flag_list = {sensor_name: False for sensor_name, activate_status in self.__active_flag_list}

    def configure(self, config):
        pass

    def __read_sensor_data(self, sensor_name: str, config: SENSOR_SETTING):
        ble_handle = self.__sensor_handles[sensor_name]
        sleep_time = 0 if config is None else config.time_period_sec
        while True:
            if self.__active_flag_list[sensor_name]:
                try:
                    ble_handle.scan(sleep_time)
                except Exception as err:
                    self.log(level='warning',
                             msg=str(traceback.format_exc()))
                    ble_handle.restart()
                    continue


class BLUEPYBLE:
    def __init__(self, publish_func):
        from bluepy.btle import Scanner, DefaultDelegate

        class ScanDelegate(DefaultDelegate):
            def __init__(self, publish_func):
                DefaultDelegate.__init__(self)
                self.publish_func = publish_func

            def handleDiscovery(self, dev, isNewDev, isNewData):
                # note: Returns a list of tuples (adtype, description, value) containing the AD type code, human-readable description and value (as reported by getDescription() and getValueText()) for all available advertising data items
                data = [dev.addr, dev.rssi, dev.getValueText(255), dev.getScanData()]
                self.publish_func(values=data)

        self.publish_func = publish_func
        self.s = Scanner().withDelegate(ScanDelegate(publish_func))

    def scan(self, freq):
        self.s.scan(freq)

    def restart(self):
        pass  # note: self.s handler can be reused
