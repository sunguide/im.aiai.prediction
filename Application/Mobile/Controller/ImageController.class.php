<?php
namespace Mobile\Controller;
use Think\Controller;
class ImageController extends Controller {
    public function index(){
        $this->show("hh");
    }
    public function lists(){
        $id = I("id");
        $ImgGroup = M("ImgGroup");
        $images =  $ImgGroup->order("rand()")->limit(20)->select();
        $this->assign("images",$images);
//        $this->display();
        echo file_get_contents("http://m.image.so.com/z?ch=beauty&cid=%E5%AE%85%E7%94%B7%E5%A5%B3%E7%A5%9E");
    }
    public function detail(){
        $Articles = M("Articles");
        $id = I("id");
        $article =  $Articles->find($id);
        $Img = M("Img");
        $img = $Img->order("rand()")->find();
        $this->assign("article", $article);
        $this->assign("img",$img);
        $this->display();
    }

}
