<?php

namespace YusamHub\AppExt\Traits\Interfaces;

use YusamHub\AppExt\Redis\RedisKernel;

interface GetSetRedisKernelInterface
{
    public function hasRedisKernel(): bool;
    public function getRedisKernel(): ?RedisKernel;
    public function setRedisKernel(?RedisKernel $redisKernel): void;
}