from common.data_type import BeaconValue
from typing import Union


class BeaconParser:
    @staticmethod
    def check_deployed(manufacturer_data: str) -> bool:
        raise NotImplementedError

    @staticmethod
    def get_beacon_value(manufacturer_data: str,
                         rssi: Union[int, None],
                         mac: Union[int, None]) -> BeaconValue:
        # note: if manufacturer_data contains
        raise NotImplementedError


# [BEACON INFO DEFINITION]
class MTRBeaconParser(BeaconParser):
    from enum import Enum, unique, EnumMeta
    MTR_SERVICE_UUID = "7045de25939e4c3b96174e143f3c78fd"
    # format of major_minor: 1(reserve)001(site_id)1(floor)x(beacon_type)xxxx(id)

    @staticmethod
    def get_beacon_value(manufacturer_data: str,
                         rssi: Union[int, None],
                         mac: Union[str, None]) -> BeaconValue:
        if MTRBeaconParser.check_deployed(manufacturer_data):
            major = int(("0x" + manufacturer_data[40:44]), 16)
            minor = int(("0x" + manufacturer_data[44:48]), 16)
            major_minor = str(major)+str(minor)
            uuid = MTRBeaconParser.MTR_SERVICE_UUID
            if len(major_minor) == 10:
                reserve = major_minor[0]
                floor = major_minor[4]
                beacon_type = major_minor[5]
                idx = major_minor[6:10]
                try:
                    beacon_type = MTRBeaconParser.BEACON_TYPE(beacon_type)
                except ValueError:
                    pass
                else:
                    return BeaconValue(beacon_mac=mac,
                                       rssi=rssi,
                                       major=str(major),
                                       minor=str(minor),
                                       uuid=uuid,
                                       tx_power=None,
                                       battery=None,
                                       floor=floor,
                                       beacon_type=beacon_type.value,
                                       idx=idx,)
        return BeaconValue(beacon_mac=mac,
                           rssi=rssi,
                           major=None,
                           minor=None,
                           uuid=None,
                           tx_power=None,
                           battery=None,)

    @staticmethod
    def check_deployed(manufacturer_data: str) -> bool:
        if not isinstance(manufacturer_data, str):
            return False
        if len(manufacturer_data) == 50 and MTRBeaconParser.MTR_SERVICE_UUID in manufacturer_data:
            return True
        return False

    @unique
    class BEACON_TYPE(Enum, metaclass=EnumMeta):
        BEACON_LOCALIZATION_TYPE = '1'
        BEACON_SHOP_TYPE = '2'


if __name__ == '__main__':
    print(MTRBeaconParser.BEACON_TYPE('1'))
    print(MTRBeaconParser.BEACON_TYPE('1') == MTRBeaconParser.BEACON_TYPE.BEACON_LOCALIZATION_TYPE)
    print(MTRBeaconParser.BEACON_TYPE('3'))  # raising error is as expected
