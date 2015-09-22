<?php
namespace Wolfunds\Controller;
use Api\Controller\BaseController;
use Common\Service\SMSService;
use Think\Crypt;

class UserController extends BaseController {

    public function reg(){
        $params = json_decode(file_get_contents("php://input","r"),true);
        $username = trim(I("username")?:$params['username']);
        $email = trim(I("email"));
        $phone = trim(I("phone"));
        $password = trim(I("password")?:$params['password']);
        if(!$username){
            $this->setError("请输入邮箱/手机号".json_encode($params),101,true);
        }
        if(!$password){
            $this->setError("请输入密码",102,true);
        }
        if(!$email && is_email($username)){
            $email = $username;
        }
        if(!$phone && is_phone($username)){
            $phone = $username;
        }
        $userInfo = M("User")->where(array("username" => $username))->find();
        if($userInfo){
            $this->setError("用户已经存在",900);
        }else{
            $password = $this->encryptPassword($password);
            $userData = compact("username","phone","email","password");
            $userData['created'] = NOW_TIME;
            $userData['updated'] = NOW_TIME;
            $userData['status'] = 1;
            $result = M("User")->add($userData);
            if(!$result){
                $this->setError("创建用户失败",800);
            }else{
                cookie("username",$username);
                cookie("uid",$userInfo['id']);
                $data = array(
                    "username" => $userInfo['username'],
                    "email" => $userInfo['email'],
                    "uid" => $userInfo['id']
                );
                $this->response($data);
            }
        }
        $this->response();
    }
    public function login(){
        $params = json_decode(file_get_contents("php://input","r"),true);
        $username = trim(I("username")?:$params['username']);
        $password = trim(I("password")?:$params['password']);
        if(!$username){
            $this->setError("请输入邮箱/手机号".json_encode($params),101,true);
        }
        if(!$password){
            $this->setError("请输入密码",102,true);
        }
        $userInfo = M("User")->where(array("username" => $username))->find();
        if($userInfo){
            $result = $this->checkUserPassword($userInfo['id'],$password);
            if(!$result){
                $this->setError("密码错误",900);
            }else{
                cookie("username",$username);
                cookie("uid",$userInfo['id']);
                $data = array(
                    "username" => $userInfo['username'],
                    "email" => $userInfo['email'],
                    "uid" => $userInfo['id']
                );
                $this->response($data);
            }
        }else{
            $this->setError("用户不存在",800);
        }

        $this->response();
    }
    public function notify(){
        $mobile = trim(I("mobile"));
        $content = trim(I("content"));
        $code = rand(1000,9999);
        $content = "【野狼投资】您的验证码是{$code}。如非本人操作，请忽略本短信";
        if(is_phone($mobile) && $content){
            $result = SMSService::getInstance()->send($mobile,$content);
            if(!$result){
                $this->setError("发送失败");
            }
        }else{
            $this->setError("手机号码不正确，或者参数错误");
        }
        $this->response();
    }

    private function encryptPassword($password){
        return Crypt::encrypt($password,"wolfunds");
    }
    private function checkUserPassword($uid,$password){
        return M("User")->where(array("id" => $uid,"password" => $this->encryptPassword($password)))->find()?true:false;
    }
}
