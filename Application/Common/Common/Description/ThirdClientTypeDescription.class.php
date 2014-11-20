<?php
/**
 * User: sunguide
 * Date: 14/11/15
 * Time: 12:58
 */

namespace Common\Common\Description;

class ThirdClientTypeDescription extends Description {

    const WECHAT          = "wechat";
    const WEIBO           = "weibo";
    static $maps = array(
        self::WECHAT => "微信",
        self::WEIBO  => "微博"
    );

}
