from enum import Enum, unique, EnumMeta
import os
from pathlib import Path
from collections import namedtuple
from utils.hardwareid import get_ble_mac

# [FOLDER_PATH]
ROOT_FOLDER = Path(os.fspath(__file__)).parent.parent
CONFIG_FOLDER = f"{ROOT_FOLDER}/config"
CORE_FOLDER = f"{ROOT_FOLDER}/core"
UTILS_FOLDER = f"{ROOT_FOLDER}/utils"
LOG_FOLDER = f"{ROOT_FOLDER}/backup/log"


# note: set up which component to debug
COMPONENT_SETTING = namedtuple("COMPONENT_SETTING", ['on', 'debug'])
TO_LOAD_MODULES = {
                   # "backup.backup": True,
                   "estimator.estimator": True,
                   # "estimator.step_alarm_estimator": True,  # todo: should move to other directory later
                   "sensors.sensor_manager": True,
                   "network.network": True,
                   "hci.hci": True,
                   "monitor.system_monitor": True,
                   "estimator.alarm_estimator.alarm_estimator": True,
                   "estimator.velocity_estimator.velocity_estimator": True,
                   "estimator.step_estimator.step_estimator": True,
                   # "id_request.id_request": True,

                   "test.mock.mock_backup.mock_backup": False,
                   "test.mock.mock_estimator.mock_estimator": False,
                   "test.mock.mock_sensors.mock_sensor_manager": False,
                   "test.mock.mock_network.mock_network": False,
                   "test.mock.mock_hci.mock_hci": False,
                   "test.mock.mock_monitor.mock_system_monitor": False,

                   "utils.debug": False
                   }


# [SERVER]
SERVER_IP = 'ust-mtr-location-server-prod-v2.eastasia.cloudapp.azure.com' # prod
# SERVER_IP = 'mtr-uat-internal.smartsensing.biz' # testing
# SERVER_IP = '143.89.49.63' # testing
SERVER_PORT = 4000
SERVER_USR = "hkust-mtr-service"
SERVER_PWD = "mtrec2020"

# [LOCAL DATABASE]
DATABASE_NAME = "on-board-program.db"
DATABASE_FILE_PATH = f"{LOG_FOLDER}/{DATABASE_NAME}"
DATABASE_TABLE_NAME = "temp"

# [SOURCE DATA STRUCTURE]
SOURCE_INFO = namedtuple('SOURCE_INFO', ['source_identifier', 'x', 'y', 'z', 'type', 'activated', 'addition_info'])

# [NETWORK]
UPLOAD_FREQ = 3
UPLOAD_TIMEOUT = 2
BACKUP_UPLOAD_FREQ = 60
# for ssl
SERVER_SNI_HOSTNAME = 'smartsensing.biz'
CLIENT_CERT = 'config/ssl_files/client.crt'
CLIENT_KEY = 'config/ssl_files/client.key'
SERVER_CERT = 'config/ssl_files/server.crt'


# [SENSOR MANAGER]
@unique
class SensorType(Enum, metaclass=EnumMeta):
    SENSOR_IMU = '1'
    SENSOR_WIFI = '2'
    SENSOR_BLE = '3'


SENSOR_SETTING = namedtuple("SENSOR_SETTING", ['sensor_type', 'active', 'time_period_sec'])

SENSOR_LIST = {"sensors.imu": SENSOR_SETTING(sensor_type=SensorType.SENSOR_IMU, active=True, time_period_sec=0.01),
               "sensors.beacon": SENSOR_SETTING(sensor_type=SensorType.SENSOR_BLE, active=True, time_period_sec=10),
               "sensors.wifi": SENSOR_SETTING(sensor_type=SensorType.SENSOR_WIFI, active=False, time_period_sec=0.01),
               }


# [HARDWARE INFO]
PI_BLE_MAC_ADDR = get_ble_mac(default_return_value=None)

# [SOFTWARE INFO]
VM = 2.1

# note: it should have a site(e.g. KOB, same name as the config .py file name) before deployment.
SENSOR_SITE = 'EXC'