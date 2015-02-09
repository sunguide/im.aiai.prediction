<?php
/**
 * User: sunguide
 * Date: 14/11/25
 * Time: 01:39
 * Description:BaseController.php
 */

namespace Home\Controller;


use Think\Controller;

class BaseController extends Controller{
    protected $_check_auth = false;//是否需要检查认证
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
        $this->sendHttpStatus($code);
        $this->ajaxReturn($data,$type);
    }
    private function _genRequestId(){
        list($mSec, $sec) = explode(" ", microtime());
        return $sec.strval($mSec * 1000000).base_convert(uniqid(),16, 10);
    }
    // 发送Http状态信息
    protected function sendHttpStatus($code) {
        static $_status = array(
            // Informational 1xx
            100 => 'Continue',
            101 => 'Switching Protocols',
            // Success 2xx
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            // Redirection 3xx
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Moved Temporarily ',  // 1.1
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            // 306 is deprecated but reserved
            307 => 'Temporary Redirect',
            // Client Error 4xx
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            // Server Error 5xx
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            509 => 'Bandwidth Limit Exceeded'
        );
        if(isset($_status[$code])) {
            header('HTTP/1.1 '.$code.' '.$_status[$code]);
            // 确保FastCGI模式下正常
            header('Status:'.$code.' '.$_status[$code]);
        }
    }
} 