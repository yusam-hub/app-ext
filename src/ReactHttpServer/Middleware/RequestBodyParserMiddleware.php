<?php

namespace YusamHub\AppExt\ReactHttpServer\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Io\MultipartParser;

class RequestBodyParserMiddleware
{
    private MultipartParser $multipart;

    /**
     * @param int|string|null $uploadMaxFilesize
     * @param int|null $maxFileUploads
     */
    public function __construct($uploadMaxFilesize = null, ?int $maxFileUploads = null)
    {
        $this->multipart = new MultipartParser($uploadMaxFilesize, $maxFileUploads);
    }

    /**
     * @param ServerRequestInterface $request
     * @param $next
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, $next)
    {
        $type = strtolower($request->getHeaderLine('Content-Type'));
        list ($type) = explode(';', $type);

        if ($type === 'application/x-www-form-urlencoded') {
            return $next($this->parseFormUrlencoded($request));
        }

        if ($type === 'multipart/form-data') {
            return $next($this->multipart->parse($request));
        }

        if ($type === JSON_EXT_CONTENT_TYPE) {
            return $next($this->parseJson($request));
        }

        return $next($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    private function parseJson(ServerRequestInterface $request): ServerRequestInterface
    {
        $ret = @json_decode((string) $request->getBody(), true);
        return $request->withParsedBody($ret);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    private function parseFormUrlencoded(ServerRequestInterface $request): ServerRequestInterface
    {
        $ret = array();
        @\parse_str((string)$request->getBody(), $ret);

        return $request->withParsedBody($ret);
    }

}