<?php
/**
 * User: sunguide
 * Date: 15/9/14
 * Time: 23:09
 * Description:StockAPI.php
 */

namespace Wolfunds\Manager;


class StockManager {


    public static function getStockName($code){
        return M("StockCode")->where(array("id" => $code))->getField("name");
    }
} 