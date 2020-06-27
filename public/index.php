<?php
require dirname(__DIR__) . '/vendor/autoload.php';

$routes = include(dirname(__DIR__) . '/app/routes.php');

/**
 * App
 */
$app = new App\App($routes);

try {
    $app->run();
} catch (Exception $e) {
    new Core\Error($e);
}