<?php
namespace Common\Service;
use Think\Exception;
use Composer\DependencyResolver\Transaction;

class QuestionAnswerService extends Service {

    public function autoIndex($keyword, $content, $uid = 0){
        $params['keyword'] = $keyword;
        $params['content'] = $content;
        $info = "操作成功";
        $error = 0;
        if($params['keyword'] && $params['content']){
            $keywords = explode(",", $params['keyword']);
            $QAModel = M("Qa");
            $QAModel->startTrans();
            try{
                $QaContentModel = M("QaContent");

                $qaContent = $QaContentModel->where("content = '{$params['content']}'")->find();
                if($qaContent){
                    $contentId = $qaContent['id'];
                }else{
                    $QaContentModel->content = trim(urldecode($params['content']));
                    $contentId = $QaContentModel->add();
                    if(!$contentId){
                        throw_exception($QaContentModel->getError());
                    }
                }
                foreach($keywords as $keyword){
                    $keyword = urldecode($keyword);
                    $qa = $QAModel->where("keyword = '{$keyword}'")->find();
                    if($qa && $qa['status'] != 1){
                        $QAModel->id = $qa['id'];
                        $QAModel->status = 1;
                        if(!$QAModel->save()){
                            throw_exception($QAModel->getError());
                        }
                    }else if(!$qa){
                        $QAModel->category = 0;
                        $QAModel->content_id = $contentId;
                        $QAModel->status = 1;
                        $QAModel->keyword = $keyword;
                        $QAModel->create_time = time();
                        if(!$QAModel->add()){
                            throw_exception($QAModel->getError());
                        }
                    }

                }
                $QAModel->commit();
            }catch (\Think\Exception $e){
                $QAModel->rollback();
                $info = $e->getMessage();
                $error = 2;
            }
            $data = array("error" => $error,"info" => $info);

        }else{
            $data = array("error" => "1","info" => "参数不全，keyword & content");
        }
        return $data;
    }

}
