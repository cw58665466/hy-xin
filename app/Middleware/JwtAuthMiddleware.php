<?php
/**
 * Created by PhpStorm.
 * User: chenjunjie
 * Date: 2020/2/28
 * Time: 10:18 AM
 */

declare(strict_types=1);
namespace App\Middleware;

use App\Model\User;
use Phper666\JwtAuth\Jwt;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Phper666\JwtAuth\Exception\TokenValidException;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;

class JwtAuthMiddleware implements MiddlewareInterface
{
    /**
     * @var HttpResponse
     */

    protected $response;
    protected $prefix = 'Bearer';
    protected $jwt;

    public function __construct(HttpResponse $response, Jwt $jwt)
    {
        $this->response = $response;
        $this->jwt      = $jwt;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $isValidToken = false;
        $token = $request->getHeader('Authorization')[0] ?? '';
        if  (strlen($token) > 0)  {
            $token = ucfirst($token);
            $arr   = explode($this->prefix . ' ', $token);
            $token = $arr[1] ?? '';
            try {
                if (strlen($token) > 0 && $this->jwt->checkToken()) {
                    $isValidToken = true;
                }
            } catch(\Exception $e) {
                $data = [
                    'code' => 500,
                    'msg' => 'redis连接失败',
                    'data' => [],
                ];
                return $this->response->json($data);
            }
        }

        if  ($isValidToken)  {
            $jwtData = $this->jwt->getParserData();

            //更改上下文，写入用户信息
            //User模型自行创建
            $user = User::query()
                ->where('email', $jwtData['account'])
                ->first();
            $request = Context::get(ServerRequestInterface::class);
            $request = $request->withAttribute('user', $user);
            Context::set(ServerRequestInterface::class, $request);

            return $handler->handle($request);
        }
        $data = [
            'code' => 401,
            'msg' => '对不起，token验证没有通过',
            'data' => [],
        ];
        return $this->response->json($data);
    }
}