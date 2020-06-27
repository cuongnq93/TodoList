<?php

namespace Core;

class Request
{
    protected $requestMethod;
    protected $params = [];

    function __construct()
    {
        foreach ($_SERVER as $key => $value) {
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

    private function toCamelCase($string)
    {
        $result = strtolower($string);

        preg_match_all('/_[a-z]/', $result, $matches);

        foreach ($matches[0] as $match) {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }

        return $result;
    }

    /**
     * @return array|void
     */
    public function getBody()
    {
        $body = [];

        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }

    /**
     * @return array|void
     */
    public function getQuery()
    {
        $query = [];

        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $query[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $query;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function isGet()
    {
        return ($this->getRequestMethod() === 'GET');
    }

    public function isPost()
    {
        return ($this->getRequestMethod() === 'POST');
    }

    protected function getRequestMethod()
    {
        return $this->requestMethod;
    }
}