<?php
	
class Waymark_Content {
	function __construct() {
		add_filter('the_content', array($this, 'the_content'));
		add_filter('the_excerpt', array($this, 'the_content'));
	}

	function the_content($content) {
		global $post;
			
		//Don't do anything if password required
		if(post_password_required()) {
			return $content;
		}
		
		//Only modify Map page		
		if($post->post_type != 'waymark_map') {
			return $content;		
		}

		//Map		
		$Map = new Waymark_Map($post->ID);
		$shortcode = '[' . Waymark_Config::get_item('shortcode');
		$shortcode .= ' map_id="' . $post->ID . '"';
		//Elevation?
		if(Waymark_Config::get_setting('misc', 'elevation_options', 'show_elevation') == '2') {
			$shortcode .= ' show_elevation="1"';		
		}
		$shortcode .= ']';
		$content = do_shortcode($shortcode);
		
		//Single Map page only
		if(is_single()) {
			//START Meta
			$map_meta = Waymark_Helper::get_map_meta($Map);
	
			//Display Collections?
			if(Waymark_Config::get_setting('misc', 'collection_options', 'link_from_maps')) {
				$collection_list = get_the_term_list($Map->post_id, 'waymark_collection', '', '<!--,-->, ');
				if($collection_list) {
			
					$meta_title = esc_html__('Collection', 'waymark-plugin');
// 					if(strpos($collection_list, '<!--,-->')) {
// 						$meta_title .= 's';
// 					}				
			
					$map_meta[] = array(
						'meta_key' => 'collection_list',
						'meta_title' => $meta_title,
						'meta_value' => $collection_list
					);
				}
			}
						
			//Add Export dropdown/link
			$has_features = array_key_exists('map_data', $Map->data) && Waymark_Helper::geojson_feature_count($Map->data['map_data']);
			if($has_features && Waymark_Config::get_setting('misc', 'map_options', 'allow_export') == true) {			
				$map_meta[] = array(
					'meta_key' => 'export_data',
					'meta_title' => esc_html__('Export', 'waymark-plugin'),
					'meta_value' => Waymark_Helper::map_export_html($Map),
					'meta_info' => '<a data-title="' . esc_attr__('This will download the Overlays currently displayed by the Map in the selected format.', 'waymark-plugin') . '" href="#" onclick="return false;" class="waymark-tooltip">?</a>',
				);
			}
					
			//Do we have something to display?
			if(sizeof($map_meta)) {
				$content .= Waymark_Helper::meta_table($map_meta, false);		
			}			
			//END Meta				
		}

		return $content;
	}	
}
new Waymark_Content;
