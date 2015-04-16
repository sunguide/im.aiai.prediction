<?php
namespace Home\Controller;
use Common\Description\UserBehaviorDescription;
use Common\Manager\UserBehaviorManager;
use Think\Controller;
class ArticleController extends BaseController {

    public function index(){
        $this->display();
    }
    public function detail(){
        $id = I("id");
        //记录用户行为
        $id && UserBehaviorManager::add($this->getUserId(),UserBehaviorDescription::GROUP_ARTICLE,$id,UserBehaviorDescription::ACTION_VIEW);
        $Article = M("Articles");
        $article = $Article->find($id);
        $this->assign("article",$article);
        $this->display();
    }
    public function love(){
        $type = I("type");
        $order = "";
        if($type == "hottest"){
            $order = "";
        }else if($type == "newest"){
            $order = "article_id DESC";
        }
        $Article = M("Articles");
        $result = $Article->order($order)->limit(20)->select();
        $this->assign("lists",$result);
        $this->display();
    }
}
