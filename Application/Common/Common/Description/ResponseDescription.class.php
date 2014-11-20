<?php
/**
 * User: sunguide
 * Date: 14/11/15
 * Time: 12:58
 */

namespace Common\Common\Description;

class ResponseDescription extends Description {

    const ERROR                      = 1;
    const UNKNOWN_ANSWER             = 2;
    const AUTO_LEARNING_SUCCESS      = 3;
    static  $maps = array(
        self::ERROR                 => array(
                                        "哥哥，对不起，小爱，刚刚开了小差。",
                                        "这个程序猿，又不好好工作了，看老娘怎么收拾他，客官请稍后再试。"
                                    ),
        self::UNKNOWN_ANSWER        => array(
                                        "小爱，年龄还小哦，还有好多都不懂哦。哥哥，可以回复[答案 xxxx],教我哦！(*^__^*) 嘻嘻……",
                                        "真个我真不知道，哥哥，可以回复[答案 xxxx],教我哦！么么哒",
                                    ),
        self::AUTO_LEARNING_SUCCESS => array(
                                        "好的，小爱知道了。",
                                        "嗯嗯",
                                        "我会了",
                                        "我学会了",
                                        "我记住了"
                                    ),
    );
    public static function mean($key){
        if(isset(self::$maps[$key])){
            if(is_array(self::$maps[$key])){
                return self::$maps[$key][array_rand(self::$maps[$key],1)];
            }
            return self::$maps[$key];
        }else{
            return null;
        }
    }
}
