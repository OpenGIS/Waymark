<?php 

class Waymark_Class {

	protected $parameters = [];
	
	function __construct($params_in = []) {
		//Set passed params
		foreach($params_in as $key => $value) {
			//Only accept valid keys
			//and String values (set in Child class)
			if(array_key_exists($key, $this->parameters) && is_string($value)) {
				$this->set_parameter($key, $value);			
			}
		}
	}	

	function get_parameter($key = null) {
		if(! $key) {
			return $this->get_parameters();
		}
		
		if(array_key_exists($key, $this->parameters)) {
			if(method_exists($this, 'process_param_out')) {
				return $this->process_param_out($key, $this->parameters[$key]);		
			} else {
				return $this->parameters[$key];
			}
		} else {
			return false;
		}
	}	

	function get_parameters() {
		$out = [];
		
		foreach($this->parameters as $key => $value) {
			if(method_exists($this, 'process_param_out')) {
				$out[$key] = $this->process_param_out($key, $value);		
			} else {
				$out[$key] = $value;
			}		
		}
		
		return $out;
	}
	
	function set_parameter($key, $value) {
		if(method_exists($this, 'process_param_in')) {
			$this->parameters[$key] = $this->process_param_in($key, $value);		
		} else {
			$this->parameters[$key] = $value;		
		}
	}	
}