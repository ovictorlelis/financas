<?php

namespace core;

class RouterBase
{

    public function run(array $routes)
    {
        $method = Request::method();
        $url = Request::url();

        $controller = "core\\Controller";
        $action = "error";
        $args = [];

        if ($method == 'post') {
            old()->add();
        }

        if (isset($routes[$method])) {
            foreach ($routes[$method] as $route => $callback) {

                if (substr($url, -1) == '/' && $url != '/') {
                    $route .= '/';
                }

                $pattern = preg_replace('(\{[a-z0-9]{1,}\})', '([a-z0-9-]{1,})', $route);

                if (preg_match('#^(' . $pattern . ')*$#i', $url, $matches) === 1) {
                    array_shift($matches);
                    array_shift($matches);

                    $itens = array();
                    if (preg_match_all('(\{[a-z0-9]{1,}\})', $route, $m)) {
                        $itens = preg_replace('(\{|\})', '', $m[0]);
                    }

                    $args = array();
                    foreach ($matches as $key => $match) {
                        $args[$itens[$key]] = $match;
                    }

                    $callbackSplit = explode('@', $callback);
                    $controller = $callbackSplit[0];
                    $controller = "app\controllers\\$controller";

                    if (isset($callbackSplit[1])) {
                        $action = $callbackSplit[1];
                    }

                    break;
                }
            }
        }
        $args = (object) $args;
        $definedController = new $controller();
        $definedController->$action($args);
    }
}
