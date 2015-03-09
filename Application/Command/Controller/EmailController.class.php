<?php
namespace Command\Controller;
use Command\Working\EmailWorking;
class EmailController extends QueueCrontabController {

    public function sending(){
        EmailWorking::getInstance()->start();
    }
}
