<?php

return [
    'adminEmail' => 'admin@example.com',
    /* api 验签秘钥 */
    'api_secret_key' => 'vk_min_e',
    // 微信小程序配置 具体可参考EasyWechat
    'wechatMiniProgramConfig' => [
        'app_id' => 'wx44aeef59ce52fe7d',
        'secret' => '725e8e95a0d01d77e39e6c2a7db872a3',
        // 下面为可选项
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        'response_type' => 'array',
        'log' => [
            'level' => 'debug',
            'file' => __DIR__ . '/wechat.log',
        ],
    ],
];
