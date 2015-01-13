<?php
namespace Mobile\Controller;
use Common\Description\CategoryDescription;
use Common\Manager\URLManager;
use Think\Controller;
class ImageController extends Controller {
    public function index(){
        $this->show("hh");
    }
    public function lists(){
        $category = trim(I("category"));
        $ImageGroupModel = M("ImgGroup");
        $where = "1";
        if($category){
            $where = " cate_name = '{$category}' ";
        }
        $imageLists = $ImageGroupModel->where($where)->order("group_id DESC")->limit(5)->select();
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
            array(
                "title" => "极品美女",
                "img_url" => "http://aiaifiles.qiniudn.com/460662b3-ac90-ea3d-376f-1c4afd9ed1fc.jpg?imageView2/2/w/400/q/100",
                "url"   => URLManager::getURL(CategoryDescription::CATEGORY_IMAGE, 1687)
            ),
            array(
                "title" => "萌萌哒，性感小美人",
                "img_url" => "http://aiaifiles.qiniudn.com/b1a1629c-90ed-2d4f-6d56-772c7b75cdda.jpg?imageView2/2/w/400/q/100",
                "url"   => URLManager::getURL(CategoryDescription::CATEGORY_IMAGE, 1601)
            )
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
