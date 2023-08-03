<?php

namespace YusamHub\AppExt\Redis;

use Psr\Log\LoggerInterface;
use YusamHub\RedisExt\RedisExt;

class RedisCacheUseFresh
{
    const CACHE_TTL_5_SECONDS = 5;
    const CACHE_TTL_10_SECONDS = 10;
    const CACHE_TTL_15_SECONDS = 15;
    const CACHE_TTL_20_SECONDS = 20;
    const CACHE_TTL_MINUTE = 60;
    const CACHE_TTL_2_MINUTES = self::CACHE_TTL_MINUTE * 2;
    const CACHE_TTL_3_MINUTES = self::CACHE_TTL_MINUTE * 3;
    const CACHE_TTL_5_MINUTES = self::CACHE_TTL_MINUTE * 5;
    const CACHE_TTL_10_MINUTES = self::CACHE_TTL_MINUTE * 10;
    const CACHE_TTL_15_MINUTES = self::CACHE_TTL_MINUTE * 15;
    const CACHE_TTL_HOUR = self::CACHE_TTL_MINUTE * 60;
    const CACHE_TTL_3_HOURS = self::CACHE_TTL_HOUR * 3;
    const CACHE_TTL_6_HOURS = self::CACHE_TTL_HOUR * 6;
    const CACHE_TTL_DAY = self::CACHE_TTL_HOUR * 24;
    const CACHE_TTL_7_DAY = self::CACHE_TTL_DAY * 7;
    const CACHE_TTL_MONTH = self::CACHE_TTL_DAY * 30;
    const CACHE_TTL_3_MONTH = self::CACHE_TTL_MONTH * 3;
    const CACHE_TTL_6_MONTH = self::CACHE_TTL_MONTH * 6;
    const CACHE_TTL_YEAR = self::CACHE_TTL_DAY * 365;

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