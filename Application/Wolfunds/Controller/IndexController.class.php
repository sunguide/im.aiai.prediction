<?php
namespace Wolfunds\Controller;
use Api\Controller\BaseController;

class IndexController extends BaseController {

    public function index(){
        $this->response("菇凉，你来了不该来的地方",'json',200);
    }
}
