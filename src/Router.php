<?php
class Router {
    
    // Handles the url splitting to get the search city query, and handles the liunk having a .php issue.
    public static function handleRequest() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = ltrim($path, '/');
        $segments = explode('/', $path);
        
        echo "<h3>Debug: URL Parsing</h3>";
        echo "Full URL: " . $_SERVER['REQUEST_URI'] . "<br>";
        echo "Path: " . $path . "<br>";
        echo "Segments: ";
        print_r($segments);
        echo "<br><br>";
        
        
        if ($segments[0] === 'weather.php') {
            if (isset($segments[1]) && !empty($segments[1])) {
                // URL like /weather.php/london - SEO and grammticaly friendly city name.
                $city = ucfirst(strtolower($segments[1]));
                echo "<h1>Weather for " . htmlspecialchars($city) . "</h1>";
                self::displayWeatherForCity($city);
            } else {
                // default to secrets for testing. 
                echo "<h1>Weather Search</h1>";
                self::showWeatherSearch();
                echo "<hr>";
                echo "<h2>Current Weather for " . OPEN_WEATHER_DEFAULT_CITY . "</h2>";
                self::displayWeatherForCity(OPEN_WEATHER_DEFAULT_CITY);
            } // handles homepage implementation i want to do later on.
        } elseif ($segments[0] === '' || $segments[0] === 'index.php') {
            self::handleHomepage();
        } else {
            self::handle404();
        }
    }
    
    // Handles the weather api service handling now for page routing scripting.
    private static function displayWeatherForCity($city) {
        require_once '../src/WeatherApp.php';
        $weatherApp = new WeatherApp();
        $coordinates = $weatherApp->getCityCoordinates($city);
        
        if (isset($coordinates['error'])) {
            echo "<div style='color: red; padding: 10px; border: 1px solid red;'>";
            echo "Error: " . $coordinates['error'];
            echo "</div>";
            echo "<p><a href='/weather.php'>← Back to Weather Search</a></p>";
            return;
        }
        $weatherData = $weatherApp->getWeatherData($coordinates['lat'], $coordinates['lon']);
        $weatherApp->displayWeather($weatherData);
        
        echo "<p><a href='/weather.php'>← Search for another city</a></p>";
    }

    // Handles Weather Form
    private static function showWeatherSearch() {
        echo "<form method='GET' action='/weather.php'>";
        echo "<label for='city'>Enter a location:</label><br>";
        echo "<input type='text' id='city' name='city' placeholder='e.g. London, Birmingham'><br><br>";
        echo "<button type='submit'>Get Weather</button>";
        echo "</form>";
        
        
        if (isset($_GET['city']) && !empty($_GET['city'])) {
            $city = trim($_GET['city']);
            header("Location: /weather.php/" . urlencode(strtolower($city)));
            exit;
        }
    }
    
    private static function handleHomepage() {
        echo "<h1>Weather App Homepage</h1>";
        echo "<p>Welcome to the UK Weather App!</p>";
        echo "<p><a href='/weather.php'>Check Weather →</a></p>";
    }
    
    private static function handle404() {
        http_response_code(404);
        echo "<h1>Page Not Found</h1>";
        echo "<p>The page you're looking for doesn't exist.</p>";
        echo "<p><a href='/'>← Back to Homepage</a></p>";
    }
}
?>