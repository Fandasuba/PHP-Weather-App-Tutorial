<?php
require_once '../config/config.php';

class WeatherApp {
    
    public function getCityCoordinates($city, $limit = 1) {
        $url = OPEN_WEATHER_CITY_DETAILS . urlencode($city) . '&limit=' . urlencode($limit) . '&appid=' . OPEN_WEATHER_API_KEY;
        
        if(empty($city)){
            return['error' => 'City name cannot be empty'];
        }
        
        $response = file_get_contents($url);
        if ($response === false) {
            return ['error' => 'Failed to connect to geocoding service. Either the link has changed, service has updated, or the server is down.'];
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['error' => 'Invalid response from geocoding service'];
        }

        if (empty($data)) {
            return ['error' => "City '$city' not found. Please check spelling and try again."];
        }

        return [
            'lat' => $data[0]['lat'],
            'lon' => $data[0]['lon'],
            'name' => $data[0]['name'],
            'country' => $data[0]['country']
        ];
    }
    
    public function getWeatherData($lat, $lon) {
        if(!is_numeric($lat) || !is_numeric($lon)){
            return ['error' => 'Invalid coordinates, please insert valid numerical coordinates. This maybe down to user input or the city coordinate converter not working.'];
        }
        $weatherUrl = OPEN_WEATHER_API_URL . 'lat=' . $lat . '&lon=' . $lon . '&units=metric&appid=' . OPEN_WEATHER_API_KEY;
        $response = file_get_contents($weatherUrl);
        if(!$response){
            return ['error' => 'Failed to connect to the weather report API.'];
        }
        $weatherData = json_decode($response, true);
        if(json_last_error() !== JSON_ERROR_NONE){
            return ['error' => 'Invalid response from weather service.'];
        }

        if (isset($weatherData['cod']) && $weatherData['cod'] !== 200) {
            return ['error' => 'Weather service error: ' . ($weatherData['message'] ?? 'Unknown error')];
        }
        
        if (!isset($weatherData['main']) || !isset($weatherData['weather'])) {
            return ['error' => 'Incomplete weather data received'];
        }
        
        return $weatherData;
    }
    
    
    public function displayWeather($weatherData) {
        if (isset($weatherData['error'])) {
            echo "<div style='color: red; border: 1px solid red; padding: 10px; margin: 10px 0;'>";
            echo "<strong>Error:</strong> " . $weatherData['error'];
            echo "</div>";
            return;
        }
        
        if (!$weatherData) {
            echo "<p style='color: red;'>No weather data available</p>";
            return;
        }
        
        echo "<h2>Success! Weather data exists</h2>";
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

        $windCompass = $this->getWindDirection($windDirection);
        $sunriseTime = date('H:i', $sunrise);
        $sunsetTime = date('H:i', $sunset);
        
        echo "<h2>Weather for " . $weatherData['name'] . "</h2>";
        echo "<p><img src='" . $iconUrl . "' alt='" . $icon . "'> " . $description . "</p>";
        echo "<p><strong>Temperature:</strong> " . round($temp, 1) . "°C</p>";
        echo "<p><strong>Feels like:</strong> " . round($feelsLike, 1) . "°C</p>";
        echo "<p><strong>Min/Max:</strong> " . round($tempMin, 1) . "°C / " . round($tempMax, 1) . "°C</p>";
        echo "<p><strong>Humidity:</strong> " . $humidity . "%</p>";
        echo "<p><strong>Wind:</strong> " . $windSpeed . " m/s " . $windCompass . " (" . $windDirection . "°)</p>";
        echo "<p><strong>Visibility:</strong> " . round($visibility / 1000, 1) . " km</p>";
        echo "<p><strong>Sunrise:</strong> " . $sunriseTime . "</p>";
        echo "<p><strong>Sunset:</strong> " . $sunsetTime . "</p>";
    }

private function getWindDirection($degrees) {
    $directions = [
        'N', 'NNE', 'NE', 'ENE',
        'E', 'ESE', 'SE', 'SSE', 
        'S', 'SSW', 'SW', 'WSW',
        'W', 'WNW', 'NW', 'NNW'
    ];
    
    $index = round($degrees / 22.5) % 16;
    return $directions[$index];
    
}
}
?>