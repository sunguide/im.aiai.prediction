<?php
/**
 * Created by PhpStorm.
 * User: sunguide
 * Date: 15/3/9
 * Time: 上午1:09
 */
namespace Command\Working;
use  Common\Service\EmailService;
class EmailWorking extends QueueWorking {

    public function getQueueName(){
        return QUEUE_EMAIL;
    }

    protected function working($data = array()){
        $to = isset($data['to']) ? $data['to'] : "";
        $name = isset($data['name']) ? $data['name'] : "";
        $title = isset($data['title']) ? $data['title'] : "";
        $content = isset($data['content']) ? $data['content'] : "";
        EmailService::getInstance()->send($to,$name,$title,$content);
    }

}
