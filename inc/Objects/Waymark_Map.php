<?php

require_once('Waymark_Object.php');
	
class Waymark_Map extends Waymark_Object {
	
	public $post_type = 'waymark_map';
	public $Queries = [];
	
	function __construct($post_id = null) {
		//Set groups
		$this->parameter_groups = Waymark_Helper::get_meta_groups();

		//Map Data
		$this->parameters['map_data'] = array(
			'input_types' => array('meta'),
			'name' => 'map_data',
			'id' => 'map_data',
			'type' => 'textarea',				
			'group' => '',
			'title' => 'Map Data',
			'class' => 'waymark-hidden'
		);
		$this->parameters['map_data_bounds'] = array(
			'input_types' => array('meta'),
			'name' => 'map_data_bounds',
			'id' => 'map_data_bounds',
			'type' => 'textarea',				
//			'tip' => '',
			'group' => '',
			'title' => 'Map Data Bounds',
			'class' => 'waymark-hidden'
		);
				
		//If we have Map Meta
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
		}
		
		parent::__construct($post_id);

		//Queries
		if(Waymark_Config::get_setting('query', 'features', 'enable_taxonomy')) {
			$query_taxonomies = get_the_terms($post_id, 'waymark_query');
			if(is_array($query_taxonomies)) {
				foreach($query_taxonomies as $query_tax) {
					//Meta available?
					$query_meta = get_term_meta($query_tax->term_id);
					$query_meta = Waymark_Helper::flatten_meta($query_meta);
								
					if(sizeof($query_meta)) {
						$this->Queries[] = new Waymark_Query(array_merge($query_meta, [
							'query_area_bounds' => $this->get_data_item('map_data_bounds')
						]));
					}		
				}
			}	
		}	
	}		
}