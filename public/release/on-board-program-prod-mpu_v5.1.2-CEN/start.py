import sys
import os
full_path = os.path.abspath(__file__)
dir_name = os.path.dirname(full_path)
sys.path = [dir_name] + sys.path
from common.router import Router
from common.component import Component
import config.common as config
import importlib
import atexit
import os
from multiprocessing import Process, Queue
import os, time, random

def set_feq_file():
    set_feq = os.popen("grep 'i2c_arm_baudrate=1000000' /boot/config.txt")
    set_feq_return = False
    try:
        set_feq_return = set_feq.readlines()[0]
    except:
        pass
    if set_feq_return:
        print('Already set maximun frequency.')
    else:
        print('Setting maximun frequency!')
        set_feq = os.popen("sed -i 's/dtparam=i2c_arm=on/dtparam=i2c_arm=on,i2c_arm_baudrate=1000000/' /boot/config.txt")
        os.popen("reboot")

def on_exit():
    import RPi.GPIO as GPIO
    GPIO.cleanup()
    print('on_exit: exit')


if __name__ == "__main__":
    update0 = os.popen('sudo dpkg --configure -a')
    print(update0.readlines())
    update1 = os.popen('sudo apt-get install bluetooth libbluetooth-dev -y')
    print(update1.readlines())
    update2 = os.popen('sudo pip3 install pybluez')
    print(update2.readlines())
    set_feq_file()

    Router().loop_forever(daemon=True)
    loaded_modules = []
    for m, flag in config.TO_LOAD_MODULES.items():
        if flag:
            loaded_modules.append(importlib.import_module(name=m))
    
    for m in Component._components:
        m.start()
    
    atexit.register(on_exit)
