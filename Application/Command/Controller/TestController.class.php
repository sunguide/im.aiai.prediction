<?php
namespace Command\Controller;
use Common\Service\QueueService;
use Think\Controller;
class TestController extends CrontabController {

    public function test(){
        $emailNotice = array(
            "to"        => "sunguide@qq.com",
            "title"     => "测试一下，孙贵德D大调",
            "content"   => "dddd"
        );
        if ( !extension_loaded('redis') ) {
            E(L('_NOT_SUPPERT_').':redis');
        }
        var_dump(QueueService::getInstance()->push(QUEUE_EMAIL,json_encode($emailNotice)));
    }
}
