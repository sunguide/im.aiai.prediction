#!/bin/bash

cnt=`ps -ef | grep "Command/MonitorCrontab/start" | grep -vc grep`
if [ $cnt -ne 1 ]; then
    ps -ef | grep "Command/MonitorCrontab/start" | grep -v grep|awk '{print $2}'|xargs kill -9
    cd /home/wwwroot/prediction.aiai.im/ && php /home/wwwroot/prediction.aiai.im/index.php Command/MonitorCrontab/start &
fi
