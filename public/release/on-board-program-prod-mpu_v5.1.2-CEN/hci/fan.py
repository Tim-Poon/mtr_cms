import RPi.GPIO as GPIO

try:
	import conf
except:
	import hci.conf as conf
import time
import os

def fan_control(conf):
	def setFanSpeed(speed, fan):
		fan.start(speed)
		return ()

	# Control fan speed
	def control_fan_speed(temperature):
		if temperature < conf.FAN_PARAMS['MIN_TEMP']:  # Turn off the fan if temperature is below MIN_TEMP
			setFanSpeed(conf.FAN_PARAMS['FAN_OFF_SPEED'], fan)
		elif temperature > conf.FAN_PARAMS['MAX_TEMP']:  # Set fan speed to MAXIMUM if the temperature is above MAX_TEMP
			setFanSpeed(conf.FAN_PARAMS['FAN_ON_SPEED'], fan)
		else:
			step = float(conf.FAN_PARAMS['FAN_HIGH_SPEED'] - conf.FAN_PARAMS['FAN_LOW_SPEED']) / float(
				conf.FAN_PARAMS['MAX_TEMP'] - conf.FAN_PARAMS['MIN_TEMP'])
			temperature -= conf.FAN_PARAMS['MIN_TEMP']
			setFanSpeed(conf.FAN_PARAMS['FAN_LOW_SPEED'] + (round(temperature) * step), fan)
		return ()

	# Setup GPIO pin
	GPIO.setwarnings(False)
	GPIO.setmode(GPIO.BOARD)
	GPIO.setup(conf.FAN_PARAMS['FAN_PIN'], GPIO.OUT, initial=GPIO.LOW)
	fan = GPIO.PWM(conf.FAN_PARAMS['FAN_PIN'], conf.FAN_PARAMS['PWM_FREQ'])
	setFanSpeed(conf.FAN_PARAMS['FAN_OFF_SPEED'], fan)
	# Handle fan speed every WAIT_TIME sec
	while True:
		CPU_temperature = os.popen('vcgencmd measure_temp').readline()
		final_CPU_temperature = float(CPU_temperature.replace("temp=", "").replace("'C\n", ""))
		control_fan_speed(final_CPU_temperature)
		time.sleep(conf.FAN_PARAMS['WAIT_TIME'])

if __name__ == '__main__':
	# temp = 100
	# fan_control(temp, conf)
	fan_control(conf)
