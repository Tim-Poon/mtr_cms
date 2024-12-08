#!/bin/sh
sudo iw reg set HK
sudo ifconfig wlan0 up
while true
do
  isRunning=$(ps -ef | grep "start.py" | grep -v "grep")
  if [ "$isRunning" ] ; then
    echo "start.py is running at `date`."
  else
    echo "starting start.py ..."
    cd /home/mtr_gdms/on-board-program
    nohup sudo python3 start.py &
    echo "start.py (PID=$!)."
  fi
  sleep 10
done
