<?php

namespace YusamHub\AppExt\SymfonyExt\Session;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use YusamHub\AppExt\Redis\RedisKernel;
use YusamHub\AppExt\SymfonyExt\CookieKernel;

class SessionStorageRedis implements SessionStorageInterface
{
    const REDIS_CONNECTION_NAME = 'session';
    const COOKIE_SESSION_NAME = 'session';

    const COOKIE_SESSION_TTL = 3600 * 24 * 60; // 60 дней

    protected Request $request;
    protected CookieKernel $cookieKernel;
    protected RedisKernel $redisKernel;
    protected SessionBagInterface $attributeBag;

    protected ?string $sessionId = null;

    public function __construct(Request $request, CookieKernel $cookieKernel, RedisKernel $redisKernel)
    {
        $this->request = $request;
        $this->cookieKernel = $cookieKernel;
        $this->redisKernel = $redisKernel;
        $this->attributeBag = new AttributeBagRedis();
        $this->start();
    }

    public function start()
    {
        /**
         * todo:
         *      1) нужно шифровать значения в куки
         *      2) куки вечные, а сессия меяется, и когда меняется сессия, то меняется и куки
         */
        $this->sessionId = (string) $this->request->cookies->get(self::COOKIE_SESSION_NAME);

        if (empty($this->sessionId)) {
            $this->regenerate();
        }

        $this->attributeBag->replace((array) $this->redisKernel->redisExt(self::REDIS_CONNECTION_NAME)->get($this->sessionId, []));
    }

    public function isStarted(): bool
    {
        return !is_null($this->sessionId);
    }

    public function getId(): ?string
    {
        return $this->sessionId;
    }

    public function setId(?string $id)
    {
        if ($this->sessionId !== $id) {
            $saveAttributes = [];
            if (!is_null($this->sessionId) && $this->redisKernel->redisExt(self::REDIS_CONNECTION_NAME)->has($this->sessionId)) {
                $saveAttributes = (array) $this->redisKernel->redisExt(self::REDIS_CONNECTION_NAME)->get($this->sessionId);
                $this->redisKernel->redisExt(self::REDIS_CONNECTION_NAME)->del($this->sessionId);
            }
            $this->sessionId = $id;
            $this->cookieKernel->set(self::COOKIE_SESSION_NAME, $this->sessionId, time() + self::COOKIE_SESSION_TTL);
            if (!empty($saveAttributes)) {
                $this->redisKernel->redisExt(self::REDIS_CONNECTION_NAME)->put($this->sessionId, $saveAttributes, self::COOKIE_SESSION_TTL);
            }
        }
    }

    public function getName(): string
    {
        return __METHOD__;
    }

    public function setName(string $name)
    {
    }

    public function regenerate(bool $destroy = false, int $lifetime = null): bool
    {
        $this->setId(md5(microtime()));
        return true;
    }

    public function save()
    {
        $attributes = $this->attributeBag->all();
        $this->redisKernel->redisExt(self::REDIS_CONNECTION_NAME)->put($this->sessionId, $attributes, self::COOKIE_SESSION_TTL);
    }

    public function clear()
    {
        $this->attributeBag->clear();
    }

    public function getBag(string $name)
    {
        return $this->attributeBag;
    }

    public function registerBag(SessionBagInterface $bag)
    {
    }

    public function getMetadataBag(): MetadataBag
    {
        return new MetadataBag();
    }
}