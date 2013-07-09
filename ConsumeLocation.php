<?php
require('functions.php');
require('database.php');

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Forecast Activities</title>
        <script src="http://code.jquery.com/jquery-latest.min.js"></script>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4ommQ8l4W9-aQTNXmgF8G43Y-rvR4t1Y&sensor=true"></script>
        <script type="text/javascript">

        $(document).ready(function(){

                navigator.geolocation.getCurrentPosition(initialize);

                function initialize(position) {

                    var mapOptions = {
                        center: new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
                        zoom: 5,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };

                    var map = new google.maps.Map(document.getElementById("map-canvas"),
                        mapOptions);

                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
                        map: map,
                        title:""
                    });
                }
                google.maps.event.addDomListener(window, 'load', initialize);
            });

    </script>
    <style>
    #map-canvas
    {
        clear:both;
        float:left;
        height:250px;
        margin-right:15px;
        padding:25px;
        width:250px;
    }
    </style>
    </head>
    <body>
        <div id="map-canvas"></div>
        <div>
            <h1>Get Weather Summary From Address</h1>
            <form method="post" action="ConsumeLocation.php">
                <label>Address (City, Country)</label>
                <br />
                <input type="text" name="address" />
                <br /><br />
                <input type="submit" value="Submit" />
                <br /><br />
            </form>
        </div>

        <?php
        if ($_POST) {
            $address = $_POST["address"];

            if ((isset($address) && !empty($address))) {
                // get coordinates from inputted address
                $coordinates = getLatLong($address);
                $lat = $coordinates['lat'];
                $long = $coordinates['long'];

                // get weather using coordinates
                $weather_summary = getWeather($lat, $long);

                // output results
                echo "Weather Summary: $weather_summary<br />";
                echo "Latitude: $lat<br />";
                echo "Longitude: $long<br />";

                // get activities from database
                $activities = getActivities($weather_summary);

                // output activities
                echo "Activities: ";
                foreach ($activities as $activity) {
                    echo "<br />-$activity";
                }
            }
            else {
                echo "Please enter an address.";
            }
        }
        ?>


    </body>
</html>
