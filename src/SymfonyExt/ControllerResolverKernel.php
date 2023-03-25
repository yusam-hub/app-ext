<?php

namespace YusamHub\AppExt\SymfonyExt;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use YusamHub\AppExt\Db\PdoExtKernel;
use YusamHub\AppExt\Locale;
use YusamHub\AppExt\Redis\RedisKernel;
use YusamHub\AppExt\SymfonyExt\Http\Controllers\BaseHttpController;
use YusamHub\AppExt\SymfonyExt\Http\Interfaces\ControllerMiddlewareInterface;
use YusamHub\AppExt\SymfonyExt\Session\SessionRedis;
use YusamHub\AppExt\Traits\GetSetConsoleTrait;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;
use YusamHub\AppExt\Traits\Interfaces\GetSetConsoleInterface;
use YusamHub\AppExt\Traits\Interfaces\GetSetHttpControllerInterface;
use YusamHub\AppExt\Traits\Interfaces\GetSetLoggerInterface;
use YusamHub\AppExt\Translate;

class ControllerResolverKernel
    extends ControllerResolver
    implements
    GetSetLoggerInterface,
    GetSetConsoleInterface
{
    use GetSetConsoleTrait;
    use GetSetLoggerTrait;
    protected ControllerKernel $controllerKernel;
    protected Request $request;
    protected ?object $resolveController = null;

    /**
     * @param ControllerKernel $controllerKernel
     * @param Request $request
     */
    public function __construct(ControllerKernel $controllerKernel, Request $request)
    {
        $this->controllerKernel = $controllerKernel;
        $this->request = $request;
        parent::__construct(null);
    }

    /**
     * @param string $class
     * @return mixed|object|string|BaseHttpController
     */
    protected function instantiateController(string $class)
    {
        $this->resolveController = parent::instantiateController($class);

        if ($this->resolveController instanceof GetSetHttpControllerInterface)
        {
            $this->resolveController->setRequest($this->request);

            $this->resolveController->setLocale(new Locale());
            $this->resolveController->setTranslate(new Translate($this->resolveController->getLocale()));

            $pdoExtKernel = new PdoExtKernel();
            $pdoExtKernel->setLogger($this->controllerKernel->getLogger());
            $pdoExtKernel->setLoggerConsoleOutputEnabled($this->controllerKernel->getLoggerConsoleOutputEnabled());
            $pdoExtKernel->setConsoleOutput($this->controllerKernel->getConsoleOutput());
            $this->resolveController->setPdoExtKernel($pdoExtKernel);

            $redisKernel = new RedisKernel();
            $redisKernel->setLogger($this->controllerKernel->getLogger());
            $redisKernel->setLoggerConsoleOutputEnabled($this->controllerKernel->getLoggerConsoleOutputEnabled());
            $redisKernel->setConsoleOutput($this->controllerKernel->getConsoleOutput());
            $this->resolveController->setRedisKernel($redisKernel);

            $cookieKernel = new CookieKernel();
            $this->resolveController->setCookieKernel($cookieKernel);
            $this->request->setSession(new SessionRedis($this->request, $cookieKernel, $redisKernel));

            $this->resolveController->setLogger($this->controllerKernel->getLogger());
            $this->resolveController->setLoggerConsoleOutputEnabled($this->controllerKernel->getLoggerConsoleOutputEnabled());

            $this->resolveController->setConsoleOutput($this->controllerKernel->getConsoleOutput());
        }

        if ($this->resolveController instanceof ControllerMiddlewareInterface) {
            $this->resolveController->controllerMiddlewareHandle($this->request);
        }

        return $this->resolveController;
    }

    /**
     * @param ResponseHeaderBag $responseHeaderBag
     * @return void
     */
    public function sendCookie(ResponseHeaderBag $responseHeaderBag): void
    {
        if ($this->resolveController instanceof GetSetHttpControllerInterface) {
            if ($this->resolveController->hasCookieKernel()) {
                $this->resolveController->getCookieKernel()->responseSendCookie($responseHeaderBag);
            }
        }
    }

}