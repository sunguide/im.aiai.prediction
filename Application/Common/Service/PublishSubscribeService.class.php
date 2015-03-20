<?php
namespace Common\Service;
use Common\Library\Com\SRedis\RedisClient;
class PublishSubscribeService extends Service{
    static $_instance;
    static $_handler;

    public static function getInstance($config = array()) {
        if(self::$_instance){
            return self::$_instance;
        }
        if(!self::$_handler){
            if(empty($config)){
                $config = array (
                    'host'          => '127.0.0.1',
                    'port'          => 6379,
                    'timeout'       => false,
                    'persistent'    => false,
                    'auth'			=> false,
                    "prefix"        => "ai_"
                );
            }
            self::$_handler = new RedisClient($config);
        }
        return self::$_instance = new PublishSubscribeService();
    }
    public function publish($channel, $message) {
        return self::$_handler->publish($channel, $message);
    }

    public function subscribe($channels, $callback) {
        return self::$_handler->subscribe($channels, $callback);
    }
}
