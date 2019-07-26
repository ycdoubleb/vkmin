<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\oauth\Login;
use apiend\modules\v1\actions\oauth\SjLogin;

/**
 * 登录认证
 *
 * @author Administrator
 */
class OauthController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['optional'] = [
            'login',
            'sj-login',
        ];
        $behaviors['verbs']['actions'] = [
            'login' => ['post'],
            'sj-login' => ['post'],
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'login' => ['class' => Login::class],
            'sj-login' => ['class' => SjLogin::class],
        ];
    }

}
