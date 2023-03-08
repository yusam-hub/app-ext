<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Traits;

use Symfony\Component\HttpFoundation\Request;
use YusamHub\AppExt\Exceptions\HttpUnauthorizedAppExtRuntimeException;

trait ApiAuthorizeTrait
{
    private ?int $apiAuthorizedId = null;

    protected function apiAuthorizeHandle(Request $request): void
    {
        $tokens = (array) app_ext_config('api.tokens');//todo: брать из БД
        $tokenValue = (string) $request->headers->get(app_ext_config('api.tokenKeyName'));

        if (!in_array($tokenValue, array_keys($tokens))) {
            throw new HttpUnauthorizedAppExtRuntimeException([
                'message' => 'Invalid token value'
            ]);
        }
        $this->apiAuthorizedId = intval($tokens[$tokenValue]);

        /*$signs = (array) app_ext_config('api.signs');//todo: брать из БД
        if (isset($signs[$this->apiAuthorizedId])) {
            $signValue = (string) $request->headers->get(app_ext_config('api.signKeyName'));

            $content = ($request->getMethod() === 'GET') ? json_encode($request->query->all()) : json_encode($request->request->all());
            $calcSignValue = md5(
                $request->getRequestUri() . $content. $signs[$this->apiAuthorizedId]
            );//todo: for text or file

            if ($signValue !== $calcSignValue) {
                throw new HttpUnauthorizedAppExtRuntimeException([
                    'message' => 'Invalid sign value',
                    'authorizedId' => $this->apiAuthorizedId
                ]);
            }
        }*/
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