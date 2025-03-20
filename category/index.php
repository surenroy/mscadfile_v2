<?php
echo $requestUri = isset($_GET['url']) ? trim($_GET['url'], '/') : ''; // Trim slashes

include_once __DIR__ . '/../config.php';




// Define routes
$routes = [
    '' => 'category.php',
];


if (array_key_exists($requestUri, $routes)) {
    $filePath = __DIR__ . DIRECTORY_SEPARATOR . $routes[$requestUri]; // Safe path handling
    if (!file_exists($filePath)) {
        //die("<pre style='color:red;'>Error: File not found - " . htmlspecialchars($filePath) . "</pre>");
        header("Location: " . $site_url . "404.php");
        exit;
    }
    require $filePath;
} else {
    //die("<pre style='color:red;'>Invalid route: " . htmlspecialchars($requestUri) . "</pre><br><a href='$site_url'> Goto Home Page </a>" );
    header("Location: " . $site_url . "404.php");
    exit;
}
