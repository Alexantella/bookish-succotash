<?php

namespace Routes;

class Router
{
    private $routes = [];

    private $params = [];

    public function addRoutes($route, $method = 'get', $destination = null)
    {
        if ($destination !== null && !is_array($route)) {
            $route = [
                $method => [
                    $route => $destination
                ]
            ];
        }

        $this->routes = array_merge($this->routes, $route);
    }

    public function processRequest($requestedUrl = null)
    {

        if ($requestedUrl === null) {
            $uri = explode('?', $_SERVER["REQUEST_URI"]);
            $requestedUrl = urldecode(rtrim($uri[0], '/'));
        }

        $method = $this->getMethod();

        if (isset($this->routes[$method][$requestedUrl])) {
            $this->params = $this->prepareUrl($this->routes[$method][$requestedUrl]);
            return $this->execute();
        }

        foreach ($this->routes[$method] as $route => $uri) {
            if (strpos($route, ':') !== false) {
                $route = str_replace(':num', '([0-9]+)', $route);
            }

            if (preg_match('#^'.$route.'$#', $requestedUrl)) {
                if (strpos($uri, '$') !== false && strpos($route, '(') !== false) {
                    $uri = preg_replace('#^'.$route.'$#', $uri, $requestedUrl);
                }
                $this->params = $this->prepareUrl($uri);

                break;
            }
        }
        return $this->execute();
    }

    private function prepareUrl($url)
    {
        return preg_split('/\//', $url, -1, PREG_SPLIT_NO_EMPTY);
    }

    private function getMethod()
    {
        return strtolower($_SERVER["REQUEST_METHOD"]);
    }

    private function execute()
    {
        $controller = isset($this->params[0]) ? 'Api\\' . $this->params[0] : null;
        $action = isset($this->params[1]) ? $this->params[1] : null;
        $params = array_slice($this->params, 2);

        if(class_exists($controller) && $controller) {
            $entity = new $controller;

            if(method_exists($entity, $action) && $action) {
                return $entity->$action($params);
            } else {
                return 'Not Found';
            }
        } else {
            return 'Not Found';
        }
    }
}
