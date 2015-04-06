<?php
namespace Home\Controller;
use Think\Controller;
class ArticleController extends BaseController {

    public function index(){
        $this->display();
    }
    public function detail(){
        $id = I("id");
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
