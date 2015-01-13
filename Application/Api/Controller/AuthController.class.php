<?php
namespace Api\Controller;
use Composer\DependencyResolver\Transaction;
use Think\Controller;
use PredictionIO\PredictionIOClient;
use Org\OAuth\ThinkOAuth2;
class AuthController extends BaseController {
    private $oauth = NULL;

    function _initialize(){
        $this->oauth = new ThinkOAuth2();

    }
    public function access_token() {

        $this->oauth->grantAccessToken();

    }
    //权限验证
    public function authorize() {

        if ($_POST) {
            $this->oauth->finishClientAuthorization($_POST["accept"] == "Yep", $_POST);
            return;
        }

        ///表单准备
        $auth_params = $this ->oauth ->getAuthorizeParams();
        $this->assign("params", $auth_params);
        $this->display();

    }
    public function verify(){

        if(!$this->oauth-> verifyAccessToken()){
            $this->ajaxReturn(null, 'no,no,no', 0, 'json');
            exit();
        }
        $this -> ajaxReturn(null, 'oauth-server', 1, 'json');

    }
    public function m(){
        $Articles = M("Articles");
        dump($Articles->find());
        $M = M("Img");
        dump($M->find());
    }
}
