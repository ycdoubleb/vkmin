<?php

namespace apiend\modules\v1\actions\vk;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\vk\Course;

/**
 * 获取课程数据
 */
class GetCourseDetail extends BaseAction
{

    /**
     * 设置接口检验必须的参数
     * @var type 
     */
    protected $requiredParams = ['id'];

    public function run()
    {
        $course_id = $this->getSecretParam('id');

        /**
         * 返回 课程数据
         */
        $data = [
            'course' => $this->getCourseDetail($course_id),
        ];

        return new Response(Response::CODE_COMMON_OK, null, $data);
    }

    /**
     * 获取课程详情
     * @param int $course_id
     * @return array
     */
    private function getCourseDetail($course_id)
    {
        $detail = Course::find()->select([
            'name', 'cover_url', 'introduction', 'url'
        ])->where(['id' => $course_id, 'type' => 1])
        ->asArray()->one();
        
        return $detail;
    }
}
