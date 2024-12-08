from common.data_type import *
from typing import List, Union, Dict
import time


class Event(DictStringify):
    # todo: should provide a functionality to construct event from dict
    def __init__(self, **kwargs):
        for k, v in kwargs.items():
            self.__setattr__(f'_{k}', v)

    @property
    def identifier(self) -> Union[str, None]:
        # note: semantic of identifier of different event are different
        # note: semantics will also change across projects, which will affect api server
        raise NotImplementedError

    @property
    def event_type(self) -> str:
        raise NotImplementedError

    @property
    def timestamp(self) -> float:
        raise NotImplementedError

    @property
    def value(self) -> Union[DictStringify, None]:
        raise NotImplementedError


class DebugEvent(Event):
    _event_type = 'debug_event'

    def __init__(self, **kwargs):
        super().__init__(**kwargs)

        for k, v in kwargs.items():
            self.__setattr__(f'_{k}', v)

        if 'event_type' not in kwargs:
            self._event_type = DebugEvent._event_type

        if not hasattr(self, '_timestamp'):
            self._timestamp = time.time()
        if not hasattr(self, '_value') or not isinstance(self._value, DictStringify):
            self._value = None

    @property
    def identifier(self) -> Union[str, None]:
        # note: semantic of identifier of different event are different
        # note: semantics will also change across projects, which will affect api server
        if hasattr(self, 'identifier'):
            return self.identifier
        else:
            return None

    @property
    def event_type(self) -> str:
        return self._event_type

    @property
    def timestamp(self) -> float:
        return self._timestamp

    @property
    def value(self) -> Union[DictStringify, None]:
        return self._value


class SensorEvent(Event):
    _event_type = 'sensor_data'  # todo: figure out the implementation

    def __init__(self, identifier: str, value: SensorDataPackage, **kwargs):
        super().__init__(**kwargs)
        self._identifier = identifier
        self._timestamp = value.timestamp
        self._value = value
        if 'event_type' not in kwargs:
            self._event_type = SensorEvent._event_type

    @property
    def identifier(self) -> Union[str, None]:
        return self._identifier

    @property
    def event_type(self) -> str:
        return self._event_type

    @property
    def timestamp(self) -> float:
        return self._timestamp

    @property
    def value(self) -> SensorDataPackage:
        return self._value


class LogEvent(Event):
    _event_type = 'log_msg'

    def __init__(self, identifier: str, value: LogMessage, **kwargs):
        super().__init__(**kwargs)
        self._identifier = identifier
        self._timestamp = time.time()
        self._value = value
        if 'event_type' not in kwargs:
            self._event_type = LogEvent._event_type

    @property
    def identifier(self) -> Union[str, None]:
        return self._identifier

    @property
    def event_type(self) -> str:
        return self._event_type

    @property
    def timestamp(self) -> float:
        return self._timestamp

    @property
    def value(self) -> LogMessage:
        return self._value


class BeaconPowerEvent(Event):
    _event_type = 'beacon_power'

    def __init__(self, identifier: str, value: BeaconValue, **kwargs):
        super().__init__(**kwargs)
        self._identifier = identifier
        self._timestamp = time.time()
        self._value = value
        if 'event_type' not in kwargs:
            self._event_type = BeaconPowerEvent._event_type

    @property
    def identifier(self) -> Union[str, None]:
        return self._identifier

    @property
    def event_type(self) -> str:
        return self._event_type

    @property
    def timestamp(self) -> float:
        return self._timestamp

    @property
    def value(self) -> BeaconValue:
        return self._value


class SystemStatusEvent(Event):
    _event_type = 'system_status_monitoring'

    def __init__(self, identifier: str, value, status_dict: Dict, timestamp=None, **kwargs):
        super().__init__(**kwargs)
        self._identifier = identifier
        self._value = value
        self._timestamp = timestamp if timestamp is not None else time.time()
        self._status_dict = status_dict
        if 'event_type' not in kwargs:
            self._event_type = SystemStatusEvent._event_type

    @property
    def identifier(self) -> Union[str, None]:
        return self._identifier

    @property
    def event_type(self) -> str:
        return self._event_type

    @property
    def timestamp(self) -> float:
        return self._timestamp

    @property
    def value(self):
        return self._value

    @property
    def status_dict(self) -> Dict:
        return self._status_dict


class HCIStatusEvent(Event):
    _event_type = 'system_status'

    def __init__(self, identifier: str, value, timestamp=None, **kwargs):
        super().__init__(**kwargs)
        self._identifier = identifier
        self._value = value
        self._timestamp = timestamp if timestamp is not None else time.time()
        if 'event_type' not in kwargs:
            self._event_type = HCIStatusEvent._event_type

    @property
    def identifier(self) -> Union[str, None]:
        return self._identifier

    @property
    def event_type(self) -> str:
        return self._event_type

    @property
    def timestamp(self) -> float:
        return self._timestamp

    @property
    def value(self):
        return self._value


# note: different project may have different type of ResultEvent, go define yourself
class ResultEvent(Event):
    _event_type = 'result_data'

    def __init__(self, identifier: str, value: PositionVelocityAlarm, **kwargs):
        super().__init__(**kwargs)
        self._identifier = identifier
        self._timestamp = value.timestamp
        self._value = value
        if 'event_type' not in kwargs:
            self._event_type = ResultEvent._event_type

    @property
    def identifier(self) -> Union[str, None]:
        return self._identifier

    @property
    def event_type(self) -> str:
        return self._event_type

    @property
    def timestamp(self) -> float:
        return self._timestamp

    @property
    def value(self) -> PositionVelocityAlarm:
        return self._value


class PositionEvent(Event):
    _event_type = 'localization_event'

    def __init__(self, identifier: str, value: Position, **kwargs):
        super().__init__(**kwargs)
        self._identifier = identifier
        self._timestamp = value.timestamp
        self._value = value
        if 'event_type' not in kwargs:
            self._event_type = PositionEvent._event_type

    @property
    def identifier(self) -> Union[str, None]:
        return self._identifier

    @property
    def event_type(self) -> str:
        return self._event_type

    @property
    def timestamp(self) -> float:
        return self._timestamp

    @property
    def value(self) -> Position:
        return self._value


class PositionVelocityEvent(Event):
    _event_type = 'result_without_alarm'

    def __init__(self, identifier: str, value: PositionVelocity, **kwargs):
        super().__init__(**kwargs)
        self._identifier = identifier
        self._timestamp = value.timestamp
        self._value = value
        if 'event_type' not in kwargs:
            self._event_type = PositionVelocityEvent._event_type

    @property
    def identifier(self) -> Union[str, None]:
        return self._identifier

    @property
    def event_type(self) -> str:
        return self._event_type

    @property
    def timestamp(self) -> float:
        return self._timestamp

    @property
    def value(self) -> PositionVelocity:
        return self._value


class StepAlarmEvent(Event):
    _event_type = 'step_alarm'

    def __init__(self, identifier: str, value: bool, step: int, **kwargs):
        super().__init__(**kwargs)
        self._identifier = identifier
        self._timestamp = time.time() if 'timestamp' not in kwargs else kwargs['timestamp']
        self._value = value
        self._step = step
        self._event_type = StepAlarmEvent._event_type if 'event_type' not in kwargs else kwargs['event_type']

    @property
    def identifier(self) -> Union[str, None]:
        return self._identifier

    @property
    def event_type(self) -> str:
        return self._event_type

    @property
    def timestamp(self) -> float:
        return self._timestamp

    @property
    def value(self) -> bool:
        return self._value

    @property
    def step(self) -> int:
        return self._step


class StepEvent(Event):
    _event_type = 'step'

    def __init__(self, identifier: str, value: int, **kwargs):
        super().__init__(**kwargs)
        self._identifier = identifier
        self._timestamp = time.time() if 'timestamp' not in kwargs else kwargs['timestamp']
        self._value = value
        self._event_type = StepEvent._event_type if 'event_type' not in kwargs else kwargs['event_type']

    @property
    def identifier(self) -> Union[str, None]:
        return self._identifier

    @property
    def event_type(self) -> str:
        return self._event_type

    @property
    def timestamp(self) -> float:
        return self._timestamp

    @property
    def value(self) -> int:
        return self._value


class RemoteRequestIdEvent(Event):
    _event_type = 'id_request'

    def __init__(self, identifier: str):
        super().__init__()
        self._identifier = identifier
        self._timestamp = time.time()
        self._value = None
        self._event_type = RemoteRequestIdEvent._event_type

    @property
    def identifier(self) -> Union[str, None]:
        return self._identifier

    @property
    def event_type(self) -> str:
        return self._event_type

    @property
    def timestamp(self) -> float:
        return self._timestamp

    @property
    def value(self) -> Union[DictStringify, None]:
        return self._value


class RemoteNotifyIdEvent(Event):
    _event_type = 'id_notification'

    def __init__(self, identifier, timestamp, site_name, **kwargs):
        super(RemoteNotifyIdEvent, self).__init__(**kwargs)
        self._identifier = identifier
        self._timestamp = timestamp
        self._value = site_name

    @property
    def identifier(self) -> Union[str, None]:
        # note: semantic of identifier of different event are different
        # note: semantics will also change across projects, which will affect api server
        return self._identifier

    @property
    def event_type(self) -> str:
        return RemoteNotifyIdEvent._event_type

    @property
    def timestamp(self) -> float:
        return self._identifier

    @property
    def value(self) -> str:
        return self._value


class BackupEvent(Event):
    _event_type = 'data_backup'

    def __init__(self, identifier, value: List, **kwargs):
        super(BackupEvent, self).__init__(**kwargs)
        self._identifier = identifier
        self._timestamp = time.time()
        self._value = value
        if 'event_type' not in kwargs:
            self._event_type = BackupEvent._event_type

    @property
    def identifier(self) -> Union[str, None]:
        return self._identifier

    @property
    def event_type(self) -> str:
        return self._event_type

    @property
    def timestamp(self) -> float:
        return self._identifier

    @property
    def value(self) -> List:
        return self._value


class BackupUploadSuccessNotificationEvent(Event):
    _event_type = 'resend_success'

    def __init__(self, identifier, value: bool, **kwargs):
        super(BackupUploadSuccessNotificationEvent, self).__init__(**kwargs)
        self._identifier = identifier
        self._timestamp = time.time()
        self._value = value
        if 'event_type' not in kwargs:
            self._event_type = BackupUploadSuccessNotificationEvent._event_type

    @property
    def identifier(self) -> Union[str, None]:
        return self._identifier

    @property
    def event_type(self) -> str:
        return self._event_type

    @property
    def timestamp(self) -> float:
        return self._identifier

    @property
    def value(self) -> bool:
        return self._value


if __name__ == "__main__":
    log = LogEvent(identifier='abcde', value=LogMessage(level="debug", msg="test"), others=123)
    print(log)
    log.vm = 1.0
    log.ble_mac = "00:00:00:00:00"
    print(log)
    print(str(log))

    result_event = ResultEvent(identifier='abcde', value=Position(x=1, y=1, z=1, timestamp=time.time(), pos_type=1))
    print(result_event)

    remote_request_event = RemoteRequestIdEvent(identifier='abcde')
    print(remote_request_event)

