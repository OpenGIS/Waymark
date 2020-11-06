<?php

require_once('Waymark_Object.php');
	
class Waymark_Map extends Waymark_Object {
	
	public $post_type = 'waymark_map';
	
	function __construct($post_id = null) {
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

		//Meta
		$map_meta = Waymark_Config::get_item('meta', 'inputs');
		if($map_meta && sizeof($map_meta)) {
			$map_meta = Waymark_Helper::convert_values_to_single_value($map_meta);
			$map_meta = Waymark_Helper::convert_single_value_to_array($map_meta);
		
			//Waymark_Helper::debug($map_meta, false);
		
			foreach($map_meta as $meta) {
				$meta_key = Waymark_Helper::make_key($meta['meta_title'], 'map');

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
			}				
		} else {
		}
	
		parent::__construct($post_id);
	}		
}