<?php

namespace App\Routing;

class SingleRoute
{
    /**
     * @var callable
     */
    protected $action;

    /**
     * @var string
     */
    protected string $path;

    /**
     * @var string[]
     */
    protected array $parameters = [];

    /**
     * @var string
     */
    protected string $method;

    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';

    /**
     * @param string $path
     * @param string $method
     * @param callable $action
     */
    public function __construct(
        string $path,
        string $method,
        callable $action
    ) {
        $this->path = trim($path, '/\\ ');
        $this->action = $action;
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return bool
     */
    public function doesItMatchWithCurrentRealPath(): bool
    {
        if(!$this->doesRouteMethodMatchRequestMethod()){
            return false;
        }

        $realPath = strtok($_SERVER["REQUEST_URI"], '?');
        preg_match_all('/{[^\/]+}/m', $this->getPath(), $matches, PREG_SET_ORDER, 0);

        $regex = '/^' . str_replace('/', '\/', $this->getPath()) . '$/m';
        $routeParameterNames = [];
        if($matches){
            foreach ($matches as $key => $match){
                $match = $match[0] ?? '';

                $regex = str_replace($match, '([^\/]+)', $regex);
                $routeParameterNames[$key+1] = $match;
            }
        }
        preg_match_all($regex, trim($realPath, '/\\ '), $realMatches, PREG_SET_ORDER, 0);
        $doesItMatch = !empty($realMatches);

        if($routeParameterNames){
            foreach ($routeParameterNames as $key => $name){
                $this->parameters[trim($name, '{}')] = $realMatches[0][$key] ?? '';
            }
        }

        return $doesItMatch;
    }

    /**
     * @return callable
     */
    public function getAction(): callable
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getParameterValue(string $name): string
    {
        return $this->parameters[$name] ?? '';
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return bool
     */
    public function doesRouteMethodMatchRequestMethod(): bool
    {
        return $this->getMethod() === $_SERVER['REQUEST_METHOD'];
    }
}