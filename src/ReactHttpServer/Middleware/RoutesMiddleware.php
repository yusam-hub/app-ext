<?php

namespace YusamHub\AppExt\ReactHttpServer\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Io\UploadedFile;
use React\Http\Message\Response;
use React\Promise\Promise;
use Symfony\Component\HttpFoundation\Request;
use YusamHub\AppExt\ReactHttpServer\ReactHttpServer;
use YusamHub\AppExt\SymfonyExt\ControllerKernel;

class RoutesMiddleware
{
    protected ReactHttpServer $httpServer;

    public function __construct(ReactHttpServer $httpServer)
    {
        $this->httpServer = $httpServer;
    }

    /**
     * @param ServerRequestInterface $request
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $requestId = $this->httpServer->incCounterRequests();

        $serverParams = [];
        foreach($request->getHeaders() as $key => $values)
        {
            $value = $request->getHeader($key)[0]??'';
            if (!empty($value)) {
                $serverKey = 'HTTP_'.strtoupper($key);
                $serverKey = str_replace("-",'_', $serverKey);
                $serverParams[$serverKey] = $value;
            }
        }

        $serverParams = array_merge(
            $serverParams,
            [
                'REQUEST_METHOD' => $request->getMethod(),
                'REQUEST_SCHEME' => $request->getUri()->getScheme(),
                'QUERY_STRING' => $request->getUri()->getQuery(),
                'REQUEST_URI' => rtrim($request->getUri()->getPath(), '/') . (!empty($request->getUri()->getQuery()) ? '?' . $request->getUri()->getQuery() : ''),
                'DOCUMENT_URI' => rtrim($request->getUri()->getPath(), '/'),
            ],
            $request->getServerParams(),
        );

        if ($this->httpServer->getHttpServerConfig()->isDebugging) {
            $this->httpServer->getConsoleOutput()->writeln('---------------Middleware---------------');
            $this->httpServer->getConsoleOutput()->writeln(sprintf('Counter Requests: %d', $requestId));
            $this->httpServer->getConsoleOutput()->writeln(sprintf("#%d# Server params: %s", $requestId, json_encode($serverParams, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)));
        }

        print_r($request->getUploadedFiles());
        $files = [];
        /**
         * @var UploadedFile $file
         */
       /* [name] => MyFile.txt (comes from the browser, so treat as tainted)
            [type] => text/plain  (not sure where it gets this from - assume the browser, so treat as tainted)
            [tmp_name] => /tmp/php/php1h4j1o (could be anywhere on your system, depending on your config settings, but the user has no control, so this isn't tainted)
            [error] => UPLOAD_ERR_OK  (= 0)
            [size] => 123   (the size in bytes)*/
        foreach($request->getUploadedFiles() as $key => $file) {
            $tmp_name = $this->httpServer->getHttpServerConfig()->tmpFileDir . DIRECTORY_SEPARATOR . md5(microtime() . $requestId);
            $files[$key] = [
                'name' => $file->getClientFilename(),
                'type' => $file->getClientMediaType(),
                'tmp_name' => $tmp_name,
                'error' => $file->getError(),
                'size' => $file->getSize()
            ];
        }
        var_dump($_FILES);
        /**
         * todo: нужно файл сохранить в tmp и передать как массив по Request
         */

        $symphonyRequest = new Request(
            $request->getQueryParams(),
            (array) $request->getParsedBody(),
            [],
            $request->getCookieParams(),
            $files,
            $serverParams,
            $request->getBody()->getContents()
        );

        $controllerKernel = new ControllerKernel(
            dirname($this->httpServer->getRoutesConfigFile()),
            $symphonyRequest,
            basename($this->httpServer->getRoutesConfigFile()),
            true
        );
        $controllerKernel->setConsoleOutput($this->httpServer->getConsoleOutput());
        $controllerKernel->setConsoleOutputEnabled($this->httpServer->getConsoleOutputEnabled());
        $controllerKernel->setLogger($this->httpServer->getLogger());

        if ($this->httpServer->getHttpServerConfig()->isDebugging) {
            $this->httpServer->getConsoleOutput()->writeln(sprintf('#%d# MemoryUsage (now: %d, diff: %d, start: %d)', $requestId, memory_get_usage(), memory_get_usage() - $this->httpServer->getMemoryUsageStart(), $this->httpServer->getMemoryUsageStart()));
            $this->httpServer->getConsoleOutput()->writeln(sprintf('#%d# MemoryUsageReal (now: %d, diff: %d, start: %d)', $requestId, memory_get_usage(true), memory_get_usage(true) - $this->httpServer->getMemoryUsageRealStart(), $this->httpServer->getMemoryUsageRealStart()));
        }
        return $this->fetchResponse($controllerKernel, $requestId);
    }

    protected function fetchResponse(ControllerKernel $controllerKernel, int $requestId): Promise
    {
        return new Promise(function ($resolve) use ($controllerKernel, $requestId) {
            if ($this->httpServer->getHttpServerConfig()->isDebugging) {
                $this->httpServer->getConsoleOutput()->writeln('---------------Promise---------------');
                $this->httpServer->getConsoleOutput()->writeln(sprintf('#%d# Counter Promises: %d', $requestId, $this->httpServer->incCounterPromises()));
            }

            try {

                $response = $controllerKernel->fetchResponse();

            } catch (\Throwable $e) {

                $responseStatusCode = 500;
                $responseStatusMessage = "Internal Server Error";

                $this->httpServer->error(sprintf("RESPONSE (%d): %s", $responseStatusCode, $responseStatusMessage), app_ext_get_error_context($e));

                $response = Response::plaintext($responseStatusMessage)->withStatus($responseStatusCode);
            }

            if ($this->httpServer->getHttpServerConfig()->isDebugging) {
                $this->httpServer->getConsoleOutput()->writeln(sprintf('#%d# MemoryUsage (now: %d, diff: %d, start: %d)', $requestId, memory_get_usage(), memory_get_usage() - $this->httpServer->getMemoryUsageStart(), $this->httpServer->getMemoryUsageStart()));
                $this->httpServer->getConsoleOutput()->writeln(sprintf('#%d# MemoryUsageReal (now: %d, diff: %d, start: %d)', $requestId, memory_get_usage(true), memory_get_usage(true) - $this->httpServer->getMemoryUsageRealStart(), $this->httpServer->getMemoryUsageRealStart()));
            }

            $resolve($response);

        });
    }
}