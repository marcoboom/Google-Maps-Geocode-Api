<?php 

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use GoogleMapsGeocodeApi\Geocode;

$geocode = new Geocode("Kalverstraat 100, Amsterdam");

echo "Lat: ".$geocode->lat();
echo "Lon: ".$geocode->lon();