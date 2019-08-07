<?php

namespace backend\modules\micro_lesson\controllers;

use yii\web\Controller;

/**
 * Default controller for the `micro_lesson` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
