import RPi.GPIO as GPIO
try:
	import hci.conf as conf
except:
	import conf
import time
import threading


class LED(threading.Thread):
	def __init__(self, threadID, name, counter, pin_list, pwm_led_list):
		threading.Thread.__init__(self)
		self.threadID = threadID
		self.name = name
		self.counter = counter
		self.pin_list = pin_list
		self.pwm_led_list = pwm_led_list

	def run(self):
		leds_group(self.pin_list, self.pwm_led_list, conf.led_light_strength[1])


def leds_control(pin, pwm_led, light, flag):
	GPIO.setmode(GPIO.BOARD)  # setup the board mode
	GPIO.setup(pin, GPIO.OUT)
	pwm_led.start(light)  # setup the light
	try:
		while flag:
			GPIO.output(pin, True)
		else:
			GPIO.output(pin, False)
	except:
		print("Keyboard interruption!")
		GPIO.output(pin, False)
	finally:
		GPIO.cleanup()


def led_on_and_off(led_sleep_interval, pin, light):
	GPIO.setmode(GPIO.BOARD)  # setup the board mode
	GPIO.setup(pin, GPIO.OUT)
	pwm_led = GPIO.PWM(pin, 1000)
	pwm_led.start(light)  # setup the light
	GPIO.output(pin, True)
	time.sleep(led_sleep_interval)
	GPIO.output(pin, False)


def leds_group(led_status):
	# for pwm_led in pwm_led_list:
	# 	pwm_led.start(conf.LED_PARAMS['LED_LIGHT_STRENGTH'])  # setup the light
	for led_color, status in led_status.items():
		GPIO.setup(conf.LED_PINS[led_color].pin, GPIO.OUT)
		GPIO.output(conf.LED_PINS[led_color].pin, status)
		time.sleep(2)

def led_control(led_pin, status):
	GPIO.output(led_pin, status)

def under_voltage_detection(voltage_information_list) -> bool:
	for idx, bit_value in enumerate(voltage_information_list):
		if len(voltage_information_list) == 22:
			if idx == 3 and int(bit_value) == 1:
				led_control(conf.LED_PINS["RED_LED"].pin, True)
				return True
			elif idx == 5 and int(bit_value) == 1:
				led_control(conf.LED_PINS["RED_LED"].pin, True)
				return True
			elif idx == 21 and int(bit_value) == 1:
				led_control(conf.LED_PINS["RED_LED"].pin, True)
				return True
			else:
				led_control(conf.LED_PINS["RED_LED"].pin, False)
				return False
		else:
			pwm = GPIO.PWM(conf.LED_PINS["RED_LED"].pin, conf.LED_PARAMS["LED_PWM"])
			pwm.stop()
			led_control(conf.LED_PINS["RED_LED"].pin, False)
			return False


if __name__ == "__main__":
	GPIO.setmode(GPIO.BOARD)  # setup the board mode
	led_status = {"ORANGE_LED": True,"WHITE_LED":False,"YELLOW_LED":False, "BLUE_LED":False,"GREEN_LED":True,"RED_LED":False}
	pwm_led_list = []
	for led_color, led_info in conf.LED_PINS.items():
		if led_status[led_color]:
			GPIO.setup(led_info.pin, GPIO.OUT)
			pwm_led = GPIO.PWM(led_info.pin, 100)
			pwm_led_list.append(pwm_led)
	#led_control(conf.led_pins[2], pwd_led_list[2], conf.led_light_strength[1], flag)
	leds_group(led_status, pwm_led_list)
	GPIO.cleanup()
