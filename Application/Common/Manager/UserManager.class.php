<?php
/**
 * User: sunguide
 * Date: 14/11/15
 * Time: 12:58
 */

namespace Common\Manager;
use Common\Description;

class UserManager {


    /**
     * @param int $offset
     * @param int $limit
     * @return mixed
     */
    public static function getMasters($offset = 0,$limit = 200){
        $UserBadge = M("UserBadge");
        $User = M("User");
        $conditions = array();
        $conditions["code"] = Description\UserBadgeDescription::MASTER_OF_SEX;
        $userBadges = $UserBadge->where($conditions)->limit($offset, $limit)->select();
        if($userBadges){
            $userIds = array();
            foreach($userBadges as $userBadge){
                $userIds[] = $userBadge['user_id'];
            }
            $conditions = array();
            $conditions['id'] = array("in",$userIds);
            return $User->where($conditions)->select();
        }else{
            return null;
        }
    }

    /**
     * @param $userId
     * @param $badge
     * @return bool
     */
    public static function assignBadge($userId, $badge){
        $UserBadge = M("UserBadge");
        if(!$UserBadge->where(array("user_id" => $userId,"code" => $badge))->find()){
            $data = array(
                "user_id" => $userId,
                "badge"   => Description\UserBadgeDescription::mean($badge),
                "code"    => $badge
            );
            return $UserBadge->add($data);
        }
        return true;
    }

    public static function create($data){
        $User = M("User");
        return $User->add($data);
    }

    public static function getInfo($userId){
        $User = M("User");
        return $User->find($userId);
    }
}
