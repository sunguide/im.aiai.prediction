<?php
namespace Home\Controller;
use Common\Manager\ThirdUserManager;
use Common\Manager\UserManager;
use Think\Controller;
class AccountController extends BaseController {


    public function home($uid){
        if(empty($uid)) $this->error("用户不存在");
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
