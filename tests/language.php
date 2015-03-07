<?php 

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use GoogleMapsGeocodeApi\Geocode;

$geo =  new Geocode("Kalverstraat 100, Amsterdam", ['language'=>'nl']);

echo "In Dutch: ".$geo->formatted().PHP_EOL;

