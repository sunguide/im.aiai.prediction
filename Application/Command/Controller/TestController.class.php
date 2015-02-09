<?php
namespace Command\Controller;
use Think\Controller;
class TestController extends CrontabController {

    public function test(){
        $this->out("test".date("Y-m-d H:i:s"));
    }
}
