<?php
/**
 * User: sunguide
 * Date: 14/11/15
 * Time: 12:58
 */

namespace Common\Common\Description;

class GenderDescription extends Description {
    const UNKNOWN           = 0;
    const MAN               = 1;
    const WOMAN             = 2;
    const GAY               = 3;
    const LES               = 4;
    const MAN_BISEXUAL      = 5;
    const WOMAN_BISEXUAL    = 6;
    static $maps = array(
        self::UNKNOWN       => "未知",
        self::MAN           => "男",
        self::WOMAN         => "女",
        self::GAY           => "男同",
        self::LES           => "女同",
        self::MAN_BISEXUAL  => "男，双性恋",
        self::WOMAN_BISEXUAL=> "女，双性恋"
    );

}
