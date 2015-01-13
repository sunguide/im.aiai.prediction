<?php
/**
 * Created by PhpStorm.
 * User: sunguide
 * Date: 14/11/23
 * Time: 16:26
 * Description: API FOR IMAGE
 */

namespace Api\Controller;

class ImageController extends BaseController{

    public function lists(){
        $ImageModel = D("Image");
        $imageLists = $ImageModel->getImageListsByRand(5);
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
        $this->response($result);
    }
    public function detail(){
        $groupId = intval(I("gid"));
        $ImageModel = D("Image");
        $imageLists = $ImageModel->getImageListsByGroupId($groupId);
        $result = array();
        if(!empty($imageLists)){
            foreach($imageLists as $imageList){
                $data = array(
                    "id"       => $imageList['id'],
                    "img_title" => $imageList['img_title'],
                    "img_url"   => $imageList['qiniu_img_url'] ?: $imageList['img_url']
                );
                if($data['img_url']){
                    $result[] = $data;
                }
            }
        }else{
            $this->setError("NO RESULT");
        }
        $this->response($result);
    }
} 