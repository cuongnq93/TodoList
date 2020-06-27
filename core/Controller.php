<?php

namespace Core;

/**
 * Base controller
 */
abstract class Controller
{

    protected $routeParams = [];

    protected $view;
    protected $request;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->view = new View();
        $this->request = new Request();
    }

    /**
     * @param $name
     * @param $args
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $args)
    {
        $method = $name.'Action';

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $args);
        } else {
            throw new \Exception("Method $method not found in controller ".get_class($this));
        }
    }

    /**
     * @throws \Exception
     */
    public function assertPostOnly()
    {
        if (!$this->request->isPost()) {
            throw new \Exception('Forbidden Error');
        }
    }

    public function apiResponse($data = [])
    {
        http_response_code(200);
        header('Content-type: application/json; charset=utf-8');
        return json_encode([
            'status' => 'OK',
            'data'   => $data
        ]);
    }

    public function apiError($msg, $httpCode = 400)
    {
        http_response_code($httpCode);
        header('Content-type: application/json; charset=utf-8');
        return json_encode([
            'status' => 'ERROR',
            'msg'    => $msg
        ]);
    }

    public function setRouteParams($params)
    {
        $this->routeParams = $params;
        $this->request->setParams($this->requestParams());
    }

    protected function requestParams()
    {
        $params = $this->routeParams;
        unset($params['controller']);
        unset($params['action']);

        return $params;
    }
}