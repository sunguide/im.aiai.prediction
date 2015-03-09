<?php
/**
 * Created by PhpStorm.
 * User: sunguide
 * Date: 15/3/9
 * Time: 上午1:09
 */
namespace Command\Working;
abstract class Working {
    private static $_instance = null;
    protected $_show_log = true;
    protected $_max_execute_times = 180;
    protected $_begin_time = 0;

    public static function getInstance() {
        if (null == self::$_instance) {
            $class = get_called_class();
            return self::$_instance = new $class;
        }
        return self::$_instance;
    }
    protected  function __construct() {
        $this->_begin_time = time();
        $this->init();
    }

    public function init() {

    }
    public function start(){
        $this->working();
    }
    public function sleep($time) {
        sleep($time);
    }
    public function getQueueName(){
        return "";
    }
    public function out($string) {
        if($this->_show_log){
            $class = get_class($this);
            $date = date('Y-m-d H:i:s');
            print "{$date} - {$class}\r\n {$string}\r\n";
        }
    }

    abstract protected function working($data = array());
}
