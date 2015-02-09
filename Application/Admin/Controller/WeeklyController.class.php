<?php
namespace Admin\Controller;
use Think\Controller;
class WeeklyController extends BaseController {

    public static function _nav(){
        return array(
          "title" => "爱爱周刊",
          "name"  => "Weekly",
          "url"   => U("Weekly/index"),
          "icon"  => "ion-ios7-navigate-outline",
          "badge" => "ddd",
          "child" => array(
              array(
                  "title" => "周刊",
                  "name"  => "Weekly/index",
                  "url"   => U("Weekly/index"),
                  "icon"  => "ion-ios7-navigate-outline",
                  "badge" => "New",
                  "child" => array(

                  )
              ),
              array(
                  "title" => "文章",
                  "name"  => "Weekly/article",
                  "url"   => U("Weekly/article"),
                  "icon"  => "ion-ios7-navigate-outline",
                  "badge" => "New",
                  "child" => array(

                  )
              ),
              array(
                  "title" => "订阅者",
                  "name"  => "Weekly/subscribe",
                  "url"   => U("Weekly/subscribe"),
                  "icon"  => "ion-ios7-navigate-outline",
                  "badge" => "New",
                  "child" => array(

                  )
              )
          )
        );
    }
    public function index(){
        $WeeklyModel = M("weekly");
        $weekly = $WeeklyModel->select();
        $this->assign("weekly",$weekly);
        $this->display("list");
    }
    //文章详情
    public function detail(){
        $id = intval(I("id"));
        $Weekly = M("Weekly");
        $article = $Weekly->find($id);
        $this->assign("article", $article);
        $this->display();
    }
    public function article(){
        $id = intval(I("id"));
        $wid = intval(I("wid"));
        $WeeklyArticleModel = M("WeeklyArticle");
        $WeeklyModel = M("Weekly");
        if($id){
            $weelys = $WeeklyModel->select();
            $article = $WeeklyArticleModel->find($id);
            $this->assign("article", $article);
            $this->assign("weeklys",$weelys);
            $this->display('article_edit');
        }else{
            if($wid){
                $conditions = array("weekly_id" => $wid);
                $articles = $WeeklyArticleModel->where($conditions)->select();
            }else{
                $articles = $WeeklyArticleModel->select();
            }
            foreach($articles as $k => $article){
                $articles[$k]['weekly_title'] = $WeeklyModel->getFieldById($article['weekly_id'],"title");
            }
            $this->assign("articles", $articles);
            $this->display();
        }

    }
    // create weekly 周刊
    public function create(){
        if(IS_POST){
            $Weekly = M("weekly");
            $id = intval(I("id"));
            $title = I("title");
            $status = I("status");
            $data = compact("id","title","status");
            $data['update_time'] = time();
            if($id){
                $result = $Weekly->save($data);
            }else{
                $data['create_time'] = time();
                $result = $Weekly->add($data);
            }
            if(!isset($result)){
                $this->setError("文章内容不能为空");
            }else if(!$result){
                $this->setError("保存失败:".$Weekly->getError());
            }
            $this->response($data,"JSON");
        }else{
            $Weekly = M("weekly");
            $id = I("id");
            $weekly = null;
            if($id){
                $weekly = $Weekly->find($id);
            }
            $this->assign("weekly",$weekly);
        }
        $this->display("edit");
    }
    // create weekly 发布周刊文章
    public function publish(){
        if(IS_POST){
            $id = intval(I("id"));
            $title = I("title");
            $content = I("article");
            $author_name = I("author_name");
            $author_url = I("author_url");
            $source = I("source");
            $weekly_id = intval(I("weekly_id"));
            if($content){
                $update_time = time();
                $data = compact("id","title","content","author_name","author_url","source","weekly_id","update_time");
                $WeeklyArticle = M("WeeklyArticle");
                if($id){
                    $result = $WeeklyArticle->save($data);
                }else{
                    $data['create_time'] = time();
                    $result = $WeeklyArticle->add($data);
                }
            }
            if(!isset($result)){
                $this->setError("文章内容不能为空");
            }else if(!$result){
                $this->setError("保存失败:".$WeeklyArticle->getLastSql().$WeeklyArticle->getError());
            }
            $this->response($data,"JSON");
        }
        $id = intval(I("id"));
        $WeeklyArticleModel = M("WeeklyArticle");
        $article = $WeeklyArticleModel->find($id);
        $this->assign("article", $article);
        $this->display("article_edit");
    }

    public function subscribe(){
        $subscribeModel = M("WeeklySubscribe");
        $page = I("page",1);
        $subscribes = $subscribeModel->page($page,50)->select();
        $this->assign("subscribes",$subscribes);
        $this->display();
    }
}
