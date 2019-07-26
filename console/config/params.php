<?php
return [
    'adminEmail' => 'admin@example.com',
    // 微信小程序配置 具体可参考EasyWechat
    'wechatMiniProgramConfig' => [
        'app_id' => 'wx69f4a713347ecd2e',
        'secret' => '55ac60d686d8f67fae0149a2032e86db',
        // 下面为可选项
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        'response_type' => 'array',
        'log' => [
            'level' => 'debug',
            'file' => __DIR__ . '/wechat.log',
        ],
    ],
];
