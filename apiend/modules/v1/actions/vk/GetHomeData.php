<?php

namespace apiend\modules\v1\actions\vk;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\system\Banner;
use common\models\vk\Course;
use common\models\vk\Topic;
use common\models\vk\TopicCourse;

/**
 * 获取首页数据
 */
class GetHomeData extends BaseAction
{

    public function run()
    {
        /**
         * 返回 Banner 、 推荐课程、专题 等数据
         */
        $data = [
            'banners' => $this->getBanners(),
            'recommend_courses' => $this->getRecommendCourses(),
            'topics' => $this->getTopics()
        ];

        return new Response(Response::CODE_COMMON_OK, null, $data);
    }

    /**
     * 获取所有Banner
     * @return array
     */
    private function getBanners()
    {
        $banners = Banner::find()->select(['path'])->where(['is_publish' => 1])
            ->orderBy(['sort_order' => SORT_ASC])
            ->asArray()->all();
        
        return $banners;
    }
    
    /**
     * 获取所有推荐课程
     * @return array
     */
    private function  getRecommendCourses()
    {
        $course = Course::find()->select([
                'id', 'cover_url AS thumb', 'name',
                'teacher_name','learning_count','url'
            ])->where([
                'is_recommend' => 1, 'is_publish' => 1
            ])->limit(4)->asArray()->all();
        
        return $course;
    }
    
    /**
     * 获取所有专题
     * @return array
     */
    private function getTopics()
    {
        $topics = TopicCourse::find()->select([
                'Topic.id', 'Topic.name', 'Topic.cover_url AS thumb', 'COUNT(course_id) AS couNum'
            ])->leftJoin(['Topic' => Topic::tableName()], 'Topic.id = topic_id')
            ->where(['is_del' => 0])
            ->groupBy(['topic_id'])->limit(8)
            ->asArray()->all();
        
        return $topics;
    }
}
