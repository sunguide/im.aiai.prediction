<?php
namespace Command\Controller;
use Think\Controller;
class NotificationController extends CrontabController {

    public function notifying(){
            $mailContent = file_get_contents("http://prediction.aiai.im/Home/Weekly/mail");
            EmailService::getInstance()->send("sun@guide.so",'dd',"欢迎您订阅爱爱周刊",$mailContent);
            return;
            $SubscribeModel = M("WeeklySubscribe");
            $subscribers = $SubscribeModel->where("status = 1")->select();
            if($subscribers){
                foreach($subscribers as $subscriber){
                    EmailService::getInstance()->send($subscriber['email'],'dd',"我来测试","我是好人");
//                think_send_mail($subscriber['email'],'dd',"我来测试","我是好人");
                }
            }//* * * * * sleep 30;/var/www/servers/shells/monitor >> /tmp/wm.work.txt
    }
}
