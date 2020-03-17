<?php
/**
 * Created by PhpStorm.
 * User: chenjunjie
 * Date: 2020/2/28
 * Time: 10:21 AM
 */
declare(strict_types = 1);

namespace App\Controller\Auth;

use App\Model\User;
use Phper666\JwtAuth\Jwt;
use App\Controller\Controller;
use Hyperf\Di\Annotation\Inject;

class LoginController extends Controller
{
    /**
     * @Inject
     *
     * @var Jwt
     */
    protected $jwt;

    /**
     * 用户登录.
     *
     * @return array
     */
    public function login()
    {
        // $hash = password_hash($this->request->input('password'), PASSWORD_DEFAULT);
        // return $this->failed($hash);
        $user = User::query()->where('email', $this->request->input('account'))->first();
        //验证用户账户密码
        if (!empty($user->password) && password_verify($this->request->input('password'), $user->password)) {
            $userData = [
                'uid' => $user->id,
                'account' => $user->email,
            ];
            $token = $this->jwt->getToken($userData);
            $data = [
                'token' => (string)$token,
                'exp' => $this->jwt->getTTL(),
            ];
            return $this->success($data);
        }
        return $this->failed('登录失败');
    }

    public function verification(){
        $data['status'] = true;
        $data['data']['is_authorization'] = true;
        $data['data']['changeLog'] = 'v2.2.0';
        return $data;
    }
}