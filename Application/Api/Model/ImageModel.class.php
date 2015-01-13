<?php
/**
 * Created by PhpStorm.
 * User: sunguide
 * Date: 14/11/21
 * Time: 23:26
 */

namespace Common\Model;


use Think\Model;

class ImageModel extends Model{

    protected $tableName = "Img";
    //随机获取1组图片
    public function getOneGroupImgByRand(){
        $ImgGroup = M("ImgGroup");
        $Img = M("Img");
        $imgGroup = $ImgGroup->order("rand()")->find();
        if($imgGroup){
            return $Img->where("img_group_id = {$imgGroup['group_id']}")->select();
        }
        return null;
    }
    //随机获取20条列表
    public function getImageListsByRand($amount = 20){
        $ImgGroup = M("ImgGroup");
        $imgGroups = $ImgGroup->order("rand()")->limit($amount)->select();
        return $imgGroups;
    }
    //获取指定组图片列表
    public function getImageListsByGroupId($id){
        $Img = M("Img");
        $id  = intval($id);
        $imgLists = $Img->where("img_group_id = $id")->select();
        return $imgLists;
    }
} 