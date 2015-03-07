<?php 

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use GoogleMapsGeocodeApi\Geocode;

echo Geocode::find("Kalverstraat 100, Amsterdam")->formatted();