<?php
namespace Weekly\Controller;
use Composer\DependencyResolver\Transaction;
use Think\Controller;
use PredictionIO\PredictionIOClient;
class IndexController extends Controller {
    public function index(){
        $url = "http://m.aiai.im/Image/lists";

        header("Location: {$url}");
    }

    public function m(){
        $Articles = M("Articles");
        dump($Articles->find());
        $M = M("Img");
        dump($M->find());
    }
}
