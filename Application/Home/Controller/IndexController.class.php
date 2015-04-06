<?php
namespace Home\Controller;
use Think\Controller;
use PredictionIO\PredictionIOClient;
class IndexController extends Controller {
    public function index(){
//        echo ($currentURL = ($_SERVER['HTTPS'] == "on" ? "https://" : "http://") . $_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI']);
        $this->display();
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
