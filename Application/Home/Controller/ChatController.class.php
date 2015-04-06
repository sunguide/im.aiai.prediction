<?php
namespace Home\Controller;
use Common\Task\QueueTask;
use Think\Controller;
class ChatController extends Controller {

    public function test(){
        if ( !extension_loaded('redis') ) {
            E(L('_NOT_SUPPERT_').':redis');
        }
        $mailContent = file_get_contents("http://prediction.aiai.im/Home/Weekly/mail");
        QueueTask::sendEmail("sunguide@qq.com",'',"欢迎您订阅爱爱周刊",$mailContent);
    }
}
