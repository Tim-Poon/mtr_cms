import requests
import re
import os
import time
import signal

API_SERVER_IP = 'ust-mtr-location-server-uat.eastasia.cloudapp.azure.com'
API_SERVER_PORT = 8080

API_UPDATE = f"http://{API_SERVER_IP}:{API_SERVER_PORT}/latest_code_info"
API_NOTIFY = f"http://{API_SERVER_IP}:{API_SERVER_PORT}/update_code_update_record"

# todo
UPDATE_SERVER_IP = f"http://{API_SERVER_IP}/mtr_cms/public/release/"
PROGRAM_DIRECTORY_NAME = 'on-board-program'
PROGRAM_MAIN_START_UP = 'start.py'
SCRIPT_AUTOLOAD = 'start_tracking.sh'
LED_PINS=[11,13,15,16,18,22]


def gpio_initialization(led_pins):
	GPIO.setmode(GPIO.BOARD)  # setup the board mode
	for led_pin in led_pins:
		GPIO.setup(led_pin, GPIO.OUT)

def led_control(led_pin, status):
	GPIO.output(led_pin, status)

def led_on_and_off(pin, gap):
	GPIO.output(pin, True)
	time.sleep(gap)
	GPIO.output(pin, False)


def led_loops(led_pins, counter, gap):
    i = 0
    for pin in led_pins:
	    GPIO.output(pin, False)
    while i <= counter:
        for pin in led_pins:
            led_on_and_off(pin, gap)
        for pin in list(reversed(led_pins)):
            led_on_and_off(pin, gap)
        i += 1


def request_url(url, para):
    try:
        response_data = requests.post(url, json=para, timeout=10)
    except Exception as e:
        return 0, e
    return 1, response_data


def kill_program(program_list):
    # kill once
    for program_name in program_list:
        program_pid = os.popen(f"ps -ef | grep {program_name} | grep -v grep").read()
        if program_pid:
            pid = int(program_pid.split()[1])
            os.kill(pid, signal.SIGKILL)
            time.sleep(1)

def get_ble_mac(default_return_value=None):
    try:
        x = os.popen('hciconfig')
        ble_mac_addr_line = x.readlines()[1]
        m = re.search(pattern='.*BD Address: ([0-9A-Za-z:]*) ', string=ble_mac_addr_line)
        ble_mac_addr = m.group(1)
    except:
        return default_return_value
    else:
        return ble_mac_addr

def download_program(update_server_ip, program_label):
    try:
        os.system("cd /home/mtr_gdms")
        os.system(f"sudo rm -r {program_label}.tar.gz")
        os.system(f"wget -q {update_server_ip}/{program_label}.tar.gz")
    except Exception as e:
        return 0
    return 1

def md5_checking(program_label, md5):
    special_string = "| awk '{ print $1 }'"
    local_md5 = os.popen(f"md5sum {program_label}.tar.gz {special_string}").read()
    if local_md5.strip() == md5:
        return 1
    else:
        os.system(f"sudo rm /home/mtr_gdms/{program_label}.tar.gz")
        return 0

def file_operation(program_directly_name, program_label):
    os.system(f"sudo rm -r /home/mtr_gdms/{program_directly_name}")
    directory = os.popen(f"tar -tf /home/mtr_gdms/{program_label}.tar.gz")
    if directory:
        tar_directory_inside_preprocessing = directory.readline()
        tar_directory_inside = tar_directory_inside_preprocessing.replace("/", "")
        os.system(f"tar -xzvf /home/mtr_gdms/{program_label}.tar.gz > /dev/null")
        os.system(f"mv /home/mtr_gdms/{tar_directory_inside.rstrip()} /home/mtr_gdms/{program_directly_name}")
        os.system(f"sudo rm /home/mtr_gdms/{program_label}.tar.gz")


if __name__ == "__main__":
    target_ble_mac = get_ble_mac()
    if target_ble_mac:
        print('- my BLE =', target_ble_mac)
        rep_code, response = request_url(API_UPDATE, {'target_ble_mac': target_ble_mac})
        if rep_code == 1:
            print('-- status_code =', response.status_code)
            if response.status_code == 300:
                # preparing updating
                print('-- check: OTA task')
                update_info = response.json()
                if update_info['label'] and update_info['md5']:
                    print('--- check: update info', update_info['label'], update_info['md5'])
                    if download_program(UPDATE_SERVER_IP, update_info['label']):
                        print('---- downloading program')
                        # get new program file in local
                        # check md5
                        if md5_checking(update_info['label'], update_info['md5']):
                            print('----- MD5 checking')
                            print('----- killing program')
                            kill_program([PROGRAM_MAIN_START_UP, SCRIPT_AUTOLOAD])
                            kill_program([PROGRAM_MAIN_START_UP, SCRIPT_AUTOLOAD])
                            time.sleep(3)
                            import RPi.GPIO as GPIO
                            GPIO.cleanup()
                            # todo try: get gpio permission
                            gpio_initialization(LED_PINS)
                            led_loops(LED_PINS, 1, 0.1)
                            led_control(LED_PINS[0], True)
                            led_control(LED_PINS[1], True)
                            time.sleep(1)
                            print('----- file operating')
                            file_operation(PROGRAM_DIRECTORY_NAME, update_info['label'])
                            led_control(LED_PINS[2], True)
                            led_control(LED_PINS[3], True)
                            time.sleep(1)
                            print('----- notify server')
                            notify = {'target_ble_mac': target_ble_mac,
                                    'ts_create': update_info['ts_create'],
                                    'md5': update_info['md5'],
                                    'ts_updated': int(time.time()),
                                    "status_code": 500}
                            led_control(LED_PINS[4], True)
                            led_control(LED_PINS[5], True)
                            time.sleep(1)
                            response = request_url(API_NOTIFY, notify)
                            led_loops(LED_PINS, 3, 0.05)
                            GPIO.cleanup()
                            print('----- restart program')
                            time.sleep(1)
                            os.system("sudo reboot")
                            # os.system("nohup sudo sh /home/mtr_gdms/start_tracking.sh >> /dev/null 2>&1 &")
                        else:
                            print('----- [error] MD5: ', update_info['label'], update_info['md5'])
                            request_url(API_NOTIFY,
                                        {'target_ble_mac': target_ble_mac, 'ts_create': update_info['ts_create'],
                                         'status_code': 302})
                    else:
                        print('---- [error] download nothing')
                        request_url(API_NOTIFY, {'target_ble_mac': target_ble_mac, 'ts_create': update_info['ts_create'],
                                                 'status_code': 301})
            else:
                print('-- no OTA tasks')
        else:
            print('-- [error] request connect :', response)
            # if isinstance(response, requests.Response):
            #     print(f'status code: {response.status_code}, text: {response.text}')
    else:
        print('- [error] cant get target_ble_mac')
