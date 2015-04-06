<?php
namespace Home\Controller;
use Common\Task\QueueTask;
use Think\Controller;
class WeeklyController extends BaseController {

    public function index(){
        $WeeklyArticleModel = M("weeklyArticle");
        $WeeklyModel = M("Weekly");
        $id = intval(I("id"));
        if($id){
            $conditions = array("weekly_id" => $id);
            $weeklyInfo = $WeeklyModel->find($id);
            $articles = $WeeklyArticleModel->where($conditions)->order("id asc")->select();
        }else{
            $weeklyInfo = $WeeklyModel->where('status = 1')->order("id desc")->find();
            $conditions = array("weekly_id" => $weeklyInfo['id']);
            $conditions['weekly_id'] = $weeklyInfo['id'];
            $articles = $WeeklyArticleModel->where($conditions)->order("id asc")->select();
        }
        $this->assign("weekly",$weeklyInfo);
        $this->assign("articles",$articles);
        $this->display("list");
    }
    public function history(){
        $WeeklyModel = M("Weekly");
        $weeklys = $WeeklyModel->select();
        $this->assign("weeklys",$weeklys);
        $this->display("history");
    }
    public function detail(){
        $id = intval(I("id"));
        $Weekly = M("Weekly");
        $article = $Weekly->find($id);
        $article['content']?$article['content'] = html_entity_decode($article['content']):"";
        $this->assign("article", $article);
        $this->display();
    }
    public function article(){
        $id = intval(I("id"));
        $WeeklyArticle = M("WeeklyArticle");
        $article = $WeeklyArticle->find($id);
        $article['content']?$article['content'] = html_entity_decode($article['content']):"";
        $this->assign("article", $article);
        $this->display();
    }
    public function publish(){
        if(IS_POST){
            $_POST['debug'] = 1;
            $id = intval(I("id"));
            $title = I("title");
            $content = I("article");
            $author_name = I("author_name");
            $author_url = I("author_url");
            $source = I("source");
            if($title){
                $data = compact("id","title","content","author_name","author_url","source");
                $WeeklyArticle = M("WeeklyArticle");
                $data['update_time'] = time();
                if($id){
                    $result = $WeeklyArticle->save($data);
                }else{
                    $data['create_time'] = time();
                    $result = $WeeklyArticle->add($data);
                }
            }
            if(!isset($result)){
                $this->setError("文章标题不能为空");
            }else if(!$result){
                $this->setError("发布失败");
            }
            $this->response("发布成功","JSON");
        }
        $id = intval(I("id"));
        $Weekly = M("Weekly");
        $article = $Weekly->find($id);
        $this->assign("article", $article);
        $this->display("edit");
    }
    public function subscribe(){
        $email = trim(I("email"));
        if($email && is_email($email)){
            $WeeklySubscribe = M("WeeklySubscribe");
            $conditions = array('email' => $email);
            $item = $WeeklySubscribe->where($conditions)->find();
            if($item && $item['status']){
                $this->response("已经成功订阅");
            }else if($item){
                $item['status'] = 1;
                $item['update_time'] = time();
                $result = $WeeklySubscribe->save($item);
            }else{
                $data = array(
                    'email' => $email,
                    'create_time' => time(),
                    'update_time' => time(),
                    'status' => 1,
                );
                $result = $WeeklySubscribe->add($data);
                //发送邮件通知订阅成功
                if($result){
                    $mailContent = file_get_contents("http://prediction.aiai.im/Home/Weekly/mail");
                    QueueTask::sendEmail($email,'',"欢迎您订阅爱爱周刊",$mailContent);
                }
            }
            if(!empty($result)){
                $this->response("订阅成功");
            }
        }else if($email){
            $this->setError("请输入正确的邮箱");
        }else{
            $this->setError("请输入邮箱");
        }
        $this->response("订阅失败");
    }
    public function unsubscribe(){

        $email = trim(I("email"));
        if($email){
            $WeeklySubscribe = M("WeeklySubscribe");

            $item = $WeeklySubscribe->where("email = '{$email}'")->find();
            if($item && $item['status']){
                $item['status'] = 0;
                $result = $WeeklySubscribe->save($item);
                if($result){
                    $this->response("已取消订阅");
                }
            }else {
                $this->response("已取消订阅");
            }
        }else{
            $this->setError("请输入邮箱");
        }
        $this->response("取消订阅失败");
    }
    public function test(){
        $email = "sun@guide.so";
        $mailContent = file_get_contents("http://prediction.aiai.im/Home/Weekly/mail");
        \Common\Task\QueueTask::sendEmail($email,'',"欢迎您订阅爱爱周刊",$mailContent);
//        $mailContent = $this->fetch("mail");
//        $r = think_send_mail("sun@guide.so",'','文件标题',$mailContent);
//        dump($r);
        exit("ddd");
    }
}
