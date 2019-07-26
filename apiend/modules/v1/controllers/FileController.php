<?php

namespace apiend\modules\v1\controllers;

use apiend\controllers\ApiController;
use apiend\modules\v1\actions\file\Upload;

/**
 * 文件上传
 *
 * @author Administrator
 */
class FileController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['optional'] = [
            'upload'
        ];
        $behaviors['verbs']['actions'] = [
            'upload' => ['post'],
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'upload' => ['class' => Upload::class],
        ];
    }

}
