<?php
namespace Command\Crontab;
use Command\Controller\BaseController;
class DayCrontab extends baseController {

    public static function start(){
        $works = array();
//        $works[] = "Command/Test/test";
        $works[] = "Command/CleanLog/clean";//清除七天以上的日志

        return $works;
    }
}
