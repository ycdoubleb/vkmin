<?php

namespace apiend\modules\v1\actions\vk;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\vk\Course;
use common\models\vk\Topic;
use common\models\vk\TopicCourse;

/**
 * 获取专题数据
 */
class GetTopicDetail extends BaseAction
{

    /**
     * 设置接口检验必须的参数
     * @var type 
     */
    protected $requiredParams = ['topic_id'];

    public function run()
    {
        // 专题id
        $topic_id = $this->getSecretParam('topic_id');
        // 当前页
        $page = $this->getSecretParam('page', 1);
        // 返回个数
        $limit = $this->getSecretParam('limit', 6);

        /**
         * 返回 专题数据，专题里面的课程数据
         */
        $data = [
            'topic' => $this->getTopicDetail($topic_id),
            'courses' => $this->getTopicCourses($topic_id, $page, $limit)
        ];

        return new Response(Response::CODE_COMMON_OK, null, $data);
    }

    /**
     * 获取专题详情
     * @param int $topic_id
     * @return array
     */
    private function getTopicDetail($topic_id)
    {
        $topic = Topic::find()->select(['cover_url'])->where(['id' => $topic_id])
            ->asArray()->one();
        
        return $topic;
    }
    
    /**
     * 获取所有专题课程
     * @param int $topic_id
     * @param int $page
     * @param int $limit
     * @return array
     */
    private function getTopicCourses($topic_id, $page, $limit)
    {
        $courses = TopicCourse::find()->select([
                'Course.id', 'Course.cover_url AS thumb', 'Course.name',
                'Course.teacher_name','Course.learning_count','Course.url'
            ])->leftJoin(['Course' => Course::tableName()], 'Course.id = course_id')
            ->where(['topic_id' => $topic_id,'is_del' => 0])
            ->offset(($page - 1) * $limit)->limit($limit)
            ->asArray()->all();

        return $courses;
    }
}
