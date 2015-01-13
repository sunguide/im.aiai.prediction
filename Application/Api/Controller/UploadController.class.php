<?php
namespace Api\Controller;
use Common\Service\SearchIndexService;
use Think\Controller;
use Think\Log;

class UploadController extends Controller {

    public function image(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            $this->success('上传成功！');
        }
    }
    public function qiniu(){
        $client = \Qiniu\Qiniu::create(array(
            'access_key' => 'uA6iVPDbShXZNNXPLe9pWtfzGjQ9wcPJoPrTW43I',
            'secret_key' => 'A6LZhZe-L0RZHoN38ozsXsBFiOA-KCgKxglqeU08',
            'bucket'     => 'aiaifiles'
        ));

//      查看文件状态
//        $res = $client->stat('jobs.jpg');
        // 测试用的文件名 key
        $key = uuid();
        $res = $client->uploadFile('', $key .'.jpg');
        var_dump($res);
        exit;
    }
    public function migrateImg(){
        $client = \Qiniu\Qiniu::create(array(
            'access_key' => 'uA6iVPDbShXZNNXPLe9pWtfzGjQ9wcPJoPrTW43I',
            'secret_key' => 'A6LZhZe-L0RZHoN38ozsXsBFiOA-KCgKxglqeU08',
            'bucket'     => 'aiaifiles'
        ));
        $Img = M("ImgGroup");

        while($images = $Img->where("migrate_status = 0")->limit(50)->select()){
            foreach($images as $image){
                if($image['img_url']){
//                    $fileName = explode("/",$image['img_url']);
                    $extension = pathinfo($image['img_url'], PATHINFO_EXTENSION);
                    $fileName = \Common\Common\Func::uuid().".$extension";

                    if(!$image['img_local_url']){
                        $filePath =  "/home/wwwroot/prediction.aiai.im/Upload/files/".$fileName;
                        if(!file_exists($filePath)){
                            \Org\Net\Http::curlDownload($image['img_url'],$filePath);
                        }
                        if(!$Img->where("id = {$image['id']}")->save(array("img_local_url" => $fileName))){
                            continue;
                        }else{
                            Log::record("Error SQL:".$Img->getLastSql(), Log::DEBUG, true);
                            Log::record("Error:".json_encode($Img->getDbError()), Log::DEBUG, true);
                        }
                    }else{
                        $fileName = $image['img_local_url'];
                        $filePath =  "/home/wwwroot/prediction.aiai.im/Upload/files/".$fileName;

                    }
                    Log::record($filePath, Log::DEBUG, true);

                    $res = $client->uploadFile($filePath, $fileName);
                    if(!$res->error){
                        $data = array(
                            "migrate_status" => 1,
                            "qiniu_img_url"  => $res->data['url']
                        );
                        $result = $Img->where("id = {$image['id']}")->save($data);
                        if(!$result){
                            Log::record("fail id:{$image['id']} local:".$filePath." qiniu_url:{$res->data['url']}",Log::DEBUG);
                        }else{
                            Log::record("success id:{$image['id']} local:".$filePath." qiniu_url:{$res->data['url']}",Log::DEBUG);
                        }
                    }else{
                        Log::record("upload fail id:{$image['id']} error:".json_encode($res->error),Log::DEBUG);
                    }
                }
            }
            Log::save();
            sleep(1);
        }
    }

    public function migrateImgGroup(){
        $ImgGroup = M("ImgGroup");
        $Img      = M("Img");
        $offset = 0;
        $limit = 50;
        while(true){
            echo "start";
            $imgGroups = $ImgGroup->limit($offset.",".$limit)->select();
            if(empty($imgGroups)){
                echo "empty";
                break;
            }
            foreach($imgGroups as $imgGroup){
                if($imgGroup['migrate_status'] == 0){
                    $img = $Img->where("img_group_id = {$imgGroup['group_id']}")->find();
                    if($img){
                        echo $img['qiniu_img_url'];
                    }else{
                        echo "img empty\n";
                        continue;
                    }
                    $data = array(
                        "migrate_status" => 1,
                        "qiniu_img_url"  => $img['qiniu_img_url'],
                        "img_local_url"  => $img['img_local_url']
                    );
                    if(!$ImgGroup->where("group_id = {$imgGroup['group_id']}")->save($data)){
                        echo $ImgGroup->getLastSql()."\n";exit;
                    }
                }
            }
            $offset += $limit;
            $imgGroups = $ImgGroup->limit($offset.",".$limit)->select();
        }
    }
    public function test(){
        $SearchIndexService = new SearchIndexService();
        var_dump($SearchIndexService->genQAIndexByIds(29));
    }
}
