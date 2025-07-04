<?php
require '../config/config.php';
echo "<p> Testing the FetchCityData API Call </p>";

function fetchCityData($city = OPEN_WEATHER_DEFAULT_CITY, $limit = 1) {
    $url = OPEN_WEATHER_CITY_DETAILS . urlencode($city) . '&limit=' . urlencode($limit) . '&appid=' . OPEN_WEATHER_API_KEY;
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    print_r($data);
    if($data){
        $lat = $data[0]['lat'];
        $long = $data[0]['lon'];

        return ['lat' => $lat, 'lon' => $long,];
    } else {
        echo "<p> No location data found, check your spelling and country or state code and try again </p>";
        return null;
    }
}

function fetchLocationWeather($lat, $lon){
    echo"<h2>Here is the Lat and Long details</h2>";
    echo $lat . ' & ' . $lon; 
    $fetchWeatherData = OPEN_WEATHER_API_URL . 'lat=' . $lat . '&lon=' . $lon . '&units=metric&appid=' . OPEN_WEATHER_API_KEY;
    print($fetchWeatherData);
    $weatherResponse = file_get_contents($fetchWeatherData);
    $weatherData = json_decode($weatherResponse, true);
    print_r($weatherData);
    if ($weatherData) {
        $temp = $weatherData['main']['temp'];
        $feelsLike = $weatherData['main']['feels_like'];
        $tempMin = $weatherData['main']['temp_min'];
        $tempMax = $weatherData['main']['temp_max'];
        $humidity = $weatherData['main']['humidity'];
        $windSpeed = $weatherData['wind']['speed'];
        $windDirection = $weatherData['wind']['deg'];
        $visibility = $weatherData['visibility'];
        $sunrise = $weatherData['sys']['sunrise'];
        $sunset = $weatherData['sys']['sunset'];
        $description = $weatherData['weather'][0]['description'];
        $icon = $weatherData['weather'][0]['icon'];
        $iconUrl = "https://openweathermap.org/img/wn/" . $icon . "@2x.png";

        
        echo "<h2>Weather for " . $weatherData['name'] . "</h2>";
        echo "<p><strong>Type:</strong><img src='" . $iconUrl . "' alt='" . $icon . "'> " . $description . "</p>";
        echo "<p><strong>Temperature:</strong> " . $temp . "°C</p>";
        echo "<p><strong>Feels like:</strong> " . $feelsLike . "°C</p>";
        echo "<p><strong>Min/Max:</strong> " . $tempMin . "°C / " . $tempMax . "°C</p>";
        echo "<p><strong>Humidity:</strong> " . $humidity . "%</p>";
        echo "<p><strong>Wind:</strong> " . $windSpeed . " m/s at " . $windDirection . "°</p>";
        echo "<p><strong>Visibility:</strong> " . $visibility . " meters</p>";
        echo "<p><strong>Sunrise:</strong> " . date('H:i', $sunrise) . "</p>";
        echo "<p><strong>Sunset:</strong> " . date('H:i', $sunset) . "</p>";
    } else {
        echo "<p>No weather data found!</p>";
    }
}

$coordinates = fetchCityData();
if ($coordinates) {
    
    fetchLocationWeather($coordinates['lat'], $coordinates['lon']);
}
?>
