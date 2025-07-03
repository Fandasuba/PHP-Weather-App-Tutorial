<?php
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (preg_match('/^([A-Z_]+)=(.*)$/', $line, $matches)) {
            $key = $matches[1];
            $value = $matches[2];
            $_ENV[$key] = $value;
        }
    }
}

define('OPEN_WEATHER_API_KEY', $_ENV['OPEN_WEATHER_API_KEY']);
define('OPEN_WEATHER_API_URL', $_ENV['OPEN_WEATHER_API_URL']);
define('OPEN_WEATHER_DEFAULT_CITY', $_ENV['OPEN_WEATHER_DEFAULT_CITY']);
define('OPEN_WEATHER_CITY_DETAILS', $_ENV['OPEN_WEATHER_CITY_DETAILS']);
?>