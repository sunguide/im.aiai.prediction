<?php
namespace Command\Controller;
use Common\Service\QueueService;
use \Common\Task\QueueTask;
use Think\Controller;
class TestController extends CrontabController {

    public function test(){
//        $emailNotice = array(
//            "to"        => "sunguide@qq.com",
//            "title"     => "测试一下，孙贵德D大调",
//            "content"   => "dddd"
//        );
//        if ( !extension_loaded('redis') ) {
//            E(L('_NOT_SUPPERT_').':redis');
//        }
//        var_dump(QueueService::getInstance()->push(QUEUE_EMAIL,$emailNotice));

        QueueTask::sendEmail("sunguide@qq.com",'小妞',"欢d周刊","ddsd",'sun@guide.so',"牛b任务");

    }
}
