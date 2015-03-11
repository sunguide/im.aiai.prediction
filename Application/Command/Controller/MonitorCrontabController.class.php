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
//        $date = date("Ymd");
        foreach($works as $work){
//            $logName = $date."_".strtolower(str_replace("/","_",trim($work))).".log";
//            $cmd = "php -q /home/wwwroot/prediction.aiai.im/index.php $work  >> /home/wwwroot/prediction.aiai.im/Logs/$logName & ";
//            exec($cmd);
            $this->exec($work);
        }
    }
    private function exec($work){
        $date = date("Ymd");
        $n = exec('ps -ef | grep "'.$work.'" | grep -vc grep');
        if(intval($n) < 1){
            $logName = $date."_".strtolower(str_replace("/","_",trim($work))).".log";
            $cmd = "php -q /home/wwwroot/prediction.aiai.im/index.php $work  >> /home/wwwroot/prediction.aiai.im/Logs/$logName & ";
            exec($cmd);
        }
    }
    private function millisecond(){
        return (floor(microtime(true) * 1000));
    }
}
