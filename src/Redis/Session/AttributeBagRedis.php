<?php

namespace YusamHub\AppExt\Redis\Session;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;
use YusamHub\Debug\Debug;
use YusamHub\RedisExt\RedisExt;

class AttributeBagRedis implements AttributeBagInterface
{
    protected RedisExt $redisExt;

    public function __construct(RedisExt $redisExt)
    {
        $this->redisExt = $redisExt;
    }

    public function has(string $name)
    {
        Debug::instance()->logPrint('debug',__METHOD__, $name);
    }

    public function get(string $name, $default = null)
    {
        Debug::instance()->logPrint('debug',__METHOD__, $name, $default);
    }

    public function set(string $name, $value)
    {
        Debug::instance()->logPrint('debug',__METHOD__, $name, $value);
    }

    public function all()
    {
        Debug::instance()->logPrint('debug',__METHOD__);
    }

    public function replace(array $attributes)
    {
        Debug::instance()->logPrint('debug',__METHOD__, $attributes);
    }

    public function remove(string $name)
    {
        Debug::instance()->logPrint('debug',__METHOD__, $name);
    }

    public function getName()
    {
        Debug::instance()->logPrint('debug',__METHOD__);
    }

    public function initialize(array &$array)
    {
        Debug::instance()->logPrint('debug',__METHOD__, $array);
    }

    public function getStorageKey()
    {
        Debug::instance()->logPrint('debug',__METHOD__);
    }

    public function clear()
    {
        Debug::instance()->logPrint('debug',__METHOD__);
    }
}