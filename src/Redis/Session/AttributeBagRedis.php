<?php

namespace YusamHub\AppExt\Redis\Session;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;

class AttributeBagRedis implements AttributeBagInterface
{
    public function __construct()
    {
    }

    public function has(string $name)
    {
        // TODO: Implement has() method.
    }

    public function get(string $name, $default = null)
    {
        // TODO: Implement get() method.
    }

    public function set(string $name, $value)
    {
        // TODO: Implement set() method.
    }

    public function all()
    {
        // TODO: Implement all() method.
    }

    public function replace(array $attributes)
    {
        // TODO: Implement replace() method.
    }

    public function remove(string $name)
    {
        // TODO: Implement remove() method.
    }

    public function getName()
    {
        // TODO: Implement getName() method.
    }

    public function initialize(array &$array)
    {
        // TODO: Implement initialize() method.
    }

    public function getStorageKey()
    {
        // TODO: Implement getStorageKey() method.
    }

    public function clear()
    {
        // TODO: Implement clear() method.
    }
}