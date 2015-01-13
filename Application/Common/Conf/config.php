<?php
/**
 * Created by PhpStorm.
 * User: sunguide
 * Date: 14-9-14
 * Time: 下午6:02
 */
    //定义回调URL通用的URL
    require_cache("../Commom/functions.php");
    define('URL_CALLBACK', 'http://www.aiai.im/index.php?m=Account&a=callback&type=');
    define("APP_VERSION", "1.0.0");
    return array(
        'APP_ENCRYPTION_KEY'    => "5yaoaiai",//自定义应用加密KEY
        'LANG_SWITCH_ON' => true,
        'DEFAULT_LANG' => 'zh-cn', // 默认语言
        'LANG_AUTO_DETECT' => true, // 自动侦测语言
        'LANG_LIST'=>'en-us,zh-cn,zh-tw',//必须写可允许的语言列表
        'DB_TYPE'=>'mysql',
        'DB_HOST'=>'localhost',
        'DB_PORT'=>'3306',
        'DB_NAME'=>'aiai',
        'DB_USER'=>'root',
        'DB_PWD'=>'woshinilao8',
        'DB_PREFIX'=>'aiai_',
        'URL_ROUTER_ON'   => true, //开启路由
        'URL_ROUTE_RULES' => array( //定义路由规则
            'about'         => 'Public/about',
            'search'        => 'Articles/search',

        ),
        'LOG_RECORD' => true,
        //子域名部署

        'APP_SUB_DOMAIN_DEPLOY'   =>    1, // 开启子域名或者IP配置
        'APP_SUB_DOMAIN_RULES'    =>    array(
            /* 域名部署配置
            *格式1: '子域名或泛域名或IP'=> '模块名[/控制器名]';
            *格式2: '子域名或泛域名或IP'=> array('模块名[/控制器名]','var1=a&var2=b&var3=*');
            */
            'm.aiai.im'     => 'Mobile',  //m.aiai.im 指向Mobile模块
            'api.aiai.im'   => 'Api',
            'app.aiai.im'   => 'App',
            'admin.aiai.im' => 'Admin'
        ),
        'URL_MODEL'     => 1,
        'TMPL_EXCEPTION_FILE' => APP_PATH.'/Common/Tpl/Public/exception.tpl',
        'X_POWERED_BY'  => 'Aiai Inc.',

        ////////////////数据缓存/////////////////
        'DATA_CACHE_TIME'       =>  0,             //长连接时间,REDIS_PERSISTENT为1时有效
        'DATA_CACHE_PREFIX'     =>  '',            //缓存前缀
        'DATA_CACHE_TYPE'       =>  'Redis',       //数据缓存类型
        'DATA_EXPIRE'           =>  0,	           //数据缓存有效期(单位:秒) 0表示永久缓存
        'DATA_PERSISTENT'       =>  1,		       //是否长连接
        'DATA_REDIS_HOST'	    =>  'localhost',   //分布式Redis,默认第一个为主服务器
        'DATA_REDIS_PORT'		=>  '6379',	       //端口,如果相同只填一个,用英文逗号分隔
        'DATA_REDIS_AUTH'       =>  '',            //redis123456Redis auth认证(密钥中不能有逗号),如果相同只填一个,用英文逗号分隔
        ////////////////数据缓存end/////////////
        //腾讯QQ登录配置
        'THINK_SDK_QQ' => array(
            'APP_KEY'    => '100522698', //应用注册成功后分配的 APP ID
            'APP_SECRET' => 'aa00efb01848d2eb17acd3bfa3f92a50', //应用注册成功后分配的KEY
            'CALLBACK'   => URL_CALLBACK . 'qq',
        ),
        //腾讯微博配置
        'THINK_SDK_TENCENT' => array(
            'APP_KEY'    => '801435271', //应用注册成功后分配的 APP ID
            'APP_SECRET' => '9642b35d2e2dad23000d3922976d649d', //应用注册成功后分配的KEY
            'CALLBACK'   => URL_CALLBACK . 'tencent',
        ),
        //新浪微博配置
        'THINK_SDK_SINA' => array(
            'APP_KEY'    => '3598307079', //应用注册成功后分配的 APP ID
            'APP_SECRET' => '1c163695f54d5fabe10dd45e747d4ac4', //应用注册成功后分配的KEY
            'CALLBACK'   => URL_CALLBACK . 'sina',
        ),
        //网易微博配置
        'THINK_SDK_T163' => array(
            'APP_KEY'    => '', //应用注册成功后分配的 APP ID
            'APP_SECRET' => '', //应用注册成功后分配的KEY
            'CALLBACK'   => URL_CALLBACK . 't163',
        ),
        //人人网配置
        'THINK_SDK_RENREN' => array(
            'APP_KEY'    => '', //应用注册成功后分配的 APP ID
            'APP_SECRET' => '', //应用注册成功后分配的KEY
            'CALLBACK'   => URL_CALLBACK . 'renren',
        ),
        //360配置
        'THINK_SDK_X360' => array(
            'APP_KEY'    => '', //应用注册成功后分配的 APP ID
            'APP_SECRET' => '', //应用注册成功后分配的KEY
            'CALLBACK'   => URL_CALLBACK . 'x360',
        ),
        //豆瓣配置
        'THINK_SDK_DOUBAN' => array(
            'APP_KEY'    => '', //应用注册成功后分配的 APP ID
            'APP_SECRET' => '', //应用注册成功后分配的KEY
            'CALLBACK'   => URL_CALLBACK . 'douban',
        ),
        //Github配置
        'THINK_SDK_GITHUB' => array(
            'APP_KEY'    => '', //应用注册成功后分配的 APP ID
            'APP_SECRET' => '', //应用注册成功后分配的KEY
            'CALLBACK'   => URL_CALLBACK . 'github',
        ),
        //Google配置
        'THINK_SDK_GOOGLE' => array(
            'APP_KEY'    => '', //应用注册成功后分配的 APP ID
            'APP_SECRET' => '', //应用注册成功后分配的KEY
            'CALLBACK'   => URL_CALLBACK . 'google',
        ),
        //MSN配置
        'THINK_SDK_MSN' => array(
            'APP_KEY'    => '', //应用注册成功后分配的 APP ID
            'APP_SECRET' => '', //应用注册成功后分配的KEY
            'CALLBACK'   => URL_CALLBACK . 'msn',
        ),
        //点点配置
        'THINK_SDK_DIANDIAN' => array(
            'APP_KEY'    => '', //应用注册成功后分配的 APP ID
            'APP_SECRET' => '', //应用注册成功后分配的KEY
            'CALLBACK'   => URL_CALLBACK . 'diandian',
        ),
        //淘宝网配置
        'THINK_SDK_TAOBAO' => array(
            'APP_KEY'    => '', //应用注册成功后分配的 APP ID
            'APP_SECRET' => '', //应用注册成功后分配的KEY
            'CALLBACK'   => URL_CALLBACK . 'taobao',
        ),
        //百度配置
        'THINK_SDK_BAIDU' => array(
            'APP_KEY'    => '', //应用注册成功后分配的 APP ID
            'APP_SECRET' => '', //应用注册成功后分配的KEY
            'CALLBACK'   => URL_CALLBACK . 'baidu',
        ),
        //开心网配置
        'THINK_SDK_KAIXIN' => array(
            'APP_KEY'    => '', //应用注册成功后分配的 APP ID
            'APP_SECRET' => '', //应用注册成功后分配的KEY
            'CALLBACK'   => URL_CALLBACK . 'kaixin',
        ),
        //搜狐微博配置
        'THINK_SDK_SOHU' => array(
            'APP_KEY'    => '', //应用注册成功后分配的 APP ID
            'APP_SECRET' => '', //应用注册成功后分配的KEY
            'CALLBACK'   => URL_CALLBACK . 'sohu',
        ),
    );

?>