<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Product page loading...<br>";
flush(); // Force output

// Check if config.php or any other includes cause issues
if (!file_exists('config.php')) {
    die("config.php missing!");
}

include_once('config.php');

echo "Config file included successfully.<br>";
flush();
