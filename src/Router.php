<?php

namespace HRMS;

class Router
{
    protected $routes = [];

    public function add( $method,  $path, $handler): void {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'path'    => trim($path, '/'),
            'handler' => $handler
        ];
    }


   


    public function dispatch(string $requestUri, string $requestMethod): void {
       
        $requestPath = trim(parse_url($requestUri, PHP_URL_PATH), '/');
        $requestMethod = strtoupper($requestMethod);

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $route['path'] === $requestPath) {
                if (is_callable($route['handler'])) {
                    call_user_func($route['handler']);
                    return;
                } elseif (is_array($route['handler'])) {
                    [$controllerName, $method] = $route['handler'];
                    $controllerClass = "HRMS\\Controllers\\$controllerName";

                    if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
                        $controller = new $controllerClass();
                        $controller->$method();
                        return;
                    }
                }
            }
        }

        http_response_code(404);
        echo "404 - Not Found";
    }
}
