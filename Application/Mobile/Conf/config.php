<?php
/**
 * Created by PhpStorm.
 * User: sunguide
 * Date: 14-9-14
 * Time: 下午6:02
 */
    return array(
        'URL_ROUTER_ON'   => true, //开启路由
        'URL_ROUTE_RULES' => array( //定义路由规则
            'about'                 => 'Public/about',
            'search'                => 'Articles/search',
            'news/:year/:month/:day'=> array('News/archive', 'status=1'),
            'article/:id'           => array('Mobile/article/detail?&id=:1'),
            'position/:id'          => array('Mobile/position/detail?&id=:1'),
            'img/:id'          => array('Mobile/image/lists?&id=:1'),
            'position/read/:id'     => '/news/:1',
        ),
    );

?>