<?php

class Waymark_HTTP {
	
	private $nonce_string = 'Waymark_Nonce';
	
	function __construct() {
		add_filter('query_vars', array($this, 'query_vars'));		
		add_action('template_redirect', array($this, 'template_redirect'));					
		
		//Setup AJAX
		Waymark_JS::add_chunk('//HTTP');					
		Waymark_JS::add_chunk('var waymark_http_endpoint = "' . Waymark_Helper::http_url() . '";');					
		Waymark_JS::add_chunk('var waymark_http_security = "' . wp_create_nonce($this->nonce_string) . '";');					
	}

	public function query_vars($vars) {
		$vars[] = 'waymark_http';
		
		return $vars;
	}

	public function template_redirect() {
		//If not Waymark HTTP request
		if(! get_query_var('waymark_http')) {
			//WP loads normally			
			return;
		}
			
		//Action
		if(array_key_exists('waymark_action', $_REQUEST)) {
			//Requires Map Data
			if(in_array($_REQUEST['waymark_action'], array('get_map_data'))) {
				//Do we have data?
				if(array_key_exists('map_id', $_REQUEST) && is_numeric($_REQUEST['map_id'])) {					
					//Valid Map
					if($Map = new Waymark_Map($_REQUEST['map_id'])) {
						if(isset($Map->data['map_data']) && ! empty($Map->data['map_data'])) {
							//Clean
							$map_data = Waymark_Helper::remove_unwanted_data_properties($Map->data['map_data']);
						//Invalid Map data
						} else {
							die("-1");
						}					
					//Invalid Map
					} else {
						die("-1");						
					}
				//Invalid Map ID
				} else {
					die("-1");											
				}

				//Gzip supported?
				if(function_exists('gzcompress') && ! in_array('ob_gzhandler', ob_list_handlers())) {
					ob_start("ob_gzhandler");							
				} else {
					ob_start();							
				}		

				//Cache
				header('Cache-control: public,max-age=' . DAY_IN_SECONDS);	
			
				switch($_REQUEST['waymark_action']) {
					// === AJAX Load ===
					case 'get_map_data' :
						//Security
						check_ajax_referer($this->nonce_string, 'waymark_security');	

						//Required data
						if(! isset($map_data) || ! isset($Map)) {
							die("-1");											
						}			

						//Modify
						if(Waymark_Config::get_setting('misc', 'collection_options', 'link_to_maps') == true && $_REQUEST['link_to_map'] == true) {
							$map_data = Waymark_Helper::add_map_link_to_description($Map->post_id, $Map->post_title, $map_data);								
						}
								
						//Type
						header('Content-Type: application/geo+json');						
					
						break;			
				}	

				//The content
				if(isset($map_data) && $map_data) {
					echo $map_data;	
				}

				//That's it, that's all...
				die();			
			}									
		}
	}
}

new Waymark_HTTP;
