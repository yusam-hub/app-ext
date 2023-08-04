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
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->paths = array_merge($this->paths, $config['paths']??[]);
        $this->replaceKeyValuePair = array_merge($this->replaceKeyValuePair, $config['replaceKeyValuePair']??[]);
    }

    /**
     * @param array $excludePaths
     * @param array $excludeReplaceKeys
     * @return string
     */
    public function generateOpenApi(array $excludePaths = [], array $excludeReplaceKeys = []): string
    {
        $openApi = \OpenApi\Generator::scan($this->getPaths($excludePaths));

        $json = $openApi->toJson();

        $localReplaceKeyValuePair = $this->getReplaceKeyValuePair($excludeReplaceKeys);
        foreach($localReplaceKeyValuePair as $key => $value) {
            $json = str_replace($key, $value, $json);
        }

        return $json;
    }

    /**
     * @param array $excludePaths
     * @return array
     */
    public function getPaths(array $excludePaths = []): array
    {
        return array_filter($this->paths, function($v) use($excludePaths) {
            foreach($excludePaths as $subV) {
                if (str_contains($v, $subV)) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * @param array $paths
     */
    public function setPaths(array $paths): void
    {
        $this->paths = $paths;
    }

    /**
     * @param array $excludeReplaceKeys
     * @return array
     */
    public function getReplaceKeyValuePair(array $excludeReplaceKeys = []): array
    {
        return array_filter($this->replaceKeyValuePair, function($v,$k) use($excludeReplaceKeys) {
            foreach($excludeReplaceKeys as $subV) {
                if (str_contains($k, $subV)) {
                    return false;
                }
            }
            return true;
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * @param array $replaceKeyValuePair
     */
    public function setReplaceKeyValuePair(array $replaceKeyValuePair): void
    {
        $this->replaceKeyValuePair = $replaceKeyValuePair;
    }


}