<?php
/**
 * User: sunguide
 * Date: 15/3/21
 * Time: 00:37
 * Description:将任务写入待处理队列中
 */

namespace Common\Task;

use Common\Service\QueueService;

class QueueTask extends Task{

    public static function sendEmail($to,$toName,$title,$content,$from = null,$fromName = null,$attachment = null){
        $emailNotice = array();
        $to && $emailNotice['to'] = $to;
        $toName && $emailNotice['to_name'] = $toName;
        $title && $emailNotice['title'] = $title;
        $content && $emailNotice['content'] = $content;
        $from && $emailNotice['from'] = $from;
        $fromName && $emailNotice['from_name'] = $fromName;
        $attachment && $emailNotice['attachment'] = $attachment;

        QueueService::getInstance()->push(QUEUE_EMAIL,$emailNotice);
    }
    public static function test(){
        echo "test for".date("Y-m-d");
    }
} 