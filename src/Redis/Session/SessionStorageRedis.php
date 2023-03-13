<?php

namespace YusamHub\AppExt\Redis\Session;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use YusamHub\AppExt\Redis\RedisKernel;
use YusamHub\AppExt\SymfonyExt\CookieKernel;
use YusamHub\Debug\Debug;

class SessionStorageRedis implements SessionStorageInterface
{
    const REDIS_CONNECTION_NAME = 'session';

    protected Request $request;
    protected CookieKernel $cookieKernel;
    protected RedisKernel $redisKernel;
    protected AttributeBagRedis $attributeBag;
    public function __construct(Request $request, CookieKernel $cookieKernel, RedisKernel $redisKernel)
    {
        $this->request = $request;
        $this->cookieKernel = $cookieKernel;
        $this->redisKernel = $redisKernel;
        $this->attributeBag = new AttributeBagRedis($this->redisKernel->redisExt(self::REDIS_CONNECTION_NAME));
    }

    public function start()
    {
        Debug::instance()->logPrint('debug',__METHOD__);
    }

    public function isStarted()
    {
        Debug::instance()->logPrint('debug',__METHOD__);
        return true;
    }

    public function getId()
    {
        Debug::instance()->logPrint('debug',__METHOD__);
        return __METHOD__;
    }

    public function setId(string $id)
    {
        Debug::instance()->logPrint('debug',__METHOD__, $id);
    }

    public function getName()
    {
        Debug::instance()->logPrint('debug',__METHOD__);
        return __METHOD__;
    }

    public function setName(string $name)
    {
        Debug::instance()->logPrint('debug',__METHOD__, $name);
    }

    public function regenerate(bool $destroy = false, int $lifetime = null)
    {
        Debug::instance()->logPrint('debug',__METHOD__, $destroy, $lifetime);
    }

    public function save()
    {
        Debug::instance()->logPrint('debug',__METHOD__);
    }

    public function clear()
    {
        Debug::instance()->logPrint('debug',__METHOD__);
    }

    public function getBag(string $name)
    {
        return $this->attributeBag;
    }

    public function registerBag(SessionBagInterface $bag)
    {

    }

    public function getMetadataBag()
    {
        //Debug::instance()->logPrint('debug',__METHOD__);
    }

}