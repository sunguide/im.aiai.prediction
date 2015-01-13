<?php
namespace Api\Controller;
use Common\Common\Func;
use Common\Service\QueueService;
use Think\Cache\Driver\Redis;
use Think\Controller;

class TestController extends BaseController {

    public function _initialize(){
        $this->_check_auth = false;
    }
    public function test(){
        $info = array("img"=>"help");
        $queueService = QueueService::getInstance();
        var_dump($queueService->set("test_api"),"我是老大");
        var_dump($queueService->get("test_api"));
    }
    public function pop(){
        $info = array("img"=>"help");
        $queueService = QueueService::getInstance();
        var_dump($queueService->pop("test_api", $info));
    }
    public function test1(){
        $info = array("img"=>"help");
        $redisCache = Redis::getInstance();
        var_dump($redisCache->push("aiai",$info));
//        $redisClient = new \Common\Library\Com\SRedis\RedisClient();
//        $redisClient->push("aiai_msg","dd");
    }
    public function get(){
        $redisCache = Redis::getInstance();
        var_dump($redisCache->pop("aiai"));
    }
}
