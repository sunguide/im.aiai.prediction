<?php
/**
 * User: sunguide
 * Date: 14/11/15
 * Time: 12:58
 */

namespace Common\Manager;
use Common\Description;

class UserBehaviorManager {

    public static function add($user, $group, $target, $action, $status = Description\UserBehaviorDescription::STATUS_NORMAL){
        self::addBehaviors();
        $UserBehaviorLog = M("UserBehaviorLog");
        $data = array(
            "user_id" => intval($user),
            "group_id"   => intval($group),
            "target_id"  => intval($target),
            "action"  => intval($action),
            "status"  => intval($status),
            "create_time" => time()
        );
        switch($action){
            case Description\UserBehaviorDescription::ACTION_FAVORITE:
                \Think\Hook::listen("user_favorite");
                break;

        }
        $result = $UserBehaviorLog->add($data);
        if($result && !self::checkBehavior($user, $group, $target, $action, $status)){
            self::doBehavior($data);
            self::doBehaviorStat($data);
            return true;
        }
        return false;
    }

    public static function checkBehavior($user, $group, $target, $action, $status){
        $UserBehavior = M("UserBehavior");
        $conditions = array(
            "user_id" => intval($user),
            "group_id"   => intval($group),
            "target_id"  => intval($target),
            "action"  => intval($action),
            "status"  => intval($status),
        );
        return $UserBehavior->where($conditions)->find();
    }
    public static function getBehavior($userId){
        $UserBehavior = M("UserBehavior");
        $conditions = array(
            "user_id" => $userId
        );
        return $UserBehavior->where($conditions)->select();
    }
    public static function getBehaviorStat($group_id, $target_id, $action){
        $UserBehaviorStat = M("UserBehaviorStat");
        $conditions = array(
            "group_id" => $group_id,
            "target_id" => $target_id,
            "action" => $action
        );
        return $UserBehaviorStat->where($conditions)->find();
    }
    public static function getBehaviorStats($group_id, $action, $orders = "amount DESC", $offset = 0, $limit = 50){
        $UserBehaviorStat = M("UserBehaviorStat");
        $conditions = array(
            "group_id" => $group_id,
            "action" => $action,
        );
        return $UserBehaviorStat->where($conditions)->order($orders)->limit($offset,$limit)->select();
    }

    private function addBehaviors(){
        \Think\Hook::add("user_favorite","\\Common\\Behavior\\FavoriteBehavior");
    }
    private function doBehavior($params){
        $UserBehavior = M("UserBehavior");
        $user_id = getParamValue($params,"user_id");
        $group_id = getParamValue($params,"group_id");
        $target_id = getParamValue($params,"target_id");
        $action = getParamValue($params,"action");
        $conditions = array(
            "user_id" => $user_id,
            "group_id" => $group_id,
            "target_id" => $target_id,
            "action" => $action
        );
        $info = $UserBehavior->where($conditions)->find();

        $currentTime = getParamValue($params,"create_time",time());
        $data = array(
            "user_id" => $user_id,
            "group_id" => $group_id,
            "target_id" => $target_id,
            "action" => $action,
            "status" => getParamValue($params,"status"),
            "update_time" => $currentTime
        );
        if($info){
            $data['id'] = $info['id'];
            return $UserBehavior->save($data);
        }else{
            $data['create_time'] = $currentTime;
            return $UserBehavior->add($data);
        }
    }

    private function doBehaviorStat($params){
        $UserBehaviorStat = M("UserBehaviorStat");
        $group_id = getParamValue($params,"group_id");
        $target_id = getParamValue($params,"target_id");
        $action = getParamValue($params,"action");
        $conditions = array(
            "group_id" => $group_id,
            "target_id" => $target_id,
            "action" => $action
        );
        $info = $UserBehaviorStat->where($conditions)->find();

        $currentTime = getParamValue($params,"create_time",time());
        $data = array(
            "group_id" => $group_id,
            "target_id" => $target_id,
            "action" => $action,
            "update_time" => $currentTime
        );
        $status = getParamValue($data,"status");
        if($info){
            $UserBehaviorStat->where("id={$info['id']}")->save(array("update_time"=>$currentTime));
            if($status == Description\UserBehaviorDescription::STATUS_NORMAL){
                return $UserBehaviorStat->where("id={$info['id']}")->setInc("amount",1);
            }else{
                return $UserBehaviorStat->where("id={$info['id']}")->setDec("amount",1);
            }
        }else{
            $data['amount'] = 1;
            $data['create_time'] = $currentTime;
            return $UserBehaviorStat->add($data);
        }
    }
}
