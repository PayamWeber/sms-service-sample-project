<?php

namespace App\Routing;

use App\Exceptions\NotFoundException;
use App\Routing\Response\JsonResponse;
use App\Routing\Response\Response;
use Exception;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;

class Route
{
    /**
     * @var self|null
     */
    protected static ?self $instance = null;

    /**
     * @var SingleRoute[]
     */
    protected array $routes = [];

    private function __construct()
    {
    }

    /**
     * This is Singleton
     * @return $this|null
     */
    public static function getInstance(): ?self
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        } else {
            return self::$instance = new self;
        }
    }

    /**
     * @param string $path
     * @param callable|array $action
     * @return SingleRoute
     */
    public static function get(string $path, callable|array $action): SingleRoute
    {
        return self::getInstance()->addRoute($path, $action);
    }

    /**
     * @param string $path
     * @param callable|array $action
     * @return SingleRoute
     */
    public static function post(string $path, callable|array $action): SingleRoute
    {
        return self::getInstance()->addRoute($path, $action, SingleRoute::METHOD_POST);
    }

    /**
     * @param string $path
     * @param callable|array $action
     * @param string $method
     * @return SingleRoute
     */
    protected function addRoute(string $path, callable|array $action, string $method = SingleRoute::METHOD_GET): SingleRoute
    {
        $action = $this->getActionAsCallable($action);
        return $this->routes[] = new SingleRoute(
            path: $path,
            method: $method,
            action: $action
        );
    }

    /**
     * @return void
     * @throws Exception
     */
    public static function detectAndPrintResponseOfCurrentRoute()
    {
        include __DIR__ . '/../../routes.php';

        foreach (self::getInstance()->routes as $route) {
            if ($route->doesItMatchWithCurrentRealPath()) {
                $parametersShouldSend = self::getInstance()->getParametersShouldSend($route);
                $response = call_user_func_array($route->getAction(), $parametersShouldSend);

                if ($response instanceof Response) {
                    echo $response->render();
                } else {
                    if (is_array($response)) {
                        echo JsonResponse::from($response)->render();
                    }
                }
                break;
            }else{
                throw new NotFoundException();
            }
        }
    }

    /**
     * @param SingleRoute $route
     * @return array
     */
    protected function getParametersShouldSend(SingleRoute $route): array
    {
        try {
            if (is_array($route->getAction())) {
                $reflectionOfAction = new ReflectionMethod($route->getAction()[0]::class, $route->getAction()[1]);
            } else {
                $reflectionOfAction = new ReflectionFunction($route->getAction());
            }
        } catch (ReflectionException $e) {
            // TODO: log the error
            return [];
        }

        $parametersShouldSend = [];
        if ($reflectionOfAction->getParameters()) {
            foreach ($reflectionOfAction->getParameters() as $reflectionParameter) {
                $parametersShouldSend[$reflectionParameter->getName()] = $route->getParameterValue(
                    $reflectionParameter->getName()
                );
            }
        }
        return $parametersShouldSend;
    }

    /**
     * @param callable|array $action
     * @return callable
     */
    protected function getActionAsCallable(callable|array $action): callable
    {
        return is_array($action) ? [new $action[0], $action[1]] : $action;
    }
}