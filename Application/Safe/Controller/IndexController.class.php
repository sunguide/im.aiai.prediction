<?php
namespace Safe\Controller;
use Composer\DependencyResolver\Transaction;
use Think\Controller;
use PredictionIO\PredictionIOClient;
class IndexController extends Controller {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>[ 您现在访问的是Home模块的Index控制器 ]</div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    public function test(){

        $client = $this->pClient();
        // generate 10 users, with user ids 1,2,....,10
        for ($i=1; $i<=10; $i++) {
            echo "Add user ". $i . "\n";
            $command = $client->getCommand('create_user', array('pio_uid' => $i,"name" => $i.rand(0,199)));
            $response = $client->execute($command);
            var_dump($response);
        }
        for ($i=1; $i<=10; $i++) {
            echo "Get user ". $i . "\n";
            $command = $client->getCommand('get_user', array('pio_uid' => $i));
            $response = $client->execute($command);
            var_dump($response);
        }

    }
    public function m(){
        $Articles = M("Articles");
        dump($Articles->find());
        $M = M("Img");
        dump($M->find());
    }
    public function brand(){
        $brandString = "d:杜蕾斯（Durex）,g:冈本（Okamoto）,b:倍力乐,d:第六感（SIXSEX）,j:久紧,k:康乐宝（fangxin konrapo）,j:杰士邦,y:毓婷,k:康乐宝,s:尚牌（ELASUN）,d:多乐士（Donless）,m:名流,d:巅峰一号,n:诺丝（NOX）,j:久奈,s:斯香妮,g:GQD OIL,d:杜伊特,b:不二,j:距点（glove）,l:零距离,s:塞呋盾（Safedom）,s:私柜,g:高邦（GOBON）,other:007,k:快乐伙伴,g:阁楼小趣,h:海氏海诺（HAINUO）,n:男子汉（STRONG MAN）,h:花花公子（PLAYBOY）,s:双蝶（Double Bulterfly）,f:泛亚（Can Asia）,l:蕾邦（LEEBON）,y:亚马逊,f:霏慕,t:挑战者,k:口秀,j:桔色,b:冰果（BinGoo）,s:石更,k:酷酷斯（KOKOS）,f:芳心康乐宝（FXKLB）,d:大卫,m:秘诱,a:爱尔,b:百乐（BAILE）,s:斯托伊（STOY）,d:第6感,t:TOP notch,d:Durex,s:SECWELL,q:青岛利时,j:JEX,q:俏女郎,other:1号巅峰,t:特瑞思,f:风色时尚,n:耐氏（NAISC）,other:7,a:爱轻松,j:Jo,m:曼妮伊斯（manniyisi）,s:森邦（scmba）,w:维娇（Weijiao）,j:久爱（9i）,other:2H&2D,b:宝马良驹,m:麻辣妹子,a:爱世界,x:XJAZZ,z:正丽,q:其他品牌,m:密室恋人（seroom lover）,y:伊敏秀,b:宝狮（polylion）,c:Come On Baby,c:春水堂,r:日科（Rike）,m:mizz zee,w:网友,h:嗨咻,o:欧邦（OBON）,d:多乐士,q:亲me,x:新天使,m:MYSEED,x:幸福1号,o:欧米茄（OMEGA）,x:夏奇,b:倍柔情,h:汇润,d:DOKIDOKI,a:爱超（aichao）,w:舞蹈家,y:优品道,k:酷人,w:舞夜,h:欢爱谷,q:七色草（7secao）,s:私享玩趣（OMYSKY）,y:伊美欣（EMASIN）,x:仙蒂,j:九色生活,s:Silk Touch,z:ZINI,j:劲秀,q:倾城,l:丽波,t:甜蜜源（sweetsource）,a:AK-HOT,k:快乐分享,y:勇士,e:EOL,b:邦爱,p:品信,p:品信情趣,w:威尔乐,j:极致地带（Ultrazone）,j:健马,a:爱到底,l:乐透（LECO）,l:乐图（lotoo）,m:迈伦（Myron）,j:佳乐（kara）,b:冰点幸福,j:计尔康,m:Meedoo,f:非非,b:邦德007,a:瑷朵（AIDUO）,m:玫瑰 约瑟芬（ROSE JSPHNE）,b:百科,n:NPG,l:蕾邦(LEEBON),x:性密码,m:mfones,s:双一,y:优丽达斯,l:拉士丁,w:玩爆潮品,a:盎来赛呵思（onlinesex）,a:阿芙拉（APHRODISIA）,g:古圣堂,s:四维空间,h:罕穆尔,l:乐曼特,n:耐能,y:意大利Lovemoment,d:杜仕邦,q:奇宝,y:雅润,a:爱丽丝（ALICE）,j:简爱,a:AVINS,a:APHRODISIA,z:智顶（Gdian）,a:A派（APER）,n:诺丝NOX,a:爱侣,s:史黛丝,j:玖玖加,g:格林宝贝,z:正义邦德（Zhengyibangde）,j:JE JOUE,x:相模（Sagami）,x:新侣（XINLV）,g:G感,r:Rodo,t:tenbody,g:冈奈,y:悦达士,o:OTG,v:VINA,w:我就喜欢,m:玛尼仕,q:其它";
        $brands = explode(",",$brandString);
        $CondomBrand =  M("CondomBrand");
        $CondomBrand->startTrans();
        foreach($brands as $brand){
            list($prefix,$name) = explode(":",$brand);
            $CondomBrand->prefix = $prefix;
            $CondomBrand->name = $name;
            $CondomBrand->status = 1;
            $CondomBrand->create_time = date("Y-m-d H:i:s");
            if(!$CondomBrand->add()){
                dump($CondomBrand->getLastsql());
                $CondomBrand->rollback();
            }
        }
        $CondomBrand->commit();
    }
    private function pClient(){
        return $client = PredictionIOClient::factory(array("appkey" => "n35ZzH2tocqW10rbJuq4lUTyATmJO6Upo7WY4xcJiCPYJGG0lXR6AWAjxaXhxHNq"));
    }
}
