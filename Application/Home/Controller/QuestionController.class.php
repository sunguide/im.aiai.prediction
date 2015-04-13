<?php
namespace Home\Controller;
use Common\Description\UserBehaviorDescription;
use Common\Manager\UserBehaviorManager;
use Think\Controller;
class QuestionController extends BaseController {

    public function index(){
        $limit = 21;
        $offset = I("p",0) * $limit;
        $QuestionModel = M("Question");
        $conditions = array("status" => 1);
        $items = $QuestionModel->where($conditions)->limit($limit,$offset)->select();
//        foreach($items as $key => $item){
//            $items[$key]['content'] = cnTruncate(strip_tags($item['content']),50);
//        }
        $this->assign("lists", $items);
        $this->display();
    }


    public function newest(){

    }

    public function hottest(){

    }

    public function unanswered(){

    }

    public function create(){
        $title = I("title");
        $content = I("content");
        $data = array(
            "user_id" => intval($this->getUserId()),
            "title" => $title,
            "content" => $content,
            "create_time" => time(),
            "update_time" => time()
        );
        $Question = M("Question");$Question->add($data);

        $this->response($Question->getLastSql());
    }

    public function detail(){
        $id = intval(I("id"));
        $QuestionModel = M("Question");
        $item = $QuestionModel->find($id);
        $this->assign("item", $item);
        $this->display();
    }

    public function more(){
        $limit = 21;
        $offset = I("p",0)*$limit;
        $QuestionModel = M("Question");
        $conditions = array("Question_style" =>2);
        $items = $QuestionModel->where($conditions)->limit($limit,$offset)->select();
        foreach($items as $key => $item){
            $items[$key]['article_content'] = cnTruncate(strip_tags($item['article_content']),50);
        }
        $this->assign("lists", $items);
        $this->display();
    }
    //其实归根到底，赞，喜欢，收藏，可看似一种操作
    public function favorite($id){
        $status = I("status",1);
        $user = $this->getUserId();
        $status ? $status = UserBehaviorDescription::STATUS_NORMAL:$status = UserBehaviorDescription::STATUS_CANCEL;
        $result = UserBehaviorManager::add($user,UserBehaviorDescription::GROUP_Question,$id,UserBehaviorDescription::ACTION_FAVORITE,$status);
        if(!$result){
           $this->setError("操作失败");
        }
        $this->response($result);
    }
    private function getQuestionImagePath($id){
        return "/Public/";
    }
}
