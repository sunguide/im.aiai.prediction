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
            $this->comparePreTradeAmount((array)$data);
        }
    }

    private function comparePreTradeAmount($data){
        $preStock = $this->getPreTradeStock($data['id']);
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
