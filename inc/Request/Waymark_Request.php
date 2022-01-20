<?php
abstract class Waymark_Request {
	protected $config;
	protected $parameters;
	protected $migrate_parameters = array();
	protected $migrate_parameters_values = array();
	protected $request_type = false;
	protected $request_endpoint;
	private $request;
	private $response;
		
	public function get_request() {
		return $this->request;
	}

	public function get_response() {
		return $this->response;
	}
	
	public function get_request_type() {
		return $this->request_type;
	}

	public function get_config(string $key) {
		if(array_key_exists($key, $this->config)) {
			return $this->config[$key];		
		}
		
		return null;
	}

	private function set_request($request) {
		$this->request = $request;
	}

	private function set_response($response) {
		$this->response = $response;
	}
	
	public function set_config(string $key, string $value) {
		$this->config[$key] = $value;
	}
	
	/**
	 * Define abstract functions
	 */
	abstract function build_request_parameters(array $params);
	abstract function process_response($response);

	/**
	 * Set parameters
	 */
	function set_parameters($params_in) {
		foreach($params_in as $param_key => $param_value) {
			$this->parameters[$param_key] = $param_value;
		}
	}

	/**
	 * Migrate parameters?
	 */
	function migrate_parameters(array $params_in) {
		$params_out = array();
		
		//Set the options
		foreach($params_in as $param_key => $param_value) {
			//Are we migrating the parameter value?
			if(array_key_exists($param_key, $this->migrate_parameters_values)) {
				if(array_key_exists($param_value, $this->migrate_parameters_values[$param_key])) {
					$param_value = $this->migrate_parameters_values[$param_key][$param_value];					
				}
//				 else {
//					throw new Exception('The <code>' . $param_key . '</code> parameter does not allow this value.');
//				}
			}
			//Are we migrating this parameter?
			if(array_key_exists($param_key, $this->migrate_parameters)) {
				$param_key = $this->migrate_parameters[$param_key];
			}				
			$params_out[$param_key] = $param_value;
		}	
		
		return $params_out;	
	}

	/**
	 * Build request URL
	 */	
	function build_request(array $params_in) {
		$request = $this->request_endpoint . '?';
		$request .= http_build_query($params_in);
		
		//debug($request, false);
		
		return $request;
	}	

	/**
	 * Get the response
	 */
	function get_processed_response() {
		//Migrate params for this request
		$params_migrated = $this->migrate_parameters($this->parameters);
		
		//Get request params
		$request_params = $this->build_request_parameters($params_migrated);		
		
		//Build request
		$request = $this->build_request($request_params);
		$this->set_request($request);
		
		//Determine cache ID
		$cache_id = 'Request_' . Waymark_Cache::do_hash($request);

		//Cached response	
		//Don't use cache in debug mode
//		if((! Waymark_Helper::is_debug()) && $cached_response = Waymark_Cache::get_item($cache_id)) {			
		if($cached_response = Waymark_Cache::get_item($cache_id)) {			
			//Get raw response from cache
			$response_raw = $cached_response;
			
			//Process it
			$response_processed = $this->process_response($response_raw);		
		//No cache
		} else {
			$response_raw = $this->perform_request($request);		
								
			//Process response
			$response_processed = $this->process_response($response_raw);

			$cache_minutes = Waymark_Config::get_setting('query', 'performance', 'cache_minutes');
						
			//Insert into cache
			Waymark_Cache::set_item($cache_id, $response_raw, $cache_minutes);	
		}
		
		$this->set_response($response_raw);
								
		return $response_processed;
	}

	/**
	 * Run the request
	 */
	function perform_request($request) {

// 		Waymark_Helper::debug($request);

		return wp_remote_get($request);
	}
}