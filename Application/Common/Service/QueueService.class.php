<?php
/**
 * Redis 队列服务
 * User: sunguide
 * Date: 14/12/8
 * Time: 00:59
 * Description:QueueService.php
 */

namespace Common\Service;
use Common\Library\Com\SRedis\RedisClient;
class QueueService extends Service {

    static $_instance;
    /**
     * 取得队列类实例
     * @static
     * @access public
     * @return mixed
     */
    public static function getInstance($config = array()) {

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
        if(self::$_instance){
            return self::$_instance;
        }
        return self::$_instance = new RedisClient($config);
    }
    public function __construct($config = array()){
        self::getInstance($config);
    }

    /**
     * 数据入队列
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param bool $right 是否从右边开始入
     */
    public function push($key, $value ,$right = true) {
        return self::$_instance->push($key, $value ,$right);
    }

    /**
     * 数据出队列
     * @param string $key KEY名称
     * @param bool $left 是否从左边开始出数据
     */
    public function pop($key , $left = true) {
        return self::$_instance->pop($key , $left);
    }
}
