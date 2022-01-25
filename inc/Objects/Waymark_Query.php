<?php

class Waymark_Query {

	private $parameters = [];
	
	function __construct($params_in = []) {
		$this->parameters = [
			'query_area' => null,
			'query_overpass_request' => null,
			'query_overpass_response' => null,
			'query_cast_overlay' => null,
			'query_cast_marker_type' => null,												
			'query_cast_line_type' => null,
			'query_data' => null,			
		];
		
		//Set passed params
		foreach($params_in as $key => $value) {
			if(is_string($value)) {
				$this->set_parameter($key, $value);			
			}
		}
		
		//Execute?
		if($this->can_execute()) {
			$this->do_execute();
		}
	}	

	function get_parameter($key) {
		if(array_key_exists($key, $this->parameters)) {
			return $this->parameters[$key];
		} else {
			return false;
		}
	}	
	
	function set_parameter($key, $value) {
		$this->parameters[$key] = $value;
	}	
	
	function can_execute() {
		return 
			(! is_null($this->parameters['query_overpass_request']))
			&&
			(! is_null($this->parameters['query_area']))
		;
	}	
	
	function do_execute() {
		if(! $this->can_execute()) {
			return false;
		}	


		//Build request
		$Request = new Waymark_Overpass_Request($this->parameters);							
		$Request->set_parameters(array(
			'data' => html_entity_decode($this->parameters['query_overpass_request'])
		));

		//Execute request
		$response = $Request->get_processed_response();
		
		//Valid response
		if(is_array($response) && array_key_exists('status', $response)) {
			switch($response['status']) {
				case 'success' :
					if(array_key_exists('query_data', $response)) {
						$this->set_parameter('query_data', $response['query_data']);											
					}					
				
					break;
			}		

			//Message?
			if(array_key_exists('message', $response)) {
				$class = '';
				if($response['status'] == 'success') {
					$class .= ' notice-success';
				} elseif($response['status'] == 'error') {
					$class .= ' notice-error';						
				}

				echo '<div class="notice' . $class . '">' . "\n";
				echo '	<p>' . $response['message'] . '</p>' . "\n";
				echo '</div>' . "\n";
			}
		}
		




		//Raw Output
// 		if(array_key_exists('raw', $response)) {
// 			$this->set_data_item('query_overpass_response', $response['raw']);
// 		}



//					Waymark_JS::add_call('Waymark_Map_Viewer.load_json(' . $this->parameters['query_data'] . ', false);');											
//				}
//			}
			//$this->save_meta();
// 		}		
	}
	
// 	function create_form() {
// 		if(! is_admin()) {
// 			return;
// 		}
// 
// 		if(isset($this->parameters['query_area'])) {
// 			$query_area_array = explode(',', $this->parameters['query_area']);
// 		//No data (yet)
// 		} else {
// 			$query_area_array = explode(',', Waymark_Config::get_setting('query', 'defaults', 'query_area'));		
// 		}
// 
// 		//Area
// 		$query_leaflet_string = '[[' . $query_area_array[1] . ',' . $query_area_array[0] . '],[' . $query_area_array[3] . ',' . $query_area_array[2] . ']]';
// 
// 		//Bounding Box
// 		Waymark_JS::add_call('
// 	//Query
// 	var bounds = ' . $query_leaflet_string . ';
// 	var rectangle = L.rectangle(bounds, {
// 		color: "#ff7800",
// 		fill: 0,
// 		weight: 1
// 	}).addTo(Waymark_Map_Viewer.map);
// 	rectangle.enableEdit();
// 	Waymark_Map_Viewer.map.on(\'editable:vertex:dragend\', function(e) {
// 		jQuery(\'#query_area\').val(e.layer.getBounds().toBBoxString());
// 	});
// 	Waymark_Map_Viewer.map.fitBounds(bounds)');	
// 		
// 		echo Waymark_Helper::build_query_map_html();
// 
// 		return parent::create_form();	
// 	}
}