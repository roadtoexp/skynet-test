<?php

declare(strict_types=1);

namespace Core;

use App\Controllers\BaseApiController;

class Route
{
    private array $routes = [];
    private string $serverRequestUri;
    private string $serverRequestMethod;

    public function __construct()
    {
        $this->serverRequestUri = $_SERVER['REQUEST_URI'];
        $this->serverRequestMethod = $_SERVER['REQUEST_METHOD'];
    }

    public function setRoutes(array $routes): Route
    {
        $this->routes = $routes;

        return $this;
    }

    public function run(): void
    {
        foreach ($this->routes as $route => $action) {
            [$routeMethod, $routeRequest] = explode(' ', $route);
            if ($this->serverRequestMethod === $routeMethod) {
                $isValidRoute = preg_match(
                    '#^'.preg_replace(
                        '#{([a-zA-Z0-9_\-]+)}#',
                        '(?<$1>[a-zA-Z0-9_\-]*)',
                        $routeRequest
                    ).'$#',
                    $this->serverRequestUri,
                    $routeMatches
                );
                if ($isValidRoute) {
                    $this->runAction(
                        $action,
                        $this->getActionParams($routeMatches)
                    );
                }
            }
        }
        (new BaseApiController())->abort(400, 'Bad Request');
    }

    private function runAction(string $action, array $params): void
    {
        [$controller, $method] = explode('@', $action);
        $controllerClass = 'App\Controllers\\'.$controller;
        call_user_func_array([new $controllerClass, $method], $params);
    }

    private function getActionParams(array $routeMatches): array
    {
        return array_values(
            array_filter(
                $routeMatches,
                fn($value, $key) => is_string($key),
                ARRAY_FILTER_USE_BOTH
            )
        );
    }
}