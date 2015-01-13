<?php
namespace Admin\Controller;
use Think\Controller;
class WeeklyController extends BaseController {

    public function index(){
        $WeeklyModel = M("weekly");
        $weekly = $WeeklyModel->select();
        $this->assign("weekly",$weekly);
        $this->display("list");
    }

    public function detail(){
        $id = intval(I("id"));
        $Weekly = M("Weekly");
        $article = $Weekly->find($id);
        $this->assign("article", $article);
        $this->display();
    }
    public function edit(){
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
                if($id){
                    $result = $Weekly->add($data);
                }else{
                    $result = $Weekly->save($data);
                }
            }
            if(!isset($result)){
                $this->setError("文章内容不能为空");
            }else if(!$result){
                $this->setError("保存失败:".$Weekly->getError());
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
