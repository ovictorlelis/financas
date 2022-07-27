<?php

namespace core;

class Request
{

    public static function url()
    {
        $url = filter_input(INPUT_GET, 'request') ?? '';
        $url = str_replace(getenv('APP_DIR'), '', $url);
        return '/' . $url;
    }

    public static function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}
