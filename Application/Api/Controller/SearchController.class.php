<?php
namespace Api\Controller;
use Common\Service\SearchService;
use Think\Controller;
use Common\Common\Description;
Use Think\Log;
use Common\Service\SearchIndexService;

class SearchController extends Controller {

    public $SearchService = null;

    public function weixin(){
        $keyword = trim(I("keyword"));
        $PositionModel = M("Articles");
        $item = $PositionModel->where("title like '%{$keyword}%'")->find();
        $this->ajaxReturn($item);
    }

    public function search(){

        $keyword = I("keyword");
        $searchService = new SearchService();
        echo $searchService->search($keyword);

    }
    public function test(){
//        $service = new SearchIndexService();
//        $service->genQAIndexByIds(20);
       var_dump(Description\ResponseDescription::mean(Description\ResponseDescription::AUTO_LEARNING_SUCCESS)) ;
    }
}
