<?php
/**
 * User: sunguide
 * Date: 14/11/15
 * Time: 12:58
 */

namespace Common\Common\Description;

class Description {

    static $maps = null;

    public static function mean($key){
        if(isset(self::$maps[$key])){
            return self::$maps[$key];
        }else{
            return null;
        }
    }
}
