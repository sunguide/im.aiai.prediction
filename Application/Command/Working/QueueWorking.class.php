<?php
/**
 * Created by PhpStorm.
 * User: sunguide
 * Date: 15/3/9
 * Time: 上午1:09
 */
namespace Command\Working;
use Common\Service\QueueService;

abstract class QueueWorking extends Working{
    protected $_max_execute_times = 180;
    protected $_sleep_time = 5000;

    public function getQueueName(){
        return "";
    }
    public function start(){

        $queue = $this->getQueueName();
        if($queue){
            $len = QueueService::getInstance()->len($queue);
            $this->out($queue." length:$len");
            while($data = QueueService::getInstance()->pop($queue)){
                $this->working($data);
                $this->_sleep_time && usleep($this->_sleep_time);
            }
        }else{
            die("请先配置需要获取的队列名.");
        }
    }
}
