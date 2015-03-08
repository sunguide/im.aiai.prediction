<?php
/**
 * Created by PhpStorm.
 * User: sunguide
 * Date: 15/3/9
 * Time: 上午1:09
 */
namespace Command\Working;
use Common\Service\QueueService;
class QueueWorking {

    public $queue = "";

    public function start(){
        if($this->queue){
            while($data = QueueService::getInstance()->pop($this->queue)){
                $this->working(json_decode($data,true));
            }
        }
    }
}
