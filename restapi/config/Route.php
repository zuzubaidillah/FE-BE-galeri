<?php

namespace Config;

header("Content-type: application/json");
class Route
{
    private static $routes = [
        "POST" => [
            "/smkti/restapi-bookshelf-level2/api/registrasi" => "function",
            "/smkti/restapi-bookshelf-level2/api/auth/registrasi" => "function"
        ]
    ];
    public static function get($uri, $action)
    {
        self::$routes['GET'][$uri] = $action;
    }
    public static function post($uri, $action)
    {
        self::$routes['POST'][$uri] = $action;
    }
    public static function put($uri, $action)
    {
        self::$routes['PUT'][$uri] = $action;
    }
    public static function delete($uri, $action)
    {
        self::$routes['DELETE'][$uri] = $action;
    }
    public static function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // Periksa apakah ada rute yang didefinisikan untuk metode ini
        if (!isset(self::$routes[$method])) {
            header("HTTP/1.0 404 Not Found");
            http_response_code(400);
            echo json_encode(["message" => "404 Not Found - Metode $method tidak didukung"]);
            return;
        }
        foreach (self::$routes[$method] as $route => $action) {
            $route_pattern = "#^" . preg_replace('/{[a-zA-Z0-9_]+}/', '([a-zA-Z0-9_]+)', $route) . "$#";
            if (preg_match($route_pattern, $uri, $matches)) {
                array_shift($matches); // Hapus full match, sisakan parameter saja
                if (is_callable($action)) {
                    // Jika action adalah closure
                    call_user_func_array($action, $matches);
                } else if (is_string($action)) {
                    // Jika action adalah string, misalnya 'Controller@method'
                    [$controller, $method] = explode('@', $action);
                    call_user_func_array([new $controller, $method], $matches);
                }
                return;
            }
        }
        // Handle not found
        header("HTTP/1.0 404 Not Found");
        http_response_code(400);
        echo json_encode(["message" => "404 Not Found - Metode $method tidak didukung"]);
    }
}
