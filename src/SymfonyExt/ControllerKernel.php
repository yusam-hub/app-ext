<?php

namespace YusamHub\AppExt\SymfonyExt;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use YusamHub\AppExt\Db\DbKernel;
use YusamHub\AppExt\Exceptions\Interfaces\HttpAppExtRuntimeExceptionInterface;
use YusamHub\AppExt\Traits\GetSetConsoleTrait;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;
use YusamHub\AppExt\Traits\Interfaces\GetSetConsoleInterface;
use YusamHub\AppExt\Traits\Interfaces\GetSetLoggerInterface;

class ControllerKernel
    implements
    GetSetLoggerInterface,
    GetSetConsoleInterface
{
    use GetSetLoggerTrait;
    use GetSetConsoleTrait;

    public static bool $memoryRealUsage = false;
    protected string $routeDir;
    protected Request $request;
    protected string $phpFile;
    protected RequestContext $requestContext;
    protected Router $router;
    private HttpKernel $httpKernel;

    private bool $runInReactHttp;

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
     * @param $origin
     * @return bool
     */
    protected function isCorsSuccess(&$origin): bool
    {
        if (!app_ext_config('cors.enabled')) {
            return true;
        }

        $origin = $this->request->headers->get('origin');

        if (!empty($origin)) {
            $allowOrigins = (array) app_ext_config('cors.allowOrigins',['*']);
            if (!empty(array_filter($allowOrigins, function($v) use($origin){return $v === '*' || strtolower($origin) === strtolower($v);}))) {
                $denyOrigins = (array) app_ext_config('cors.denyOrigins',[]);
                if (empty(array_filter($denyOrigins, function($v) use($origin){return $v === '*' || strtolower($origin) === strtolower($v);}))) {
                    return true;
                }
            }
            return false;
        }

        return true;
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
        $headers = [];
        if (app_ext_config_has('api.tokenKeyName')) {
            $headers[app_ext_config('api.tokenKeyName')] = $this->request->headers->get(app_ext_config('api.tokenKeyName'));
        }
        if (app_ext_config_has('api.signKeyName')) {
            $headers[app_ext_config('api.signKeyName')] = $this->request->headers->get(app_ext_config('api.signKeyName'));
        }
        $requestContext = [
            'query' => $this->request->query->all(),
            'params' => $this->request->request->all(),
            'headers' => $headers,
        ];

        if ($this->isCorsSuccess($origin)) {

            try {
                $this->router = new Router(
                    new PhpFileLoader(
                        new FileLocator($this->routeDir)
                    ),
                    $this->phpFile,
                    [
                        // 'cache_dir' => app_ext()->getRootDir() . '/storage/app/caches/routes'
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

                $this->httpKernel = new HttpKernel($dispatcher, new ControllerResolverKernel($this, $this->request), new RequestStack(), new ArgumentResolver());

                $this->debug($requestMessage, $requestContext);

                if ($this->request->getMethod() == 'OPTIONS') {
                    $response = new Response();
                } else {
                    $response = $this->httpKernel->handle($this->request);
                }

            } catch (NotFoundHttpException $e) {

                $this->debug($e->getMessage(), app_ext_get_error_context($e));
                $response = new Response('Not Found', Response::HTTP_NOT_FOUND);
                if (strtolower(strval($this->request->headers->get('accept'))) === JSON_EXT_CONTENT_TYPE) {
                    $response->setContent(json_ext_json_encode_unescaped(json_ext_error('Not Found')));
                    $response->headers->set("Content-Type", JSON_EXT_CONTENT_TYPE);
                }

            } catch (HttpAppExtRuntimeExceptionInterface $e) {

                $this->debug($e->getMessage(), app_ext_get_error_context($e));
                $response = new Response($e->getMessage(), $e->getStatusCode());
                if (strtolower(strval($this->request->headers->get('accept'))) === JSON_EXT_CONTENT_TYPE) {
                    $response->setContent(json_ext_json_encode_unescaped(json_ext_throwable($e)));
                    $response->headers->set("Content-Type", JSON_EXT_CONTENT_TYPE);
                }

            } catch (\Throwable $e) {

                $this->error($e->getMessage(), app_ext_get_error_context($e, true));
                $response = new Response(Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],Response::HTTP_INTERNAL_SERVER_ERROR);
                if (strtolower(strval($this->request->headers->get('accept'))) === JSON_EXT_CONTENT_TYPE) {
                    $response->setContent(json_ext_json_encode_unescaped(json_ext_error(Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR])));
                    $response->headers->set("Content-Type", JSON_EXT_CONTENT_TYPE);
                }

            }

            if (!empty($origin)) {
                $response->headers->set('Access-Control-Allow-Origin', '*');
            }

        } else {

            $response = new Response(sprintf('CORS request did not succeed for origin: [%s]', strval($origin)), Response::HTTP_NOT_ACCEPTABLE);

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

        if (empty($response->headers->get('content-type'))) {
            $response->headers->set('content-type', 'text/html; charset=UTF-8');
        }

        if ($this->runInReactHttp) {

            /**
             * if file was created in react, we need to destroy them
             */
            $_files = (array) $this->request->attributes->get('_files', []);
            foreach($_files as $key => $fileItem) {
                if (isset($fileItem['tmp_name']) && file_exists($fileItem['tmp_name'])) {
                    @unlink($fileItem['tmp_name']);
                }
            }

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
        try {

            $response = $this->fetchResponse();

        } catch (\Throwable $e) {

            $responseStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $responseStatusMessage = Response::$statusTexts[$responseStatusCode];

            $this->error(sprintf("RESPONSE (%d): %s", $responseStatusCode, $responseStatusMessage), app_ext_get_error_context($e, true));
            $response = new Response($responseStatusMessage, $responseStatusCode);
        }

        $response->send();
        $this->httpKernel->terminate($this->request, $response);
    }

}