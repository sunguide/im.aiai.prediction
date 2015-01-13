<?php
namespace Common\Service;
use Common\Description;

class TulingRobotService extends Service{
    public $SearchService = null;
    private $apiKey = "812282d2e80eac0e5be00524f87829b1";

    public function getResponse($reqInfo){
        $apiURL = "http://www.tuling123.com/openapi/api?key=KEY&info=INFO";
        $url = str_replace("INFO", $reqInfo, str_replace("KEY", $this->apiKey, $apiURL));
        $res =file_get_contents($url);
        $resJSON = json_decode($res, true);
        if(isset($resJSON['code']) && $resJSON['code'] == 100000){
            if($resJSON['text'] != $reqInfo){
                return $resJSON['text'];
            }
        }
        return "";
    }

}
