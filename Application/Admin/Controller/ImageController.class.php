<?php
namespace Admin\Controller;
use Think\Controller;
class ImageController extends Controller {
    public function index(){
        $this->show("hh");
    }
    public function lists(){
        $ImageGroupModel = M("ImgGroup");
        $where = "1";
        $limit = 20;
        $imageLists = $ImageGroupModel->where($where)->order("group_id DESC")->limit($limit)->select();
        $result = array();
        if(!empty($imageLists)){
            foreach($imageLists as $imageList){
                if(!$imageList['img_url']) continue;
                $data = array(
                    "gid" => $imageList['group_id'],
                    "img_title" => $imageList['title'],
                    "img_url"       => $imageList['qiniu_img_url'] ?: $imageList['img_url']
                );
                if($data['img_url']){
                    $result['images'][] = $data;
                }
            }
        }
        $result['navs'] = array(
            array("title" => "dfdf","img_url" => "http://m.yooli.com/resrc/img/banner/gain.jpg"),
            array("title" => "dfdf","img_url" => "http://m.yooli.com/resrc/img/banner/security.jpg")
        );
        $this->assign("images",$result['images']);
        $this->assign("navs",$result['navs']);
        $this->display();
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
