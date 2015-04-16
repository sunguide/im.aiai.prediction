<?php
namespace Home\Controller;
use Common\Manager\ThirdUserManager;
use Common\Manager\UserBehaviorManager;
use Common\Manager\UserManager;
use Common\Description\UserBehaviorDescription;
use Think\Controller;
class AccountController extends BaseController {


    public function home($uid){
        if(empty($uid)) $this->error("用户不存在");
        //记录用户行为
        UserBehaviorManager::add($this->getUserId(),UserBehaviorDescription::GROUP_USER,$uid,UserBehaviorDescription::ACTION_VIEW);
        $User = M("User");
        $user = $User->find($uid);
        $user['avatar'] = $user['avatar']?getAvatar($user['avatar']):getDefaultAvatar();
        $this->assign("user",$user);
        $this->display();
    }
    //登录地址
    public function logout(){
        $this->unsetUser();
        redirect("/");
    }

}
