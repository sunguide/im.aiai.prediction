<?php
/**
 * User: sunguide
 * Date: 15/9/14
 * Time: 23:09
 * Description:StockAPI.php
 */

namespace Wolfunds\Manager;


class StockAPI {


    public static function getStockInfo($codes){
        $url = "http://apis.baidu.com/apistore/stockservice/stock";
        $result = self::apiRequest($url,array("stockid" => $codes,"list" => 0));
        $result = json_decode($result,true);

        return $result;
    }
    public static function getStockPrice($code){
        $stocks = self::getStockInfo($code);
        $stocks['retData']['stockinfo'];
    }
    private function apiRequest($url,$params=array(),$header = array()){

        $header[] = "apikey:f3985f3e812a3952e492b72f219ebdb4";
        $header[] = "Content-Type: application/json;charset=utf-8";
        $url .="?".http_build_query($params);
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => false,
            //,CURLOPT_FOLLOWLOCATION => true
        ));

        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        //get response
        $output = curl_exec($ch);

        //Print error if any
        if(curl_errno($ch))
        {
            echo 'error:' . curl_error($ch);
        }

        curl_close($ch);

        return $output;
    }
} 