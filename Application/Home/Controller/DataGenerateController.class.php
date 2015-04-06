<?php
namespace Home\Controller;
use Think\Controller;
class DataGenerateController extends BaseController {
    //随机产生1000个种子用户
    public function user(){
        $users = file_get_contents(APP_PATH."../Data/user.txt");
        $users = explode("\n",$users);
        $userKeys  = array_rand($users,1000);
        $avatarModel = M("Avatar");
        $avatars = $avatarModel->order("rand()")->limit(1000)->select();
        $UserModel = M("User");
        foreach($userKeys as $k=>$userKey){
            $nickname = array_pop(explode(",",$users[$userKey]));
            $avatars[$k] && $avatar = $avatars[$k]["local_img_url"];
            if($nickname && $avatar){
                $userInfo = array("nickname" => $nickname,"avatar" => $avatar ,"is_seed" => 1, "reg_time" => NOW);
                $UserModel->add($userInfo);
            }
        }
    }
    public function attachArticleToUser(){
        $UserModel = M("User");
        $ArticleModel = M("Articles");
        $articles = $ArticleModel->select();
        $users = $UserModel->where("is_seed = 1")->order("rand()")->limit(200)->select();
        foreach($articles as $article){
            $data = array("user_id" => $users[rand(0,199)]['id']);
            $ArticleModel->where("article_id = ".$article['article_id'])->save($data);
            dump($ArticleModel->getLastSql());
        }

    }

    public function assignMasters(){
        $UserBadgeModel = M("UserBadge");
        for($userId = 50;$userId<101;$userId++){
            $data = array(
                "user_id" => $userId,
                "badge"   => "性爱大师"
            );
            $UserBadgeModel->add($data);
        }

    }
}
