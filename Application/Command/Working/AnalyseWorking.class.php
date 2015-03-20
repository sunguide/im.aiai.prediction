<?php
/**
 * Created by PhpStorm.
 * User: sunguide
 * Date: 15/3/9
 * Time: 上午1:09
 */
namespace Command\Working;
use  Common\Service\QueueService;
class AnalyseWorking extends QueueWorking {

    public function getQueueName(){
        return QUEUE_STOCK_ANALYSE;
    }

    protected function working($data = array()){
        if($data){
//            $this->comparePreTradeAmount((array)$data);
            $this->comparePreFiveDayAverageTradeAmount((array)$data);
        }
    }

    private function comparePreTradeAmount($data){
        $preStock = $this->getPreTradeStock($data['id'],$data['code']);
        if($preStock){
            if($data['trade_number'] > ($preStock['trade_number'] * 1.5)){
                $title = $data['name']."交易量大增50%以上:".$data['trade_number'];
                $emailNotice = array(
                    "to"        => "sunguide@qq.com",
                    "title"     => $title,
                    "content"   => $data['name']."交易量大增50%以上--Pre:".$preStock['trade_number']
                );
                $this->out($title);
                QueueService::getInstance()->push(QUEUE_EMAIL,$emailNotice);
            }
        }
    }
    private function comparePreFiveDayAverageTradeAmount($data){
        $fiveDayAverageTradeAmount = $this->getFiveDayAverageTradeAmount($data['id'],$data['code']);
        if($data['trade_number'] > ($fiveDayAverageTradeAmount * 1.5)){
            $rate = (floatval($data['trade_number']) / $fiveDayAverageTradeAmount);
            $title = $data['name']."交易量超过五日均量50%以上";
            $emailNotice = array(
                "to"        => "sunguide@qq.com",
                "title"     => $title,
                "content"   => $data['name']."交易量较五日均量大增50%以上",
                "from_name"      => "股票分析",
            );
            $this->out($title);
            QueueService::getInstance()->push(QUEUE_EMAIL,$emailNotice);
        }
    }
    private function getFiveDayAverageTradeAmount($id,$code){
        $StockModel = M("Stock");
        $stocks = $StockModel->where("id < $id AND code = $code")->order("id DESC")->limit(5)->select();
        $totalAmount = 0;
        if($stocks){
            foreach($stocks as $stock){
                $totalAmount += $stock['trade_number'];
            }
        }
        return $totalAmount ? $totalAmount/count($stocks) : $totalAmount;
    }
    private function getStockFromCache($id){
        $stock = S($id);
        if($stock){
            return $stock;
        }
        $StockModel = M("Stock");
        return $StockModel->find($id);
    }

    private function getPreTradeStock($id,$code){
        $StockModel = M("Stock");
        return $StockModel->where("id < $id AND code = $code")->order("id DESC")->find();
    }
}
