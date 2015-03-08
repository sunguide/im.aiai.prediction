<?php
namespace Command\Controller;
use Common\Service\QueueService;
class QueueCrontabController extends BaseController {

    public $queue = "";

    public function start(){
        if($this->queue){
            while($data = QueueService::getInstance()->pop($this->queue)){
                $this->working(json_decode($data,true));
            }
        }
    }
}
