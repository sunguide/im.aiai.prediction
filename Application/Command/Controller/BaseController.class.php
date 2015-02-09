<?php
namespace Command\Controller;
use Think\Controller;
use Think\Log;
class BaseController extends Controller {

    private static $_log_length = 0;

    public function out($message,$level=Log::DEBUG,$record=false){
        if(!$record){
            echo strval($message)."\n";
        }
        Log::record($message, $level, $record);
        if(self::$_log_length > 5){
            Log::save(Log::DEBUG, $this->getLogPath());
            self::$_log_length = 0;
        }else{
            self::$_log_length++;
        }
    }
    public function __destruct(){

        Log::save("", $this->getLogPath());

        parent::__destruct();
    }
    private function getLogPath(){

        return C('LOG_PATH')."/Commands/".date('y_m_d_').str_replace('\\',"",get_called_class()).'.log';

    }
}
