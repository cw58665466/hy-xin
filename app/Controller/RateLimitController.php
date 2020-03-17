<?php
/**
 * Created by PhpStorm.
 * User: chenjunjie
 * Date: 2020/2/28
 * Time: 3:23 PM
 */
namespace App\Controller;

use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\RateLimit\Annotation\RateLimit;
use Psr\Log\LoggerInterface;
use Hyperf\Logger\LoggerFactory;

/**
 * @Controller(prefix="rate-limit")
 */
class RateLimitController
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    public function __construct(LoggerFactory $loggerFactory)
    {
        $this->logger = $loggerFactory->get('sql');
    }
    /**
     * @RequestMapping(path="test")
     */
    public function test()
    {
        $this->logger->info("QPS 1, 峰值3");
        return ["QPS 1, 峰值3"];
    }

}