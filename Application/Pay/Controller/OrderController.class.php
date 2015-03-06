<?php
namespace Pay\Controller;
use Composer\DependencyResolver\Transaction;
use Think\Controller;
use PredictionIO\PredictionIOClient;
class OrderController extends Controller {
    public function index(){
        $this->display("pinus");
    }

    public function create(){
        $channel = I('channel');
        $amount = I('amount');

        $input_data = json_decode(file_get_contents("php://input"), true);
        $channel = $input_data['channel'];
        $amount = $input_data['amount'];
        $subject = I("subject");
        $description = I("description");
        $_GET["debug"] = 1;
        $orderNo = substr(md5(time()), 0, 12);
        //$extra 在渠道为 upmp_wap 和 alipay_wap 时，需要填入相应的参数，具体见技术指南。其他渠道时可以传空值也可以不传。
        $extra = array();
        switch ($channel) {
            case 'alipay_wap':
                $extra = array(
                    'success_url' => 'http://pay.aiai.im/success',
                    'cancel_url' => 'http://pay.aiai.com/cancel'
                );
                break;
            case 'upmp_wap':
                $extra = array(
                    'result_url' => 'http://pay.aiai.im/result?code='
                );
                break;
        }

        \Pingpp::setApiKey("sk_test_OOyT0SaHeTOSyzjHC0O4SKKC");
        $ch = \Pingpp_Charge::create(
            array(
                "subject"   => $subject,
                "body"      => $description,
                "amount"    => $amount,
                "order_no"  => $orderNo,
                "currency"  => "cny",
                "extra"     => $extra,
                "channel"   => $channel,
                "client_ip" => $_SERVER["REMOTE_ADDR"],
                "app"       => array("id" => "app_vvbnvTXnfvL0DqDO")
            )
        );

        echo $ch;
    }
}
