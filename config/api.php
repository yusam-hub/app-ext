<?php

return [
    'publicSwaggerUiDir' => app()->getPublicDir('/swagger-ui'),
    'publicSwaggerUiUri' => '/swagger-ui',
    'apiBaseUri' => '/api',
    'tokenKeyName' => 'X-Token',
    'signKeyName' => 'X-Sign',
    'tokens' => [
        'testing' => 0 //по токену находим ID
    ],
    'signs' => [
        0 => 'testing', //по ID находим ключ подписи для ID
    ],
    'tokenHandle' => function(\Symfony\Component\HttpFoundation\Request $request) {
        if ($request->getRequestUri() === '/') {
            return null;
        }
        $tokens = (array) app_ext_config('api.tokens');

        if (empty($tokens)) {
            return null;
        }

        $tokenValue = (string) $request->headers->get(app_ext_config('api.tokenKeyName'));

        if (!in_array($tokenValue, array_keys($tokens))) {
            throw new \YusamHub\AppExt\Exceptions\HttpUnauthorizedAppExtRuntimeException([
                'message' => 'Invalid token value'
            ]);
        }
        return intval($tokens[$tokenValue]);
    },
    'signHandle' => function(\Symfony\Component\HttpFoundation\Request $request, int $apiAuthorizedId) {
        $signs = (array) app_ext_config('api.signs');

        if (isset($signs[$apiAuthorizedId])) {

            $signValue = (string) $request->headers->get(app_ext_config('api.signKeyName'));
            //todo: в режиме отладки подписывать не нужно ничего, просто сверяем два ключа как есть, что бы тестировать через сваггер
            if ($signValue !== $signs[$apiAuthorizedId]) //просто проверяем совпадение, не подписываем параметры, для этого нужен новый пакет для генерации подписи на основе контента или заголовка
            {
                throw new \YusamHub\AppExt\Exceptions\HttpUnauthorizedAppExtRuntimeException([
                    'message' => 'Invalid sign value',
                    'authorizedId' => $apiAuthorizedId
                ]);
            }
        }
    },
    //'infoTitle' => 'Api %s Server',
    //'infoVersion' => '1.0.0',
];