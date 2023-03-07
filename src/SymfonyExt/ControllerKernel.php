<?php

namespace YusamHub\AppExt\SymfonyExt;

use Asm89\Stack\CorsService;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use YusamHub\AppExt\Interfaces\GetSetConsoleInterface;
use YusamHub\AppExt\Interfaces\GetSetLoggerInterface;
use YusamHub\AppExt\Traits\GetSetConsoleTrait;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;

class ControllerKernel implements GetSetLoggerInterface, GetSetConsoleInterface
{
    use GetSetLoggerTrait;
    use GetSetConsoleTrait;

    public static bool $memoryRealUsage = false;
    protected string $routeDir;
    protected Request $request;
    protected string $phpFile;
    protected bool $runInReactHttp;
    protected RequestContext $requestContext;
    protected Router $router;
    private HttpKernel $httpKernel;


    /**
     * @param string $routeDir
     * @param Request $request
     * @param string $phpFile
     * @param bool $runInReactHttp
     */
    public function __construct(string $routeDir, Request $request, string $phpFile, bool $runInReactHttp = false)
    {
        $this->routeDir = $routeDir;
        $this->request = $request;
        $this->phpFile = $phpFile;
        $this->runInReactHttp = $runInReactHttp;
        $this->requestContext = new RequestContext();
        $this->requestContext->fromRequest($request);
    }

    /**
     * @return \React\Http\Message\Response|Response
     * @throws \Exception|\Throwable
     */
    public function fetchResponse()
    {
        $executeStarted = microtime(true);
        $mStart = memory_get_usage(self::$memoryRealUsage);

        $requestMessage = "REQUEST: " . $this->request->getMethod() . ' ' . $this->request->getRequestUri();
        $requestContext = [
            'query' => $this->request->query->all(),
            'params' => $this->request->request->all(),
        ];

        try {
            $this->router = new Router(
                new PhpFileLoader(
                    new FileLocator($this->routeDir)
                ),
                $this->phpFile,
                [
                    // 'cache_dir' => app()->getRootDir() . '/storage/app/caches/routes'
                ],
                $this->requestContext
            );

            $dispatcher = new EventDispatcher();

            $dispatcher->addSubscriber(
                new RouterListener(
                    new UrlMatcher(
                        $this->router->getRouteCollection(),
                        new RequestContext()
                    ),
                    new RequestStack()
                )
            );

            $this->httpKernel = new HttpKernel($dispatcher, new ControllerResolver(), new RequestStack(), new ArgumentResolver());

            $this->debug($requestMessage, $requestContext);
            $response = $this->httpKernel->handle($this->request);

        } catch (NotFoundHttpException $e) {

            $this->debug($requestMessage, $requestContext);
            $response = new Response('Not Found', 404);//todo: call controller for NotFound

        }

        $mEnd = memory_get_usage(self::$memoryRealUsage);
        $this->debug(sprintf("RESPONSE (%d): %s", $response->getStatusCode(), $response->getContent()), [
            'executed' => microtime(true) - $executeStarted,
            'memory' => [
                'start' => $mStart,
                'end' => $mEnd,
                'diff' => $mEnd - $mStart
            ]
        ]);

        if ($this->runInReactHttp) {
            return new \React\Http\Message\Response(
                $response->getStatusCode(),
                $response->headers->all(),
                $response->getContent()
            );
        }
        return $response;
    }

    /**
     * @return void
     */
    public function runIndex(): void
    {
        $cors = new CorsService(app_ext_config('cors'));

        if ($cors->isPreflightRequest($this->request)) {
            $response = $cors->handlePreflightRequest($this->request);
            $cors->varyHeader($response, 'Access-Control-Request-Method');
            $response->send();
            $this->httpKernel->terminate($this->request, $response);
            return;
        }

        try {

            $response = $this->fetchResponse();

        } catch (\Throwable $e) {

            $responseStatusCode = 500;
            $responseStatusMessage = "Internal Server Error";

            $this->error(sprintf("RESPONSE (%d): %s", $responseStatusCode, $responseStatusMessage), [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'class' => get_class($e),
                ],
            ]);
            $response = new Response($responseStatusMessage, $responseStatusCode);
        }

        if ($this->request->getMethod() === 'OPTIONS') {
            $cors->varyHeader($response, 'Access-Control-Request-Method');
        }

        $response->send();
        $this->httpKernel->terminate($this->request, $response);
    }

}