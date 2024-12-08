import time
import threading
from collections import deque
from typing import List, Sequence, Callable
from config.common import SENSOR_SITE, PI_BLE_MAC_ADDR
from common.component import Component
from common.event import *
from common.data_type import *
import importlib
import traceback
import math
from functools import partial


class OverSpeedEstimator(Component):
    def __init__(self):
        super().__init__()
        self.add_event_listener(StepEvent._event_type, self.on_receive_step_event)
        self.add_event_listener(PositionVelocityEvent._event_type, self.on_receive_result_event_without_alarm)  # note: don't know why it warns
        self.add_event_publisher(ResultEvent._event_type)

        self._alarm_zone: MAYBE[List] = None
        # self._start_time = time.time()
        self._history_vel: MAYBE[deque[float]] = None  # over speed more than #alarm_tolerance, then we consider it as real over speed
        self._history_step: MAYBE[deque[int]] = None
        self.latest_step: MAYBE[int] = None

    def start(self):
        try:
            site_config = importlib.import_module(name=f'config.{SENSOR_SITE}')
        except Exception as err:
            self.publish(LogEvent(identifier=PI_BLE_MAC_ADDR,
                                  value=LogMessage(level='error',
                                                   msg=f"unable to load config for core: {traceback.format_exc()}")))
            self.remove_event_listener(self.on_receive_result_event_without_alarm)
            self.remove_event_listener(self.on_receive_step_event)
        else:
            self.alarm_rule_continuous_very_high_speed = partial(self.alarm_rule_continuous_very_high_speed,
                                                                 very_high_threshold=site_config.ALARM_PARAMS['ALARM_VELOCITY_THRESHOLD_HIGHER'],
                                                                 definition_of_continuous=site_config.ALARM_PARAMS['ALARM_TOLERANCE_NUMBER'],
                                                                 )

            self.alarm_rule_very_high_step = partial(self.alarm_rule_very_high_step,
                                                     very_high_step_threshold=site_config.ALARM_PARAMS['STEP_ALARM_THRESHOLD_HIGHER']
                                                     )

            self.alarm_rule_both_high_speed_and_high_step = partial(self.alarm_rule_both_high_speed_and_high_step,
                                                                    high_speed_threshold=site_config.ALARM_PARAMS['ALARM_VELOCITY_THRESHOLD_LOWER'],
                                                                    high_step_threshold=site_config.ALARM_PARAMS['STEP_ALARM_THRESHOLD_LOWER'],
                                                                    )

            self._alarm_zone = site_config.ALARM_PARAMS['ALARM_ZONE']
            self._history_vel = deque(maxlen=site_config.ALARM_PARAMS['ALARM_TOLERANCE_NUMBER'])
            self._history_step = deque(maxlen=site_config.ALARM_PARAMS['ALARM_TOLERANCE_NUMBER'])

    def _pos_within_alarm_zone(self, pos, alarm_zone):
        res = False
        for rectangle in alarm_zone:
            p1, p2, p3, p4 = rectangle
            p1_p2_cross_product_p1_p = (p1[0] - p2[0]) * (p1[1] - pos[1]) - (p1[1] - p2[1]) * (p1[0] - pos[0])
            p3_p4_cross_product_p3_p = (p3[0] - p4[0]) * (p3[1] - pos[1]) - (p3[1] - p4[1]) * (p3[0] - pos[0])

            p1_p4_cross_product_p1_p = (p1[0] - p4[0]) * (p1[1] - pos[1]) - (p1[1] - p4[1]) * (p1[0] - pos[0])
            p3_p2_cross_product_p3_p = (p3[0] - p2[0]) * (p3[1] - pos[1]) - (p3[1] - p2[1]) * (p3[0] - pos[0])
            res |= (p1_p2_cross_product_p1_p * p3_p4_cross_product_p3_p >= 0) and (p1_p4_cross_product_p1_p * p3_p2_cross_product_p3_p >= 0)
        return res

    def on_receive_result_event_without_alarm(self, e: PositionVelocityEvent):
        # threading.Thread(target=self._check_speeding, args=(pos, vel)).start()
        pos = e.value.x, e.value.y, e.value.z
        vel = e.value.vx, e.value.vy, e.value.vz
        vel_norm = math.sqrt(vel[0] ** 2 + vel[1] ** 2)
        self._history_vel.append(vel_norm)

        alarm = True
        if not self._pos_within_alarm_zone(pos, self._alarm_zone):
            alarm = False
        else:
            try:
                if self.latest_step != -1 and self.latest_step < 1.41:
                    alarm = False
                if self.latest_step == -1:
                    # on cart
                    alarm = False
                # print(f'TEST-----------------------------------------------, {self.latest_step:.2f},
                # {vel_norm:.2f}, {alarm}') if not ( # self.alarm_rule_continuous_very_high_speed(self._history_vel)
                # or self.alarm_rule_very_high_step(self.latest_step) or
                # self.alarm_rule_both_high_speed_and_high_step(vel=vel_norm, step=self.latest_step)): alarm = False
            except:
                alarm = False

        # cur_time = time.time()

        # if cur_time - self._start_time < 20:
        #     self._history_overspeed_alarm.append(False)
        # elif len(self._alarm_zone[0]) == 0:
        #     if vel_norm > self._speed_threshold:
        #         self._history_overspeed_alarm.append(True)
        #     else:
        #         self._history_overspeed_alarm.append(False)
        # else:
        #     cur_over_speed = False
        #     if self._pos_within_alarm_zone(pos) and vel_norm > self._speed_threshold:
        #         cur_over_speed = True
        #     self._history_overspeed_alarm.append(cur_over_speed)
        #
        # res = True
        # for recent_result in self._history_overspeed_alarm:
        #     res = res and recent_result
        # with self._alarm_flag_lock:
        #     self._alarm_flag = self._alarm_step_flag and res   # alarm when both overstep and overspeed
        vel_step = round(vel_norm, 2)
        if self.latest_step != None:
            vel_step = self.latest_step
        self.publish(ResultEvent(identifier=PI_BLE_MAC_ADDR,
                                 value=PositionVelocityAlarm(x=e.value.x,
                                                             y=e.value.y,
                                                             z=e.value.z,
                                                             vx=vel_step,
                                                             vy=0,
                                                             vz=e.value.vy,
                                                             overspeed_alarm=alarm,
                                                             timestamp=e.timestamp,
                                                             pos_type=e.value.pos_type,
                                                             raw_p=e.value._raw_p
                                                             )))

    def on_receive_step_event(self, step_event: StepEvent):
        step = step_event.value
        # print(f'#step = {step}')
        self.latest_step = step
        self._history_step.append(step)

    def alarm_rule_continuous_very_high_speed(self,
                                              history_vels: Sequence[float],
                                              very_high_threshold: float,
                                              definition_of_continuous: int) -> bool:
        mask = '1' * definition_of_continuous
        history_very_high_speed_flags = ''
        for history_vel in history_vels:
            if history_vel > very_high_threshold:
                history_very_high_speed_flags += '1'
            else:
                history_very_high_speed_flags += '0'
        return mask in history_very_high_speed_flags

    def alarm_rule_very_high_step(self,
                                  step: int,
                                  very_high_step_threshold: int
                                  ) -> bool:
        return step >= very_high_step_threshold

    def alarm_rule_both_high_speed_and_high_step(self,
                                                 vel: float,
                                                 step: int,
                                                 high_speed_threshold: float,
                                                 high_step_threshold: float,
                                                 ) -> bool:
        return vel > high_speed_threshold and step > high_step_threshold


