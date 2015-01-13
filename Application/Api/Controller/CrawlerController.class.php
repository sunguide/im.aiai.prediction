<?php
/**
 * User: sunguide
 * Date: 14/12/4
 * Time: 00:43
 * Description:CrawlerController.php
 */

namespace Api\Controller;
use Think\Log;
use Think\Controller;

class CrawlerController extends Controller {
    public $html = null;
    public function index(){

    }
    /*	$r = file_get_contents($url); //用file_get_contents将网址打开并读取所打开的页面的内容

    preg_match("/<meta name=\"description\" content=\"(.*?)\">/is",$r,$booktitle);//匹配此页面的标题
    print_r($booktitle);
    $bookname = $booktitle[1];//取第二层数组
    $preg = '/<li><a href=(.*).shtml target=_blank class=a03>/isU';
    preg_match_all($preg, $r, $zj); //将此页面的章节连接匹配出来
    $bookzj = count($zj[1]);// 计算章节标题数量
    if ($ver=="new"){
    $content_start = "<!--正文内容开始-->";
    $content_end = "<!--正文内容结束-->";
    }
    if ($ver=="old"){
    $content_start = "<\/table><!--NEWSZW_HZH_END-->";
    $content_end = "<br>";
    }
    //特殊字符由于在正则表达式中“ \ ”、“ ? ”、“ * ”、“ ^ ”、“ $ ”、“ + ”、“（”、“）”、“ | ”、“ { ”、“ [ ”等字符已经具有一定特殊意义，如果需要用它们的原始意义，则应该对它进行转义，例如希望在字符串中至少有一个“ \ ”，那么正则表达式应该这么写： \\+ 。
    //header("Content-Type:text/html;charset=gb2312");
    */

//用file_get_contents将章节连接打开并读取所打开的页面的内容

    public function htmlDomParse($url,$element){
        // 新建一个Dom实例
        $this->html = new \simple_html_dom();
        // 从url中加载
        $this->html->load_file($url);
        return $this->html->find($element);
    }
    public function run(){
        $domain = "http://www.98mei.com";
        $lists = array(
//            array("url" => "/xinggan/list_1_{i}.html","cate_id" => 3, "cate_name" => "性感美女"),
            array("url" => "/riben/list_2_{i}.html","cate_id" => 7, "cate_name" => "日韩美女"),
            array("url" => "/xiezhen/list_5_{i}.html","cate_id" => 5, "cate_name" => "高清美女"),
            array("url" => "/chemo/list_3_{i}.html","cate_id" => 4, "cate_name" => "性感车模"),
            array("url" => "/siwa/list_4_{i}.html","cate_id" => 8, "cate_name" => "美女自拍"),
            array("url" => "/qingchun/list_6_{i}.html","cate_id" => 9, "cate_name" => "清纯美女"),
            array("url" => "/mingxing/list_7_{i}.html","cate_id" => 6, "cate_name" => "明星美女"),
        );
        foreach($lists as $list){
            if(strpos($list['url'],"{i}") !== false){
                $i = 1;
                while($i){
                    $fetch = $list;
                    $fetch['url'] = str_replace("{i}",$i, $fetch['url']);
                    if(!$this->fetchList($domain, $fetch)){
                        break;
                    }
                    $i++;
                }
            }else{
                $this->fetchList($domain, $list);
            }
        }
    }
    public function fetchList($domain, $list){
        $fetchURL = $domain.$list['url'];
        $items = $this->htmlDomParse($fetchURL,".picbox");
        if(!empty($items)){
            $results = array();
            foreach($items as $item){
                $data = array();
                $data['url'] = $item->children(1)->children(0)->children(0)->href;
                $data['img_title'] = $item->children(0)->alt;
                $data['img_url'] = $item->children(0)->attr["data-original"];
                if($data['img_url'] && $data['url']){
                    $results[] = $data;
                }
            }
//            var_dump($results);
            if(!empty($results)){
                $ImgGroup = M("ImgGroup");
                foreach($results as $data){
                    $imgPath = pathinfo($data['img_url']);
                    var_dump($imgPath);
                    if($imgPath && isset($imgPath['basename'])){
                        $imgGroup = $ImgGroup->where("img_url like '%{$imgPath['basename']}%'")->find();
                        if($imgGroup){
                            Log::record("exits",Log::INFO);
                            Log::record(json_encode($imgGroup),Log::INFO, true);
                        }else{
                            $data = array(
                                "cate_id"       => 1,
                                "cate_name"     => "性感美女",
                                "title"         => $data['img_title'],
                                "img_url"       => $data['img_url'],
                                "create_time"   => date("Y-m-d H:i:s"),
                                "source"        => $domain,
                                "original_url"  => $data['url']
                            );
                            $ImgGroup->add($data);
                        }
                    }
                }
            }
            Log::save("File", date("Y-m-d")."_crawler.log");
            $this->html->clear();
            return true;
        }else{
            $this->html->clear();
            return false;
        }

    }
    public function fetchContent(){
        $domain = "http://www.98mei.com";
        $ImgGroup = M("ImgGroup");
        $Img = M("Img");
        $offset = 0;
        $limit = 50;
        $where = "migrate_status = 0 and source = '$domain'";
        $lists = $ImgGroup->where($where)->limit("$offset, $limit")->select();
        while($lists){
            foreach($lists as $list){
                $url = $domain.$list['original_url'];
                $items = $this->htmlDomParse($url,"#pictureurls img");
                if(!empty($items)){
                    foreach($items as $item){
                        $data = array();
                        $data['img_title'] = $list['title'];
                        $data['img_url'] = $item->src;
                        $imgItem = $Img->where("img_url ='{$data['img_url']}'")->find();
                        if(!$imgItem){
                            $data['img_group_id'] = $list['group_id'];
                            $data['create_time'] = date("Y-m-d H:i:s");
                            $Img->add($data);
                        }
                    }
                    $list['migrate_status'] = -1;
                    $ImgGroup->save($list);
                }else{
                    $list['migrate_status'] = -2;
                    $ImgGroup->save($list);
                }
                $this->html->clear();
            }
            $lists = $ImgGroup->where($where)->limit("$offset, $limit")->select();
        }
        //end
    }
}
