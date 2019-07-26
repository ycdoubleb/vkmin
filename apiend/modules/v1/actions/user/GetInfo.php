<?php

namespace apiend\modules\v1\actions\user;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\User;
use Yii;

class GetInfo extends BaseAction
{

    public function run()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;

        return new Response(Response::CODE_COMMON_OK, null, $user->toArray([
                    'id','username', 'nickname', 'phone', 'sex', 'avatar'
        ]));
    }

}
