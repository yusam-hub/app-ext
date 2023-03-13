<?php

namespace YusamHub\AppExt\SymfonyExt\Session;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;

class AttributeBagRedis implements AttributeBagInterface
{
    protected array $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function has(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    public function get(string $name, $default = null)
    {
        return $this->attributes[$name]??$default;
    }

    public function set(string $name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function all(): array
    {
        return $this->attributes;
    }

    public function replace(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function remove(string $name)
    {
        if (isset($this->attributes[$name])) {
            unset($this->attributes[$name]);
        }
    }

    public function getName()
    {
        return __METHOD__;
    }

    public function initialize(array &$array)
    {
        $this->attributes = $array;
    }

    public function getStorageKey(): string
    {
        return __METHOD__;
    }

    public function clear()
    {
        $this->attributes = [];
    }
}