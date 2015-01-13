<?php
/**
 * Created by PhpStorm.
 * User: sunguide
 * Date: 14-9-14
 * Time: 下午6:02
 */
    return array(
        'LOG_RECORD' => true, // 开启日志记录
        'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR,WARN,INFO,DEBUG,SQL', // 只记录EMERG ALERT CRIT ERR 错误
        'SHOW_PAGE_TRACE' =>true,
        'URL_ROUTER_ON'   => true, //开启路由
        'URL_ROUTE_RULES' => array( //定义路由规则
            'about'                 => 'Public/about',
            'search'                => 'Articles/search',
            'news/:year/:month/:day'=> array('News/archive', 'status=1'),
            'article/:id'           => array('Mobile/article/detail?&id=:1'),
            'aritcle/:id'           => array('Mobile/article/detail?&id=:1'),//兼容错误的url

//            'position/lists?:params'          => array('Api/position/lists?:1'),
//            'position/:id'          => array('Api/position/detail?&id=:1'),
            'img/:id'          => array('Mobile/image/detail?&id=:1'),
            'position/read/:id'     => '/news/:1',
        ),
    );

?>