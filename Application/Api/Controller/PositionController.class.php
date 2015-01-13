<?php
/**
 * Created by PhpStorm.
 * User: sunguide
 * Date: 14/11/23
 * Time: 16:26
 * Description: API FOR IMAGE
 */

namespace Api\Controller;

use Common\Description\CategoryDescription;
use Common\Manager\URLManager;
use Think\Crypt\Driver\Think;

class PositionController extends BaseController{

    public function lists(){
        $PositionModel = M("Position");
        $cursor = intval(I("cursor"));
        $limit = 5;
        $lists = $PositionModel->where("position_style = 2")->order("id DESC")->limit("$cursor,$limit")->select();
        $positions = array();
        if(!empty($lists)){
            foreach($lists as $list){
                $positions[] = array(
                    "id"        => Think::encrypt($list['id'],C("APP_ENCRYPTION_KEY") ),//C("APP_ENCRYPTION_KEY")
                    "title" => $list['position_title'],
                    "img_url"   => $list['position_image'],
                    "url"       => URLManager::getURL(CategoryDescription::CATEGORY_POSITION, $list['id'])
                );
            }
        }
        $navs = array();
        $navs[] = array(
            "id"        => 1,//C("APP_ENCRYPTION_KEY")
            "title"     => "我是测试",
            "img_url"   => "http://m.yooli.com/resrc/img/banner/runningcoupons.jpg",
            "url"       => ""
        );
        $navs[] = array(
            "id"        => 2,//C("APP_ENCRYPTION_KEY")
            "title"     => "我是d测试",
            "img_url"   => "http://m.yooli.com/resrc/img/banner/runningcoupons.jpg",
            "url"       => ""
        );
        $result = array(
            "navs"      => $navs,
            "positions" => $positions
        );
        $this->response($result);
    }
    public function detail(){
        $id = I("id");
        if(!is_numeric($id)){
            $id = Think::decrypt($id, C("APP_ENCRYPTION_KEY"));
        }
        $result = array();
        if($id){
            $PositionModel = M("Position");
            $position = $PositionModel->find($id);
            if($position){
                $result = array(
                    "id"        => $id,
                    "img_url"   => $position['position_image'],
                    "title"     => $position['position_title'],
                    "content"   => $position['article_content'],
                    "url"       => URLManager::getURL(CategoryDescription::CATEGORY_POSITION, $id)
                );
            }
        }
        if(empty($result)){
            $this->setError("NO RESULT");
        }
        $this->response($result);

    }
} 