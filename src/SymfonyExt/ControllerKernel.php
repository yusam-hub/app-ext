<?php

namespace YusamHub\AppExt\SymfonyExt;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\PhpFileLoader;
class ControllerKernel
{
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
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @return string
     */
    public function getRouteDir(): string
    {
        return $this->routeDir;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return RequestContext
     */
    public function getRequestContext(): RequestContext
    {
        return $this->requestContext;
    }

    /**
     * @return array
     */
    public function getRouteMatch(): array
    {
        return $this->router->match($this->requestContext->getPathInfo());
    }

    /**
     * @return \React\Http\Message\Response|Response
     * @throws \Exception
     */
    public function fetchResponse()
    {
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

        $response = $this->httpKernel->handle($this->request);

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
     * @throws \Exception
     */
    public function runIndex()
    {
        $response = $this->fetchResponse();
        $response->send();
        $this->httpKernel->terminate($this->request, $response);
    }

}