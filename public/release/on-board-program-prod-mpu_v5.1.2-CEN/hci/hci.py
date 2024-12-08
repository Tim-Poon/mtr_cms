import hci.led as led
import hci.conf as conf
import RPi.GPIO as GPIO
import time
from common.component import Component
from threading import Thread
from common.event import *
from config.common import PI_BLE_MAC_ADDR
import threading
import hci.speaker as speaker
import hci.button as button
import hci.fan as fan
from collections import defaultdict

class HCI(Component):
	def __init__(self):
		super().__init__()
		self.add_event_publisher(HCIStatusEvent._event_type)
		self.add_event_listener(SystemStatusEvent._event_type, self._visualization_on)
		self.gpio_initialization()
		#self.pwm_led_list = [GPIO.PWM(conf.LED_PINS["GREEN_LED"].pin, conf.LED_PARAMS["LED_PWM"]),GPIO.PWM(conf.LED_PINS["RED_LED"].pin, conf.LED_PARAMS["LED_PWM"])]
		#self.pwm_initialization()
		self.add_event_listener(ResultEvent._event_type, self.alarm)
		self.add_event_listener(StepAlarmEvent._event_type, self.alarm)
		self.alarm_status = defaultdict(float)
		self.led_status_dict = {"ORANGE_LED": False, "WHITE_LED": False, "YELLOW_LED": False, "BLUE_LED": False, "GREEN_LED": False, "RED_LED": False}

	def start(self):
		Thread(target=self._button_control).start()
		Thread(target=self._fan_start).start()

	def gpio_initialization(self):
		GPIO.setmode(GPIO.BOARD)  # setup the board mode
		for led_color, led_info in conf.LED_PINS.items():
			GPIO.setup(led_info.pin, GPIO.OUT)

	def alarm(self, event: ResultEvent):
		if isinstance(event, ResultEvent) and event.value.overspeed_alarm > 0:
			# speaker.speaker(conf.SPEAKER_PARAMS['SPEAKER_PIN'], conf.SPEAKER_PARAMS['SPEAKER_LOOP'], conf.SPEAKER_PARAMS['SPEAKER_TIME_INTERVAL'])
			self.led_status_dict['ORANGE_LED'] = True
			led.led_control(conf.LED_PINS['ORANGE_LED'].pin, True)
			self.publish(HCIStatusEvent(identifier=PI_BLE_MAC_ADDR, value=self.led_status_dict))

	def _visualization_on(self, event: Event):
		self.led_status_dict['GREEN_LED'] = True
		led.led_control(conf.LED_PINS["GREEN_LED"].pin, True)
		if event.event_type == SystemStatusEvent._event_type:
			status_dict = event.status_dict
			pi_voltage_information = status_dict['pi_V']
			pi_voltage_processed = str(pi_voltage_information).replace("throttled=", "")
			pi_voltage_final = str(pi_voltage_processed).replace("\n", "")
			if led.under_voltage_detection(list(bin(int(pi_voltage_final, 16)))):
				self.led_status_dict['RED_LED'] = True
				led.led_control(conf.LED_PINS['RED_LED'].pin, True)
			network_status = status_dict['network']
			if network_status:
				led.led_control(conf.LED_PINS['BLUE_LED'].pin, True)
				self.led_status_dict['BLUE_LED'] = True
		self.publish(HCIStatusEvent(identifier=PI_BLE_MAC_ADDR, value=self.led_status_dict))

	def _fan_start(self):
		fan.fan_control(conf)

	def _button_control(self):
		final_led_status_dict = button.button_control(self.led_status_dict)
		self.publish(HCIStatusEvent(identifier=PI_BLE_MAC_ADDR, value=final_led_status_dict))

    # def print_sensor_data(self):
    #     beacon_data = event.values
    #     self.count_ble+=1
    #     print(f"beacon_data {self.count_ble}")
	# 	if event.sensor_type == SensorType.SENSOR_IMU:
    #         self.count_imu += 1
	#         print(f"imu_data {self.count_imu}")
	#         imu_data = event.values
	# 		print(f"all data {self.count_ble, self.count_imu}, average_data per second ({round((self.count_ble/(time.time()-self.start_time)),2)},{round((self.count_imu/(time.time()-self.start_time)),2)})")

	# def pwm_initialization():
	# 	for pin in pin_list:
	# 	    GPIO.setup(pin, GPIO.OUT)
	# 	    pwm_led = GPIO.PWM(pin, 100)
	# 	    pwm_led_list.append(pwm_led)

if __name__ == '__main__':
	pass
