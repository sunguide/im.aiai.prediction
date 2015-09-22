<?php
namespace Wolfunds\Controller;
use Api\Controller\BaseController;
use Wolfunds\Manager\StockAPI;
use Wolfunds\Manager\StockManager;

class StockController extends BaseController {

    public function best(){
        $stocks = M("StockBest")->where("status = 1")->select();
        $result = array();
        if(!empty($stocks)){
            foreach($stocks as $stock){
                $result[] = array(
                    "code" => $stock['stock_code'],
                    "name" => $stock['stock_name'],
                    "buy_price" => $stock['buy_price'],
                    "target_price" => $stock['target_price'],
                );
            }
        }
        $this->response($result);
    }

    public function bestCreate(){
        $stockCode = trim(I("code"));
        $stockName = trim(I("name"));
        $star = intval(I("star"));
        $stockName = $stockName?:StockManager::getStockName($stockCode);
        $buyPrice = I("buy_price");
        $buyDate = date("Y-m-d",strtotime(I("buy_date")));
        $targetPrice = I("target_price");
        if($stockCode && $buyPrice && $targetPrice){
            $data = array(
                "stock_code" => $stockCode,
                "stock_name" => $stockName,
                "buy_price" => $buyPrice,
                "target_price" => $targetPrice,
                "star" => $star,
                "buy_date" => $buyDate,
                "create_time" => NOW_TIME
            );
            $result = M("StockBest")->add($data);
            if(!$result){
                $this->setError("推荐失败");
            }
        }else{
            $this->setError("参数不对");
        }
        $this->response();

    }
    public function combination(){
        $stockCombinations = M("StockCombination")->select();
        $totalAssets = M("StockCombination")->sum('assets');
        $result = array();
        if(!empty($stockCombinations)){
            foreach($stockCombinations as $stock){
                $result[] = array(
                    "code" => $stock['stock_code'],
                    "name" => $stock['stock_name'],
                    "cost_price" => $stock['cost_price'],
                    "buy_time" => $stock['buy_time'],
                    "amount" => $stock['amount'],
                    "assets" => $stock['assets'],
                    "rate" => (($stock['assets'] / $totalAssets) * 100)
                );
            }
        }
        $this->response($result);
    }

    public function combinationCreate(){
        $stockCode = I("code");
        $stockName = I("name");
        $buyPrice = I("buy_price");
        $amount = I("amount");
        $buyTime = I("buy_time")?strtotime(I("buy_time")):NOW_TIME;
        if($stockCode && $buyPrice && $amount && $buyTime){
            $stockName = $stockName?:StockManager::getStockName($stockCode);
            $tradeAssets = floatval($buyPrice) * $amount;
            $currencyInfo = M("StockCombination")->where(array("stock_code" => '999999'))->find();

            if($currencyInfo['assets'] >= $tradeAssets){
                $stockInfo = M("StockCombination")->where(array("stock_code" => $stockCode))->find();
                if($stockInfo){
                    $stockInfo['assets'] = $stockInfo['assets'] + $tradeAssets;
                    $stockInfo['amount'] += $amount;
                    $stockInfo['cost_price'] = floatval($stockInfo['assets']) / $stockInfo['amount'];
                    $result = M("StockCombination")->save($stockInfo);
                }else{
                    $data = array(
                        "stock_code" => $stockCode,
                        "stock_name" => $stockName,
                        "cost_price" => $buyPrice,
                        "amount" => $amount,
                        "assets" => $tradeAssets,
                        "create_time" => NOW_TIME,
                        "buy_time" => $buyTime
                    );
                    $result = M("StockCombination")->add($data);
                }
                if(!$result){
                    $this->setError("调仓记录失败",700);
                }else{
                    $currencyInfo['amount'] -= floatval($tradeAssets);
                    $currencyInfo['assets'] -= floatval($tradeAssets);
                    M("StockCombination")->save($currencyInfo);
                }
            }else{
                $this->setError("余额不足，无法交易",800);
            }

        }else{
            $this->setError("参数传递不正确",900);
        }
        $this->response();

    }
    public function reference(){
        $reference =  M("StockReference")->order("id desc")->find();
        $this->response($reference);
    }

}
