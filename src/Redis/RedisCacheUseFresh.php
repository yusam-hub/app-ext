<?php

namespace YusamHub\AppExt\Redis;

use Psr\Log\LoggerInterface;
use YusamHub\RedisExt\RedisExt;

class RedisCacheUseFresh
{
    public static function rememberExt(
        RedisExt $redisExt,
        LoggerInterface $logger,
        string $cacheKey,
        bool $cacheUse,
        bool $cacheFresh,
        int $cacheTtl,
        \Closure $callback
    )
    {
        if ($cacheFresh) {
            if ($redisExt->has($cacheKey)) {
                $redisExt->del($cacheKey);
            }
        }

        if ($cacheUse) {
            if ($redisExt->has($cacheKey)) {
                $result = $redisExt->get($cacheKey);
                $logger->debug("[REDIS_CACHE_USE_FRESH]: " . $cacheKey . " => ttl:" . $cacheTtl, (array) $result);
            } else {
                $result = $callback();
                $redisExt->put($cacheKey, $result, $cacheTtl);
            }
            return $result;
        }

        return $callback();
    }
}