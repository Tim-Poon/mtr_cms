import time
from threading import Thread, Lock
from typing import Union, List, Set, Callable, Dict
from collections import defaultdict


# todo: weird design, couple batch logic with other filter logic
class SensorDataPreprocessor:
    # todo: it needs a config itself, and such config is tightly coupled with project background
    # todo: general configuration on multiple sensors, make it a pluggable module?
    def __init__(self,
                 input_q,
                 batch_output_q,
                 cal_period_sec,
                 beacon_preprocessor_config: Dict = {},
                 imu_preprocessor_config: Dict = {},
                 wifi_preprocessor_config: Dict = {}):

        # note: the input_q should only handle data with same target_identifier
        self.input_q = input_q
        self.batch_output_q = batch_output_q  # note: every element is of form [{'target_identifier', 'data_type', 'value', 'timestamp', **other_keys}]
        self.cal_period_sec = cal_period_sec

        self.beacon_preprocessor = BeaconDataPreprocessor(**beacon_preprocessor_config)
        self.imu_preprocessor = IMUDataPreprocessor(**imu_preprocessor_config)
        self.wifi_preprocessor = WIFIDataPreprocessor(**wifi_preprocessor_config)

    def run(self):
        Thread(target=self.batch_input_data).start()

    def batch_input_data(self):
        # note: define the batching strategy
        while True:
            batches = defaultdict(list)  # note: {target_ident: {'beacon_batch':[], 'imu_batch':[], 'wifi_batch':[]}}
            s_ts = time.time()
            while not self.input_q.empty():

                sensor_data = self.input_q.get()
                # print("SensorDataPreprocessor, self.input_q.get()", sensor_data)
                target_ident = sensor_data['target_identifier']
                sensor_type = sensor_data['data_type']

                if sensor_type == '3':
                    batches['beacon_batch'].append(sensor_data)
                elif sensor_type == '1':
                    batches['imu_batch'].append(sensor_data)
                elif sensor_type == '2':
                    batches['wifi_batch'].append(sensor_data)

            if batches:
                processed_beacon_batch = self.beacon_preprocessor.preprocess_batch(batches['beacon_batch'])
                processed_wifi_batch = self.imu_preprocessor.preprocess_batch(batches['wifi_batch'])
                processed_imu_batch = self.wifi_preprocessor.preprocess_batch(batches['imu_batch'])

                # print("SensorDataPreprocessor, processed_beacon_batch", processed_beacon_batch)
                if processed_beacon_batch:
                    self.batch_output_q.put({'target_identifier': target_ident,
                                             'beacon_batch': processed_beacon_batch,
                                             'imu_batch': processed_imu_batch,
                                             'wifi_batch': processed_wifi_batch})
            e_ts = time.time()
            # print(f'preprocess+batch data time consume = {e_ts - s_ts}s')
            time.sleep(self.cal_period_sec)


class BeaconDataPreprocessor:
    def __init__(self,
                 rssi_threshold: Union[int, None] = None,
                 valid_beacon_format_list: Union[List[Callable[[str], bool]], None] = None,
                 equivalent_beacon_set: Union[None, List[Set]] = None,
                 avg_rssi_flag=False,
                 activated_source_identifier_set=None,
                 **kwargs
                 ):

        self.rssi_threshold = rssi_threshold
        self.source_identifier_validation_func_list = valid_beacon_format_list
        self.equivalent_beacon_set = equivalent_beacon_set
        self.avg_flag = avg_rssi_flag
        self.activated_source_identifier_set = activated_source_identifier_set

    def preprocess_batch(self, raw_beacon_batch: List) -> List:
        # print(raw_beacon_batch)
        batch = raw_beacon_batch
        if self.source_identifier_validation_func_list:
            batch = self.filter_none_deploy_beacons(batch, self.source_identifier_validation_func_list)
        if self.activated_source_identifier_set:
            batch = self.filter_non_activated(batch)
        if self.rssi_threshold:
            batch = self.filter_weak_rssi(batch, self.rssi_threshold)
        if self.equivalent_beacon_set:
            batch = self.fuse_multi_in_one(batch)
        if self.avg_flag:
            batch = self.average_rssi(batch)
        # print(batch)
        return batch

    @staticmethod
    def filter_non_activated(batch_data: List) -> List:
        # todo
        return batch_data

    @staticmethod
    def average_rssi(batch_data: List) -> List:
        avg_batch = []
        batch_records = defaultdict(list)
        for beacon_data in batch_data:
            batch_records[beacon_data['target_identifier']].append(beacon_data)
        for batch in batch_records.values():
            target_identifier = batch[0]['target_identifier']
            data_type = batch[0]['data_type']
            source = batch[0]['source']

            rssi = 0
            for beacon_data in batch:
                rssi += beacon_data['value']['rssi']
            rssi = rssi / len(batch)

            timestamp = 1_000_000_000
            for beacon_data in batch:
                timestamp = max(timestamp, beacon_data['timestamp'])
            avg_batch.append(({'target_identifier': target_identifier,
                               'data_type': data_type,
                               'value':  {'rssi': rssi},
                               'source': source,
                               'timestamp': timestamp}))
        return avg_batch

    @staticmethod
    def filter_weak_rssi(batch_data: List, rssi_threshold: int) -> List:
        # todo: bad design, you don't even know how beacon_data looks like, but maybe I don't need to know how it looks like?
        batch = []
        if rssi_threshold is not None:
            for data in batch_data:
                if 'value' in data and 'rssi' in data['value'] and data['value']['rssi'] >= rssi_threshold:
                    batch.append(data)
        else:
            return batch_data
        return batch

    @staticmethod
    def fuse_multi_in_one(batch_data: List) -> List:
        # todo
        fused_multi_in_one_data = {}
        fused_beacon_data = []
        for d in batch_data:
            t, major_minor_str, rssi, beacon_info, ts = d
            if 'multi-in-one-id' in beacon_info.addition_info:
                multi_in_one_id = beacon_info.addition_info['multi-in-one-id']
                if multi_in_one_id in fused_multi_in_one_data:
                    if rssi < fused_multi_in_one_data[multi_in_one_id][2]:
                        continue
                    fused_multi_in_one_data[multi_in_one_id] = (t,
                                                                major_minor_str,
                                                                rssi,
                                                                (beacon_info.pos_x, beacon_info.pos_y,
                                                                 beacon_info.pos_z),
                                                                ts)
            else:
                fused_beacon_data.append((t,
                                          major_minor_str,
                                          rssi,
                                          (beacon_info.pos_x, beacon_info.pos_y, beacon_info.pos_z),
                                          ts)
                                         )
        return fused_beacon_data + list(fused_multi_in_one_data.values())

    @staticmethod
    def filter_none_deploy_beacons(batch_data: List, valid_beacon_format_list: List[Callable[[str], bool]]) -> List:
        batch = []
        if valid_beacon_format_list:
            for data in batch_data:
                if 'value' in data and 'manufacturer' in data['value']:
                    for valid_format in valid_beacon_format_list:
                        if valid_format(data['value']['manufacturer']):
                            batch.append(data)
        else:
            return batch_data
        return batch
        # def __filter_beacon_data(self, e: SensorEvent):
        #     if self.__site_config is None:
        #         return None
        #
        #     mac, rssi, manufacturer_data, other_ble_scan_info = e.values
        #     try:
        #         major_minor = MTR_SERVICE_MAJOR_MINOR.get_mtr_service_major_minor_from_manufacturer_data(manufacturer_data)
        #         assert major_minor is not None
        #     except:
        #         print(f'[estimator] incorrect major_minor')
        #         return None
        #
        #     try:
        #         beacon_info = self.__site_config.BEACON_TABLE[major_minor.raw]
        #         assert beacon_info is not None
        #     except:
        #         print(f'[estimator] major_minor not in table')
        #         return None
        #     else:
        #         if not beacon_info.activated:
        #             print(f'[estimator] beacon not activated')
        #             return None
        #         if beacon_info.major_minor.beacon_type != MTR_SERVICE_MAJOR_MINOR.BEACON_TYPE.BEACON_LOCALIZATION_TYPE.value:
        #
        #             print(f'[estimator] beacon not localization type')
        #             return None
        #         if rssi < self.__site_config.FILTER_MODULE_PARAMS['RSSI_THRESHOLD']:
        #
        #             print(f'[estimator] beacon rssi too weak')
        #             return None
        #         return 3, major_minor.raw, rssi, beacon_info, e.timestamp


class IMUDataPreprocessor:
    def __init__(self, **kwargs):
        pass

    def preprocess_batch(self, raw_imu_batch: List) -> List:
        return raw_imu_batch

    @staticmethod
    def imu_step_estimation():
        pass


class WIFIDataPreprocessor:
    def __init__(self, **kwargs):
        pass

    def preprocess_batch(self, raw_wifi_batch: List) -> List:
        return raw_wifi_batch

    @staticmethod
    def wifi_fuse_multi_ssid_in_one():
        pass

