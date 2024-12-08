import re
import os


def get_ble_mac(default_return_value=None):
    try:
        x = os.popen('hciconfig')
        ble_mac_addr_line = x.readlines()[1]
        m = re.search(pattern='.*BD Address: ([0-9A-Za-z:]*) ', string=ble_mac_addr_line)
        ble_mac_addr = m.group(1)
    except:
        return default_return_value
    else:
        return ble_mac_addr


def get_wifi_mac():
    pass


if __name__ == "__main__":
    print(get_ble_mac())
