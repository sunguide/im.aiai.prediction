<?php
namespace Mobile\Controller;
use Composer\DependencyResolver\Transaction;
use Think\Controller;
use PredictionIO\PredictionIOClient;
class PublicController extends Controller {
    public function index(){

    }

    public function detail(){
        $id = intval(I("id"));
        $PositionModel = M("Position");
        $item = $PositionModel->find($id);
        $this->assign("item", $item);
        $this->display();
    }

}
