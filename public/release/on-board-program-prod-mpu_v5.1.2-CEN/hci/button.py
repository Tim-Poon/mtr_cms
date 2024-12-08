import RPi.GPIO as GPIO
import time
import hci.led as led
import os
import hci.conf as conf
from threading import Thread

def button_callback1(channel):
    print(f"Key 1 is pressed")

def button_callback2(channel):
    print(f"Key 2 is pressed")

def button_control(led_status_dict):
    GPIO.setwarnings(False)  # Ignore warning for now
    # GPIO.setmode(GPIO.BOARD)  # Use physical pin numbering
    GPIO.setup(conf.BUTTON_PARAMS["BUTTON_ON_PIN"], GPIO.IN, pull_up_down=GPIO.PUD_DOWN)  # Set pinto be an input pin and set initial value to be pulled low (off)
    #GPIO.add_event_detect(button_on_pin, GPIO.RISING, callback=button_callback1)  # Setup event on pin rising edge
    GPIO.setup(conf.BUTTON_PARAMS["BUTTON_OFF_PIN"], GPIO.IN, pull_up_down=GPIO.PUD_DOWN)  # Set pinto be an input pin and set initial value to be pulled low (off)
    #GPIO.add_event_detect(button_off_pin, GPIO.RISING, callback=button_callback2)  # Setup event on pin rising edge
    while True:
        if GPIO.input(conf.BUTTON_PARAMS["BUTTON_ON_PIN"]) == GPIO.LOW:
            
            # if GPIO.input(conf.BUTTON_PARAMS["BUTTON_ON_PIN"]) == GPIO.HIGH:  # decide whether the button is pressed second time
            #     while GPIO.input(conf.BUTTON_PARAMS["BUTTON_ON_PIN"]) == GPIO.HIGH:  # after pressing, the button will be loosen,
            #         pass
            led.led_control(conf.LED_PINS['ORANGE_LED'].pin, False)
            led_status_dict["ORANGE_LED"] = False
        time.sleep(conf.BUTTON_PARAMS["BUTTON_SLEEP_TIME"])  # time delay
    return led_status_dict
    # elif GPIO.input(button_off_pin) == GPIO.HIGH:
    #     time.sleep(0.01)  # time delay
    #    # print(f"Button {button_off_pin} (off) needed to be pressed!")
    #     if GPIO.input(button_off_pin) == GPIO.HIGH:  # decide whether the button is pressed second time
    #         time.sleep(0.01)  # time delay
    #         # while GPIO.input(button_off_pin) == GPIO.HIGH:  # after pressing, the button will be loosen,
    #         #     pass
    #         led_status_dict["WHITE_LED"] = True
            #led.led_control(conf.LED_PINS["WHITE_LED"].pin, True)
    final_led_status_dict = led_status_dict
    #print("final_led_status_dict",final_led_status_dict)
    return final_led_status_dict
                # todo listen HCI status
                # todo publish updated HCI status
                # todo integrated with the hci module
                # todo test the fan module, control the speed via the tempature and integrated
                # todo add another button function for future usage
                # self.led_status_dict['ORANGE_LED'] = False
                # self.publish(HCIStatusEvent(identifier=PI_BLE_MAC_ADDR, value=self.led_status_dict))

if __name__ == '__main__':
   GPIO.setmode(GPIO.BOARD)
   for led_color, led_info in conf.LED_PINS.items():
        GPIO.setup(led_info.pin, GPIO.OUT)
   button_on_pin = conf.BUTTON_PARAMS["BUTTON_ON_PIN"]
   button_off_pin = conf.BUTTON_PARAMS["BUTTON_OFF_PIN"]
   button_control(button_on_pin,button_off_pin)
