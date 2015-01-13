<?php
/**
 * Created by PhpStorm.
 * User: sunguide
 * Date: 14/11/17
 * Time: 16:11
 */
namespace Common\Common;

class Func {

    public static function datetime(){
        return date("Y-m-d H:i:s");
    }
    /**
     * Filter Array Keys to Get Designed Keys
     *
     * @author     sunguide <sunguide@qq.com>
     * @param      array  $arr to transfer keyskeys
     * @param      array|string  $arrFilter filter keys  eg:array("img_url" => "url");
     * @return     array  the transfered array
     */
    public static function array_filter_keys($arr, $arrFilter){
        if(!is_array($arrFilter)){
            $arrFilter = array("$arrFilter" => "");
        }
        foreach($arr as $key => $item){
            if(!array_key_exists($key, $arrFilter)){
                unset($arr[$key]);
            }else if($arrFilter[$key]){
                $arr[$arrFilter[$key]] = $item;
                unset($arr[$key]);
            }
        }
        return $arr;
    }

    /**
     * Generates an UUID
     *
     * @author     Anis uddin Ahmad <admin@ajaxray.com>
     * @param      string  an optional prefix
     * @return     string  the formatted uuid
     */
    public static function uuid( $prefix  =  '' )
    {
        $chars  = md5(uniqid(mt_rand(), true));
        $uuid   =  substr ( $chars ,0,8) .  '-' ;
        $uuid  .=  substr ( $chars ,8,4) .  '-' ;
        $uuid  .=  substr ( $chars ,12,4) .  '-' ;
        $uuid  .=  substr ( $chars ,16,4) .  '-' ;
        $uuid  .=  substr ( $chars ,20,12);
        return   $prefix  .  $uuid ;
    }
    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    public static function get_gravatar( $email, $s = 120, $d = 404, $r = 'g') {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        return $url;
    }

}
