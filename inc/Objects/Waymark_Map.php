<?php

require_once('Waymark_Object.php');
	
class Waymark_Map extends Waymark_Object {
	
	public $post_type = 'waymark_map';
	
	function __construct($post_id = null) {
		//Set groups
		$this->parameter_groups = Waymark_Helper::get_meta_groups();
	
		//Map Data
		$this->parameters['map_data'] = array(
			'input_types' => array('meta'),
			'name' => 'map_data',
			'id' => 'map_data',
			'type' => 'textarea',				
			'tip' => 'You are seeing the Map Data (in GeoJSON format) because you have the Waymark Debug Mode enabled. The amount of text shown here can get VERY large, so your browser might struggle to scroll through it. You can disable Debug Mode in Settings > Misc. > Advanced.',
			'group' => '',
			'title' => 'Map Data',
			'class' => 'waymark-hidden'
		);
		
		//Queries
		$Query = new Waymark_Query;		
		$this->parameters['map_queries'] = array(
			'input_types' => array('meta'),
			'name' => 'map_queries',
			'id' => 'map_queries',
			'type' => 'select_multi',				
// 			'tip' => '',
			'group' => '',
			'title' => 'Map Queries',
			'options' => $Query->get_list()
		);
		$this->parameters['map_queries_data'] = array(
			'input_types' => array('meta'),
			'name' => 'map_queries_data',
			'id' => 'map_queries_data',
			'type' => 'textarea',				
// 			'tip' => '',
			'group' => '',
			'title' => 'Map Queries Data'
		);		
		unset($Query);
		
		//Meta
		$map_meta = Waymark_Config::get_item('meta', 'inputs', true);
		if($map_meta && sizeof($map_meta)) {
		
			foreach($map_meta as $meta) {
				$meta_key = Waymark_Helper::make_key($meta['meta_title'], 'map');
								
				//Submissions
				if(($post_id === null) && (! is_admin()) && class_exists('Waymark_Submission')) {
					$Submission = new Waymark_Submission;
					
					//Meta feature not allowed for user
					if(! in_array('meta', $Submission->get_features())) {
						//Skip it
						continue;										
					}
				
					//Not allowed in submissions
					if(! $meta['meta_submission']) {
						//Skip it
						continue;					
					}
				}

				$this->parameters[$meta_key] = array(
					'input_types' => array('meta'),
					'group' => '',
					'name' => $meta_key,
					'id' => $meta_key,
					'default' => $meta['meta_default'],
					'title' => $meta['meta_title']
				);				

				//Do we have a type?
				if(isset($meta['meta_type'])) {
					$this->parameters[$meta_key]['type'] = $meta['meta_type'];				

					//Select?
					if(in_array($meta['meta_type'], array('select', 'select_multi')) && isset($meta['meta_options'])) {

						$this->parameters[$meta_key]['options'] = Waymark_Helper::comma_string_to_array($meta['meta_options']);
					
						//Ensure the default is keyified
						//Multi
						if(strpos($meta['meta_default'], ',')) {
							$default_exploded = explode(',', $meta['meta_default']);
							foreach($default_exploded as &$val) {
								$val = Waymark_Helper::make_key(trim($val));
							}
							$default_imploded = implode(',', $default_exploded);
						
							$this->parameters[$meta_key]['default'] = $default_imploded;
						//Single
						} else {
							$this->parameters[$meta_key]['default'] = Waymark_Helper::make_key(trim($meta['meta_default']));
						}
					}
				}	
						
				//Tip?
				if(isset($meta['meta_tip']) && ! empty($meta['meta_tip'])) {
					$this->parameters[$meta_key]['tip'] = $meta['meta_tip'];
				}

				//Group?
				if(isset($meta['meta_group'])) {
					$this->parameters[$meta_key]['group'] = $meta['meta_group'];
				}				
			}				
		} else {
		}
	
		parent::__construct($post_id);
		
		//Queries?
		if(array_key_exists('map_queries', $this->data)) {
			$map_queries = Waymark_Helper::array_string_to_array($this->data['map_queries']);
			$queries_data = [];

			foreach($map_queries as $query_id) {
				$Query = new Waymark_Query($query_id);						
				
				if($data = $Query->get_data_item('query_data')) {
					$queries_data[] = $data;							
				}
			}
			
			$this->set_data_item('map_queries_data', $queries_data);
		}
	}		
}