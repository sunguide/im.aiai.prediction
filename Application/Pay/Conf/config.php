<?php
/**
 * Created by PhpStorm.
 * User: sunguide
 * Date: 14-9-14
 * Time: 下午6:02
 */
    require_once(APP_PATH."Common/Library/Com/Pingxx/Pingpp.php");
    return array(
        'URL_ROUTER_ON'   => true, //开启路由
        'URL_ROUTE_RULES' => array( //定义路由规则
            'success'               => 'Index/success',
            'cancel'                => 'Index/cancel',
        ),
    );

?>