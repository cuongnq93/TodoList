<?php

namespace App;

use Core\Router;

class App
{
    protected $router;

    public function __construct($routes)
    {
        $this->router = new Router($routes);
    }

    /**
     * @throws \Exception
     */
    public function run()
    {
        $this->router->dispatch($_SERVER['QUERY_STRING']);
    }
}