<?php
namespace Weekly\Controller;
use Think\Controller;
class StockController extends Controller {

    public function detail(){
        $id = intval(I("id"));
        $PositionModel = M("Position");
        $item = $PositionModel->find($id);
        $this->assign("item", $item);
        $this->display();
    }
    public function index(){
        $StockModel = M("Stock");

    }

}
