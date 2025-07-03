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
}

$coordinates = fetchCityData();
if ($coordinates) {
    fetchLocationWeather($coordinates['lat'], $coordinates['lon']);
}

?>