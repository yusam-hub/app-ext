<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Traits;

use Symfony\Component\HttpFoundation\Request;
use YusamHub\AppExt\SymfonyExt\Http\Interfaces\ApiAuthorizeModelInterface;

trait ApiAuthorizeTrait
{
    /**
     * @var null|int|string
     */
    private $apiAuthorizedIdentifier = null;

    protected function apiAuthorizeHandle(Request $request): void
    {
        if (property_exists($this, 'apiAuthorizePathExcludes')) {
            if (in_array($request->getRequestUri(), $this->apiAuthorizePathExcludes)) {
                return;
            }
        }

        $tokenHandle = app_ext_config('api.tokenHandle');

        if (is_callable($tokenHandle)) {
            $v = $tokenHandle($this, $request);

            if (is_null($v)) {
                return;
            } elseif (is_int($v)) {
                $this->apiAuthorizedIdentifier = $v;
            } elseif (is_string($v)) {
                $this->apiAuthorizedIdentifier = $v;
            } elseif ($v instanceof ApiAuthorizeModelInterface) {
                $this->apiAuthorizedIdentifier = $v->getAuthorizeIdentifierAsInt();
            }

            $signHandle = app_ext_config('api.signHandle');
            if (is_callable($signHandle)) {
                $signHandle($this, $request, $this->apiAuthorizedIdentifier, $v instanceof ApiAuthorizeModelInterface ? $v : null);
            }
        }
    }

    /**
     * @return int|null
     */
    public function apiAuthorizedIdentifierAsInt():?int
    {
        return is_null($this->apiAuthorizedIdentifier) ? null : (int) $this->apiAuthorizedIdentifier;
    }

    /**
     * @return string|null
     */
    public function apiAuthorizedIdentifierAsString():?string
    {
        return is_null($this->apiAuthorizedIdentifier) ? null : (string) $this->apiAuthorizedIdentifier;
    }

    /**
     * @return bool
     */
    public function apiAuthorizedHas():bool
    {
        return !is_null($this->apiAuthorizedIdentifier);
    }
}