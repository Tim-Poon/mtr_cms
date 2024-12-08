import RPi.GPIO as GPIO
import time
import hci.conf as conf


def speaker(alarm_pin, alarm_loop, alarm_time_interval):
    GPIO.setwarnings(False)
    GPIO.setmode(GPIO.BOARD)
    GPIO.setup(alarm_pin, GPIO.OUT)
    for i in range(alarm_loop):
        GPIO.output(alarm_pin, True)
        time.sleep(alarm_time_interval)
        GPIO.output(alarm_pin, False)
        time.sleep(alarm_time_interval)


if __name__ == '__main__':
    speaker(conf.SPEAKER_PARAMS['SPEAKER_PIN'], conf.SPEAKER_PARAMS['SPEAKER_LOOP'],
            conf.SPEAKER_PARAMS['SPEAKER_TIME_INTERVAL'])
