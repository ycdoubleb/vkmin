<?php

namespace apiend\modules\v1\actions\vk;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\vk\Course;
use common\models\vk\CourseLearning;
use common\models\vk\TopicCourse;

/**
 * 获取专题数据
 */
class GetMyCourse extends BaseAction
{

    /**
     * 设置接口检验必须的参数
     * @var type 
     */
    protected $requiredParams = ['user_id'];

    public function run()
    {
        $user_id = $this->getSecretParam('user_id');

        /**
         * 返回 专题数据，专题里面的课程数据
         */
        $data = [
            'learnLog' => $this->getLearningLog($user_id),
            'courses' => $this->getMyCourses($user_id)
        ];

        return new Response(Response::CODE_COMMON_OK, null, $data);
    }

    /**
     * 获取学习记录
     * @param int $user_id
     * @return array
     */
    private function getLearningLog($user_id)
    {
        $learnLog = CourseLearning::find()->select([
            'COUNT(id) AS course_num', 'ROUND(SUM(learning_time / 60), 2) AS learning_time'
        ])->where(['user_id' => $user_id, 'is_del' => 0])->asArray()->one();
        
        return $learnLog;
    }
    
    /**
     * 获取所有我的课程
     * @param int $topic_id
     * @return array
     */
    private function getMyCourses($user_id)
    {
        $courses = CourseLearning::find()->select([
            'Course.id', 'Course.name', 'Course.url','Course.cover_url AS thumb',
            'ROUND(learning_time / 60, 2) AS learning_time',
            "FROM_UNIXTIME(start_time, '%Y-%m-%d %H:%i') AS start_time"
        ])->leftJoin(['Course' => Course::tableName()], 'Course.id = course_id')
        ->where(['user_id' => $user_id,'is_del' => 0])->asArray()->all();

        return $courses;
    }
}
