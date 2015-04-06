<?php
namespace Home\Controller;
use Think\Controller;
class PositionController extends Controller {

    public function index(){
        $id = intval(I("id"));
        $PositionModel = M("Position");
        $items = $PositionModel->select();
        $this->assign("lists", $items);
        $this->display();
    }

    public function detail(){
        $id = intval(I("id"));
        $PositionModel = M("Position");
        $item = $PositionModel->find($id);
        $this->assign("item", $item);
        $this->display();
    }
}
