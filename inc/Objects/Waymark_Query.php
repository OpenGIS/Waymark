<?php

class Waymark_Query extends Waymark_Class {

	protected $parameters = [
		'query_area' => null,
		'query_overpass_request' => null,
// 			'query_overpass_response' => null,
		'query_cast_overlay' => null,
		'query_cast_marker_type' => null,												
		'query_cast_line_type' => null,
		'query_data' => null,			
	];
	
	private $inputs = [];
	private $input_groups = [
		'test1' => [
			'group_title' => 'Test 1',
		],
		'test2' => [
			'group_title' => 'Test 2',
		]
	];	
	
	function __construct($params_in = []) {
		parent::__construct($params_in);

 		$marker_types = Waymark_Helper::get_object_types('marker', 'marker_title', true);
 		$default_marker_type = array_keys($marker_types)[0];

		$line_types = Waymark_Helper::get_object_types('line', 'line_title', true);
		$default_line_type = array_keys($line_types)[0];
		
		//Area passed?
// 		if($query_area) {
// 			$default_query_area = $query_area;
// 		} else {
// 			$default_query_area = Waymark_Config::get_setting('query', 'defaults', 'query_area');
// 		}
		
		$this->inputs['query_area'] = array(
			'id' => 'query_area',
			'type' => 'textarea',				
			'title' => 'Query Area',
			'class' => 'waymark-hidden',
			'default' => null,
		);
		
		$this->inputs['query_overpass_request'] = array(
			'id' => 'query_overpass_request',
			'type' => 'textarea',				
			'tip' => 'OverpassQL Query.',
			'tip_link' => 'https://osm-queries.ldodds.com/tutorial/',				
// 			'group' => 'test1',
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
// 			'group' => 'test1',
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
// 			'group' => 'test2',
			'title' => 'Marker Type',
			'default' => $default_marker_type,
			'options' => Waymark_Helper::get_object_types('marker', 'marker_title', true)
		);
			
		$this->inputs['query_cast_line_type'] = array(
			'name' => 'query_cast_line_type',
			'id' => 'query_cast_line_type',
			'type' => 'select',				
			'tip' => 'Cast to Type',
// 			'group' => 'test2',
			'title' => 'Line Type',
			'default' => $default_line_type,
			'options' => Waymark_Helper::get_object_types('line', 'line_title', true)
		);
		
		//Execute?
		if($this->can_execute()) {
			$this->do_execute();
		}		
	}	

	function can_execute() {
		return 
			(! empty($this->parameters['query_overpass_request']))
			&&
			(! empty($this->parameters['query_area']))
		;
	}	
	
	function do_execute() {
		if(! $this->can_execute()) {
			return false;
		}	
		
		$request_string = stripslashes($this->parameters['query_overpass_request']);
		$request_string = html_entity_decode($request_string);

		//Build request
		$Request = new Waymark_Overpass_Request($this->parameters);							
		$Request->set_parameters(array(
			'data' => $request_string
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
	
	function create_form($data = []) {
		$form_type = 'add';
		if(sizeof($data)) {
			$form_type = 'edit';		
		}

		$out = '<div class="waymark-query-form waymark-query-' . $form_type . ' waymark-self-clear">' . "\n";
		
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

	function create_preview() {
		//Waymark Instance
		$data = [
			'hash' => 'query_preview',
			'add_class' => 'waymark-query-preview'
		];

		//Bounds
		$query_area = Waymark_Config::get_setting('query', 'defaults', 'query_area');
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
}