<?php

namespace HRMS\Core;

/**
 * Class Router
 *
 * A simple router for handling requests in an MVC structure.
 */
class Router
{
    /**
     * @var array Stores the registered routes.
     */
    protected $routes = [];

    /**
     * Registers a route with a corresponding controller action.
     *
     * @param string $method The HTTP method (GET, POST).
     * @param string $path The route path.
     * @param callable|array $handler The controller and method to call.
     * @return void
     */
    public function add($method, $path, $handler): void
    {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'path'    => trim($path, '/'),
            'handler' => $handler
        ];
    }

     /**
     * Matches the requested URL with registered routes and dispatches the request.
     *
     * @param string $requestUri The requested URI.
     * @param string $requestMethod The HTTP method of the request.
     * @return void
     */
    public function dispatch(string $requestUri, string $requestMethod): void
    {

        $requestPath = trim(parse_url($requestUri, PHP_URL_PATH), '/');
        $requestMethod = strtoupper($requestMethod);


        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $route['path'] === $requestPath) {
                // If the handler is a callable function, execute it
                if (is_callable($route['handler'])) {
                    call_user_func($route['handler']);
                    return;
                }
                // If the handler is an array, it represents a Controller and Method
                elseif (is_array($route['handler'])) {
                    [$controllerName, $method] = $route['handler'];
                    $controllerClass = "HRMS\\Controllers\\$controllerName";

                    // Check if the controller class exists and method exists before creating instance
                    if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
                        $controller = new $controllerClass();
                        $controller->$method();
                        return;
                    }
                }
            }
        }

        //If page not found, return 404 error code
        http_response_code(404);
        echo "404 - Not Found";
    }
}
