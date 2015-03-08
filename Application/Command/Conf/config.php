<?php
/**
 * User: sunguide
 * Date: 15/1/25
 * Time: 01:41
 * Description:config.php
 */
const QUEUE_DB  = 7;//redis队列数据库
const QUEUE_EMAIL = "EMAIL"; //邮件队列名
const QUEUE_STOCK_ANALYSE = "STOCK_ANALYSE"; //邮件队列名

return array(
    'DB_TYPE'=>'mysql',
    'DB_HOST'=>'localhost',
    'DB_PORT'=>'3306',
    'DB_NAME'=>'aiai',
    'DB_USER'=>'root',
    'DB_PWD'=>'woshinilao8',
    'DB_PREFIX'=>'aiai_',
);