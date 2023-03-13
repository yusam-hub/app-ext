<?php

namespace YusamHub\AppExt\Redis\Session;

use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;

class SessionStorageRedis implements SessionStorageInterface
{
    public function start()
    {
        // TODO: Implement start() method.
    }

    public function isStarted()
    {
        // TODO: Implement isStarted() method.
    }

    public function getId()
    {
        // TODO: Implement getId() method.
    }

    public function setId(string $id)
    {
        // TODO: Implement setId() method.
    }

    public function getName()
    {
        // TODO: Implement getName() method.
    }

    public function setName(string $name)
    {
        // TODO: Implement setName() method.
    }

    public function regenerate(bool $destroy = false, int $lifetime = null)
    {
        // TODO: Implement regenerate() method.
    }

    public function save()
    {
        // TODO: Implement save() method.
    }

    public function clear()
    {
        // TODO: Implement clear() method.
    }

    public function getBag(string $name)
    {
        return new AttributeBagRedis();
    }

    public function registerBag(SessionBagInterface $bag)
    {
        // TODO: Implement registerBag() method.
    }

    public function getMetadataBag()
    {
        // TODO: Implement getMetadataBag() method.
    }

}