<?php
namespace Home\Controller;
use Common\Manager\ThirdUserManager;
use Common\Manager\UserManager;
use Think\Controller;
class AuthController extends BaseController {
//    public function login(){
//        import("ORG.ThinkSDK.ThinkOauth");
//        $type = I("type");
//        switch(strtolower($type)){
//            case "sina":
//                $this->sinaLogin();
//                break;
//            case "qq":
//                $this->qqLogin();
//                break;
//            default:
//
//        }
//        $this->display("Public/login");
//    }

    //登录地址
    public function login($type = null){
        empty($type) && $this->error('参数错误');
        //加载ThinkOauth类并实例化一个对象
        $sns  = \Org\ThinkSDK\ThinkOauth::getInstance($type);
        //跳转到授权页面
        redirect($sns->getRequestCodeURL());
    }
    //授权回调地址
    public function callback($type = null, $code = null, $error = null){
        (empty($type) || (empty($code) && empty($error))) && $this->error('参数错误');
        $sns  = \Org\ThinkSDK\ThinkOauth::getInstance($type);
        //腾讯微博需传递的额外参数
        $extend = null;
        if($type == 'tencent'){
            $extend = array('openid' => $this->_get('openid'), 'openkey' => $this->_get('openkey'));
        }
        if($error){
            $errorInfo = array(
                "error" => $error,
                "error_description" => I("error_description"),
            );
            $this->error("你取消了授权");
//            $this->redirect($this->getReferer());
        }
        //请妥善保管这里获取到的Token信息，方便以后API调用
        //调用方法，实例化SDK对象的时候直接作为构造函数的第二个参数传入
        //如： $qq = ThinkOauth::getInstance('qq', $token);
        $token = $sns->getAccessToken($code , $extend);
        //获取当前登录用户信息
//        dump($token);exit;
        if(is_array($token)){
            $user_info = ThirdUserManager::$type($token);
//            echo("<h1>恭喜！使用 {$type} 用户登录成功</h1><br>");
//            echo("授权信息为：<br>");
//            dump($token);
//            echo("当前登录用户信息为：<br>");
//            dump($user_info);
            $UserThird = M("UserThird");
            $conditions = array();
            $conditions['client_type'] = $user_info['type'];
            $conditions['openid'] = $token['openid'];
            $userThirdInfo = $UserThird->where($conditions)->find();
            if($userThirdInfo){
                $userId = $userThirdInfo['user_id'];
            }else{
                $UserThird->add();
                $userInfoData = array(
                    "nickname" => $user_info['nick'],
                    "avatar"   => $user_info['head']
                );
                $userId = UserManager::create($userInfoData);
                if($userId){
                    $userThirdInfoData = array(
                        "user_id" => $userId,
                        "openid"  => $token['openid'],
                        "nickname" => $user_info['nick'],
                        "client_type" => $user_info['type']
                    );
                    $UserThird->add($userThirdInfoData);
                }
            }
            $this->setUser($userId);
            redirect("/");
        }
    }
    //登录地址
    public function logout(){
        $this->unsetUser();
        redirect("/");
    }
//    public function sinaLogin(){
//        //生成登录链接
//        import("@.ORG.Sina");
//        $sina_k='3598307079'; //新浪微博应用App Key
//        $sina_s='1c163695f54d5fabe10dd45e747d4ac4'; //新浪微博应用App Secret
//        echo $callback_url=U('Auth/Callback/sina@aiai.im');//授权回调网址
//        $sina=new Sina($sina_k, $sina_s);
//        $login_url=$sina->login_url($callback_url);
//        echo '<a href="',$login_url,'">点击进入授权页面</a>';
//    }
//    public function qqLogin(){
//        //生成登录链接
//        import("@.ORG.QQ");
//        $qq_k='100522698'; //QQ应用APP ID
//        $qq_s='aa00efb01848d2eb17acd3bfa3f92a50'; //QQ应用APP KEY
//        echo $callback_url=U('Callback/qq@aiai.im'); //授权回调网址
//        $scope='get_user_info,add_share'; //权限列表，具体权限请查看官方的api文档
//        $qq=new QQ($qq_k, $qq_s);
//        $login_url=$qq->login_url($callback_url, $scope);
//        echo '<a href="',$login_url,'">点击进入授权页面</a>';
//    }
//    public function _sina(){
//        //授权回调页面，即配置文件中的$callback_url
//        import('@.ORG.Sina');
//        $sina_k='3598307079'; //新浪微博应用App Key
//        $sina_s='1c163695f54d5fabe10dd45e747d4ac4'; //新浪微博应用App Secret
//        $callback_url=U('Auth/Callback/sina@aiai.im'); //授权回调网址
//        if(isset($_GET['code']) && $_GET['code']!=''){
//            $o=new Sina($sina_k, $sina_s);
//            $result=$o->access_token($callback_url, $_GET['code']);
//        }
//        if(isset($result['access_token']) && $result['access_token']!=''){
//            echo '授权完成，请记录<br/>access token：<input size="50" value="',$result['access_token'],'">';
//
//            //保存登录信息，此示例中使用session保存
//            $_SESSION['sina_t']=$result['access_token']; //access token
//        }else{
//            echo '授权失败';
//        }
//        echo '<br/><a href="demo.php">返回</a>';
//    }
//    public function _qq(){
//        //授权回调页面，即配置文件中的$callback_url
//        import('@.ORG.QQ');
//        $qq_k='100522698'; //QQ应用APP ID
//        $qq_s='aa00efb01848d2eb17acd3bfa3f92a50'; //QQ应用APP KEY
//        echo $callback_url=U('Callback/qq@aiai.im'); //授权回调网址
//        if(isset($_GET['code']) && trim($_GET['code'])!=''){
//            $qq=new QQ($qq_k, $qq_s);
//            $result=$qq->access_token($callback_url, $_GET['code']);
//        }
//        if(isset($result['access_token']) && $result['access_token']!=''){
//            echo '授权完成，请记录<br/>access token：<input size="50" value="',$result['access_token'],'">';
//
//            //保存登录信息，此示例中使用session保存
//            $_SESSION['qq_t']=$result['access_token']; //access token
//        }else{
//            echo '授权失败';
//        }
//        echo '<br/><a href="demo.php">返回</a>';
//
//    }

}
