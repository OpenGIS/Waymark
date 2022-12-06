<?php

class Waymark_Meta {
	
	//private $Waymark_Object;
	private $current_screen;
	
	function __construct() {
		add_action('current_screen', array($this, 'current_screen'));	

		add_action('post_edit_form_tag', array($this, 'add_post_enctype'));
		add_action('post_updated', array($this, 'post_updated'), 10, 2);			
	}
	
	function current_screen() {

		if(function_exists('get_current_screen')) {  
			$this->current_screen = get_current_screen();
			
			switch($this->current_screen->post_type) {
				//Map
				case 'waymark_map' :									
					add_meta_box('waymark_map_meta', esc_html__('Map Editor', 'waymark'), array($this, 'get_map_form'), 'waymark_map', 'normal', 'high');			
			
					if(Waymark_Config::get_setting('query', 'features', 'enable_map')) {
						add_meta_box('waymark_map_queries', __('Map Queries', 'waymark'), array($this, 'map_queries_content'), 'waymark_map', 'side', 'default');			

						add_meta_box('tax_queries_content', __('Tax Queries', 'waymark'), array($this, 'tax_queries_content'), 'waymark_map', 'side', 'default');			
					}
					
					break;
// 
// 				Query
// 				case 'waymark_query' :									
// 					add_meta_box('waymark_query_meta', 'Query', array($this, 'get_query_form'), 'waymark_query', 'normal', 'high');			
// 
// 					break;
			}		
		}
		
		add_meta_box('waymark_map_shortcode', __('Shortcode', 'waymark'), array($this, 'map_shortcode_content'), 'waymark_map', 'side', 'default');			
		add_meta_box('waymark_map_export', __('Export', 'waymark'), array($this, 'map_export_content'), 'waymark_map', 'side', 'default');			
		add_meta_box('waymark_map_help', __('Help', 'waymark'), array($this, 'map_help_content'), 'waymark_map', 'side', 'default');			
	}	

	function add_post_enctype() {
		global $post;

		if(in_array($post->post_type, array('waymark_map'))) {
			echo ' enctype="multipart/form-data"';			
		}
	}	

	/**
	 * ===========================================
	 * =============== SAVE POST =================
	 * ===========================================
	 */	
	function post_updated() {
		global $post;
		
		if(is_object($post) && ! (wp_is_post_revision($post->ID) || wp_is_post_autosave($post->ID))) {
			switch($post->post_type) {
				// ============ MAP ============
				
				case 'waymark_map' :									
					$Map = new Waymark_Map;
					$Map->set_data($_POST);				
					$Map->save_meta($post->ID);
					
					//Queries?
					if(isset($_POST['map_queries']) && is_array($_POST['map_queries'])) {
						$map_queries = [];

						foreach($_POST['map_queries'] as $query_data) {							
							$Query = new Waymark_Query($query_data);		
							
							$map_queries[] = $Query->get_request_meta();
						}

						update_post_meta($post->ID, 'waymark_map_queries', json_encode($map_queries));
					}
					
					break;			
			}			
		}
	}

	function map_export_content() {	
		global $post;
		
		$Map = new Waymark_Map($post->ID);
		
		$has_features = array_key_exists('map_data', $Map->data) && Waymark_GeoJSON::get_feature_count(json_decode($Map->data['map_data'], null, 512, JSON_OBJECT_AS_ARRAY));
		if($has_features) {			
			echo '<a data-title="' . esc_attr__('Download the Overlays added to this Map in the selected format.', 'waymark') . '" href="#" onclick="return false;" class="waymark-tooltip">?</a>';
			echo Waymark_Helper::map_export_html($Map);
		}
	}
	
	function map_shortcode_content() {	
		global $post;
		
		//Shortcode output
		echo '<a data-title="' . esc_attr__('Add this Map to your content with this Shortcode. Click here for more details.', 'waymark') . '" href="' . Waymark_Helper::site_url('docs/shortcodes') . '" class="waymark-tooltip">?</a>';
		echo '<input type="text" value="[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;' . $post->ID . '&quot;]" />';
		//echo '<p>' . sprintf(__('Add this Map to your content with this <a href="%s">Shortcode</a>.', 'waymark'), ) . '</p>';
	}

	function map_help_content() {	
		//Required the PHP EXIF extension
		if(! function_exists('exif_read_data')) {
			echo '<p><b>' . esc_html__('Image Location Detection Not Supported!', 'waymark') . '</b></p>';
			echo '<p>' . sprintf(__('Your hosting environment does not currently have the <a href="%s">PHP EXIF Extension</a> enabled, which is required to read Image location metadata. Try asking your host to enable it.', 'waymark'), 'https://www.php.net/manual/en/book.exif.php') . '</p>';		
		}	

		echo '<p><b>' . esc_html__('Read from File', 'waymark') . '</b></p>';
		echo '<p>' . esc_html__('You can read Lines and Markers from GPX, KML and GeoJSON files (most mapping apps will be able to export to one of these).', 'waymark') . '</p>';

		echo '<p><b>' . esc_html__('Types', 'waymark') . '</b></p>';
		echo '<p>' . sprintf(__('Types allow you to control how Overlays (Markers, Lines and Shapes) are displayed on the Map. Types can be customised in <a href="%s" target="_blank">Waymark &gt; Settings</a>.', 'waymark'), admin_url('edit.php?post_type=waymark_map&page=waymark-settings&tab=markers')) . '</p>';		

		echo '<p><b>' . esc_html__('Basemap', 'waymark') . '</b></p>';
		echo '<p>' . sprintf(__('Add and edit Basemaps in <a href="%s" target="_blank">Waymark &gt; Settings</a>. The first listed will be used as the default, unless specified in the shortcode like this: %s', 'waymark'), admin_url('edit.php?post_type=waymark_map&page=waymark-settings&tab=tiles'), '<code>[Waymark map_id="1234" basemap="Basemap Name"]</code>') . '</p>';		

		echo '<p><a class="button" href="' . Waymark_Helper::site_url('docs') . '" target="_blank">' . esc_html__('Read the Docs', 'waymark') . ' &raquo;</a></p>';

		echo '<p>&nbsp;</p>';
	}

	/**
	 * ===========================================
	 * =============== MAP EDITOR ================
	 * ===========================================
	 */	
	function get_map_form($post) {	
		//WP Media Library
		wp_enqueue_media();
		
		//WP TinyMCE
		wp_enqueue_editor();
		
		$data = Waymark_Helper::flatten_meta(get_post_meta($post->ID));
								
		// ====== Waymark Instance ======

		$instance_data = [
			'type' => 'editor'
		];

		//Set basemap
		if($editor_basemap = Waymark_Config::get_setting('misc', 'editor_options', 'editor_basemap')) {
			$instance_data['basemap'] = $editor_basemap;		
		}

		$Waymark_JS = new Waymark_Instance($instance_data);
		$Waymark_JS->add_js();
		echo $Waymark_JS->get_html();	

		//Map Data (GeoJSON) exists?
		if(array_key_exists('waymark_map_data', $data)) {
			$Waymark_JS->load_json($data['waymark_map_data']);			
		}


		// ==============================

				
		//Create Feed meta input
		$Map = new Waymark_Map($post->ID);		
		$Map->set_data($data);
		$Map->set_input_type('meta');
		echo $Map->create_form();		

// 		Waymark_Helper::debug($Map);

		//Queries data?
// 		if(Waymark_Config::get_setting('query', 'features', 'enable_taxonomy')) {
// 			foreach($Map->Queries as $Query) {
// 				if($query_data = $Query->get_parameter('query_data')) {
// 					$Waymark_JS->load_json($query_data, 'query_data');								
// 				}
// 			}
// 		}
		
		echo '<p>' . sprintf(__('You can manage Meta fields in <a href="%s">Settings</a>.', 'waymark'), admin_url('edit.php?post_type=waymark_map&page=waymark-settings&tab=meta')) . '</p>';		
	}

	/**
	 * ===========================================
	 * ============== MAP QUERIES ================
	 * ===========================================
	 */	
	function map_queries_content() {
		global $post;
		
		//Get for Map
		$meta_queries = get_post_meta($post->ID, 'waymark_map_queries', true);
		
		$meta_queries = json_decode($meta_queries, JSON_OBJECT_AS_ARRAY);		

		//Output
		$Query = new Waymark_Query();

		//Valid Queries		
		if(is_array($meta_queries)) {
			$Query->create_map_form($meta_queries);			
		//Blank
		} else {
			$Query->create_map_form();		
		}
	}	

	/**
	 * ===========================================
	 * ============== TAX QUERIES ================
	 * ===========================================
	 */	
	function tax_queries_content() {
		global $post;
		
		//Create Feed meta input
		$Map = new Waymark_Map($post->ID);		

// 		Waymark_Helper::debug($Map);

		//Queries data?
		if(Waymark_Config::get_setting('query', 'features', 'enable_taxonomy')) {
			$tax_queries = [];
			if(sizeof($Map->Queries) && is_array($Map->Queries)) {
				foreach($Map->Queries as $Query) {
					$tax_queries[] = $Query->get_request_meta();				
				}
				$Query->create_map_form($tax_queries, 'tax_queries');									
			}
		}
	}		
}
new Waymark_Meta;
