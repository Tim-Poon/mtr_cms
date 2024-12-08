from typing import List, Tuple
from common.component import Component
from threading import Thread
from common.event import *
import time
from config.common import SENSOR_SITE, SensorType, PI_BLE_MAC_ADDR
from common.data_type import *
from utils.update import overwrite_site_setting
from utils.utils import kill_thread


# functionality
# if no site information when boot up, i.e. SENSOR_SITE=None, this module will start to request site information
# until site information is received, i.e. receive RemoteNotifyIdEvent, then overwrite site info in config/common.py
class IDRequest(Component):
    def __init__(self):
        super().__init__()
        self.add_event_publisher(RemoteRequestIdEvent._event_type)
        self.add_event_listener(RemoteNotifyIdEvent._event_type, self.on_receive_remote_id_notification)
        self._id_request_thread = None

    def start(self):
        if not SENSOR_SITE:
            self._id_request_thread = Thread(target=self._id_request)
            self._id_request_thread.start()

    def _id_request(self):
        while True:
            self.publish(RemoteRequestIdEvent(identifier=PI_BLE_MAC_ADDR))
            time.sleep(1)  # todo: just for test, it should be sleep longer in production

    def on_receive_remote_id_notification(self, event: RemoteNotifyIdEvent):
        if PI_BLE_MAC_ADDR == event.identifier:
            self.remove_event_listener(RemoteNotifyIdEvent._event_type)
            self.remove_event_publisher(RemoteRequestIdEvent._event_type)
            kill_thread(self._id_request_thread)
            overwrite_site_setting(event.value)  # blocking op, not good
        else:
            self.publish(LogEvent(identifier=PI_BLE_MAC_ADDR,
                                  value=LogMessage(level='error',
                                                   msg=(f'api server notify wrong target identifier={event.identifier}\n'
                                                        f'current PI_BLE_MAC_ADDR={PI_BLE_MAC_ADDR}'))))
