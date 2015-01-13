<?php
namespace Common\Service;
use Common\Library\Com\AliyunOpenSearch\CloudsearchIndex;
use Common\Library\Com\AliyunOpenSearch\CloudsearchSearch;
use Common\Library\Com\AliyunOpenSearch\CloudsearchClient;
use Common\Library\Com\AliyunOpenSearch\CloudsearchDoc;
class SearchService extends Service{
    private $ACCESSKEYID = "42BkfEowOc7QmLjg";
    private $SECRET      = "Nm4dGUA3U6vHh9hYQWhec77UrWcekY";
    private $KEY_TYPE    = "aliyun";
    private $APP_NAME    = "aiai_articles";
    private $host        = null;
    private $client      = null;
    // 生成索引
    public function __construct($accessKeyId = '', $secret = '', $host = "http://opensearch.aliyuncs.com", $keyType = "aliyun") {
        $accessKeyId ? $this->ACCESSKEYID  = $accessKeyId : "";
        $secret      ? $this->SECRET       = $secret      : "";
        $this->KEY_TYPE    = $keyType;
        $this->host        = $host;
        if($this->client === null){
            $this->client = $this->_getClient();
        }
    }
    private function _getClient(){
        return $this->client = new CloudsearchClient(
            $this->ACCESSKEYID,
            $this->SECRET,
            array('host' => $this->host),
            $this->KEY_TYPE
        );
    }
    // 搜索服务状态
    public function indexStatus($indexName = "articles"){

        $search = new CloudsearchIndex($indexName, $this->client);
        return $search->status();

    }
    public function addDocs($docs, $tableName){

        $cloudSearchDoc = new CloudsearchDoc($this->APP_NAME, $this->client);
        $docsData = array();
        foreach($docs as $doc){
            $docsData[] = array(
                "fields" => $doc,
                "cmd"    => "ADD"
            );
        }
        return $cloudSearchDoc->add(json_encode($docsData), $tableName);

    }

    public function updateDocs($docs, $tableName){

        $cloudSearchDoc = new CloudsearchDoc($this->APP_NAME, $this->client);
        $docsData = array();
        foreach($docs as $doc){
            $docsData[] = array(
                "fields" => $doc,
                "cmd"    => "UPDATE"
            );
        }
        return $cloudSearchDoc->update(json_encode($docsData), $tableName);

    }

    public function removeDocs($docs, $tableName){

        $cloudSearchDoc = new CloudsearchDoc($this->APP_NAME, $this->client);
        $docsData = array();
        foreach($docs as $doc){
            $docsData[] = array(
                "fields" => $doc,
                "cmd"    => "REMOVE"
            );
        }
        return $cloudSearchDoc->remove(json_encode($docsData), $tableName);

    }

    // 搜索服务
    public function search($keyword){
        $search = new CloudsearchSearch($this->client);
        // 添加指定搜索的应用：
        $search->addIndex($this->APP_NAME);
        // 指定搜索的关键词，
        $search->setQueryString("default:'{$keyword}'");
        // 指定搜索返回的格式。
        $search->setFormat('json');
        // 返回搜索结果。
        return $search->search();
    }
}
