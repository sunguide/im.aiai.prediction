<?php
namespace Api\Controller;
use Common\Common\Func;
use Think\Controller;
class AvatarController extends BaseController {
    public $baseURL = "http://avatar.aiai.im/Upload/avatars/";
    public function _initialize(){
        $this->_check_auth = false;
    }
    public function index(){
        $AvatarModel = M("Avatar");
        $items = $AvatarModel->where("rand()")->limit(20)->select();
        $filter = array(
            "img_url" => "url"
        );
        foreach($items as $k => $item){
            $item['img_url'] =  $this->baseURL . $item['local_img_url'];
            $items[$k] = Func::array_filter_keys($item, $filter);
        }
        $this->response($items);
    }
    public function array_filter_keys($arr, $arrFilter){
        if(!is_array($arrFilter)){
            $arrFilter = array("$arrFilter" => "");
        }
        foreach($arr as $key => $item){
            if(!array_key_exists($key, $arrFilter)){
                unset($arr[$key]);
            }else if($arrFilter[$key]){
                $arr[$arrFilter[$key]] = $item;
                unset($arr[$key]);
            }
        }
    }
    public function randOne(){
        $email = I('email');
        echo Func::get_gravatar($email);
    }
    //采集百度的头像
    public function collect(){
        $keyword = "帅哥头像";
        $img_cat = 1;//美女头像2，帅哥头像1
        $pn = 0;//offset;
        $rn = 180;//limit
        $url = "http://image.baidu.com/i?tn=resultjsonavatarnew&ie=utf-8&word={$keyword}&cg=head&pn={$pn}&rn={$rn}&z=&fr=&width=200&height=200&lm=-1&ic=0&s=0";
        $result = json_decode(file_get_contents($url), true);
        while(1){
            if(count($result['imgs']) > 0){
                $AvatarModel = M("Avatar");
                foreach($result['imgs'] as $img){
                    $img['objURL'] = trim($img['objURL']);
                    $identical_token = md5($img['objURL'] );
                    $where = array(
                        "identical_token" => $identical_token
                    );
                    if(!$AvatarModel->where($where)->find()){
                        $extension = pathinfo($img['objURL'], PATHINFO_EXTENSION);
                        $fileName = \Common\Common\Func::uuid().".$extension";
                        $filePath =  "/home/wwwroot/prediction.aiai.im/Upload/avatars/".$fileName;
                        $this->cmdDowload($img['objURL'],$filePath);

                        $data = array(
                            "img_url"   => $img['objURL'],
                            "img_title" => $img['fromPageTitle'],
                            "img_cat"   => $img_cat,
                            "identical_token" => $identical_token
                        );
                        if(file_exists($filePath)){
                            $data['down'] = 1;
                            $data['local_img_url'] = $fileName;
                        }
                        $AvatarModel->add($data);
                    }
                }
            }else{
                exit("end");
            }
            $pn += $rn;
            $url = "http://image.baidu.com/i?tn=resultjsonavatarnew&ie=utf-8&word={$keyword}&cg=head&pn={$pn}&rn={$rn}&z=&fr=&width=200&height=200&lm=-1&ic=0&s=0";
            $result = json_decode(file_get_contents($url), true);
        }

        $this->response($result);
    }
    public function collectDownload(){
        $AvatarModel = M("Avatar");
        $offset = 0;
        $limit  = 50;
        $items = $AvatarModel->where("down = 0")->limit("$offset, $limit")->select();
        while($items){
            foreach($items as $img){
                $extension = pathinfo($img['img_url'], PATHINFO_EXTENSION);
                $fileName = \Common\Common\Func::uuid().".$extension";
                $filePath =  "/home/wwwroot/prediction.aiai.im/Upload/avatars/".$fileName;
//                \Org\Net\Http::fsockopenDownload($img['img_url'],$filePath);
                $this->cmdDowload($img['img_url'],$filePath);
                if(file_exists($filePath)){
                    $img['down'] = 1;
                    $img['local_img_url'] = $fileName;
                    $AvatarModel->save($img);
                }
            }
            $items = $AvatarModel->where("down = 0")->limit("$offset, $limit")->select();
        }
    }

    public function cmdDowload($url, $path){
        $command = " wget -c $url -O $path ";
        system($command);
    }
    public function fix(){
        $AvatarModel = M("Avatar");
        $offset = 0;
        $limit  = 100;
        $items = $AvatarModel->limit("$offset, $limit")->select();
        if($items){
            foreach($items as $img){
                $filePath =  "/home/wwwroot/prediction.aiai.im/Upload/avatars/".$img['local_img_url'];

                if(!file_exists($filePath)){
                    echo "$filePath";
                    $this->cmdDowload($img['img_url'],$filePath);
                }else{
                    echo $img['id']."\n";
                    echo $img['local_img_url'];
                }
            }
        }
    }
}
