<?php
namespace Command\Controller;
use Common\Service\QueueService;
use Command\Working\AnalyseWorking;
use Think\Controller;
class StockController extends CrontabController {

    public function test(){
        $this->out("test".date("Y-m-d H:i:s"));
    }
    public function crawler(){
        $startTimeStrap = strtotime(date("Y-m-d"));
        $nowTimeStrap = time();
        $timeStrapStance = ($nowTimeStrap -$startTimeStrap);
        if($timeStrapStance >  (3600 * 15 + 600) || $timeStrapStance < (3600 * 9)){
            exit();
        }
        echo "crawler start \n";
        $StockCodeModel = M("StockCode");
        $stocks = $StockCodeModel->order("id ASC")->select();
        foreach($stocks as $stock){
            if($stock['id'] < 600000) {
                $this->getStock("sz".($stock['id']));
            }else {
                $this->getStock("sh".($stock['id']));
            }
        }
        echo "crawler end \n\n";
    }
    public function getStock($stockCode = "sh603998"){
        $url = "http://hq.sinajs.cn/list={$stockCode}";
        $stockInfo = mb_convert_encoding(file_get_contents($url),"UTF-8", "GB2312");//iconv("gb2312","utf8",file_get_contents($url));
        echo($stockInfo);
        if($stockInfo){
            $stockData = explode('="',$stockInfo);
            $stockData = $stockData[1];
            $stockData                = explode(",", $stockData);
            if(count($stockData) < 31){
                echo $stockCode."error";
            }else {
                $stockName                = $stockData[0];
                $stockTodayPrice          = $stockData[1];//今日开盘价格
                $stockYesterdayPrice      = $stockData[2];//昨日收盘价格
                $stockCurrentPrice        = $stockData[3];
                $stockTodayHighPrice      = $stockData[4];
                $stockTodayLowPrice       = $stockData[5];
                $stockTradeAmount         = $stockData[8]; //成交数量
                $stockTradeCurrencyAmount = $stockData[8];//交易额
                $stockTradeDate           = $stockData[30];
                $stockTradeTime           = $stockData[31];
                $id                 = date("Ymd",strtotime($stockTradeDate)) . trim($stockCode,"shsz");
                $data = array(
                    "id"              => $id,
                    "code"            => trim($stockCode,"shsz"),
                    "name"            => $stockName,
                    "start_price"     => $stockTodayPrice,
                    "end_price"       => $stockCurrentPrice,
                    "current_price"   => $stockCurrentPrice,
                    "yesterday_price" => $stockYesterdayPrice,
                    "high_price"      => $stockTodayHighPrice,
                    "low_price"       => $stockTodayLowPrice,
                    "trade_number"    => $stockTradeAmount,
                    "trade_amount"    => $stockTradeCurrencyAmount,
                    "trade_date"      => $stockTradeDate,
                    "trade_time"      => $stockTradeTime,
                );
                $identify = md5(json_encode($data));
                $cacheInfo = S($id);
                if(!$cacheInfo || $cacheInfo['identify'] != $identify){
                    $data['identify'] = $identify;
                    $StockModel = M("Stock");
                    if($StockModel->find($id)){
                        $StockModel->where("id={$id}")->save($data);
                    }else{
                        $StockModel->add($data);
                    }
                    $this->initCache();
                    S($id,$data);
                    QueueService::getInstance()->push(QUEUE_STOCK_ANALYSE,$data);
                }
            }
        }
    }
    public function valid(){
        $i = 4000;
        $stock = 600000;
        echo "start";
        while($i){
            $i--;
            $this->recordValidStock("sh".($stock++));
        }
        $i=2800;
        $stock = 0;
        while($i){
            $i--;
            $stock++;
            $this->recordValidStock("sz".(str_pad($stock,6,"0",STR_PAD_LEFT)));
        }
    }
    public function analyse(){
        AnalyseWorking::getInstance()->start();
    }

    public function reAnalyse(){
        $StockCodeModel = M("Stock");
        $stocks = $StockCodeModel->order("id ASC")->select();
        foreach($stocks as $stock){
            var_dump($stock);
            QueueService::getInstance()->push(QUEUE_STOCK_ANALYSE,$stock);
        }
    }

    private function recordValidStock($stockCode){
        $url = "http://hq.sinajs.cn/list={$stockCode}";
        $stockInfo = mb_convert_encoding(file_get_contents($url),"UTF-8", "GB2312");//iconv("gb2312","utf8",file_get_contents($url));
        echo $stockInfo;
        if($stockInfo){
            $stockData = explode('="',$stockInfo);
            $stockData = $stockData[1];
            $stockData                = explode(",", $stockData);
            if(count($stockData) > 30){
                $StockCodeModel = M("StockCode");
                $data = array(
                    "id" => trim($stockCode,"shsz"),
                    "name" => $stockData[0]
                );
                var_dump($data);
                $StockCodeModel->add($data);
            }
        }
    }

    private function comparePreTradeAmount($data){
        $preStock = $this->getPreTradeStock($data['id']);
        if($preStock){
            if($data['trade_number'] > ($preStock['trade_number'] * 1.5)){
                $emailNotice = array(
                    "to"        => "sunguide@qq.com",
                    "title"     => $data['name']."交易量大增50%以上:".$data['trade_number'],
                    "content"   => $data['name']."交易量大增50%以上--Pre:".$preStock['trade_number']
                );
                QueueService::getInstance()->push("email_queue",$emailNotice);
            }
        }
    }

    private function initCache(){
        S(array('type'=>'file','prefix'=>'stock','expire'=>36000));
    }

    private function getStockFromCache($id){
        $stock = S($id);
        if($stock){
            return $stock;
        }
        $StockModel = M("Stock");
        return $StockModel->find($id);
    }
    private function getPreTradeStock($id){
        $StockModel = M("Stock");
        return $StockModel->where("id < $id")->order("id DESC")->find();
    }

}
