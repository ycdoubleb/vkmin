<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\vk\GetCourseDetail;
use apiend\modules\v1\actions\vk\GetHomeData;
use apiend\modules\v1\actions\vk\GetMyCourse;
use apiend\modules\v1\actions\vk\GetSearchInfo;
use apiend\modules\v1\actions\vk\GetTopicDetail;

/**
 * 微课接口
 *
 * @author Administrator
 */
class VkController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['optional'] = [
            'get-home-data',
            'get-course-detail',
            'get-topic-detail',
            'get-my-course',
            'get-search-info'
        ];
        $behaviors['verbs']['actions'] = [
            'get-home-data' => ['get'],
            'get-course-detail' => ['get'],
            'get-topic-detail' => ['get'],
            'get-my-course' => ['get'],
            'get-search-info' => ['get'],
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'get-home-data' => ['class' => GetHomeData::class],
            'get-course-detail' => ['class' => GetCourseDetail::class],
            'get-topic-detail' => ['class' => GetTopicDetail::class],
            'get-my-course' => ['class' => GetMyCourse::class],
            'get-search-info' => ['class' => GetSearchInfo::class],
        ];
    }

}
