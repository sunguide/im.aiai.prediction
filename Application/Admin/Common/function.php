<?php
/**
 * User: sunguide
 * Date: 15/2/3
 * Time: 01:25
 * Description:functions.php
 */

function get_current_url(){
    return $url = "http://" . $_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI'];
}
//判断导航nav是否active
function _na($url){
    return false === strpos(get_current_url(),$url) ? false : true;
}