<?php
namespace Common\Service;
use Common\Common\Description;
use Common\Manager\URLManager;
class SearchIndexService extends Service{
    public $SearchService = null;

    public function genArticleIndex(){
        $Articles = M("Articles");
        $offset = 0;
        $limit = 50;
        while($articles = $Articles->limit("$offset,$limit")->select()){

            $docs = array();
            foreach($articles as $article){
                $doc = array(
                    "id"        => md5(Description\CategoryDescription::CATEGORY_ARTICLE . $article['article_id']),
                    "type_id"   => 1,
                    "cat_id"    => Description\CategoryDescription::CATEGORY_ARTICLE,
                    "title"     => $article['title'],
                    "body"      => strip_tags($article['article_content']),
                    "url"       => URLManager::getURL(Description\CategoryDescription::CATEGORY_ARTICLE, $article['article_id']),
                    "author"    => "小爱",
                    "thumbnail" => "",
                    "source"    => "",
                    "create_timestamp" => time(),
                    "update_timestamp" => time(),
                    "hit_num"          => 0,
                    "focus_count"      => 0,
                    "grade"            => 0,
                    "comment_count"    => 0,
                    "tag"              => ""
                );
                $docs[] = $doc;
            }
            $resultJson = json_decode($this->_getSearchService()->addDocs($docs, "main"), true);
            if($resultJson['stauts'] = "OK"){
                echo "success\n";
            }
            $offset += $limit;
        }
    }
    public function genArticleIndexByIds($ids){
        $Articles = M("Articles");
        $offset = 0;
        $limit = 50;
        if(is_array($ids)){
            $ids = implode(",",$ids);
            $condition['id']  = array('in', $ids);
        }else{
            $condition['id']  = $ids;
        }
        $condition['article_id']  = array('in', $ids);
        while($articles = $Articles->where($condition)->limit("$offset,$limit")->select()){

            $docs = array();
            foreach($articles as $article){
                $doc = array(
                    "id"        => md5(Description\CategoryDescription::CATEGORY_ARTICLE . $article['article_id']),
                    "type_id"   => 1,
                    "cat_id"    => Description\CategoryDescription::CATEGORY_ARTICLE,
                    "title"     => $article['title'],
                    "body"      => strip_tags($article['article_content']),
                    "url"       => URLManager::getURL(Description\CategoryDescription::CATEGORY_ARTICLE, $article['article_id']),
                    "author"    => "小爱",
                    "thumbnail" => "",
                    "source"    => "",
                    "create_timestamp" => time(),
                    "update_timestamp" => time(),
                    "hit_num"          => 0,
                    "focus_count"      => 0,
                    "grade"            => 0,
                    "comment_count"    => 0,
                    "tag"              => ""
                );
                $docs[] = $doc;
            }
            $resultJson = json_decode($this->_getSearchService()->addDocs($docs, "main"), true);
            if($resultJson['stauts'] = "OK"){
                echo "success\n";
            }
            $offset += $limit;
        }
    }
    public function genPositionIndex(){
        $Position = M("Position");
        $offset = 0;
        $limit = 50;
        while($articles = $Position->limit("$offset,$limit")->select()){

            $docs = array();
            foreach($articles as $article){
                $doc = array(
                    "id"        => md5(Description\CategoryDescription::CATEGORY_POSITION . $article['id']),
                    "type_id"   => 1,
                    "cat_id"    => Description\CategoryDescription::CATEGORY_POSITION,
                    "title"     => $article['position_title'],
                    "body"      => strip_tags($article['article_content']),
                    "url"       => URLManager::getURL(Description\CategoryDescription::CATEGORY_POSITION, $article['id']),
                    "author"    => "小爱",
                    "thumbnail" => $article['position_image'],
                    "source"    => "",
                    "create_timestamp" => time(),
                    "update_timestamp" => time(),
                    "hit_num"          => 0,
                    "focus_count"      => 0,
                    "grade"            => 0,
                    "comment_count"    => 0,
                    "tag"              => ""
                );
                $docs[] = $doc;
            }
            $resultJson = json_decode($this->_getSearchService()->addDocs($docs, "main"), true);
            if($resultJson['stauts'] = "OK"){
                echo "success\n";
            }
            $offset += $limit;
        }
    }
    public function genPositionIndexByIds($ids){
        $Position = M("Position");
        $offset = 0;
        $limit = 50;
        if(is_array($ids)){
            $ids = implode(",",$ids);
            $condition['id']  = array('in', $ids);
        }else{
            $condition['id']  = $ids;
        }
        while($articles = $Position->where($condition)->limit("$offset,$limit")->select()){

            $docs = array();
            foreach($articles as $article){
                $doc = array(
                    "id"        => md5(Description\CategoryDescription::CATEGORY_POSITION . $article['id']),
                    "type_id"   => 1,
                    "cat_id"    => Description\CategoryDescription::CATEGORY_POSITION,
                    "title"     => $article['position_title'],
                    "body"      => strip_tags($article['article_content']),
                    "url"       => URLManager::getURL(Description\CategoryDescription::CATEGORY_POSITION, $article['id']),
                    "author"    => "小爱",
                    "thumbnail" => $article['position_image'],
                    "source"    => "",
                    "create_timestamp" => time(),
                    "update_timestamp" => time(),
                    "hit_num"          => 0,
                    "focus_count"      => 0,
                    "grade"            => 0,
                    "comment_count"    => 0,
                    "tag"              => ""
                );
                $docs[] = $doc;
            }
            $resultJson = json_decode($this->_getSearchService()->addDocs($docs, "main"), true);
            if($resultJson['stauts'] = "OK"){
                echo "success\n";
            }
            $offset += $limit;
        }
    }

    //生成QA Index
    public function genQAIndex(){
        $Qa = M("Qa");
        $QaContent = M("QaContent");
        $offset = 0;
        $limit = 50;
        while($articles = $Qa->limit("$offset,$limit")->select()){

            $docs = array();
            foreach($articles as $article){
                $item = $QaContent->find($article['content_id']);
                $doc = array(
                    "id"        => md5(Description\CategoryDescription::CATEGORY_QUESTION_ANSWER . $article['id']),
                    "type_id"   => $article['category'],
                    "cat_id"    => Description\CategoryDescription::CATEGORY_QUESTION_ANSWER,
                    "title"     => $article['keyword'],
                    "body"      => strip_tags($item['content']),
                    "url"       => "",
                    "author"    => "小爱机器人",
                    "thumbnail" => "",
                    "source"    => "",
                    "create_timestamp" => time(),
                    "update_timestamp" => time(),
                    "hit_num"          => 0,
                    "focus_count"      => 0,
                    "grade"            => 0,
                    "comment_count"    => 0,
                    "tag"              => ""
                );
                $docs[] = $doc;
                echo $article['keyword']."\n";
            }
            $resultJson = json_decode($this->_getSearchService()->addDocs($docs, "main"), true);
            if($resultJson['stauts'] = "OK"){
                echo "success\n";
            }
            $offset += $limit;
        }
    }

    public function genQAIndexByIds($ids){
        $Qa = M("Qa");
        $QaContent = M("QaContent");
        $offset = 0;
        $limit = 50;
        if(is_array($ids)){
            $ids = implode(",",$ids);
            $condition['id']  = array('in', $ids);
        }else{
            $condition['id']  = $ids;
        }

        while($articles = $Qa->where($condition)->limit("$offset,$limit")->select()){

            $docs = array();
            foreach($articles as $article){
                $item = $QaContent->find($article['content_id']);
                $doc = array(
                    "id"        => md5(Description\CategoryDescription::CATEGORY_QUESTION_ANSWER . $article['id']),
                    "type_id"   => $article['category'],
                    "cat_id"    => Description\CategoryDescription::CATEGORY_QUESTION_ANSWER,
                    "title"     => $article['keyword'],
                    "body"      => strip_tags($item['content']),
                    "url"       => "",
                    "author"    => "小爱机器人",
                    "thumbnail" => "",
                    "source"    => "",
                    "create_timestamp" => time(),
                    "update_timestamp" => time(),
                    "hit_num"          => 0,
                    "focus_count"      => 0,
                    "grade"            => 0,
                    "comment_count"    => 0,
                    "tag"              => ""
                );
                $docs[] = $doc;
            }
            $resultJson = json_decode($this->_getSearchService()->addDocs($docs, "main"), true);
            if($resultJson['stauts'] = "OK"){
                echo "success\n";
            }
            $offset += $limit;
        }
    }

    private function _getSearchService(){

        if($this->SearchService){
            return $this->SearchService;
        }else{
            return $this->SearchService = new SearchService();
        }

    }
}
