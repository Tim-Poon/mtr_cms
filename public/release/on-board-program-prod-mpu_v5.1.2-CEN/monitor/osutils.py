import os
# currently, only usable in Raspberry Pi

# This returns the current frequency of the ARM cores
def get_CPU_clock_frequency():
    CPU_clock_frequency_total = os.popen('vcgencmd measure_clock arm').readline()
    _, CPU_clock_frequency = CPU_clock_frequency_total.split("=")
    #  + "GHz"
    return float(round((float(CPU_clock_frequency)/1000000000),2))

# Return % of CPU used by user as a character string
def get_CPU_usage():
    usg = 0.0
    try:
        usg = str(os.popen("top -n1 | awk '/Cpu\(s\):/ {print $2}'").readline().strip())
    except Exception as e:
        print(f"error while geting cpu usage {e}")
    usg = float(usg)
    return usg

# Return CPU temperature as a character string
def get_CPU_temperature():
    CPU_temperature = os.popen('vcgencmd measure_temp').readline()
    # "C"
    return float(CPU_temperature.replace("temp=", "").replace("'C\n", ""))

# get the network status of Pi
def get_network_status():
    network_status = os.popen('ping 8.8.8.8').readline()
    if network_status:
        return 1
    else:
        return 0

# Displays the current voltage of Pi
def get_pi_volt():
    # 0x0 means nothing wrong。
    # 0x50000 means throttled has occurred since the last reboot.
    # 0x50005 means you are currently under-voltage and throttled.
    pi_voltage_totla = os.popen('vcgencmd get_throttled').readline()
    _, pi_voltage = pi_voltage_totla.split("=")
    return int(pi_voltage.replace('\n', ''), 16)

# Displays the current voltages used by ARM cores
def get_core_volt():
    core_voltage_full = os.popen('vcgencmd measure_volts core').readline()
    _, core_voltage = core_voltage_full.split("=")
    core_voltage = core_voltage[:-2]
    return float(core_voltage)

# Return RAM information (unit=kb) in a list
# Index 0: total RAM
# Index 1: used RAM
# Index 2: free RAM
def get_RAM_info():
    RAW_total_info = os.popen('free')
    i = 0
    while 1:
        i += 1
        singe_line_info = RAW_total_info.readline()
        if i == 2:
            return (singe_line_info.split()[1:4])


# Return information about disk space as a list (unit included)
# Index 0: total disk space
# Index 1: used disk space
# Index 2: remaining disk space
# Index 3: percentage of disk used
def get_sd_card_storage_space_info():
    sd_card_storage_space_total_info = os.popen("df -h /")
    i = 0
    while 1:
        i += 1
        singe_line_info = sd_card_storage_space_total_info.readline()
        if i == 2:
            return (singe_line_info.split()[1:5])


if __name__ == '__main__':
    # CPU informatiom
        CPU_clock_frequency = get_CPU_clock_frequency()
        CPU_usage = get_CPU_usage()
        CPU_temperature = get_CPU_temperature()

        # network status
        network_status = get_network_status()

        # voltage
        pi_voltage = get_pi_volt()
        arm_core_voltage = get_core_volt()

        # RAM information
        # Output is in kb, here I convert it in Mb for readability
        RAM_info = get_RAM_info()
        RAM_total = round(int(RAM_info[0]) / 1000, 1)
        RAM_used = round(int(RAM_info[1]) / 1000, 1)
        RAM_free = round(int(RAM_info[2]) / 1000, 1)

        # Disk information
        sd_card_storage_space_info = get_sd_card_storage_space_info()
        sd_card_storage_space_total = sd_card_storage_space_info[0]
        sd_card_storage_space_used = sd_card_storage_space_info[1]
        sd_card_storage_space_used_percentage = sd_card_storage_space_info[3]

        print('CPU informatiom')
        print("CPU_clock_frequency=" + str(CPU_clock_frequency))
        print('CPU_usage = ' + CPU_usage)
        print('CPU Temperature = ' + CPU_temperature)
        print('-------------------------------------')
        print("Network Status:", network_status)
        print('-------------------------------------')
        print('Voltage Information')
        print("Pi Voltage", pi_voltage)
        print("Arm_Core_Voltage", arm_core_voltage)
        print('-------------------------------------')
        print('RAM Total = ' + str(RAM_total) + 'MB')
        print('RAM Used = ' + str(RAM_used) + 'MB')
        print('RAM Free = ' + str(RAM_free) + 'MB')
        print('-------------------------------------')
        print('sd_card_storage_space_total = ' + str(sd_card_storage_space_total) + 'B')
        print('sd_card_storage_space_used = ' + str(sd_card_storage_space_used) + 'B')
        print('sd_card_storage_space_used_percentage = ' + str(sd_card_storage_space_used_percentage))
