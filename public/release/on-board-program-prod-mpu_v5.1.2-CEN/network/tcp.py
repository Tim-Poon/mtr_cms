from typing import Callable, Any, Tuple
from threading import Thread, Lock
import socket
import time
import select
from queue import Queue
import ssl
import zlib
from utils.logger import logger_gen
logger = logger_gen('tcp module')


class TCPClient:
    def __init__(self, ip,
                 port,
                 user_name=None,
                 psw=None,
                 with_ssl=False,
                 server_sni_hostname=None,
                 server_cert=None,
                 client_cert=None,
                 client_key=None):
        # todo: need to wrap ssl
        # todo: using context manager from outside to redirect the print statement
        self.__s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.__s.setblocking(False)
        self.__s.settimeout(2)
        self.__ip = ip
        self.__port = port
        self.__user_name = user_name
        self.__psw = psw
        self.__socket_good = False
        self.__network_good = False
        self.__shut_down = False
        # self.__close_socket_lock = Lock()

        # SSL
        if with_ssl and server_cert and client_cert and client_key:
            self.context = ssl.create_default_context(ssl.Purpose.SERVER_AUTH, cafile=server_cert)
            self.context.load_cert_chain(certfile=client_cert, keyfile=client_key)
            self.server_sni_hostname = server_sni_hostname
            self.__s = self.context.wrap_socket(self.__s, server_side=False, server_hostname=self.server_sni_hostname)
        else:
            self.context = None

        try:
            self.__s.connect((self.__ip, self.__port))
            logger.info(f"Initial connect: SUCCESS! ({self.__ip},{self.__port})")
        except socket.error as e:
            logger.warning("Initial connect: FAIL!")
            # self.__s.close()
            self.__socket_good = False
        else:
            self.__socket_good = True

        Thread(target=self.__keep_conn_alive).start()

    def send(self, byte_msg: bytes, timeout=None) -> bool:
        if not self.__socket_good:
            logger.error(f"Lost connection while sending data (len={len(byte_msg)})")
            return False
        if type(byte_msg) is not bytes:
            logger.error(f"Format ERROR, sending data is NOT bytes!")
            return False
        if timeout is not None:
            self.__s.settimeout(timeout)

        try:
            #s_ts = time.time()
            compressed_data = zlib.compress(byte_msg)
            compressed_data += b'/'
            # SSL
            self.__s.sendall(self.wrap_data(compressed_data))
            s_len = len(compressed_data)
            # print(s_len, len(byte_msg))
            # buff = 1024*50
            # count = buff
            # while s_len > count:
            #     self.__s.sendall(compressed_data[count-buff:count])
            #     # print(count, round(count/s_len*100,0),'%')
            #     count += buff
            # self.__s.sendall(compressed_data[count-buff:])
            #e_ts = time.time()
            #print(f"send time = {e_ts - s_ts}", len(compressed_data)/1024, 'kb', 'org', len(byte_msg))
            return True
        except Exception as e:
            logger.error(f"Sending data FAIL (len={len(byte_msg)}) due to: {e}")
            self.__socket_good = False
            self.__network_good = False
            return False


    @staticmethod
    def wrap_data(byte_msg: bytes) -> bytes:
        # note: wrap a byte msg with a 5 byte long digit, for the sake of 65536
        return str(len(byte_msg)).zfill(10).encode(encoding='utf-8') + byte_msg

    # def receive(self) -> bytes:
    #     bs = b''
    #     while True:
    #         try:
    #             # self.__close_socket_lock.acquire()
    #             print("start receiving")
    #             recv = self.__s.recv(1024)  # todo: why this will affect self.__s.connect?
    #             if not recv:
    #                 # self.__close_socket_lock.release()   # note: this way of programming logic is liable to be forgotten
    #                 return b''  # note: when returning b'', it can be at least two possiblities, terminated by remote server or the connect fail but the socket is not yet closed.
    #             else:
    #                 bs = bs + recv
    #             try:
    #                 msg, remain = TCPServer.unwrap_data(bs)
    #             except Exception as e:
    #                 print("fail to unwrap data")
    #                 # self.__close_socket_lock.release()
    #                 return b''
    #             if msg is None:
    #                 bs = remain
    #             else:
    #                 # self.__close_socket_lock.release()
    #                 return msg
    #         except OSError as e:
    #             # self.__close_socket_lock.release()
    #             print(f"error in receive: {e}")
    #             time.sleep(1)  # note: wait for reconnect
    #         except Exception as e:
    #             # self.__close_socket_lock.release()
    #             import traceback
    #             print(f"try to receive data from server but fail, due to error: {traceback.format_exc()}")
    #             time.sleep(1)

    def close(self):
        self.__shut_down = True

    def __keep_conn_alive(self):
        while True:
            time.sleep(5)
            # logger.info(f"Keep connection: socket state: {self.__socket_good}")
            if not self.__socket_good:
                try:
                    logger.info(f"Trying to reconnect: {self.__ip, self.__port}")
                    self.__s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
                    self.__s.setblocking(False)
                    self.__s.settimeout(5)
                    # SSL
                    # if self.context:
                    self.__s = self.context.wrap_socket(self.__s,
                                                        server_side=False,
                                                        server_hostname=self.server_sni_hostname)
                    self.__s.connect((self.__ip, self.__port))
                except OSError as e:
                    logger.error(f"Reconnect FAIL! ({e})")
                    self.__socket_good = False
                    continue
                else:
                    self.__socket_good = True
                    logger.info(f"Reconnect SUCCESS! socket state: {self.__socket_good}")



# class TCPServer:
#     def __init__(self,
#                  ip,
#                  port,
#                  username=None,
#                  psw=None,
#                  with_ssl=False,
#                  client_cert=None,
#                  server_cert=None,
#                  server_key=None):
#         self.__socket_handler = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
#         self.__ip = ip
#         self.__port = port
#         self.__callback = None
#         self.__receive_buf = Queue()
#         self.__conns = {}  # Dict[Tuple: client_sock}
#         self.__conns_lock = Lock()
#
#         if with_ssl and client_cert and server_cert and server_key:
#             self.context = ssl.create_default_context(ssl.Purpose.CLIENT_AUTH)
#             self.context.verify_mode = ssl.CERT_REQUIRED
#             self.context.load_cert_chain(certfile=server_cert, keyfile=server_key)
#             self.context.load_verify_locations(cafile=client_cert)
#         else:
#             self.context = None
#
#     def start(self):
#         Thread(target=self.__loop_forever).start()
#
#     def __loop_forever(self):
#         self.__socket_handler.bind((self.__ip, self.__port))
#         self.__socket_handler.listen(60)  # note: each sensor will consume one
#         while True:
#             conn, addr = self.__socket_handler.accept()
#             print(f"accept connection addr: ", addr)
#             if addr in self.__conns:
#                 print(f"accept another connection with same ip addr = {addr}, discard it")
#                 conn.close()
#                 continue
#             if self.context:
#                 conn = self.context.wrap_socket(conn, server_side=True)
#             self.__conns[addr] = conn
#             Thread(target=self.client_handle, args=(addr, )).start()  # note: can be used to mimic the crash
#
#     def receive(self):  # blocking operation
#         return self.__receive_buf.get()
#
#     def response(self, msg: bytes, addr: Tuple):
#         self.__conns_lock.acquire()
#         if addr in self.__conns:
#             client_socket = self.__conns[addr]
#         else:
#             print(f"bad connection to {addr}, discard msg {msg}")
#             return None
#         self.__conns_lock.release()
#
#         try:
#             wrapped_data = TCPClient.wrap_data(msg)
#             client_socket.sendall(wrapped_data)
#         except Exception as e:
#             print(e)
#
#     def client_handle(self, addr):
#         bs = b''
#         self.__conns_lock.acquire()
#         conn = self.__conns[addr]
#         self.__conns_lock.release()
#         with conn:
#             while True:
#                 try:
#                     recv = conn.recv(1024)
#                 except OSError:
#                     break
#                 if not recv:  # note: if client side call .close() then this will be executed, otherwise the connection will wait till timeout and the resource will not release till then
#                     break
#                 else:
#                     bs = bs + recv
#                 try:
#                     msg, remain = self.unwrap_data(bs)
#                 except Exception as e:
#                     print(e)
#                     break
#                 try:
#                     if msg is not None:
#                         self.__receive_buf.put((msg, addr))
#                 except Exception as e:
#                     print(e)
#                 bs = remain
#         self.__conns_lock.acquire()
#         del self.__conns[addr]
#         self.__conns_lock.release()
#         print("close connection")
#
#     @staticmethod
#     def unwrap_data(byte_msg: bytes):
#         """
#         :param byte_msg:
#         :return: (complete_bytes: bytes, remain_bytes: bytes)
#
#         if byte_msg doesn't contain a complete message, then the return message will be None
#         """
#         if len(byte_msg) <= 10:
#             return None, byte_msg
#         else:
#             b_byte_len = byte_msg[:10]
#             try:
#                 byte_length = int(b_byte_len.decode(encoding='utf-8'))
#             except Exception:
#                 raise
#             else:
#                 if len(byte_msg) - 10 < byte_length:
#                     return None, byte_msg
#                 return byte_msg[10: 10 + byte_length], byte_msg[10+byte_length:]


if __name__ == "__main__":
    client = TCPClient(ip='', port=5354)
    num = 1
    while num > 0:
        send_success = True
        send_success = send_success and client.send(b"no timeout"*1000)
        # send_success = send_success and client.send(b"timeout", timeout=1)
        if send_success:
            num -= 1
        time.sleep(2)
    client.close()

# server side code
# from network.tcp import TCPServer
#
#
# def on_receive_sensor_data(msg: bytes, addr):
#     print(f"receive {msg} from {addr}")
#
#
# server = TCPServer(ip='', port=5354)
# server.start(on_receive_sensor_data=on_receive_sensor_data)
