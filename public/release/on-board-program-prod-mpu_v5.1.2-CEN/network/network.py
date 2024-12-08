from common.component import Component
from config.common import *
from common.event import *
from utils.security import encrypt, decrypt
from threading import Thread, Lock
import json
import time
from queue import Queue
from .tcp import TCPClient
from .udp import UDPClient
from typing import Union
from multiprocessing import Process, Queue
import os, time, random
import zlib


class Network(Component):
    def __init__(self):
        super().__init__()
        # self.add_event_publisher(BackupEvent._event_type)  # todo
        # self.add_event_publisher("resend_success")  # todo
        # self.add_event_publisher("sensor_pause")   # todo
        # self.add_event_publisher(RemoteNotifyIdEvent._event_type)  # todo

        self.add_event_listener(HCIStatusEvent._event_type, lambda e: self.__safe_upload(_add_upload_vm(e)))
        self.add_event_listener(SensorEvent._event_type, lambda e: self.__safe_upload(_reformat_sensor_event(_add_upload_vm(_filter_sensor_data(e)))))
        self.add_event_listener(ResultEvent._event_type, lambda e: self.__safe_upload(_add_upload_site(_add_upload_vm(e))))
        self.add_event_listener(LogEvent._event_type, lambda e: self.__safe_upload(_add_upload_vm(_filter_log_data(e))))
        self.add_event_listener(SystemStatusEvent._event_type, lambda e: self.__safe_upload(_add_upload_vm(e)))
        # self.add_event_listener(RemoteRequestIdEvent._event_type, lambda e: self.__safe_upload(_add_upload_vm(e)))

        # self.add_event_listener("resend_data", self.__resend_data)  # todo

        self.__safe_buffer_lock = Lock()
        self.__unsafe_buffer_lock = Lock()

        self.__safe_uploader = TCPClient(SERVER_IP,
                                         SERVER_PORT,
                                         with_ssl=True,
                                         server_sni_hostname=SERVER_SNI_HOSTNAME,
                                         server_cert=SERVER_CERT,
                                         client_key=CLIENT_KEY,
                                         client_cert=CLIENT_CERT)

        # self.__backup_uploader = TCPClient(SERVER_IP,
        #                                    SERVER_PORT,
        #                                    with_ssl=True,
        #                                    server_sni_hostname=SERVER_SNI_HOSTNAME,
        #                                    server_cert=SERVER_CERT,
        #                                    client_key=CLIENT_KEY,
        #                                    client_cert=CLIENT_CERT)

        self.__safe_buffer = Queue()  # insert type str
        self.__unsafe_buffer = Queue()
        # self.__backup_buffer = Queue()
        self.__network_status = True  # todo: is this necessary?

    def start(self):
        Thread(target=self.__safe_buffer_poll).start()
        # Thread(target=self.__backup_buffer_poll).start()
        # Thread(target=self.__unsafe_buffer_poll).start()  # todo
        # Thread(target=self.__redirect_remote_requests).start()  # todo: test

    @staticmethod
    def super_tcp(q, f):
        # sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        # sock.connect(('ust-mtr-location-server-prod-v2.eastasia.cloudapp.azure.com', 4000))

        while True:
            idx = 0
            while not q.empty():
                byte_msg = q.get()
                # print('='*50, q.qsize())
                idx += 1
                compressed_data = zlib.compress(byte_msg)
                compressed_data += b'/'
                # print(idx, len(compressed_data))
                # buff = 1024 * 50
                # count = buff
                # while s_len > count:
                #     self.__s.sendall(compressed_data[count - buff:count])
                #     # print(count, round(count/s_len*100,0),'%')
                #     count += buff
                # self.__s.sendall(compressed_data[count - buff:])
                # self.__s.sendall(compressed_data)
            time.sleep(2)


    def __safe_buffer_poll(self):
        # q = Queue()
        # proc_write1 = Process(target=self.super_tcp, args=(q, 0))
        # proc_write1.start()
        while True:
            if not self.__safe_buffer.empty():
                data = []
                # self.__safe_buffer_lock.acquire()
                while not self.__safe_buffer.empty():
                    data.append(self.__safe_buffer.get())
                # self.__safe_buffer_lock.release()

                if self.__network_status:
                    # resend_ids = []
                    packet_dict = {}
                    for i, d in enumerate(data):
                        packet_dict[i] = d
                        # if isinstance(d, ResendData):
                        #     resend_ids.append(d.idx)
                    packet = json.dumps(packet_dict).encode('utf-8')
                    # print(f"net work pgk", len(packet))
                    # q.put(packet)
                    # todo: need to test if timeout work
                    send_success_flag = self.__safe_uploader.send(packet, timeout=UPLOAD_TIMEOUT)

                #     q!!!!!!


                # todo
                #     if send_success_flag:
                #         if len(resend_ids) > 0:
                #             self.publish(event=Event(event_type="resend_success", ids=resend_ids))
                #     else:
                #         self.__network_status = False
                #
                # if not self.__network_status:
                #     data_list_backup = []
                #     for d in data:
                #         if not isinstance(d, ResendData):
                #             data_list_backup.append((d, ))  # note: make it compatible with db insertion
                #     self.publish(event=Event("data_backup", content=data_list_backup))
            time.sleep(UPLOAD_FREQ)  # note: UPLOAD_FREQ should adjust according to the traffic, seems 2 sec is good.

    # todo
    # def __unsafe_buffer_poll(self):
    #     while True:
    #         if not self.__unsafe_buffer.empty():
    #             self.__unsafe_buffer_lock.acquire()
    #             d = self.__unsafe_buffer.get()
    #             self.__unsafe_buffer_lock.release()
    #             packet = json.dumps({0: d}).encode(encoding='utf-8')
    #             self.__unsafe_uploader.send(packet)
    #         time.sleep(UPLOAD_FREQ)

    def __backup_buffer_poll(self):
        while True:
            backup_buffer_len = self.__backup_buffer.qsize()
            data = []
            while backup_buffer_len > 0:
                data.append(self.__backup_buffer.get())
                backup_buffer_len -= 1

            resend_ids = []
            packet_dict = {}
            for i, d in enumerate(data):
                packet_dict[i] = d
                resend_ids.append(d.idx)

            packet = json.dumps(packet_dict).encode('utf-8')
            send_success_flag = self.__safe_uploader.send(packet, timeout=UPLOAD_TIMEOUT)

            if send_success_flag and resend_ids:
                self.publish(event=Event(event_type="resend_success", ids=resend_ids))

            time.sleep(BACKUP_UPLOAD_FREQ)

    def __redirect_remote_requests(self):
        while True:
            msg = self.__safe_uploader.receive()  # blocking operation
            if msg == b'':
                time.sleep(10)  # note: assuming that remote server will never close the socket.
                continue
            print('[debug] receive msg: ')
            print(msg)
            # todo: no encryption for now
            msg_dict = json.loads(msg)
            print(f"[debug] receive {msg_dict}")
            try:
                # assert msg_dict['event_type'] == 'response_id_request'
                assert msg_dict['event_type'] == 'remote_request'   # todo: api_server need to cooperate with it
                self.publish(Event(event_type='remote_request', data=msg_dict['data']))
            except Exception as e:
                print("[debug] error in __redirect_remote_requests")
                self.publish(LogEvent(level='warn', msg=f"Incorrect remote data format = {msg_dict}"))

    @staticmethod
    def pack_single_event(single_event: Union[List, DictStringify]) -> str:
        # note: all encrypt and compress is done here
        # todo: need an effective compress algorithm
        d = json.dumps(str(single_event)).encode(encoding='utf-8')
        encrypted_d = encrypt(d)
        res = encrypted_d.decode(encoding='utf-8')
        return res

    @staticmethod
    def unpack_single_event(single_event_msg: str) -> DictStringify:
        # todo: the mechanism of current module loading not allows import network.py from the outside.
        msg = json.loads(decrypt(bytes(single_event_msg, encoding='utf-8')))
        return msg

    def __safe_upload(self, e: Union[List, DictStringify, None]):
        if e is None:
            return
        try:
            packed_data = self.pack_single_event(e)
        except Exception as err:
            self.publish(LogEvent(identifier=PI_BLE_MAC_ADDR,
                                  value=LogMessage(level='warn',
                                                   msg=f'in Networked.__safe_upload, unable to pack event: {err}, event = {e}')))
            return
        with self.__safe_buffer_lock:
            self.__safe_buffer.put(packed_data)

    def __unsafe_upload(self, e: Union[DictStringify, None]):
        if e is None:
            return
        try:
            packed_data = self.pack_single_event(e)
        except Exception as e:
            return
        with self.__unsafe_buffer_lock:
            self.__unsafe_buffer.put(packed_data)

    def __resend_data(self, e: Event):
        if hasattr(e, 'data') and hasattr(e, 'idx'):
            self.__backup_buffer.put(ResendData(e.data, e.idx))

    # def __update_network_status(self, e: SystemStatusEvent):
    #     if hasattr(e, 'network_good'):
    #         self.__network_status = e.network_good


class ResendData:
    def __init__(self, d, idx):
        self.data = d
        self.idx = idx


# def _filter_system_status(e: SensorEvent) -> Union[DictStringify, None]:
#     # todo: filter out system status not required by server
#     return e


def _filter_sensor_data(e: SensorEvent) -> Union[SensorEvent, None]:
    if e.value.data_type == SensorType.SENSOR_BLE.value:
        beacon_data_pack = e.value
        if not (beacon_data_pack.value.major and beacon_data_pack.value.minor):
            return None
    if e.value.data_type == SensorType.SENSOR_IMU.value:
        return None
    return e


def _filter_log_data(e: LogEvent) -> Union[DictStringify, None]:
    level = e.value.level.lower()
    if level == 'warn' or level == 'error':
        return e
    return None


def _add_upload_vm(e: Union[Event, None]) -> Union[Event, None]:
    if e is not None:
        e.vm = VM
        return e
    return None


def _add_upload_site(e: Union[Event, None]) -> Union[Event, None]:
    if e is not None:
        e.site = SENSOR_SITE
        return e
    return None


def _reformat_sensor_event(event: Union[SensorEvent, None]) -> Union[List, None]:
    if not event:
        return None
    if isinstance(event.value, BeaconDataPackage) and hasattr(event, 'vm'):
        return [event.event_type,
                event.value.data_type,
                event.identifier,
                event.value.value.beacon_mac,
                event.value.value.major,
                event.value.value.minor,
                event.value.value.rssi,
                event.timestamp,
                event.vm,
                ]
    elif isinstance(event.value, IMUDataPackage):
        return [event.event_type,
                event.value.data_type,
                event.identifier,
                event.value.value.mpu,
                event.timestamp, ]
        # return [event.event_type,
        #         event.value.data_type,
        #         event.identifier,
        #         round(event.value.value.acc_x, 4),
        #         round(event.value.value.acc_y, 4),
        #         round(event.value.value.acc_z, 4),
        #         round(event.value.value.gyro_x, 4),
        #         round(event.value.value.gyro_y, 4),
        #         round(event.value.value.gyro_z, 4),
        #         round(event.value.value.mag_x, 4),
        #         round(event.value.value.mag_y, 4),
        #         round(event.value.value.mag_z, 4),
        #         round(event.value.value.roll, 4),
        #         round(event.value.value.pitch, 4),
        #         round(event.value.value.yaw, 4),
        #         event.timestamp, ]
    elif isinstance(event.value, WIFIDataPackage):
        pass
