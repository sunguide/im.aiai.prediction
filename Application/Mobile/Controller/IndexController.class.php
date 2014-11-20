<?php
namespace Mobile\Controller;
use Composer\DependencyResolver\Transaction;
use Think\Controller;
use PredictionIO\PredictionIOClient;
class IndexController extends Controller {
    public function index(){

        $this->display();
    }

    public function m(){
        $Articles = M("Articles");
        dump($Articles->find());
        $M = M("Img");
        dump($M->find());
    }
}
