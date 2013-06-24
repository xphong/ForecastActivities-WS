<?php

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

function getWeather($lat, $long) {
    $json = file_get_contents("https://api.forecast.io/forecast/5fbb07cf1d7055ece87605c3f811b50b/$lat,$long");
    $data = json_decode($json, TRUE);

    $current = $data['currently'];

    return $current['summary'];
}

?>
