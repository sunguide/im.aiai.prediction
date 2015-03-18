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
        $toName = isset($data['to_name']) ? $data['to_name'] : "";
        $title = isset($data['title']) ? $data['title'] : "";
        $content = isset($data['content']) ? $data['content'] : "";
        $from = isset($data['from']) ? $data['from'] : "";
        $fromName = isset($data['from_name']) ? $data['from_name'] : "";
        EmailService::getInstance()->send($to,$toName,$title,$content,$from,$fromName);
    }

}
