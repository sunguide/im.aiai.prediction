<?php
namespace Command\Controller;
use Think\Controller;
class CleanLogController extends CrontabController {

    public function clean(){
        $logs =  C('LOG_PATH')."/Commands/*".date('y_m_d_',time() - 864000);
        $cmd = "rm ".$logs."*";
        exec($cmd);
    }
}
