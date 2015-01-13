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

    public function getOneGroupImgByRand(){
        $ImgGroup = M("ImgGroup");
        $Img = M("Img");
        $imgGroup = $ImgGroup->order("rand()")->find();
        if($imgGroup){
            return $Img->where("img_group_id = {$imgGroup['group_id']}")->select();
        }
        return null;
    }
} 