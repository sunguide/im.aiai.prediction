<?php
namespace Api\Controller;
use Think\Controller;
class ArticleController extends Controller {
    public function index(){
        $this->show("hh");
    }
    public function detail(){
        $Articles = M("Articles");
        $id = I("id");
        $article =  $Articles->find($id);
        $this->assign("article", $article);
        $this->display();
    }

}
