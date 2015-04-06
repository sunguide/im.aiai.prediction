<?php
/**
 * User: sunguide
 * Date: 14/11/15
 * Time: 12:58
 */

namespace Common\Description;

class UserBadgeDescription extends Description {
    const UNKNOWN           = 0;
    const MASTER_OF_SEX     = 1;
    static $maps = array(
        self::UNKNOWN       => "未知",
        self::MASTER_OF_SEX => "性爱大师",
    );

}
