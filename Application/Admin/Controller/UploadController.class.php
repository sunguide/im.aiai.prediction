<?php
/**
 * User: sunguide
 * Date: 15/1/13
 * Time: 00:50
 * Description:UploadController.php
 */
namespace Admin\Controller;
use Composer\DependencyResolver\Transaction;
use Think\Controller;
class UploadController extends Controller{

    public function image(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     '/home/wwwroot/prediction.aiai.im/Upload/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->uploadOne($_FILES['upload_file']);
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            $url = "http://file.aiai.im/Upload/".$info['savepath'].$info['savename'];
            $this->ajaxReturn(array("success"=>true,"msg" => "Upload Success","file_path"=>$url),"JSON");
        }
    }
}