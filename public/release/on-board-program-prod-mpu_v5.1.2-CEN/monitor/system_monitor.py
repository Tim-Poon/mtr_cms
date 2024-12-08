# todo: system include hardware/os, not included on-board-program
# todo: check db status and re-__safe_upload
from common.data_type import *
from common.event import SystemStatusEvent
from common.component import Component
from common.event import Event
import monitor.osutils as osutils
import time
import threading
from config.common import PI_BLE_MAC_ADDR

from utils.logger import logger_gen
logger = logger_gen('monitor')

class SystemMonitor(Component):
    def __init__(self):
        super().__init__()
        self.add_event_publisher(SystemStatusEvent._event_type)

    def start(self):
        threading.Thread(target=self.loop_forever).start()

    def loop_forever(self):
        while 1:
            all_msg = {}
            all_msg["network"] = osutils.get_network_status()
            all_msg["CPU_clock_feq"] = osutils.get_CPU_clock_frequency()
            all_msg["CPU_usg"] = 0
            all_msg["CPU_T"] = osutils.get_CPU_temperature()
            all_msg["pi_V"] = osutils.get_pi_volt()
            all_msg["arm_core_V"] = osutils.get_core_volt()
            RAM_info = osutils.get_RAM_info()
            all_msg["RAM_total"] = float(round(int(RAM_info[0]) / 1000, 1))
            all_msg["RAM_used"] = float(round(int(RAM_info[1]) / 1000, 1))
            all_msg["RAM_free"] = round(int(RAM_info[2]) / 1000, 1)
            sd_card_storage_space_info= osutils.get_sd_card_storage_space_info()
            all_msg["sd_total"] = float(sd_card_storage_space_info[0][:-1])
            all_msg["sd_used"] = float(sd_card_storage_space_info[1][:-1])
            logger.info(f'CPU_T:{all_msg["CPU_T"]} | RAM:{all_msg["RAM_used"]/all_msg["RAM_total"]*100:.1f}%')
            self.publish(SystemStatusEvent(identifier=PI_BLE_MAC_ADDR,
                                           value=None,
                                           status_dict=all_msg))
            time.sleep(10)
