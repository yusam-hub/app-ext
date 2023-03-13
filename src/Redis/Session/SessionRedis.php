<?php

namespace YusamHub\AppExt\Redis\Session;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use YusamHub\AppExt\SymfonyExt\CookieKernel;

class SessionRedis extends Session
{
    public function __construct(Request $request, CookieKernel $cookieKernel)
    {
        $attributeBag = new AttributeBagRedis();
        $sessionStorage = new SessionStorageRedis();
        $sessionStorage->registerBag($attributeBag);
        parent::__construct();
    }

}