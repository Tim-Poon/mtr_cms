import fan
import speaker
import time
import conf
import led

# 采店铺数据的方法 可以做成按一下按钮 然后响一下 接着6个灯依次点亮 采完最后再响一下 可以保证每次采数据的时长平均
def execute():
	start_time = time.time()
	while end_time-start_time <= 120:
		speaker.speaker(conf.speaker_pin, conf.speaker_loop, conf.speaker_time_interval)
		### led test
		for pin in conf.led_pins:
			led.led_on_and_off(conf.led_sleep_interval, pin, conf.led_light_strength[0])
		for pin in list(reversed(conf.led_pins)):
			led.led_on_and_off(conf.led_sleep_interval, pin, conf.led_light_strength[1])
		end_time = time.time()
	speaker.speaker(conf.speaker_pin, conf.speaker_loop, conf.speaker_time_interval)

if __name__ == '__main__':
	execute()
