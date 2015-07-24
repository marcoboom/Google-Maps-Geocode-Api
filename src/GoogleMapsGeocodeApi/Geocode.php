<?php namespace GoogleMapsGeocodeApi;


use GuzzleHttp\Client as Guzzle;


/**
 * Geocode class.
 *
 * Use the Google Geocode API to find information about a address string
 * Such as Latitude, Longitude and Country code
 *
 * Api description: https://developers.google.com/maps/documentation/geocoding/#JSON
 *
 */
 
class Geocode {
	
	
	/**
	 * Baseurl of the Google API
	 * 
	 * @const string
	 */	 
	
	const BASEURL = 'https://maps.googleapis.com/maps/api/geocode/json';
	
	/**
	 * Placeholder for the JSON data fetched from the api
	 * 
	 * @var mixed
	 * @access protected
	 */
	 
	protected $data;
	
	 
	protected $address;
	
	
	/**
	 * The error code that the API gives when failes
	 * 
	 * @var mixed
	 * @access protected
	 */	
	
	protected $error;
		
	/**
	 * The parameters to add to the API request
	 * 
	 * @var mixed
	 * @access protected
	 */

	 
	protected $parameters = [
	
		'address'	=>	null,
		'key'		=>	null,
		'language'	=>	null,
		
	];
	
	/**
	 * The constructor directly calls the Google API by the address parameters
	 * 
	 * @access public
	 * @param string $address
	 * @return Geocode
	 */
	 
	 
	 
	public function __construct($address, array $options=array()) 
	{
		
		$options['address'] = $address;
		
		$this->set_options($options);
		
		$this->fetch();	
		
	}	
	
	/**
	 * Method to make an instance of the object wit ha static method
	 * 
	 * @access public
	 * @static
	 * @param string $address
	 * @return Geocode
	 */
	 
	 
	public static function find($address, array $options=array()) 
	{
		
		return new static($address, $options);
			
	}
	
	/**
	 * Determine if the request failed
	 * 
	 * @access public
	 * @return void
	 */
	 
	 
	public function failed() 
	{
		return !empty($this->error);
	}
	
	
	/**
	 * Get GEO info by __call method, where this method first checks if the request failed
	 * 
	 * @access public
	 * @param mixed $method
	 * @param mixed $arguments
	 * @return void
	 */
	 
	 
	public function __call($method, $arguments) {
		
		if ($this->failed()) {
			return null;
		}
		
		if (!method_exists($this, "_".$method)) {
			return null;
		}
		
		return call_user_func([$this, "_".$method]);
	}
	
	
	/**
	 * Set the options given in the constructor
	 * 
	 * @access protected
	 * @param mixed $options
	 * @return void
	 */
	 
	 
	protected function set_options($options) {
		
		foreach ($options as $key=>$value) {
						
			$this->parameters[$key] = $value;
			
		}
		
	}
	
	
	/**
	 * Get the error given by the API
	 * 
	 * @access public
	 * @return string|null
	 */
	 
	 
	public function error() 
	{
		
		return $this->error;	
		
	}
	
	
	/**
	 * Set the error given by the API
	 * 
	 * @access protected
	 * @param mixed $error
	 * @return void
	 */
	 
	 
	protected function set_error($error) 
	{
		
		$this->error = $error;	
		
	}
		
	
	/**
	 * Call the Google API and format the data or throw an exception when fails
	 * 
	 * @access public
	 * @return Geocode
	 */
	 
	 
	protected function fetch() 
	{
		$client 	= 	new Guzzle();
		$response 	= 	$client->get($this->get_url());
		
		if ($response->getStatusCode() != 200) {
			
			$this->set_error('REQUEST_FAILED');
			
			return $this;
			
		}
		
		$this->set_data(json_decode($response->getBody(), true));
		
		$this->validate_data();
		
		return $this;
	}
	
	
	/**
	 * Validate the status of the data given by the API
	 * If status is not OK, then set the error
	 * 
	 * @access protected
	 * @return bool
	 */
	 
	 
	protected function validate_data() 
	{
		
		if (!isset($this->data['status'])) {
			
			$this->set_error("REQUEST_FAILED");
			
			return false;
		}	
		
		if ($this->data['status'] !== 'OK') {
			
			$this->set_error($this->data['status']);
			
			return false;
		}
		
		return true;
		
	}
		
	
	/**
	 * Get the raw data returned by the API
	 * 
	 * @access public
	 * @return void
	 */
	 
	 
	public function get_data() 
	{
		
		return $this->data;
		
	}
	
	
	/**
	 * Save the raw data to $this->data
	 * 
	 * @access public
	 * @return void
	 */


	public function set_data($data) 
	{
		
		$this->data = $data;	
		
	}
	
	
	/**
	 * Get the url to call the api bases on the base url and the given address
	 * 
	 * @access public
	 * @return void
	 */


	protected function get_url() 
	{
		
		return static::BASEURL.'?'.$this->build_url();
		
	}
	
	
	/**
	 * Clean up the address string to send in correct format to the API
	 * 
	 * @access protected
	 * @return void
	 */
	 
	 
	protected function build_url() {
		$query = [];
		
		foreach ($this->parameters as $key=>$value) {
			
			if (empty($value)) {
				continue;
			}
			
			$query[$key] = urlencode(trim(str_replace(array("\n","\l","\r"), ", ", $value)));
			
		}
		
		return http_build_query($query);		
	}
	
	
	/**
	 * Find the element within address_components of the data array with the given type name and the given field name
	 * 
	 * @access protected
	 * @param string $type
	 * @param string $field (default: "long_name")
	 * @return string|int|null
	 */
	 
	 
	protected function find_element($type, $field="long_name") {
		
		foreach ($this->data['results'][0]['address_components'] as $component) {
			
			if (!isset($component['types'])) {
				continue;
			}
			
			if (in_array($type, $component['types'])) {
				
				return $component[$field];
					
			}
			
		}
		
		return null;
		
	}
	
	
	
	
	/**
	 * Get the latitude of the address
	 * 
	 * @access protected
	 * @return string
	 */
	 
	 
	protected function _lat() {
		
		return $this->data['results'][0]['geometry']['location']['lat'];
		
	}
	
	
	/**
	 * Get the longitude of the address
	 * 
	 * @access protected
	 * @return string
	 */
	
	
	protected function _lon() {
		
		return $this->data['results'][0]['geometry']['location']['lng'];
		
	}
	
	
	/**
	 * Get the latitude and longitude of the address and give it as an array
	 * 
	 * @access protected
	 * @return array
	 */
	
	protected function _latlon() {
		
		return ['lat'=>$this->_lat(), 'lon'=>$this->_lon()];
		
	}
	
	
	/**
	 * Get the housenumber of the address
	 * 
	 * @access protected
	 * @return int
	 */
	 
	 
	protected function _housenumber() {
		
		return $this->find_element('street_number');
		
	}
	
	
	/**
	 * Get the street of the address
	 * 
	 * @access protected
	 * @return string
	 */
	 
	
	protected function _street() {
		
		return $this->find_element('route');
		
	}
	
	
	/**
	 * Get the postcode of the address
	 * 
	 * @access protected
	 * @return string
	 */
	 
	
	protected function _postcode() {
		
		return $this->find_element('postal_code');
		
	}
		
	
	/**
	 * Get the city of the address
	 * 
	 * @access protected
	 * @return string
	 */
	 
	 
	public function _city() {
		
		return $this->find_element('locality');
		
	}
	
	
	/**
	 * Get the town of the address
	 * 
	 * @access protected
	 * @return string
	 */
	 
	
	protected function _town() {
		
		return $this->find_element('administrative_area_level_2');
		
	}
	
	
	/**
	 * Get the state of the address
	 * 
	 * @access protected
	 * @return string
	 */
	 
	 
	protected function _state() {
		
		return $this->find_element('administrative_area_level_1');
		
	}
	
	
	/**
	 * Get the country of the address
	 * 
	 * @access protected
	 * @return string
	 */
	 
	 
	protected function _country() {
		
		return $this->find_element('country');
		
	}
	
	
	/**
	 * Get the ISO country code of the address
	 * 
	 * @access protected
	 * @return string
	 */
	 
	
	protected function _country_code() {
		
		return $this->find_element('country', 'short_name');
		
	}
	
	
	
	/**
	 * Get the formatted string of the address
	 * 
	 * @access protected
	 * @return string
	 */
	 
	 
	protected function _formatted() 
	{
		
		return $this->data['results'][0]['formatted_address'];
		
	}

	
}	

