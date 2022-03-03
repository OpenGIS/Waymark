<?php

class Waymark_Query extends Waymark_Class {

	protected $parameters = [
		'query_area_type' => null,
		'query_area_bounds' => null,
		'query_area_polygon' => null,
		'query_overpass_request' => null,
		'query_cast_overlay' => null,
		'query_cast_marker_type' => null,												
		'query_cast_line_type' => null,
		'query_data' => null,			
	];
	
	private $inputs = [];
	private $input_groups = [
// 		'test1' => [
// 			'group_title' => 'Test 1',
// 		],
// 		'test2' => [
// 			'group_title' => 'Test 2',
// 		]
	];	
	
	private $json_strip_chars = [
		'\"',
		'"',
		'{',
		'}',					
	];
		
	private $json_add_chars = [
		 '__quote__',
		 '__quote__',
		 '__lcurl__',
		 '__rcurl__',
	];	
	
	function __construct($params_in = [], $auto_execute = true) {
		parent::__construct($params_in);

 		$marker_types = Waymark_Helper::get_object_types('marker', 'marker_title', true);
 		$default_marker_type = array_keys($marker_types)[0];

		$line_types = Waymark_Helper::get_object_types('line', 'line_title', true);
		$default_line_type = array_keys($line_types)[0];
		
		$this->inputs['query_area_type'] = array(
			'id' => 'query_area_type',
			'type' => 'select',
			'options' => [
				'polygon' => __('Polygon', 'waymark'),				
				'bounds' => __('Bounds', 'waymark'),
//				'around' => __('Around', 'waymark')
			],
			'title' => 'Query Area Type',
			'default' => 'bounds',
		);

		$this->inputs['query_area_polygon'] = array(
			'id' => 'query_area_polygon',
			'type' => 'text',				
// 			'class' => 'waymark-hidden',
			'default' => null,
		);

		$this->inputs['query_area_bounds'] = array(
			'id' => 'query_area_bounds',
			'type' => 'text',				
// 			'class' => 'waymark-hidden',
			'default' => null,
		);
		
		$this->inputs['query_overpass_request'] = array(
			'id' => 'query_overpass_request',
			'type' => 'textarea',				
			'tip' => 'OverpassQL Query.',
			'tip_link' => 'https://osm-queries.ldodds.com/tutorial/',				
			'title' => 'Overpass QL Query',
			'default' => Waymark_Config::get_setting('query', 'defaults', 'query_overpass_request'),
			'output_processing' => array(
				'html_entity_decode($param_value)'
			)				
		);
			
		$this->inputs['query_cast_overlay'] = array(
			'id' => 'query_cast_overlay',
			'type' => 'select',				
			'tip' => 'Marker/Line/Shape',
			'title' => 'Overlay Type',
			'default' => Waymark_Config::get_setting('query', 'defaults', 'query_cast_overlay'),
			'options' => [
				'marker' => 'Marker',
				'line' => 'Line',
//					'shape' => 'Shape'										
			]
		);
			
		$this->inputs['query_cast_marker_type'] = array(
			'name' => 'query_cast_marker_type',
			'id' => 'query_cast_marker_type',
			'type' => 'select',				
			'tip' => 'Cast to Type',
			'title' => 'Marker Type',
			'default' => $default_marker_type,
			'options' => Waymark_Helper::get_object_types('marker', 'marker_title', true)
		);
			
		$this->inputs['query_cast_line_type'] = array(
			'name' => 'query_cast_line_type',
			'id' => 'query_cast_line_type',
			'type' => 'select',				
			'tip' => 'Cast to Type',
			'title' => 'Line Type',
			'default' => $default_line_type,
			'options' => Waymark_Helper::get_object_types('line', 'line_title', true)
		);
		
		//Execute?
		if($auto_execute && $this->can_execute()) {
			$this->do_execute();
		}		
	}	


	function process_param_in(string $key, $value = '') {
		switch($key) {
			//Area
			case 'query_area_polygon' :
			case 'query_area_bounds' :
			case 'query_overpass_request' :
				$value = str_replace($this->json_add_chars, $this->json_strip_chars, $value);

				break;
		}
		
		return $value;
	}
	
	function process_param_out(string $key, $value = '') {
		switch($key) {
			//Area
			case 'query_area_polygon' :
			case 'query_area_bounds' :
			case 'query_overpass_request' :

				$value = str_replace($this->json_strip_chars, $this->json_add_chars, $value);

				break;
		}
		
		return $value;
	}	

	function can_execute() {
		//No query?
		if(empty($this->get_parameter('query_overpass_request'))) {
			return false;
		}
		
		//Valid Query Area
		return is_array($this->get_query_area());
	}	
	
	function get_query_overpass() {
		$overpass_query = $this->get_parameter('query_overpass_request');

		if(! empty($overpass_query)) {
			//Remove comments & whitespace
			$overpass_query = preg_replace('/\/\/(.*)/', '', $overpass_query);
			$overpass_query = preg_replace('!/\*.*?\*/!s', '', $overpass_query);
			$overpass_query = preg_replace('/\n\s*\n/', "\n", $overpass_query);			
		}
		
		return $overpass_query;
	}
	
	function get_query_area() {
		switch($this->get_parameter('query_area_type')) {
			//Bounds
			case 'bounds' :
				if(empty($this->get_parameter('query_area_bounds'))) {
					return false;
				} else {
					return [
						'type' => $this->get_parameter('query_area_type'),
						'area' => $this->get_parameter('query_area_bounds')					
					];				
				}
				
				break;

			//Polygon
			case 'polygon' :
				if(empty($this->get_parameter('query_area_polygon'))) {
					return false;
				} else {
					return [
						'type' => $this->get_parameter('query_area_type'),
						'area' => $this->get_parameter('query_area_polygon')					
					];				
				}
				
				break;				
		}
		
		return false;
	}
	
	function do_execute() {
		if(! $this->can_execute()) {
			return false;
		}	
		
		$request_string = stripslashes($this->parameters['query_overpass_request']);
		$request_string = html_entity_decode($request_string);

		//Build request
		$Request = new Waymark_Overpass_Request([
			'Query' => $this
		]);							

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
// 			if(array_key_exists('message', $response)) {
// 				$class = '';
// 				if($response['status'] == 'success') {
// 					$class .= ' notice-success';
// 				} elseif($response['status'] == 'error') {
// 					$class .= ' notice-error';						
// 				}
// 
// 				echo '<div class="notice' . $class . '">' . "\n";
// 				echo '	<p>' . $response['message'] . '</p>' . "\n";
// 				echo '</div>' . "\n";
// 			}
		}
	}
	
	function create_tax_form($data = []) {
		$form_type = 'add';
		if(sizeof($data)) {
			$form_type = 'edit';		
		}

		$out = '<div class="waymark-form waymark-query-form waymark-tax-query waymark-query-' . $form_type . ' waymark-self-clear">' . "\n";
		
		//Add
		if(sizeof($data)) {
			$out .= Waymark_Input::create_parameter_groups($this->inputs, $this->input_groups, $data);				
		//Edit
		} else {
			$out .= Waymark_Input::create_parameter_groups($this->inputs, $this->input_groups);
		}

		$out .= '</div>' . "\n";

		echo $out;
	}

	function create_map_form(Array $data = []) {
		$out = '<div class="waymark-form waymark-query-form waymark-map-query waymark-self-clear">' . "\n";
		
		$out .= Waymark_Input::create_repeatable_parameter_groups('map_queries', $this->inputs, $this->input_groups, $data);				

		$out .= '</div>' . "\n";

		echo $out;
	}

	function create_preview() {
		//Waymark Instance
		$data = [
			'hash' => 'query_preview',
			'add_class' => 'waymark-query-preview'
		];

		//Bounds
		$query_area = Waymark_Config::get_setting('query', 'defaults', 'query_area_bounds');
		$query_area = explode(',', $query_area);
		$data['init_bounds'] = '[[' . $query_area[1] . ',' . $query_area[0] . '],[' . $query_area[3] . ',' . $query_area[2] . ']]';

		//Set basemap
		if($editor_basemap = Waymark_Config::get_setting('misc', 'editor_options', 'editor_basemap')) {
			$data['basemap'] = $editor_basemap;		
		}

		$Waymark_JS = new Waymark_Instance($data);
		$Waymark_JS->add_js();
		echo $Waymark_JS->get_html();	
	}
	
	function get_inputs() {
		return $this->inputs;
	}
		
	function get_request_meta() {
		$parameters = $this->get_parameters();
				
		unset($parameters['query_data']);
		
		return $parameters;	
	}
}