<?php
/**
 * User: sunguide
 * Date: 14/11/15
 * Time: 12:58
 * Description:用户行为描述
 */

namespace Common\Description;

class UserBehaviorDescription extends Description {
    const UNKNOWN           = 0;

    const GROUP_ARTICLE     = 1;
    const GROUP_TOPIC       = 2;
    const GROUP_POSITION    = 3;
    const GROUP_USER        = 4;

    const ACTION_VIEW       = 1;
    const ACTION_FAVORITE   = 2;
    const ACTION_FOLLOW     = 3;

    const STATUS_NORMAL = 1;
    const STATUS_CANCEL = 2;

    static $group_maps = array(
        self::UNKNOWN       => "未知",
        self::GROUP_ARTICLE => "文章",
        self::GROUP_TOPIC   => "话题",
        self::GROUP_POSITION => "姿势",
        self::GROUP_USER    => "用户"
    );

    static $action_maps = array(
        self::UNKNOWN       => "未知",
        self::ACTION_VIEW   => "查看",
        self::ACTION_FAVORITE => "喜欢",
        self::ACTION_FOLLOW  => "关注"

    );

    static $status_maps = array(
        self::UNKNOWN       => "未知",
        self::STATUS_NORMAL => "正常",
        self::STATUS_CANCEL => "取消",
    );

    public static function group($id){
        return self::$group_maps[$id];
    }
    public static function action($id){
        return self::$action_maps[$id];
    }
    public static function status($id){
        return self::$status_maps[$id];
    }


}
