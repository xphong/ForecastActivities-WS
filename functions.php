<?php

// get latitude, longitude from address
function getLatLong($address) {
    if (!is_string($address))
        die("All Addresses must be passed as a string");
    $_url = sprintf('http://maps.google.com/maps?output=js&q=%s', rawurlencode($address));
    $_result = false;
    if ($_result = file_get_contents($_url)) {
        if (strpos($_result, 'errortips') > 1 || strpos($_result, 'Did you mean:') !== false)
            return false;
        preg_match('!center:\s*{lat:\s*(-?\d+\.\d+),lng:\s*(-?\d+\.\d+)}!U', $_result, $_match);
        $_coords['lat'] = $_match[1];
        $_coords['long'] = $_match[2];
    }
    return $_coords;
}

// get weather summary from latitude, longitude
function getWeather($lat, $long) {
    // retrieve weather from API
    $json = file_get_contents("https://api.forecast.io/forecast/5fbb07cf1d7055ece87605c3f811b50b/$lat,$long");
    // convert json return
    $data = json_decode($json, TRUE);

    $current = $data['currently'];
    
    // return weather summary
    return $current['summary'];
}

// get activities from database
function getActivities($weather) {
    $db = Database::getDB();
    // if weather is rainy
    if ($weather == "Drizzle" || $weather == "Rain" || $weather == "Light Rain") {
        $query = "SELECT * FROM activities WHERE weather = 'Rain'";
    }
    // if weather is cloudy
    else if ($weather == "Partly Cloudy" || $weather == "Mostly Cloudy" || $weather == "Overcast"){
        $query = "SELECT * FROM activities WHERE weather = 'Cloudy'";
        
    }
    // if weather is sunny
    else if ($weather == "Clear" || $weather == "Breezy") {
         $query = "SELECT * FROM activities WHERE weather = 'Sunny'";       
    }
    else {
        $query = "SELECT * FROM activities";
    }
    // database query
    $result = $db->query($query);
    $activities = array();
    foreach ($result as $row) {
        $activities[] = $row['activity'];
    }
    return $activities;
}

?>
