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
			),
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

 		//Waymark_Helper::debug($this, false);

		if(isset($this->data['query_overpass']) && isset($this->data['query_area'])) {
// 				$query_area_array = explode(',', $this->data['query_area']);
// 				$query_area_string = $query_area_array[0] . ',' . $query_area_array[1] . ',' . $query_area_array[2] . ',' . $query_area_array[3];

			//Build request
			$Request = new Waymark_Overpass_Request();							
			$Request->set_config('bounding_box', $this->data['query_area']);
			$Request->set_parameters(array(
				'data' => $this->data['query_overpass']
			));

			//Execute request
			$response_elements = $Request->get_processed_response();
	
			//Save response
			$this->data['query_result'] = json_encode($response_elements);
			$this->save_meta();						

			Waymark_JS::add_chunk('var waymark_query_data  = ' . json_encode($this->data) . ';');				
		}			

		echo Waymark_Helper::build_query_map_html();
	
		return parent::create_form();	
	}
}