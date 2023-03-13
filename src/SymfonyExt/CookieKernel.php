<?php

namespace YusamHub\AppExt\SymfonyExt;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CookieKernel
{
    /**
     * @var array|Cookie[]
     */
    private array $cookies = [];

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->cookies[$name]);
    }

    /**
     * @param string $name
     * @param string|null $value
     * @param int $expire
     * @param string|null $path
     * @param string|null $domain
     * @param bool|null $secure
     * @param bool $httpOnly
     * @param bool $raw
     * @param string|null $sameSite
     * @return void
     */
    public function set(string $name, string $value = null, int $expire = 0, ?string $path = '/', string $domain = null, bool $secure = null, bool $httpOnly = true, bool $raw = false, ?string $sameSite = 'lax'): void
    {
        $this->cookies[$name] = new Cookie($name, $value, $expire, $path, $domain, $secure, $httpOnly, $raw, $sameSite);
    }

    /**
     * @param string $name
     * @return Cookie|null
     */
    public function get(string $name): ?Cookie
    {
        return $this->cookies[$name]??null;
    }

    /**
     * @param string $name
     * @return void
     */
    public function del(string $name): void
    {
        $this->cookies[$name] = new Cookie($name, null);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        $out = [];
        /**
         * @var Cookie $cookieModel
         */
        foreach($this->cookies as $name => $cookieModel) {
            $out[$name] = $cookieModel->getValue();
        }
        return $out;
    }

    /**
     * @param ResponseHeaderBag $responseHeaderBag
     * @return void
     */
    public function responseSendCookie(ResponseHeaderBag $responseHeaderBag): void
    {
        foreach($this->cookies as $name => $cookieModel) {
            $responseHeaderBag->setCookie($cookieModel);
        }
    }

}