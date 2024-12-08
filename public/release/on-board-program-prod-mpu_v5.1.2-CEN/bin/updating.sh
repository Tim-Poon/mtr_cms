#!/bin/sh
sudo iw reg set HK
sudo ifconfig wlan0 up
while true
do
  isRunning=$(ps -ef | grep "update.py" | grep -v "grep")
  if [ "$isRunning" ] ; then
    echo "update.py is running at `date`."
  else
    echo "starting update.py ..."
    cd /home/mtr_gdms
    nohup sudo python3 update.py &
    echo "update.py (PID=$!)."
  fi
  sleep 90
done
