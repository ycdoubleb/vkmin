<?php

namespace apiend\modules\v1\actions\user;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\User;
use Yii;

/**
 * 解密
 */
class DecryptedData extends BaseAction
{

    public function run()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;
        
        $decryptedData = Yii::$app->wechat->miniProgram->encryptor->decryptData(
                $user->auths->credential, 
                $this->getSecretParam('iv'), 
                $this->getSecretParam('encryptedData'));
        
        return new Response(Response::CODE_COMMON_OK, null, $decryptedData);
    }

}
