from collections import namedtuple

## led configuration
LED_INFO = namedtuple("LED_INFO", ["color", "pin", "led_order"])
LED_PARAMS = {'LED_SLEEP_INTERVAL': 59, 'LED_LIGHT_STRENGTH': 10, "LED_PWM":100}  # from left to right
LED_PINS = {"ORANGE_LED": LED_INFO(color='orange', pin=11, led_order=1),
            "WHITE_LED": LED_INFO(color='white', pin=13, led_order=2),
            "YELLOW_LED": LED_INFO(color='yellow', pin=15, led_order=3),
            "BLUE_LED": LED_INFO(color='blue', pin=16, led_order=4),
            "GREEN_LED": LED_INFO(color='green', pin=18, led_order=5),
            "RED_LED": LED_INFO(color='red', pin=22, led_order=6)}
## speaker configuration
SPEAKER_PARAMS = {'SPEAKER_PIN': 32, 'SPEAKER_LOOP': 2, 'SPEAKER_TIME_INTERVAL': 0.2}  # it can't be empty list or None.
## button_configuration
BUTTON_PARAMS = {'BUTTON_ON_PIN': 26, 'BUTTON_OFF_PIN': 31, 'BUTTON_PRESS_INITIAL_COUNTER': 0, "BUTTON_SLEEP_TIME": 1}
###fan
FAN_PARAMS = {'FAN_PIN': 33, 'WAIT_TIME': 20, 'PWM_FREQ': 50,
              'MIN_TEMP': 40, 'MAX_TEMP': 60,'FAN_LOW_SPEED': 20, 'FAN_HIGH_SPEED': 100, 'FAN_OFF_SPEED': 0,'FAN_ON_SPEED': 100}
# WAIT_TIME - [s] Time to wait between each refresh & PWM_FREQ - [Hz] 25kHz for Noctua PWM control
