<?php

namespace YusamHub\AppExt\SymfonyExt\Session;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use YusamHub\AppExt\Redis\RedisKernel;
use YusamHub\AppExt\SymfonyExt\CookieKernel;

class SessionRedis extends Session
{
    public function __construct(Request $request, CookieKernel $cookieKernel, RedisKernel $redisKernel)
    {
        parent::__construct(new SessionStorageRedis($request, $cookieKernel, $redisKernel));
    }

}