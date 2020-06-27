<?php

namespace Core;

class Config
{
    protected $config;

    public function __construct()
    {
        $this->config = include(dirname(__DIR__) . '/app/config.php');;
    }

    public function dbConfig($key)
    {
        $key = 'db_'.$key;
        return (isset($this->config[$key])) ? $this->config[$key] : null;
    }
}