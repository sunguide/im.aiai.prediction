<?php
/**
 * User: sunguide
 * Date: 14/11/25
 * Time: 01:39
 * Description:BaseController.php
 */

namespace Api\Controller;


use Think\Controller\RestController;

class BaseController extends RestController{
    protected $_check_auth = true;//是否需要检查认证
    protected $_error = array();
    protected $_request_hash = null;//用于判断数据是否有更新
    /**
     * 架构函数
     * @access public
     */
    public function __construct() {
        $this->_request_hash = trim(I("RequestHash"));
        parent::__construct();
    }
    //初始化函数
    function _initialize(){
        if($this->_check_auth){
            $access_token = trim(I("access_token"));
            $_access_token = "c92585a8dec3a3d632afc2e834d3849b";//可以从改成从数据库获取
            if($access_token != $_access_token){
                $this->setError("Auth Fail",401);
                $this->response("", "json", 401);
            }
        }
        if(REQUEST_METHOD == "OPTIONS"){
            header("Access-Control-Allow-Origin:*");
            header("Access-Control-Allow-Methods:POST, GET, OPTIONS, PUT, DELETE");
            header("Access-Control-Max-Age:1000");
            header("Access-Control-Allow-Headers:origin, x-csrftoken, content-type, accept");
            echo "dhh";
            exit;
        }
    }
    /**
     * 重写 输出返回数据
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type 返回类型 JSON XML
     * @param integer $code HTTP状态
     * @return void
     */
    protected function setError($error,$errorCode = 999){
        array_push($this->_error, array(
            "error" => strval($error),
            "code"  => intval($errorCode)
        ));
    }
    protected function response($data,$type='json',$code=200) {
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:POST, GET, OPTIONS, PUT, DELETE");
        header("Access-Control-Max-Age:1000");
        header("Access-Control-Allow-Headers:origin, x-csrftoken, content-type, accept");
        $md5Hash = md5(json_encode($data));
        if(empty($this->_error)){
            $data = array(
                "status"    => "OK",
                "result"    => $data,
                "RequestId" => $this->_genRequestId()
            );
        }else{
            $data = array(
                "status"    => "FAIL",
                "result"    => $data,
                "errors"     => $this->_error,
                "RequestId" => strval($this->_genRequestId())
            );
        }
        $data['RequestHash'] = $md5Hash;
        if($this->_request_hash && $this->_request_hash === $md5Hash){
            $data['result'] = "NO_UPDATE";
        }
        parent::response($data, $type, $code);
    }
    private function _genRequestId(){
        list($mSec, $sec) = explode(" ", microtime());
        return $sec.strval($mSec * 1000000).base_convert(uniqid(),16, 10);
    }
} 