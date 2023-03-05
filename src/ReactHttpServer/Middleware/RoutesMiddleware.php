<?php

namespace YusamHub\AppExt\ReactHttpServer\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
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

        $symphonyRequest = new Request(
            $request->getQueryParams(),
            (array) $request->getParsedBody(),
            [],
            $request->getCookieParams(),
            $request->getUploadedFiles(),
            array_merge(
                $request->getServerParams(),
                [
                    'REQUEST_METHOD' => $request->getMethod(),
                    'HTTP_HOST' => $request->getUri()->getHost(),
                    'REQUEST_SCHEME' => $request->getUri()->getScheme(),
                    'QUERY_STRING' => $request->getUri()->getQuery(),
                    'REQUEST_URI' => $request->getUri()->getPath() . (!empty($request->getUri()->getQuery()) ? '?' . $request->getUri()->getQuery() : ''),
                    'DOCUMENT_URI' => $request->getUri()->getPath(),
                ]
            ),
            $request->getBody()->getContents()
        );

        $controllerKernel = new ControllerKernel(
            app()->getRootDir() . '/routes',
            $symphonyRequest,
            'default.php',
            true
        );
        $controllerKernel->setConsoleOutput($this->httpServer->getConsoleOutput());
        $controllerKernel->setConsoleOutputEnabled($this->httpServer->getConsoleOutputEnabled());
        $controllerKernel->setLogger($this->httpServer->getLogger());

        try {

            return $controllerKernel->fetchResponse();

        } catch (\Throwable $e) {

            $responseStatusCode = 500;
            $responseStatusMessage = "Internal Server Error";

            $this->httpServer->error(sprintf("RESPONSE (%d): %s", $responseStatusCode, $responseStatusMessage), [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'class' => get_class($e),
                ],
            ]);

            return Response::plaintext($responseStatusMessage)->withStatus($responseStatusCode);
        }
    }
}