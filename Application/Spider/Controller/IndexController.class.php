<?php
namespace Spider\Controller;
use Composer\DependencyResolver\Transaction;
use Think\Controller;
use PredictionIO\PredictionIOClient;
class IndexController extends Controller {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>[ 您现在访问的是Home模块的Index控制器 ]</div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    public function du(){
        for($i = 1; $i<100;$i++){

            $url = "http://m.socialab.com.cn/m/".$i;
            $html = file_get_contents($url);
            if(false === strpos("该文章不存在", $html)){
                echo $i."<br>";
            }
        }
    }
    public function import(){
        $PositionPending = M("PositionPending");
        $path = "/home/wwwroot/prediction.aiai.im/Data/durex.log";
        $data = json_decode(file_get_contents($path));
        foreach($data as $item){
            $PositionPending->position_title = $item->title;
            $PositionPending->article_content = $item->content;
            $PositionPending->position_image = $item->cover;
            if(!$PositionPending->add()){
                dump($PositionPending->getLastsql());
            }
        }
        echo "over";
    }

    public function filter(){
        $PositionPending = M("PositionPending");
        $data = $PositionPending->where("article_status = 1")->order("id ASC")->limit(30)->select();
        $this->assign("data",$data);
        $this->display();
    }
    public function markAjax(){
        $id = I("id");
        $status = I("status");
        $PositionPending = M("PositionPending");
        $Position = M("Position");
        $error = "";
        $result = true;
        if($status == 2 || $status == 3){
            $positionAR = $PositionPending->find($id);
            if($positionAR){
                $Position->position_title = $positionAR['position_title'];
                $Position->position_style = $status;
                $Position->article_content = $positionAR['article_content'];
                $Position->position_image = $positionAR['position_image'];
                if(!$Position->add()){
                    $error = $positionAR['id']."add fail".$Position->getLastSql();
                }else{
                    $positionAR->article_status = 0;
                    $result = $PositionPending->where("id= {$id}")->save(array("article_status"=>0));
                }
            }

        }else{
//            $result = $PositionPending->save(array(
//                "id" => $id,
//                "article_status" => $status
//            ));
        }

        $this->ajaxReturn(array(
            "result" => intval($result),
            "code"   => 0,
            "error"  => $error
        ));
    }
    public function autoFilter(){
        $PositionPending = M("PositionPending");
        $Position = M("Position");

        $data = $Position->where("1")->select();
        foreach($data as $item){
            $articleContent = str_replace("杜杜","小爱",$item['article_content']);
            if($articleContent != $item['article_content']){
                $Position->where("id = {$item['id']}")->save(array("article_content"=> $articleContent));
                echo "old:".$item['article_content'];
                echo "new:".$articleContent."<br>";
            }
        }
    }
}
