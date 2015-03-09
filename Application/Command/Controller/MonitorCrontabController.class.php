<?php
namespace Command\Controller;
//监控和启动整个 Crontab 系统
use Command\Crontab\DayCrontab;
use Command\Crontab\HourCrontab;
use Command\Crontab\MinuteCrontab;
use Command\Crontab\SecondCrontab;

class MonitorCrontabController extends CrontabController {
    public function _initialize(){

    }
    public function start(){
        while(true){
            $cmd = "php -q /home/wwwroot/prediction.aiai.im/index.php Command/MonitorCrontab/working  > /dev/null & ";
            exec($cmd);
            sleep(1); //usleep(1000000);
        }
    }
    public function working(){
//        $microtime = microtime(true);
//        $millisecond = floor($microtime * 10);
        $second = time();//floor($microtime);
        $this->execute(SecondCrontab::start());
        if(($second % 60) == 0){
            $this->execute(MinuteCrontab::start());
        }else if(($second % 3600) == 0){
            $this->execute(HourCrontab::start());
        }else if(($second % 86400) == 0){
            $this->execute(DayCrontab::start());
        }
    }
    private function execute($works){
        $date = date("Ymd");
        foreach($works as $work){
            $logName = $date."_".strtolower(str_replace("/","_",trim($work))).".log";
            $cmd = "php -q /home/wwwroot/prediction.aiai.im/index.php $work  >> /home/wwwroot/prediction.aiai.im/Logs/$logName & ";
            exec($cmd);
        }
    }
    private function exec($cmd){
        $date = date("Ymd");
        $logName = $date."_".strtolower(str_replace("/","_",trim($cmd))).".log";
        $c = 'cnt=`ps -ef | grep "'.$cmd.'" | grep -vc grep`
            if [ $cnt -lt 2 ]; then
            ps -ef | grep "'.$cmd.'" | grep -v grep|awk "{print $2}"|xargs kill -9
            cd /home/wwwroot/prediction.aiai.im/ && php /home/wwwroot/prediction.aiai.im/g.php '.$cmd.'  >> /home/wwwroot/prediction.aiai.im/Logs/'.$logName.' &
            fi';
        exec($c);
    }
    private function millisecond(){
        return (floor(microtime(true) * 1000));
    }
}
