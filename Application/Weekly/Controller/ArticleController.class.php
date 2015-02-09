<?php
namespace Weekly\Controller;
use Think\Controller;
class ArticleController extends Controller {
    public function index(){
        $this->show("hh");
    }
    public function detail(){
        $Articles = M("Articles");
        $id = I("id");
        $article =  $Articles->find($id);
        $Img = M("Img");
        $img = $Img->order("rand()")->find();
        if(isset($img['qiniu_img_url']) && $img['qiniu_img_url']){
            $img['img_url'] = $img['qiniu_img_url'];
        }
        $this->assign("article", $article);
        $this->assign("img",$img);
        $this->display();
    }

}
