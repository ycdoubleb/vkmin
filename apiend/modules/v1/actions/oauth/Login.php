<?php

namespace apiend\modules\v1\actions\oauth;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\User;
use common\models\UserAuths;
use common\models\UserProfile;
use Exception;
use Yii;

class Login extends BaseAction
{

    protected $requiredParams = ['code'];

    public function run()
    {
        //微信认证code2Session data=[openid,session_key];
        $code = $this->getSecretParam('code');
        $data = Yii::$app->wechat->miniProgram->auth->session($code);

        \Yii::info("code:$code openid:{$data['openid']} session_key:{$data['session_key']}", 'wskeee');

        //查询用户是否存在
        $userAuth = UserAuths::findOne(['identifier' => $data['openid']]);
        $tran = Yii::$app->db->beginTransaction();
        try {
            if ($userAuth) {
                //旧用户
                $user = User::findOne(['id' => $userAuth->user_id]);
                $user->generateAccessToken();
                $user->save();
                $userAuth->credential = $data['session_key'];
                $userAuth->save();
            } else {
                //新用户
                $user = new User(['username' => '', 'nickname' => '', 'password_hash' => '']);
                $user->setScenario(User::SCENARIO_OAUTH);
                $user->generateAccessToken();
                if ($user->save()) {
                    $userAuth = new UserAuths([
                        'user_id' => $user->id,
                        'identity_type' => UserAuths::TYPE_WEXIN,
                        'identifier' => $data['openid'],
                        'credential' => $data['session_key']
                    ]);
                    //关联成功
                    $userAuth->save();
                }
            }
            $tran->commit();
        } catch (Exception $ex) {
            $tran->rollBack();
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, '登录失败！', $ex);
        }
        return new Response(Response::CODE_COMMON_OK, null, [
            'openid' => $userAuth->identifier,
            'token' => $user->access_token,
            'token_expire_time' => $user->access_token_expire_time,
            'user' => $user
        ]);
    }

}
