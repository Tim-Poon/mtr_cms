from config.common import SensorType
from typing import Callable, List
from config.common import PI_BLE_MAC_ADDR
from common.data_type import *
from common.event import *
from utils.beacon_parser import MTRBeaconParser


class Sensor:
    def __init__(self, publish_func: Callable[[Event], None]):
        self.publish_func = publish_func

    def activate(self, sensor, config):  # note: require non-blocking
        raise NotImplementedError

    def deactivate(self):
        raise NotImplementedError

    def get_sensors_list(self):
        raise NotImplementedError

    def configure(self, config):
        raise NotImplementedError

    def send(self, sensor_type: SensorType, values: List, timestamp: float = None, **kwargs):  # note: it should be non-blocking
        if not timestamp:
            timestamp = time.time()

        if sensor_type == SensorType.SENSOR_BLE:
            beacon_mac = values[0]
            rssi = values[1]
            manufacturer_data = values[2]
            if MTRBeaconParser.check_deployed(manufacturer_data=manufacturer_data):
                beacon_value = MTRBeaconParser.get_beacon_value(mac=values[0],
                                                                rssi=values[1],
                                                                manufacturer_data=manufacturer_data)
                source_identifier = str(beacon_value.major) + str(beacon_value.minor)
            else:
                beacon_value = BeaconValue(beacon_mac=beacon_mac,
                                           rssi=rssi,
                                           major=None,
                                           minor=None,
                                           uuid=None,
                                           tx_power=None,
                                           battery=None,
                                           manufacturer_data=manufacturer_data,
                                           )
                source_identifier = beacon_mac
            value = BeaconDataPackage(target_identifier=PI_BLE_MAC_ADDR,
                                      value=beacon_value,
                                      source=SourceInfo(source_identifier=source_identifier,
                                                        source_pos=None),
                                      timestamp=timestamp,
                                      )
        elif sensor_type == SensorType.SENSOR_IMU:
            value = IMUDataPackage(target_identifier=PI_BLE_MAC_ADDR,
                                   value=IMUValue(mpu=values,
                                                  # acc_x=values[0],
                                                  # acc_y=values[1],
                                                  # acc_z=values[2],
                                                  # gyro_x=values[3],
                                                  # gyro_y=values[4],
                                                  # gyro_z=values[5],
                                                  # mag_x=values[6],
                                                  # mag_y=values[7],
                                                  # mag_z=values[8],
                                                  # roll=values[9],
                                                  # pitch=values[10],
                                                  # yaw=values[11],
                                                  # accl_x=values[12],
                                                  # accl_y=values[13],
                                                  # accl_z=values[14],
                                                  ),

                                   timestamp=timestamp)
        elif sensor_type == SensorType.SENSOR_WIFI:
            return  # todo
        else:
            return
        
        self.publish_func(SensorEvent(identifier=PI_BLE_MAC_ADDR,
                                      sensor_type=sensor_type.value,
                                      timestamp=timestamp,
                                      value=value,
                                      **kwargs))

    def log(self, level: str, sensor_type: SensorType,  msg: str, timestamp: float = None, **kwargs):
        self.publish_func(LogEvent(identifier=PI_BLE_MAC_ADDR,
                                   value=LogMessage(level=level,
                                                    msg=msg,
                                                    sensor_type=sensor_type,
                                                    **kwargs)))

