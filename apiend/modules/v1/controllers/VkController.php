<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\vk\GetHomeData;

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
            'get-home-data'
        ];
        $behaviors['verbs']['actions'] = [
            'get-home-data' => ['get'],
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
        ];
    }

}