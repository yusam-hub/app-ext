<?php

namespace YusamHub\AppExt\Redis\Session;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use YusamHub\AppExt\Redis\RedisKernel;
use YusamHub\AppExt\SymfonyExt\CookieKernel;
use YusamHub\Debug\Debug;
use YusamHub\RedisExt\RedisExt;

class SessionStorageRedis implements SessionStorageInterface
{
    const REDIS_CONNECTION_NAME = 'session';
    const COOKIE_SESSION_NAME = 'session';

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
            if (!is_null($this->sessionId) && $this->redisKernel->redisExt(self::REDIS_CONNECTION_NAME)->has($this->sessionId)) {
                $this->redisKernel->redisExt(self::REDIS_CONNECTION_NAME)->del($this->sessionId);
            }
            $this->sessionId = $id;
            $this->cookieKernel->set(self::COOKIE_SESSION_NAME, $this->sessionId);
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
        $this->redisKernel->redisExt(self::REDIS_CONNECTION_NAME)->put($this->sessionId, $attributes);
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