<?php
/**
 * User: sunguide
 * Date: 15/1/21
 * Time: 01:56
 * Description:functions.php
 */
//获取公共静态文件
function getCDNFile($filePath){
    return CDN_DOMAIN.$filePath;
}