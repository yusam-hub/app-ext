<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Traits;

use Symfony\Component\HttpFoundation\Request;

trait ApiAuthorizeTrait
{
    private ?int $apiAuthorizedId = null;

    protected function apiAuthorizeHandle(Request $request): void
    {
        $tokenHandle = app_ext_config('api.tokenHandle');
        if (is_callable($tokenHandle)) {
            $this->apiAuthorizedId = $tokenHandle($request);
            if (is_null($this->apiAuthorizedId)) {
                return;
            }
        }

        $signHandle = app_ext_config('api.signHandle');
        if (is_callable($signHandle)) {
            $signHandle($request, $this->apiAuthorizedId);
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