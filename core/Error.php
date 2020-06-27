<?php

namespace Core;

class Error
{
    public function __construct(\Exception $e)
    {
        http_response_code($e->getCode());
        if (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            header('Content-type: application/json; charset=utf-8');
            echo json_encode([
                'status' => 'ERROR',
                'msg'    => $e->getMessage()
            ]);
        } else {
            echo $e->getMessage();
        }
    }
}