<?php
namespace Home\Controller;
use Common\Description\UserBehaviorDescription;
use Common\Manager\AnswerManager;
use Common\Manager\UserBehaviorManager;
use Think\Controller;
class AnswerController extends BaseController {

    public function index(){
        $limit = 21;
        $offset = I("p",0) * $limit;
        $AnswerModel = M("Answer");
        $conditions = array("status" => 1);
        $items = $AnswerModel->where($conditions)->limit($limit,$offset)->select();
//        foreach($items as $key => $item){
//            $items[$key]['content'] = cnTruncate(strip_tags($item['content']),50);
//        }
        $this->assign("lists", $items);
        $this->display();
    }


    public function newest(){
        $limit = 21;
        $offset = I("p",0) * $limit;
        $items = AnswerManager::newest($offset,$limit);
        $this->assign("nav_tab","newest");
        $this->assign("lists", $items);
        $this->display("index");
    }

    public function hottest(){
        $limit = 21;
        $offset = I("p",0) * $limit;
        $items = AnswerManager::hottest($offset,$limit);
        $this->assign("nav_tab","hottest");
        $this->assign("lists", $items);
        $this->display("index");
    }

    public function vote(){
        $limit = 21;
        $offset = I("p",0) * $limit;
        $items = AnswerManager::unanswered($offset,$limit);

        $this->assign("nav_tab","unanswered");
        $this->assign("lists", $items);
        $this->display("index");
    }

    public function create(){
        $content = I("content");
        $questionId = I("question_id");
        $result = false;
        if($content && $questionId){
            $data = array(
                "user_id" => intval($this->getUserId()),
                "question_id" => $questionId,
                "content" => $content,
                "create_time" => time(),
                "update_time" => time()
            );
            $Answer = M("Answer");
            $result = $Answer->add($data);
        }else{
            $this->setError("回答内容不能为空");
        }
        $this->response($result);
    }

    public function detail(){
        $id = intval(I("id"));
        //记录访问
        $id && UserBehaviorManager::add($this->getUserId(),UserBehaviorDescription::GROUP_Answer,$id,UserBehaviorDescription::ACTION_VIEW);
        $AnswerModel = M("Answer");
        $item = $AnswerModel->find($id);
        $this->assign("item", $item);
        $this->display();
    }

    public function more(){
        $limit = 20;
        $offset = I("p",0)*$limit;
        $AnswerModel = M("Answer");
        $conditions = array("status" => 1);
        $items = $AnswerModel->where($conditions)->limit($limit,$offset)->select();
        foreach($items as $key => $item){
            $items[$key]['article_content'] = cnTruncate(strip_tags($item['article_content']),50);
        }
        $this->assign("lists", $items);
        $this->display();
    }
    private function getAnswerImagePath($id){
        return "/Public/";
    }
}
