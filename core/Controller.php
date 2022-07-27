<?php

namespace core;

class Controller
{
    public function error()
    {
        $this->render('404');
    }

    public function render(string $view, array $data = [])
    {
        if (file_exists(ROOT . 'app/views/' . $view . '.php')) {
            extract($data);
            require ROOT . 'app/views/' . $view . '.php';
        } else {
            die('View not found');
        }
    }
}
