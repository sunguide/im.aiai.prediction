<?php
namespace Api\Controller;
use Api\Common\WeChatCallback;
use Think\Controller;
class QAController extends Controller {

    public function index(){
        define("TOKEN", "aiai");
        $wechatObj = new WeChatCallback();
        $wechatObj->valid();
    }
    public function autoIndex(){
        $params['keyword'] = trim(I('keyword'));
        $params['content'] = trim(urldecode(I('content')));
        $info = "操作成功";
        $error = 0;
        if($params['keyword'] && $params['content']){
            $keywords = explode(",", $params['keyword']);
            $QAModel = M("Qa");
            $QAModel->startTrans();
            try{
                $QaContentModel = M("QaContent");

                $qaContent = $QaContentModel->where("content = '{$params['content']}'")->find();
                if($qaContent){
                    $contentId = $qaContent['id'];
                }else{
                    $QaContentModel->content = trim(urldecode($params['content']));
                    $contentId = $QaContentModel->add();
                    if(!$contentId){
                        throw_exception($QaContentModel->getError());
                    }
                }
                foreach($keywords as $keyword){
                    $keyword = urldecode($keyword);
                    $qa = $QAModel->where("keyword = '{$keyword}'")->find();
                    if($qa && $qa['status'] != 1){
                        $QAModel->id = $qa['id'];
                        $QAModel->status = 1;
                        if($QAModel->save()){
                            throw_exception($QAModel->getError());
                        }
                    }else if(!$qa){
                        $QAModel->category = 0;
                        $QAModel->content_id = $contentId;
                        $QAModel->status = 1;
                        $QAModel->keyword = $keyword;
                        $QAModel->create_time = time();
                        if(!$QAModel->add()){
                            throw_exception($QAModel->getError());
                        }
                    }

                }
                $QAModel->commit();
            }catch (ThinkException $e){
                $QAModel->rollback();
                $info = $e->getMessage();
                $error = 1;
            }
            $data = array("error" => $error,"info" => $info);

        }else{
            $data = array("error" => "1","info" => "参数不全，keyword & content");
        }
        $this->ajaxReturn($data);
    }
}