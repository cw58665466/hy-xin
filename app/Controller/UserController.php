<?php
/**
 * Created by PhpStorm.
 * User: chenjunjie
 * Date: 2020/2/28
 * Time: 10:22 AM
 */

declare(strict_types=1);

namespace App\Controller;

use Phper666\JwtAuth\Jwt;
use Hyperf\Di\Annotation\Inject;

class UserController extends Controller
{

    /**
     * @Inject()
     * @var Jwt
     */
    protected $jwt;

    /**
     * 获取用户信息
     * @return [type] [description]
     */
    public function info()
    {
        //获取用户数据
        $user = $this->request->getAttribute('user');
        return $this->success($user);

    }

    /**
     * 用户退出
     * @return [type] [description]
     */
    public function logout()
    {
        if  ($this->jwt->logout())  {
            return $this->success('','退出登录成功');
        };
        return $this->failed('退出登录失败');
    }

}