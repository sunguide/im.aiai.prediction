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

    private $frequencyInterval = 3600;
    public function getQueueName(){
        return QUEUE_EMAIL;
    }

    protected function working($data = array()){
        if(!$this->checkFrequency($data)){
            return true;
        }
        $to = isset($data['to']) ? $data['to'] : "";
        $toName = isset($data['to_name']) ? $data['to_name'] : "";
        $title = isset($data['title']) ? $data['title'] : "";
        $content = isset($data['content']) ? $data['content'] : "";
        $from = isset($data['from']) ? $data['from'] : "";
        $fromName = isset($data['from_name']) ? $data['from_name'] : "";
        EmailService::getInstance()->send($to,$toName,$title,$content,$from,$fromName);
    }
    protected function checkFrequency($data){
        $options = array('type'=>'file','prefix'=>'email_send','expire'=>$this->frequencyInterval);
        $id = md5(json_encode($data));
        if(S($id,"",$options)){
            return false;
        }else{
            S($id,$data,$options);
            return true;
        }
    }
}
