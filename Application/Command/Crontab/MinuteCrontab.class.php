<?php
namespace Command\Crontab;
use Command\Controller\BaseController;
class MinuteCrontab extends BaseController {
    
    public static function start(){
        $works = array();
        $works[] = "Command/Stock/crawler";
        $works[] = "Command/Email/sending";
        $works[] = "Command/Stock/analyse";

        return $works;
    }
}
