<?php
include 'vendor/autoload.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
use Routes\Router;

$routes = [
    'get' => [
        '/orders' => 'OrderController/index',
        '/order/:num' => 'OrderController/view/$1',
    ],
    'post' => [
        '/order' => 'OrderController/create',
    ],
    'delete' => [
        '/order/:num' => 'OrderController/destroy/$1',
    ],
];

$router = new Router;

$router->addRoutes($routes);

try {
    $response = $router->processRequest();
    echo json_encode($response);
} catch (Exception $e) {
    json_encode($e->getMessage());
}
