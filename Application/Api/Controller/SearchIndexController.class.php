<?php
namespace Api\Controller;
use Common\Service\SearchIndexService;
use Think\Controller;

class SearchIndexController extends Controller {

    public function genArticleIndex(){
        $SearchIndexService = new SearchIndexService();
        $SearchIndexService->genArticleIndex();
    }

    public function genPositionIndex(){
        $SearchIndexService = new SearchIndexService();
        $SearchIndexService->genPositionIndex();
    }

    public function genQAIndex(){
        $SearchIndexService = new SearchIndexService();
        $SearchIndexService->genQAIndex();

    }
    public function genAllIndex(){
        $SearchIndexService = new SearchIndexService();
        $SearchIndexService->genArticleIndex();
        $SearchIndexService->genPositionIndex();
        $SearchIndexService->genQAIndex();
    }
    public function updateAllIndex(){
        $SearchIndexService = new SearchIndexService();
        $SearchIndexService = $SearchIndexService->setOptType("update");
        $SearchIndexService->genArticleIndex();
        $SearchIndexService->genPositionIndex();
        $SearchIndexService->genQAIndex();
    }
    public function test(){
        $SearchIndexService = new SearchIndexService();
        var_dump($SearchIndexService->genQAIndexByIds(29));
    }
}
