<?php

require_once('Waymark_Object.php');
	
class Waymark_Query extends Waymark_Object {
	
	public $post_type = 'waymark_query';
	
	function __construct($post_id = null) {
		//Query Data
		$this->parameters = array(
			'query_area' => array(
				'input_types' => array('meta'),
				'name' => 'query_area',
				'id' => 'query_area',
				'type' => 'textarea',				
//				'tip' => 'Query Area.',
				'group' => '',
				'title' => 'query_area',
				'default' => Waymark_Config::get_setting('query', 'defaults', 'query_area'),
//				'class' => 'waymark-hidden'
			),
// 			'query_area_locked' => array(
// 				'name' => 'query_area_locked',
// 				'id' => 'query_area_locked',
// 				'type' => 'boolean',				
// //				'tip' => 'Query Area.',
// 				'group' => '',
// 				'title' => 'Lock Area',
// 				'default' => 1
// 			),					
			'query_area_ratio' => array(
				'input_types' => array('meta'),
				'name' => 'query_area_ratio',
				'id' => 'query_area_ratio',
				'type' => 'text',
				'group' => '',				
				'title' => 'query_area_ratio',
				'default' => Waymark_Config::get_setting('query', 'defaults', 'query_area_ratio'),
//				'class' => 'waymark-hidden'
			),				
			'query_overpass' => array(
				'input_types' => array('meta'),
				'name' => 'query_overpass',
				'id' => 'query_overpass',
				'type' => 'textarea',				
				'tip' => 'Overpass Turbo Query. {{bbox}} will be replaced by the Map area.',
				'group' => '',
				'title' => 'Overpass Turbo Query',
				'default' => Waymark_Config::get_setting('query', 'defaults', 'query_overpass'),
				'output_processing' => array(
					'html_entity_decode($param_value)'
				)				
			),
			//!!!
			'query_result' => array(
				'input_types' => array('meta'),
				'name' => 'query_result',
				'id' => 'query_result',
				'type' => 'textarea',				
				'group' => '',
				'title' => 'Query Result',
//				'class' => 'waymark-hidden'
			)			
		);

		parent::__construct($post_id);
	}		
	
	function create_form() {
		if(! is_admin()) {
			return;
		}
		
		//Already exists
		if(isset($this->data['query_overpass']) && isset($this->data['query_area'])) {
			$query_overpass = $this->data['query_overpass'];
			$query_area_array = explode(',', $this->data['query_area']);
		//Default
		} else {
			$query_overpass = Waymark_Config::get_setting('query', 'defaults', 'query_overpass');
			$query_area_array = explode(',', Waymark_Config::get_setting('query', 'defaults', 'query_area'));		
		}
		
// 		Waymark_Helper::debug($query_overpass, false);
// 		Waymark_Helper::debug($query_area_array, true);
				
		//Area
		$query_leaflet_string = '[[' . $query_area_array[1] . ',' . $query_area_array[0] . '],[' . $query_area_array[3] . ',' . $query_area_array[2] . ']]';
		$query_overpass_string = $query_area_array[1] . ',' . $query_area_array[0] . ',' . $query_area_array[3] . ',' . $query_area_array[2];

		//Bounding Box
		Waymark_JS::add_call('
	//Query
	var bounds = ' . $query_leaflet_string . ';
	var rectangle = L.rectangle(bounds, {
		color: "#ff7800",
		weight: 1
	}).addTo(Waymark_Map_Viewer.map);
	rectangle.enableEdit();
	Waymark_Map_Viewer.map.on(\'editable:vertex:dragend\', function(e) {
		jQuery(\'#query_area\').val(e.layer.getBounds().toBBoxString());
	});
	Waymark_Map_Viewer.map.fitBounds(bounds)');						

		//Build request
		$Request = new Waymark_Overpass_Request();							
		$Request->set_config('bounding_box', $query_overpass_string);
		$Request->set_parameters(array(
			'data' => html_entity_decode($query_overpass)
		));

		//Execute request
		$response = $Request->get_processed_response();

		//Markers
		if(array_key_exists('nodes', $response)) {
			Waymark_JS::add_call('Waymark_Map_Viewer.load_json(' . $response['nodes'] . ', false);');						
		}

		//Lines
		if(array_key_exists('ways', $response)) {
			Waymark_JS::add_call('Waymark_Map_Viewer.load_json(' . $response['ways'] . ', false);');						
		}			

		echo Waymark_Helper::build_query_map_html();
	
		return parent::create_form();	
	}
}