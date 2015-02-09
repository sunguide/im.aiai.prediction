<?php
namespace Admin\Controller;
use Think\Controller;
class WeeklyArticleController extends BaseController {

    public function index(){
        $WeeklyModel = M("weeklyArticle");
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
    // create weekly 周刊
    public function create(){
        if(IS_POST){
            $Weekly = M("weekly");
            $id = intval(I("id"));
            $title = I("title");
            $description = I("description");
            $data = compact("id","title","description");
            if($id){
                $result = $Weekly->save($data);
            }else{
                $data['create_time'] = time();
                $result = $Weekly->add($data);
            }
            if(!isset($result)){
                $this->setError("文章内容不能为空");
            }else if(!$result){
                $this->setError("保存失败:".$Weekly->getLastSql().$Weekly->getError());
            }
            $this->response($data,"JSON");
        }
        $this->display();
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
            if($content){
                $data = compact("id","title","content","author_name","author_url","source");
                $Weekly = M("weekly");
                $data['update_time'] = time();
                if($id){
                    $result = $Weekly->save($data);
                }else{
                    $data['create_time'] = time();
                    $result = $Weekly->add($data);
                }
            }
            if(!isset($result)){
                $this->setError("文章内容不能为空");
            }else if(!$result){
                $this->setError("保存失败:".$Weekly->getLastSql().$Weekly->getError());
            }
            $this->response($data,"JSON");
        }
        $id = intval(I("id"));
        $Weekly = M("Weekly");
        $article = $Weekly->find($id);
        $this->assign("article", $article);
        $this->display();
    }
}
