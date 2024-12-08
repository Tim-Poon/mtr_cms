from typing import List, Tuple
from collections import deque
from common.component import Component
from common.event import *
from common.data_type import *
from config.common import SENSOR_SITE, PI_BLE_MAC_ADDR
import importlib
import traceback
import math


class VelocityEstimator(Component):
    def __init__(self):
        super().__init__()
        self.add_event_publisher(event_type=PositionVelocityEvent._event_type)
        self.add_event_publisher(event_type=LogEvent._event_type)
        self.add_event_listener(PositionEvent._event_type, self.on_receive_position_event)
        self._velocity_estimator: MAYBE[LeastSquareVelocityModule] = None

    def start(self):
        try:
            site_config = importlib.import_module(name=f'config.{SENSOR_SITE}')
        except Exception as err:
            self.publish(LogEvent(identifier=PI_BLE_MAC_ADDR,
                                  value=LogMessage(level='error',
                                                   msg=f"unable to load config for core: {traceback.format_exc()}")))
            self.remove_event_listener(self.on_receive_position_event)
        else:
            self._velocity_estimator = LeastSquareVelocityModule(window_size=site_config.VELOCITY_MODULE_PARAMS['VELOCITY_LEAST_SQUARE_WINDOW'],
                                                                 cal_fq=site_config.VELOCITY_MODULE_PARAMS['LOCALIZATION_CAL_PERIOD'])

    def on_receive_position_event(self, e: PositionEvent):
        if self._velocity_estimator:
            cur_pos = (e.value.x, e.value.y, e.value.z)
            cur_vel = self._velocity_estimator.estimate_velocity(cur_pos)
            self.publish(PositionVelocityEvent(identifier=PI_BLE_MAC_ADDR,
                                               value=PositionVelocity(x=cur_pos[0],
                                                                      y=cur_pos[1],
                                                                      z=cur_pos[2],
                                                                      vx=cur_vel[0],
                                                                      vy=cur_vel[1],
                                                                      vz=cur_vel[2],
                                                                      timestamp=e.timestamp,
                                                                      pos_type=e.value.pos_type,
                                                                      raw_p=e.value._raw_p)))


class LeastSquareVelocityModule:
    def __init__(self, window_size, cal_fq):
        self.cal_fq = float(cal_fq)
        self.history_position = deque([], maxlen=int(window_size))
        self.history_ts = deque([], maxlen=window_size)
        self.latest_ts = 0

        self.last_velocity = (0., 0., 0.)

    def estimate_velocity(self, cur_pos: Tuple[float, float, float]) -> Tuple[float, float, float]:
        self.latest_ts += self.cal_fq            # accumulate time in self.latest_ts
        self.history_position.append(cur_pos)
        self.history_ts.append(self.latest_ts)
        res = self._calculate_velocity_using_least_square()
        self._normalized_ts()
        return res

    def _calculate_velocity_using_least_square(self) -> Tuple[float, float, float]:
        total_time_slot = len(self.history_position)
        if total_time_slot < 2:
            return 0., 0., 0.
        t_sum, t_square_sum, x_sum, y_sum, z_sum, tx_sum, ty_sum, tz_sum = 0, 0, 0, 0, 0, 0, 0, 0
        i = 1
        while i < total_time_slot:
            cur_t = self.history_ts[i] - self.history_ts[0]
            x_sum += abs(self.history_position[i][0] - self.history_position[0][0])
            y_sum += abs(self.history_position[i][1] - self.history_position[0][1])
            i += 1
        vx = math.sqrt(x_sum)/cur_t
        vy = math.sqrt(y_sum)/cur_t
        vz = 0
        # print(f'{x_sum:.2f},{y_sum:.2f},{cur_t:.2f}, {x_sum/cur_t:.2f},{y_sum/cur_t:.2f}, {round(math.sqrt((x_sum/cur_t) ** 2 + (y_sum/cur_t) ** 2), 2):.2f}')
        # print(vx, vy, vz)
        cur_vel = (vx, vy, vz)
        self.last_velocity = cur_vel
        return cur_vel
        # for i in range(total_time_slot):
        #     cur_t = self.history_ts[i] - self.history_ts[0]
        #     # cur_t = self.history_ts[i] - start_time  # it's ok to have same base time and it won't out of range
        #     x_sum += self.history_position[i][0]
        #     y_sum += self.history_position[i][1]
        #     z_sum += self.history_position[i][2]

        #     t_sum += cur_t
        #     t_square_sum += cur_t ** 2

        #     tx_sum += cur_t * self.history_position[i][0]
        #     ty_sum += cur_t * self.history_position[i][1]
        #     tz_sum += cur_t * self.history_position[i][2]
        # # print(f"{x_sum, y_sum, z_sum}")
        # try:
        #     t_mean, tx_mean, ty_mean, tz_mean = t_sum / total_time_slot, tx_sum / total_time_slot, ty_sum / total_time_slot, tz_sum / total_time_slot
        #     x_mean, y_mean, z_mean, t_square_mean = x_sum / total_time_slot, y_sum / total_time_slot, z_sum / total_time_slot, t_square_sum / total_time_slot

        #     cur_vel = (
        #         round((tx_mean - t_mean * x_mean) / (t_square_mean - t_mean**2),2),
        #         round((ty_mean - t_mean * y_mean) / (t_square_mean - t_mean**2),2),
        #         (tz_mean - t_mean * z_mean) / (t_square_mean - t_mean**2),
        #     )  # todo: bug when calculating vz
        #     self.last_velocity = cur_vel
        #     return cur_vel
        # except ZeroDivisionError:
        #     print("division by zero")
        #     print("history_position = ", self.history_position)
        #     print("history_ts = ", self.history_ts)
        #     return self.last_velocity

    def _normalized_ts(self):
        offset = self.history_ts[0]
        for i in range(len(self.history_ts)):
            self.history_ts[i] -= offset
        if len(self.history_ts) > 0:
            self.latest_ts = self.history_ts[-1]
