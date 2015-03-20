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
    private $frequencyCheck = true;
    private $frequencyInterval = 3600;
    public function getQueueName(){
        return QUEUE_EMAIL;
    }

    protected function working($data = array()){
        if($this->checkFrequency($data)){
            $to = isset($data['to']) ? $data['to'] : "";
            $toName = isset($data['to_name']) ? $data['to_name'] : "";
            $title = isset($data['title']) ? $data['title'] : "";
            $content = isset($data['content']) ? $data['content'] : "";
            $from = isset($data['from']) ? $data['from'] : "";
            $fromName = isset($data['from_name']) ? $data['from_name'] : "";
            $attachment = isset($data['attachment']) ? $data['attachment'] : "";
            $result = EmailService::getInstance()->send($to,$toName,$title,$content,$from,$fromName,$attachment);
            $data['status'] = $result === true ? 1:0;
            $data['status'] || $data['error'] = $result;
        }else{
            $data['status'] = 2;
        }
        $EmailLog = M("EmailLog");
        $data['created'] = time();
        isset($data['to']) && ($data['to_user'] = $data['to']);
        unset($data['to']);
        isset($data['from']) && ($data['from_user'] = $data['from']);
        unset($data['from']);
        $EmailLog->add($data);
    }
    protected function checkFrequency($data){
        if(!$this->frequencyCheck) return true;
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
