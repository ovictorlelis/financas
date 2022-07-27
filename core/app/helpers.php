<?php
define('ROOT', __DIR__ . '/../../');

function old(string $key = null)
{
  $data = new class
  {
    public function add()
    {
      $_SESSION['old'] = $_POST ?? [];
    }

    public function get(string $key)
    {
      return session()->get(['old', $key]);
    }
  };

  if ($key) {
    return $data->get($key);
  }

  return $data;
}

function session(mixed $key = null)
{
  $data = new class
  {
    public function add(mixed $key, string $content)
    {
      if (is_array($key)) {
        return $_SESSION[$key[0]][$key[1]] = $content;
      }

      return $_SESSION[$key] = $content;
    }

    public function has(mixed $key)
    {
      if (is_array($key)) {
        return isset($_SESSION[$key[0]][$key[1]]) ? true : false;
      }

      return isset($_SESSION[$key]) ? true : false;
    }

    public function show(mixed $key)
    {
      if (is_array($key)) {
        return isset($_SESSION[$key[0]][$key[1]]) ? $_SESSION[$key[0]][$key[1]] : '';
      }

      return isset($_SESSION[$key]) ? $_SESSION[$key] : '';
    }

    public function get(mixed $key)
    {
      if (is_array($key)) {
        $get = isset($_SESSION[$key[0]][$key[1]]) ? $_SESSION[$key[0]][$key[1]] : '';
        unset($_SESSION[$key[0]][$key[1]]);
      } else {
        $get = isset($_SESSION[$key]) ? $_SESSION[$key] : '';
        unset($_SESSION[$key]);
      }

      return $get;
    }
  };

  if ($key) {
    return $data->get($key);
  }

  return $data;
}

function render(string $include, array $data = [])
{
  if (file_exists(ROOT . "app/views/includes/{$include}.php")) {
    extract($data);
    require ROOT . "app/views/includes/{$include}.php";
  }
}

function active(string $route = '', string $class = 'active')
{
  $uri = str_replace(getenv('APP_DIR'), '', $_SERVER['REQUEST_URI']);
  $route = "/{$route}";

  str()->verifyLast($uri, '/') ? $uri = substr($uri, 0, -1) : '';
  str()->verifyLast($route, '/') ? $route = substr($route, 0, -1) : '';

  return $uri == $route && $_SERVER['REQUEST_METHOD'] == 'GET' ? $class : '';
}

function route(string $route = '')
{
  $data = new class
  {
    public function base()
    {
      $base = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://';
      $base .= $_SERVER['SERVER_NAME'];
      if ($_SERVER['SERVER_PORT'] != '80') {
        $base .= ':' . $_SERVER['SERVER_PORT'];
      }
      $base .= getenv('APP_DIR');

      return $base;
    }

    public function redirect(string $to = '')
    {
      $to = $to ? $to : '/';
      return header("Location: " . $this->base() . $to);
    }

    public function assets(string $path)
    {
      return $this->base() . "/assets" . $path;
    }
  };

  if ($route) {
    return $data->base() . $route;
  }

  return $data;
}

function str()
{
  $data = new class
  {
    public function slug(string $string, string $separator = '-')
    {
      $string = preg_replace('~[^\pL\d]+~u', $separator, $string);
      $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);
      $string = preg_replace('~[^-\w]+~', '', $string);
      $string = trim($string, $separator);
      $string = preg_replace('~-+~', $separator, $string);
      $string = strtolower($string);

      return $string;
    }

    public function verifyLast(string $string, string $last)
    {
      return substr($string, -1) == $last;
    }
  };

  return $data;
}
