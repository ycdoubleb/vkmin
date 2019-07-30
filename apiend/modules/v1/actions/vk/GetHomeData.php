<?php

namespace apiend\modules\v1\actions\vk;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;

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
            'banners' => [],
            'recommend_courses' => [],
            'topics' => []
        ];

        return new Response(Response::CODE_COMMON_OK, null, $data);
    }

}
