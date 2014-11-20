<?php
namespace Wechat\Controller;
use Com\Wechat\WechatAuth;
use Common\Common\Description\CategoryDescription;
use Common\Common\Description\ThirdClientTypeDescription;
use Think\Controller;
use Com\Wechat\Wechat;
use Common\Service\SearchService;
use Common\Service\QuestionAnswerService;
use User\Api\UserApi;
use Think\Log;
//http://wx.aiai.im/index.php?s=/home/weixin/index.html
class TestController extends Controller {
    public $token = "weiphp"; //微信后台填写的TOKEN
    public $wechat = null;
    public $wechatAuth = null;

    public function response(){
        Log::write('start receice data：', Log::DEBUG);
        /* 加载微信SDK */
        $wechat = new Wechat($this->token);
        /* 获取请求信息 */
        $data = $wechat->request();
        $this->_recordData($data);
        if($data && is_array($data)){

            /**
             * 你可以在这里分析数据，决定要返回给用户什么样的信息
             * 接受到的信息类型有9种，分别使用下面九个常量标识
             * Wechat::MSG_TYPE_TEXT       //文本消息
             * Wechat::MSG_TYPE_IMAGE      //图片消息
             * Wechat::MSG_TYPE_VOICE      //音频消息
             * Wechat::MSG_TYPE_VIDEO      //视频消息
             * Wechat::MSG_TYPE_MUSIC      //音乐消息
             * Wechat::MSG_TYPE_NEWS       //图文消息（推送过来的应该不存在这种类型，但是可以给用户回复该类型消息）
             * Wechat::MSG_TYPE_LOCATION   //位置消息
             * Wechat::MSG_TYPE_LINK       //连接消息
             * Wechat::MSG_TYPE_EVENT      //事件消息
             *
             * 事件消息又分为下面五种
             * Wechat::MSG_EVENT_SUBSCRIBE          //订阅
             * Wechat::MSG_EVENT_SCAN               //二维码扫描
             * Wechat::MSG_EVENT_LOCATION           //报告位置
             * Wechat::MSG_EVENT_CLICK              //菜单点击
             * Wechat::MSG_EVENT_MASSSENDJOBFINISH  //群发消息成功
             */

            switch($data['MsgType']){
                case Wechat::MSG_TYPE_TEXT:
                    $searchService = new SearchService();
                    $keyword = $data['Content'];
                    if(strpos($keyword, "答案") !== false){
                        $this->autoLearning($data, $keyword);
                        return;
                    }

                    $result = json_decode($searchService->search($keyword), true);
                    if($result['status'] == "OK"){
                        $items = $result['result']['items'];
                        if(!empty($items)){
                            $item = $items[0];
                            $title = strip_tags($item['title']);
                            $url = $item['url'];
                            $picurl = $item["thumbnail"] ? $item['thumbnail'] : "";
                            $description = $item['body'];
                            if($item['cat_id'] == CategoryDescription::CATEGORY_QUESTION_ANSWER){
                                $result = $wechat->replyText($description);
//                                $result = $wechat->response($description, Wechat::MSG_TYPE_TEXT);
                            }else{
                                $result = $wechat->replyNewsOnce($title, $description, $url, $picurl); //回复单条图文消息
                            }

                        }else{
                            $result = $wechat->replyText("小爱，年龄还小哦，还有好多都不懂哦。哥哥，可以回复[答案：xxxx],教我哦！(*^__^*) 嘻嘻……");
                        }
                    }else{
                        $responseContent = "哥哥，对不起，小爱，刚刚开了小差。";
                        $wechat->response($responseContent, Wechat::MSG_TYPE_TEXT);
                    }
                    break;
                case Wechat::MSG_TYPE_EVENT:
                    $this->handleEvent($data);
                default:

            }
            $content = ''; //回复内容，回复不同类型消息，内容的格式有所不同
            $type    = ''; //回复消息的类型
//            Log::write('response data：'.$content, Log::DEBUG);
            /* 响应当前请求(自动回复) */
//            $result = $wechat->response($content, $type);

            Log::write('response result：'.json_encode($result), Log::DEBUG);
            /**
             * 响应当前请求还有以下方法可以只使用
             * 具体参数格式说明请参考文档
             *
             * $wechat->replyText($text); //回复文本消息
             * $wechat->replyImage($media_id); //回复图片消息
             * $wechat->replyVoice($media_id); //回复音频消息
             * $wechat->replyVideo($media_id, $title, $discription); //回复视频消息
             * $wechat->replyMusic($title, $discription, $musicurl, $hqmusicurl, $thumb_media_id); //回复音乐消息
             * $wechat->replyNews($news, $news1, $news2, $news3); //回复多条图文消息
             * $wechat->replyNewsOnce($title, $discription, $url, $picurl); //回复单条图文消息
             *
             */
        }

    }
    private function _recordData($data){
        Log::write('receice data：'.json_encode($data), Log::DEBUG);
        $WechatLog = M("WechatLog");
        $WechatLog->data = json_encode($data);
        $WechatLog->from_user_name = $data['FromUserName'];
        $WechatLog->to_user_name   = $data['ToUserName'];
        if(!$WechatLog->add()){
            Log::write("record wechat log fail",Log::ALERT);
        }
    }
    public function autoLearning($data,$answer){
        $WechatLog = M("WechatLog");
        $QuestionAnswerService = new QuestionAnswerService();
        $condition['from_user_name'] = $data['FromUserName'];
        $condition['create_time'] = array('lt',datetime());
        $log = $WechatLog->where($condition)->order("id desc")->find();
        if($log){
            $answer = str_replace("答案:", "", $answer);
            if(strpos($answer, "答案") === 0){
                $answer = str_replace("答案", "", $answer);
            }
            $questionData = json_decode($log['data'], true);
            if($questionData['content'] && trim($answer)){
                $QuestionAnswerService->autoIndex($questionData['content'], $answer);
            }
        }
    }
    public function handleEvent($data){
        $wechat = new Wechat($this->token);
        $responseContent = "小哥，相见恨晚哦，快来调戏我把！";
        switch($data['Event']){
            case Wechat::MSG_EVENT_SUBSCRIBE:          //订阅
                $responseContent = "小哥，相见恨晚哦，快来调戏我把！";
                $this->_handleEventSubscribe($data['FromUserName']);
                break;
            case Wechat::MSG_EVENT_SCAN:               //二维码扫描
                break;
            case Wechat::MSG_EVENT_LOCATION:           //报告位置
                break;
            case Wechat::MSG_EVENT_CLICK:              //菜单点击
                break;
            case Wechat::MSG_EVENT_MASSSENDJOBFINISH:  //群发消息成功
                break;
            default:

        }
        $wechat->response($responseContent, Wechat::MSG_TYPE_TEXT);
    }
    private function _handleEventSubscribe($openID){
        $condition['openid'] = $openID;
        $UserThird = M("UserThird");
        $userThird = $UserThird->where($condition)->find();
        if(!$userThird){
            $UserApi = new UserApi;
            $uid = $UserApi->genUserID();
            $UserThird->add(array(
                "openid"    =>  $openID,
                "user_id"   =>  $uid,
            ));
        }
    }


    private function _getWechat(){
        if($this->wechat != null){
            return $this->wechat;
        }
        return $this->wechat = Wechat::getInstance($this->token);
    }
    //必须是微信认证用户才可以使用
    private function _getWechatAuth(){
        if($this->wechat != null){
            return $this->wechat;
        }
        $OAuthClient = M("OauthClient", null);
        $condition = array("client_type" => ThirdClientTypeDescription::WECHAT);
        $client = $OAuthClient->where($condition)->find();
        if($client){
            return $this->wechatAuth = WechatAuth::getInstance($client['client_id'],$client['client_secret'],$client['access_token']);
        }
        return null;

    }
    public function test(){
        $indexController = new IndexController();
        $indexController->autoLearning(array(""),"wo是好人");
    }
}
