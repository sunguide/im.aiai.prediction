<?php
namespace Home\Controller;
use Think\Controller;
use PredictionIO\PredictionIOClient;
class IndexController extends Controller {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>[ 您现在访问的是Home模块的Index控制器 ]</div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    public function test(){

        $client = $this->pClient();
        // generate 10 users, with user ids 1,2,....,10
        for ($i=1; $i<=10; $i++) {
            echo "Add user ". $i . "\n";
            $command = $client->getCommand('create_user', array('pio_uid' => $i,"name" => $i.rand(0,199)));
            $response = $client->execute($command);
            var_dump($response);
        }
        for ($i=1; $i<=10; $i++) {
            echo "Get user ". $i . "\n";
            $command = $client->getCommand('get_user', array('pio_uid' => $i));
            $response = $client->execute($command);
            var_dump($response);
        }

    }
    public function m(){
        $s = utime();
        $Articles = M("Articles");
        dump($Articles->find());
        $M = M("Img");
        dump($M->find());
        echo utime() - $s;
    }
    private function pClient(){
        return $client = PredictionIOClient::factory(array("appkey" => "n35ZzH2tocqW10rbJuq4lUTyATmJO6Upo7WY4xcJiCPYJGG0lXR6AWAjxaXhxHNq"));
    }
}
