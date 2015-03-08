<?php
namespace Command\Controller;

use Common\Service\EmailService;

class EmailController extends QueueCrontabController {

    public $queue = QUEUE_EMAIL;
    public function working($data){
        $to = isset($data['to']) ? $data['to'] : "";
        $name = isset($data['name']) ? $data['name'] : "";
        $title = isset($data['title']) ? $data['title'] : "";
        $content = isset($data['content']) ? $data['content'] : "";
        EmailService::getInstance()->send($to,$name,$title,$content);
    }
}
