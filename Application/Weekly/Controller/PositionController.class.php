<?php
namespace Weekly\Controller;
use Think\Controller;
class PositionController extends Controller {

    public function detail(){
        $id = intval(I("id"));
        $PositionModel = M("Position");
        $item = $PositionModel->find($id);
        $this->assign("item", $item);
        $this->display();
    }

}
