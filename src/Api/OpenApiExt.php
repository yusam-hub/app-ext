<?php

namespace YusamHub\AppExt\Api;

class OpenApiExt
{
    protected array $paths = [
        __DIR__ . '/Oa/Info',
        __DIR__ . '/Oa/Server',
        __DIR__ . '/Oa/SecurityScheme/Token',
        __DIR__ . '/Oa/SecurityScheme/Sign',
        __DIR__ . '/Oa/Methods',
        __DIR__ . '/Oa/Schemas',
    ];

    /**
     * @var array|string[]
     */
    protected array $replaceKeyValuePair = [
        '__OA_INFO_TITLE__' => 'Api',
        '__OA_INFO_VERSION__' => '1.0.0',
        '__OA_SERVER_HOSTNAME__' => 'localhost',
        '__OA_SERVER_PATH__' => '',
        '__OA_SERVER_SCHEMA__' => 'https',
        '__OA_SECURITY_SCHEME_TOKEN_HEADER_NAME__' => 'X-Token',
        '__OA_SECURITY_SCHEME_SIGN_HEADER_NAME__' => 'X-Sign',
        '__OA_METHOD_GET_HOME_PATH__' => '/',
    ];

    /**
     * @param array $paths
     * @param array $replaceKeyValuePair
     */
    public function __construct(array $paths, array $replaceKeyValuePair = [])
    {
        $this->paths = array_merge($this->paths, $paths);
        $this->replaceKeyValuePair = array_merge($this->replaceKeyValuePair, $replaceKeyValuePair);
    }

    /**
     * @return string
     */
    public function generateOpenApi(): string
    {
        $openApi = \OpenApi\Generator::scan($this->paths);

        $json = $openApi->toJson();

        $localReplaceKeyValuePair = $this->replaceKeyValuePair;
        foreach($localReplaceKeyValuePair as $key => $value) {
            $json = str_replace($key, $value, $json);
        }

        return $json;
    }
}