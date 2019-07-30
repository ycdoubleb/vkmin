<?php

namespace apiend\modules\v1\actions\vk;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;

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
        $topic_id = $this->getSecretParam('topic_id');

        /**
         * 返回 专题数据，专题里面的课程数据
         */
        $data = [
            'topic' => [],
            'courses' => []
        ];

        return new Response(Response::CODE_COMMON_OK, null, $data);
    }

}
