<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'lmgclj@qq.com',
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordAccessTokenExpire' => 3600 * 24 * 7,
    /**
     * krajee 组件设置
     */
    'bsVersion' => '3.x',           //使用Bootstrap 版本
    'bsDependencyEnabled' => false, // this will not load Bootstrap CSS and JS for all Krajee extensions 
    /* 
     * redis 配置
     */
    'redis' => [
        'prefix' => 'vk:',                  //区分其它应用
    ],
    /* 
     * ueditor 富文本配置
     * 该配置会覆盖 common\modules\ueditor\Config.php $config 
     */
    'ueditor' => [
        "useAliyun" => true,    //是否把文件保存到线上
        "imagePathFormat" => "vk/upload/ueditor/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "scrawlPathFormat" => "vk/upload/ueditor/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "snapscreenPathFormat" => "vk/upload/ueditor/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "catcherPathFormat" => "vk/upload/ueditor/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "videoPathFormat" => "vk/upload/ueditor/video/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "filePathFormat" => "vk/upload/ueditor/file/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "imageManagerListPath" => "vk/upload/ueditor/image/", /* 指定要列出图片的目录 */
        "fileManagerListPath" => "vk/upload/ueditor/file/", /* 指定要列出文件的目录 */
    ],
    /* Webuploader 配置 */
    'webuploader' => [
        'savePath' => 'vk/files/',
        'thumbPath' => 'vk/thumb/'
    ],
    
    /* 365在线预览配置 */
    'ow365' => [
        'url' => 'http://ow365.cn/',
        'i' => [
            'http://tt.mconline.gzedu.net' => '14578',      //指向在线课程制作平台，测试机
            'http://ccoa.gzedu.net' => '145??',             //指向课程建设平台
            'http://mconline.gzedu.net' => '14825',         //指向在线课程制作平台，生产机
        ]
    ],
    
    /* 发送验证码配置 */
    'sendYunSms' => [
        'SMS_APP_ID' => '49917c0a7f0000017de534cb37de5f37',         //应用ID
        'SMS_TEMPLATE_ID' => [
            'BINGDING_PHONE' => '59f8a2537f00000131eb494e9101a537',    //注册绑定手机号码/密码登录短信模板ID
            'RESET_PASSWORD' => '59f9202d7f0000017d6283032c3f6631',    //重置密码短信模板ID
        ]
    ],
    
];
