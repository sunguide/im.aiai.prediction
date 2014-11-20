<?php
/**
 * User: sunguide
 * Date: 14/11/15
 * Time: 12:58
 */

namespace Common\Common\Description;

class CategoryDescription extends Description {

    const CATEGORY_QUESTION_ANSWER = 1;
    const CATEGORY_ARTICLE         = 2;
    const CATEGORY_POSITION        = 3;
    static $maps = array(
        self::CATEGORY_QUESTION_ANSWER => "问答",
        self::CATEGORY_ARTICLE         => "文章",
        self::CATEGORY_POSITION        => "姿势"
    );

}
