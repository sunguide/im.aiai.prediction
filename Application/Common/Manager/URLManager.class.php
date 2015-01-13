<?php
/**
 * User: sunguide
 * Date: 14/11/15
 * Time: 12:58
 */

namespace Common\Manager;
use Common\Description;

class URLManager {

    public static function getURL($category, $id){
        if($id){
            $url = "";
            switch($category){
                case Description\CategoryDescription::CATEGORY_ARTICLE:
                    $url = "http://m.aiai.im/article/".$id;
                    break;
                case Description\CategoryDescription::CATEGORY_POSITION:
                    $url = "http://m.aiai.im/position/".$id;
                    break;
                case Description\CategoryDescription::CATEGORY_QUESTION_ANSWER:
                    break;
                case Description\CategoryDescription::CATEGORY_IMAGE:
                    $url = "http://m.aiai.im/img/".$id;
                    break;
                default:
                    break;
            }
            return $url;
        }else{
            return "";
        }
    }
}
