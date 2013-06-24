<?php
include('functions.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
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
                $coordinates = getLatLong($address);

                $lat = $coordinates['lat'];
                $long = $coordinates['long'];

                $weather_summary = getWeather($lat, $long);

                echo "Weather Summary: $weather_summary<br />";
                echo "Latitude: $lat<br />";
                echo "Longitude: $long<br />";
            } else {
                echo "Please enter an address.";
            }
        }
        ?>


    </body>
</html>
