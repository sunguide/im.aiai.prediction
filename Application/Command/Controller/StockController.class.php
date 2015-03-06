<?php
namespace Command\Controller;
use Think\Controller;
class StockController extends CrontabController {

    public function test(){
        $this->out("test".date("Y-m-d H:i:s"));
    }
    public function crawler(){
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
                $identify                 = date("Ymd",strtotime($stockTradeDate)) . trim($stockCode,"shsz");
                $data = array(
                    "id"              => $identify,
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
                    "trade_time"      => $stockTradeTime
                );
                $StockModel = M("Stock");
                if($StockModel->find($identify)){
                    $StockModel->where("id={$identify}")->save($data);
                }else{
                    $StockModel->add($data);
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

}
