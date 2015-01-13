<?php
namespace App\Controller;
use Think\Controller;
class ImageController extends Controller {
    public function index(){
        $this->show("hh");
    }
    public function lists(){
        $id = I("id");
        $ImgGroup = M("ImgGroup");
        $images =  $ImgGroup->order("rand()")->limit(20)->select();
        foreach($images as $key => $img){
            if(isset($img['qiniu_img_url']) && $img['qiniu_img_url']){
                $images[$key]['img_url'] = $img['qiniu_img_url'];
            }
        }
        $this->assign("images",$images);
    }
    public function detail(){
        $id = intval(I("id"));
        $Img = M("Img");
        $ImgGroup = M("ImgGroup");
        $imgs =  $Img->where("img_group_id = $id")->limit(10)->select();
        $imgGroup = $ImgGroup->find($id);
        foreach($imgs as $key => $img){
            if(isset($img['qiniu_img_url']) && $img['qiniu_img_url']){
                $imgs[$key]['img_url'] = $img['qiniu_img_url'];
            }
        }
        $title = $imgGroup['title'] ? "有色图|".$imgGroup['title'] : "有色图";
        $this->assign("title",$title);
        $this->assign("imgs",$imgs);
        $this->display();
    }
    public function detailTest(){
        $id = intval(I("id"));
        $Img = M("Img");
        $ImgGroup = M("ImgGroup");
        $imgs =  $Img->where("img_group_id = $id")->limit(10)->select();
        $imgGroup = $ImgGroup->find($id);
        foreach($imgs as $key => $img){
            if(isset($img['qiniu_img_url']) && $img['qiniu_img_url']){
                $imgs[$key]['img_url'] = $img['qiniu_img_url'];
            }
        }
        $title = $imgGroup['title'] ? "有色图|".$imgGroup['title'] : "有色图";
        $this->assign("title",$title);
        $this->assign("imgs",$imgs);
        $this->display("detailTest");
    }

}
