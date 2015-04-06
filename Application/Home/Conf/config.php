<?php
/**
 * User: sunguide
 * Date: 15/1/19
 * Time: 02:23
 * Description:Conf.php
 */
    return array(
        'URL_ROUTE_RULES'=>array(
//            'news/:year/:month/:day' => array('News/archive', 'status=1'),
            'weekly/:id\d'               => 'Weekly/index',
            'category/:id\d'               => 'Category/index',
            'article/:id\d'               => 'Article/detail',
            'article/love/:type'               => 'Article/love',
//            'news/read/:id'          => '/news/:1',
        ),

        'URL_MAP_RULES' => array( //定义路由规则
            'http://weekly.aiai.im/' => 'Articles/index',
        ),
    );