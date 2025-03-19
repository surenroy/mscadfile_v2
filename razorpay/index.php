<?php
$requestUri = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
include_once '../config.php';


// Define routes
$routes = [
    '' => 'payment.php',
    'status' => 'status.php',
    'test' => 'test.php',

];



// Check if route exists
if (array_key_exists($requestUri, $routes)) {
    require $routes[$requestUri];
} else {
    header("Location: " . $site_url . "404.php");
    exit; 
}

