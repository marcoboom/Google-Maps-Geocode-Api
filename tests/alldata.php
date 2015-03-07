<?php 

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use GoogleMapsGeocodeApi\Geocode;

$geo =  new Geocode("Kalverstraat 100, Amsterdam");

echo "Street: ".$geo->street().PHP_EOL;
echo "Housenumber: ".$geo->housenumber().PHP_EOL;
echo "Postcode: ".$geo->postcode().PHP_EOL;
echo "City: ".$geo->city().PHP_EOL;
echo "Town: ".$geo->town().PHP_EOL;
echo "Province: ".$geo->state().PHP_EOL;
echo "Country: ".$geo->country().PHP_EOL;
echo "Country code: ".$geo->country_code().PHP_EOL;
echo "Latitude: ".$geo->lat().PHP_EOL;
echo "Longitude: ".$geo->lon().PHP_EOL;
echo "Formatted address: ".$geo->formatted().PHP_EOL;

