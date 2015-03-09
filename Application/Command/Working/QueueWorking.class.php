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
    public static function start(){
        $queue = self::getQueueName();
        if($queue){
            while($data = QueueService::getInstance()->pop($queue)){
                self::working(json_decode($data,true));
            }
        }
    }
    public function working(){}
    public function getQueueName(){
        return "";
    }
}
