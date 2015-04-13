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
            'weekly/:id\d' => 'Weekly/index',
            'category/:id\d' => 'Category/index',
            'article/:id\d' => 'Article/detail',
            'article/love/:type' => 'Article/love',
            'position/:id\d' => 'Position/detail',
            'question/:id\d' => 'Question/detail',
            'user/:uid\d' => 'Account/home',
            'register' => 'Public/register',
            'login' => 'Public/login',
            'about' => 'Public/about',
            'terms' => 'Public/terms',
//            'news/read/:id'          => '/news/:1',
        ),

        'URL_MAP_RULES' => array( //定义路由规则
            'http://weekly.aiai.im/' => 'Articles/index',
        ),

        //默认错误跳转对应的模板文件
        'TMPL_ACTION_ERROR' => 'Public:error',
        //默认成功跳转对应的模板文件
        'TMPL_ACTION_SUCCESS' => 'Public:success',
    );