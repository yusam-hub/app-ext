<?php

namespace YusamHub\AppExt\Traits;

use YusamHub\AppExt\Redis\RedisKernel;

trait GetSetRedisKernelTrait
{
    private ?RedisKernel $redisKernel = null;

    public function hasRedisKernel(): bool
    {
        return !is_null($this->redisKernel);
    }
    public function getRedisKernel(): ?RedisKernel
    {
        return $this->redisKernel;
    }
    public function setRedisKernel(?RedisKernel $redisKernel): void
    {
        $this->redisKernel = $redisKernel;
    }
}