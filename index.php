<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
ini_set("display_errors","On");error_reporting(E_ERROR);
function err_handler($e){
    var_dump($e);
}
set_exception_handler("err_handler");

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);

define('APP_RUN_ENV',"dev");
// 定义应用目录
define('APP_PATH',__DIR__.'/Application/');//必须使用绝对路径
// 引入composer
require __DIR__. "/vendor/autoload.php";

// 引入ThinkPHP入口文件
require __DIR__ . '/ThinkPHP/ThinkPHP.php';


// 亲^_^ 后面不需要任何代码了 就是如此简单