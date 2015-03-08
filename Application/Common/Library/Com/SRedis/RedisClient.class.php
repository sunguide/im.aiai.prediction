<?php
/**
 * Redis Client
 *
 * @author sunguide <sunguide@qq.com>
 * @date 2014-12-08
 */
namespace Common\Library\Com\SRedis;

class RedisClient
{
    protected $options;
    protected $handler;
    /**
     * 架构函数
     * @param array $options 缓存参数
     * @access public
     */
    public function __construct($options=array()) {
        if(empty($options)) {
            $options = array (
                'host'          => '127.0.0.1',
                'port'          => 6379,
                'timeout'       => false,
                'persistent'    => false,
                'auth'			=> false,
            );
        }
        $options['host'] = explode(',', $options['host']);
        $options['port'] = explode(',', $options['port']);
        $options['auth'] = explode(',', $options['auth']);
        foreach ($options['host'] as $key=>$value) {
            if (!isset($options['port'][$key])) {
                $options['port'][$key] = $options['port'][0];
            }
            if (!isset($options['auth'][$key])) {
                $options['auth'][$key] = $options['auth'][0];
            }
        }
        $this->options =  $options;
        $this->options['expire'] =  isset($options['expire']) ?  $options['expire']  :   0;
        $this->options['prefix'] =  isset($options['prefix']) ?  $options['prefix']  :   "";
        $this->options['length'] =  isset($options['length']) ?  $options['length']  :   0;
        $this->handler  = new \Redis;
    }

    /**
     * 连接Redis服务端
     * @access public
     * @param bool $is_master : 是否连接主服务器
     */
    public function connect($is_master = true) {
        if ($is_master) {
            $i = 0;
        } else {
            $count = count($this->options['host']);
            if ($count == 1) {
                $i = 0;
            } else {
                $i = rand(1, $count - 1);	//多个从服务器随机选择
            }
        }
        $func = $this->options['persistent'] ? 'pconnect' : 'connect';

        $this->options['timeout'] === false ?
            $this->handler->$func($this->options['host'][$i], $this->options['port'][$i]) :
            $this->handler->$func($this->options['host'][$i], $this->options['port'][$i], $this->options['timeout']);
        if ($this->options['auth'][$i]) {
            $this->handler->auth($this->options['auth'][$i]);
        }
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($name) {
        self::connect(false);
        $value = $this->handler->get($this->options['prefix'].$name);
        $jsonData  = json_decode( $value, true );
        return ($jsonData === NULL) ? $value : $jsonData;	//检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
    }

    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     * @param integer $expire  有效时间（秒）
     * @return boolean
     */
    public function set($name, $value, $expire = null) {
        self::connect(true);
        if(is_null($expire)) {
            $expire  =  $this->options['expire'];
        }
        $name   =   $this->options['prefix'].$name;
        //对数组/对象数据进行缓存处理，保证数据完整性
        $value  =  (is_object($value) || is_array($value)) ? json_encode($value) : $value;
        if(is_int($expire) && $expire > 0) {
            $result = $this->handler->setex($name, $expire, $value);
        }else{
            $result = $this->handler->set($name, $value);
        }

        return $result;
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function rm($name) {
        self::connect(true);
        return $this->handler->delete($this->options['prefix'].$name);
    }

    /**
     * 清除缓存
     * @access public
     * @return boolean
     */
    public function clear() {
        self::connect(true);
        return $this->handler->flushDB();
    }

    /**
     * 数据入队列
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param bool $right 是否从右边开始入
     */
    public function push($key, $value ,$right = true) {
        self::connect(true);
        $key = $this->options['prefix'].$key;
        $value = json_encode($value);
        return $right ? $this->handler->rPush($key, $value) : $this->handler->lPush($key, $value);
    }

    /**
     * 数据出队列
     * @param string $key KEY名称
     * @param bool $left 是否从左边开始出数据
     */
    public function pop($key , $left = true) {
        self::connect(true);
        $key = $this->options['prefix'].$key;
        $val = $left ? $this->handler->lPop($key) : $this->handler->rPop($key);
        return json_decode($val);
    }

    /**
     * 数据自增
     * @param string $key KEY名称
     */
    public function increment($key) {
        self::connect(true);
        $key = $this->options['prefix'].$key;
        return $this->handler->incr($key);
    }

    /**
     * 数据自减
     * @param string $key KEY名称
     */
    public function decrement($key) {
        self::connect(true);
        $key = $this->options['prefix'].$key;
        return $this->handler->decr($key);
    }

    /**
     * key是否存在，存在返回ture
     * @param string $key KEY名称
     */
    public function exists($key) {
        self::connect(true);
        $key = $this->options['prefix'].$key;
        return $this->handler->exists($key);
    }

    /**
     * 关闭长连接
     * @access public
     */
    public function __destruct() {
        if ($this->options['persistent'] == 'pconnect') {
            $this->handler->close();
        }
    }
}
