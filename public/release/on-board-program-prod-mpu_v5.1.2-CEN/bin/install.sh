#!/bin/bash
sudo apt-get update
sudo apt-get install python3-pip libglib2.0-dev vim python3-rpi.gpio python3-numpy git bc libatlas-base-dev
sudo pip3 install bluepy scipy smbus

sudo sed -i '19a\nohup sh /home/mtr_gdms/start_tracking.sh >/dev/null 2>&1 &' /etc/rc.local
sudo sed -i '20a\nohup sh /home/mtr_gdms/updating.sh >/dev/null 2>&1 &' /etc/rc.local
