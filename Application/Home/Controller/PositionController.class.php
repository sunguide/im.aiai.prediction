<?php
namespace Home\Controller;
use Common\Description\UserBehaviorDescription;
use Common\Manager\UserBehaviorManager;
use Think\Controller;
class PositionController extends BaseController {

    public function index(){
        $limit = 21;
        $offset = I("p",0) * $limit;
        $PositionModel = M("Position");
        $conditions = array("position_style" =>2);
        $items = $PositionModel->where($conditions)->limit($limit,$offset)->select();
        foreach($items as $key => $item){
            $items[$key]['article_content'] = cnTruncate(strip_tags($item['article_content']),50);
        }
        $this->assign("lists", $items);
        $this->display();
    }

    public function detail(){
        $id = intval(I("id"));
        $PositionModel = M("Position");
        $item = $PositionModel->find($id);
        $this->assign("item", $item);
        $this->display();
    }

    public function more(){
        $limit = 21;
        $offset = I("p",0)*$limit;
        $PositionModel = M("Position");
        $conditions = array("position_style" =>2);
        $items = $PositionModel->where($conditions)->limit($limit,$offset)->select();
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
        $result = UserBehaviorManager::add($user,UserBehaviorDescription::GROUP_POSITION,$id,UserBehaviorDescription::ACTION_FAVORITE,$status);
        if(!$result){
           $this->setError("操作失败");
        }
        $this->response($result);
    }
    private function getPositionImagePath($id){
        return "/Public/";
    }
}
