<?php
namespace Home\Controller;
use Common\Task\QueueTask;
use Think\Controller;
class TestController extends Controller {

//    public function test(){
//        if ( !extension_loaded('redis') ) {
//            E(L('_NOT_SUPPERT_').':redis');
//        }
//        $mailContent = file_get_contents("http://prediction.aiai.im/Home/Weekly/mail");
//        QueueTask::sendEmail("sunguide@qq.com",'',"欢迎您订阅爱爱周刊",$mailContent);
//    }

    public function test(){
//        dump(\Common\Manager\UserManager::getMasters());
//        echo getSocialTime(time()-100000000);
        dump(strpos("/sdfdsfdfl/fwefsdf","/"));
    }
}
