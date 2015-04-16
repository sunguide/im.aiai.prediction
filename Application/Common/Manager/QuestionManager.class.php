<?php
/**
 * User: sunguide
 * Date: 14/11/15
 * Time: 12:58
 */

namespace Common\Manager;
use Common\Description\UserBehaviorDescription;

class QuestionManager {

    public static function find($id){
        $Question = M("Question");
        return $Question->find($id);
    }
    public static function newest($offset = 0, $limit = 50){
        $orders = array("id"=>"DESC");
        $conditions = array("status" => 1);
        return self::search($conditions,$orders,$offset,$limit);
    }

    //浏览量比较大的是比较热门的问题
    public static function hottest($offset = 0, $limit = 50){
        $hottestQuestionStats = UserBehaviorManager::getBehaviorStats(UserBehaviorDescription::GROUP_QUESTION,UserBehaviorDescription::ACTION_VIEW);

        $results = array();
        if(!empty($hottestQuestionStats)){
            foreach($hottestQuestionStats as $hottestQuestionStat){
                $question = self::find($hottestQuestionStat['target_id']);
                if($question){
                    $question['view_amount'] = $hottestQuestionStat['amount'];
                    $results[] = $question;
                }
            }
        }
        return  $results;
    }

    public static function unanswered($offset = 0, $limit = 50){
        $conditions = array("status" => 1,"resolved" => 0);
        $orders = array("id"=>"DESC");
        return self::search($conditions,$orders,$offset,$limit);
    }

    private function search($conditions,$orders, $offset = 0, $limit = 50){
        $QuestionModel = M("Question");
        return $QuestionModel->where($conditions)->order($orders)->limit($limit,$offset)->select();
    }

}
