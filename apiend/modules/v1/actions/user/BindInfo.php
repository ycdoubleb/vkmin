<?php

namespace apiend\modules\v1\actions\user;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\User;
use Yii;

/**
 * 绑定用户信息
 */
class BindInfo extends BaseAction
{

    public function run()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;

        $decryptedData = Yii::$app->wechat->miniProgram->encryptor->decryptData(
                $user->auths->credential, $this->getSecretParam('iv'), $this->getSecretParam('encryptedData'));

        $user->username = $decryptedData['openId'];
        $user->nickname = $decryptedData['nickName'];
        $user->avatar = $decryptedData['avatarUrl'];
        $user->sex = $decryptedData['gender'];

        $user->save();

        return new Response(Response::CODE_COMMON_OK, null, $decryptedData);
    }

}
