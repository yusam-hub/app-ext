<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Traits;

use Symfony\Component\HttpFoundation\Request;
use YusamHub\AppExt\SymfonyExt\Http\Interfaces\ApiAuthorizeModelInterface;

trait ApiAuthorizeTrait
{
    private ?int $apiAuthorizedId = null;

    protected function apiAuthorizeHandle(Request $request): void
    {
        if (property_exists($this, 'apiAuthorizePathExcludes')) {
            if (in_array($request->getRequestUri(), $this->apiAuthorizePathExcludes)) {
                return;
            }
        }

        $tokenHandle = app_ext_config('api.tokenHandle');

        if (is_callable($tokenHandle)) {
            $apiUser = $tokenHandle($this, $request);

            if (is_null($apiUser)) {
                return;
            } elseif (is_int($apiUser)) {
                $this->apiAuthorizedId = $apiUser;
            } elseif ($apiUser instanceof ApiAuthorizeModelInterface) {
                $this->apiAuthorizedId = $apiUser->getAuthorizeIdentifierAsInt();
            }

            $signHandle = app_ext_config('api.signHandle');
            if (is_callable($signHandle)) {
                $signHandle($this, $request, $this->apiAuthorizedId, $apiUser instanceof ApiAuthorizeModelInterface ? $apiUser : null);
            }
        }
    }

    /**
     * @return int|null
     */
    public function apiAuthorizedId():?int
    {
        return $this->apiAuthorizedId;
    }

    /**
     * @return bool
     */
    public function apiAuthorizedHas():bool
    {
        return !is_null($this->apiAuthorizedId);
    }
}