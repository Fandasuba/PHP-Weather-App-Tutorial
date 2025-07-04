<?php
require_once '../src/WeatherApp.php';

echo "<h1>Weather App Test</h1>";

$weatherApp = new WeatherApp();
$coordinates = $weatherApp->getCityCoordinates('Manchester');
if (isset($coordinates['error'])) {
    echo "<p style='color: red;'>Error: " . $coordinates['error'] . "</p>";
} else {
    echo "<p style='color: green;'>Found coordinates for " . $coordinates['name'] . "</p>";
    
    
    $weatherData = $weatherApp->getWeatherData($coordinates['lat'], $coordinates['lon']);
    
    $weatherApp->displayWeather($weatherData);
}
?>