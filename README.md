## Google Maps Geocode API
Simple PHP wrapper for the Google Maps Geocode API

## Installation and Requirements

First, you'll need to require the package with Composer:

```bash
$ composer require marcoboom/google-maps-geocode-api
```

## Usage

```php
	
	$geocode = new GoogleMapsGeocodeApi\Geocode("Kalverstraat 100 Amsterdam");
	
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


```