<?php

namespace  src\Core\Router;

class Router

{
    protected static array $routes = [];


    /**
     * Add rota do tipo GET.
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @param string $url
     * @param array $action
     * @access public
     * @return void
     */
    public static function get(string $url, array $action): void
    {
        self::addRoute('GET', $url, $action);
    }

    /**
     * Add rota do tipo POST.
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @param string $url
     * @param array $action
     * @access public
     * @return void
     */
    public static function post(string $url, array $action): void
    {
        self::addRoute('POST', $url, $action);
    }

    /**
     * Add rota do tipo PUT.
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @param string $url
     * @param array $action
     * @access public
     * @return void
     */
    public static function put(string $url, array $action): void
    {
        self::addRoute('PUT', $url, $action);
    }

    /**
     * Add rota do tipo DELETE.
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @param string $url
     * @param array $action
     * @access public
     * @return void
     */
    public static function delete(string $url, array $action): void
    {
        self::addRoute('DELETE', $url, $action);
    }

    /**
     * Add rotas para serem posteriormente tratadas.
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @param string $method
     * @param string $url
     * @param array $action
     * @access public
     * @return void
     */
    protected static function addRoute(string $method, string $url, array $action): void
    {
        self::$routes[$method][] = [
            'action' => $action,
            'url' =>   $url,
        ];
    }


    /**
     * Inicia as rotas.
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com> 
     * @access public
     * @return void
     */
    public static function dispatch(string $sub_folder = ""): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        $routes = self::$routes[$method] ?? [];
        $Accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $retorno_json = false;

        if ($Accept == 'application/json') {
            $retorno_json = true;
            header('Content-Type: application/json');
        }

        foreach ($routes as $route) {
            $routeUri = $sub_folder . $route['url'];
            if (hash_equals($requestUri, $routeUri)) {
                $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
                $jsonData = null;
                if (
                    in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE']) &&
                    str_contains($contentType, 'application/json')
                ) {

                    $raw = file_get_contents('php://input');
                    if ($raw) {
                        $jsonData = json_decode($raw, true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            http_response_code(400);
                            if ($retorno_json) {
                                echo json_encode(['error' => 'JSON inválido.']);
                            } else {
                                echo  'JSON inválido.';
                            }

                            return;
                        }
                    }
                }

                [$class, $methodName] = $route['action'];

                if (!class_exists($class)) {
                    http_response_code(500);
                    if ($retorno_json) {
                        echo json_encode(['error' => 'Erro ao resolver rota']);
                    } else {
                        echo "Erro ao resolver rota";
                    }

                    return;
                }

                $controller = new $class();

                if (!method_exists($controller, $methodName)) {
                    http_response_code(500);
                    if ($retorno_json) {
                        echo json_encode(['error' => 'Erro ao resolver rota']);
                    } else {
                        echo "Erro ao resolver rota";
                    }

                    return;
                }
                if ($jsonData) {
                    call_user_func_array([$controller, $methodName], [$jsonData]);
                } else {
                    call_user_func_array([$controller, $methodName], []);
                }
                return;
            }
        }
        http_response_code(401);
        if ($retorno_json) {
            echo json_encode(['error' => 'Página não encontrada']);
        } else {
            echo "Página não encontrada";
        }
    }
}
