<?php


class Waymark_Admin {

	private $current_screen;

	function __construct() {
		//Don't do anything if we're not logged in to the back-end
		if(! is_admin()) {
			return;
		}
		
		//Actions
		add_action('admin_init', array($this, 'admin_init'));
		add_action('admin_menu', array($this, 'menu_init'));			
		add_action('current_screen', array($this, 'current_screen'));	
    add_action('admin_notices', array($this, 'admin_notices'));
		//add_action('widgets_init', array($this, 'widgets_init'));	
		add_action('admin_action_waymark_duplicate_post', array($this, 'duplicate_post'));		
		add_action('manage_waymark_map_posts_custom_column' , array($this, 'map_posts_custom_column'), 10, 2);		
		add_action('template_redirect', array($this, 'redirect_view_to_edit'));
		
		//Filters
		add_filter('post_row_actions', array($this, 'edit_post_links'), 10, 2 );	
		add_filter('manage_edit-waymark_collection_columns', array($this, 'collection_taxonomy_columns'), 10, 3);		
		add_filter('manage_waymark_collection_custom_column' , array($this, 'collection_taxonomy_custom_column'), 10, 3);		
		add_filter('manage_waymark_map_posts_columns', array($this, 'map_posts_columns'));
		add_filter('wp_read_image_metadata', array($this, 'add_gps_exif'), 10, 3);
		add_filter('upload_mimes', array($this, 'upload_mimes'), 1);				
//		add_filter('wp_check_filetype_and_ext', array($this, 'wp_check_filetype_and_ext'), 10, 4);				
	}
	
	function admin_init() {
		require_once('Admin/Waymark_JS.php');			
		require_once('Admin/Waymark_AJAX.php');			
		require_once('Admin/Waymark_Meta.php');	

		//Add nonce
		Waymark_JS::add_chunk('//Admin');					
 		Waymark_JS::add_chunk('var waymark_multi_value_seperator  = "' . Waymark_Config::get_item('multi_value_seperator') . '"');	
		Waymark_JS::add_chunk('var waymark_settings  = ' . Waymark_Config::get_settings_js());			

 		//Debug Mode
 		if(Waymark_Config::get_setting('misc', 'advanced', 'debug_mode')) {
	 		Waymark_JS::add_call('jQuery("body").addClass("waymark-debug");');						 		
 		}				
	}
	
	function menu_init() {
		require_once('Admin/Waymark_Settings.php');		
		require_once('Admin/Waymark_CSS.php');					
		require_once('Admin/Waymark_Menu.php');	
	}

	function current_screen() {
		if(function_exists('get_current_screen')) {  
			$this->current_screen = get_current_screen();
			
			//Actions for specific admin pages
			switch($this->current_screen->base) {
				//Add / Edit Single
				case 'post-new' :
				case 'post' :
				
					break;	
			
				//Collections
				case 'term' :
				case 'edit-tags' :
					if($this->current_screen->taxonomy == 'waymark_collection') {
						Waymark_CSS::add_chunk('
							.inline-edit-col label:last-child,
							.form-field p { 
								display: none !important;
							}
						');												
					}
				
					break;												
			}
		}
	}
		
	function widgets_init() {
		require_once('Admin/Waymark_Widgets.php');			
		register_widget('Waymark_Meta_Widget');				
	}	

	function duplicate_post(){
		//Check for post ID
		if (! isset($_GET['post_id']) || ! is_numeric($_GET['post_id'])) {
			wp_die(__('Can not duplicate, no post to supplied!', 'waymark'));
		}
	 
		//Nonce verification
		if(! isset($_GET['duplicate_nonce']) || ! wp_verify_nonce($_GET['duplicate_nonce'], basename(__FILE__))) {			
			wp_die(__('Security verification error!', 'waymark'));
		}	 
	 
		$Object = new Waymark_Object($_GET['post_id']);
		$new_post_id = $Object->duplicate_post();
		
		wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
	}

	function edit_post_links($actions, $post) {		
		//Queries & Layout
		if(in_array($post->post_type, array('waymark_map'))) {
			//Add Duplicate Link
			$actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=waymark_duplicate_post&post_id=' . $post->ID, basename(__FILE__), 'duplicate_nonce') . '" title="' . esc_attr__('Duplicate this post', 'waymark') . '" rel="permalink">' . esc_html__('Duplicate', 'waymark') . '</a>';			
			
			//Remove Quick Edit
			unset($actions['inline hide-if-no-js']);			
		}

		return $actions;
	}	
	
	function collection_taxonomy_columns($columns) {
		unset($columns['slug']);
		unset($columns['description']);

	  $columns['shortcode'] = esc_html__('Shortcode', 'waymark');
		
		return $columns;
	}	

	function collection_taxonomy_custom_column($content, $column_name, $term_id) {
		switch($column_name) {
			case 'shortcode';

				//Shortcode output			
				echo '<input type="text" value="[' . Waymark_Config::get_item('shortcode') . ' collection_id=&quot;' . $term_id . '&quot;]" />';
				
				break;
		}
	}		

	function map_posts_columns($columns) {
	  $columns['shortcode'] = 'Shortcode';

		unset($columns['date']);
		$columns['date'] = esc_html__('Date', 'waymark');
	
	  return $columns;
	}
	
	function map_posts_custom_column($column, $post_id) {
		switch($column) {
			case 'shortcode';

				//Shortcode output			
				echo '<input type="text" value="[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;' . $post_id . '&quot;]" />';
				
				break;
		}
	}		

	function redirect_view_to_edit() {
	  global $post;

	  if(is_single() && $post->post_type == 'waymark_map') {
	    wp_redirect(admin_url('post.php?post=' . $post->ID . '&action=edit'), 301 );
	    exit;
	  }
	}	

	function admin_notices() {		
		if(function_exists('get_current_screen')) {  
			$title = '';
			$description = '';	
				
			//Collections List
			if($this->current_screen->base == 'edit-tags' && $this->current_screen->taxonomy == 'waymark_collection') {
				$title = esc_html__('Collections', 'waymark');
				$description = sprintf(__('Collections allow you to organise your Maps. Use the <a href="%s">Shortcode</a> to display all Maps in a Collection at once. <a href="%s" class="button waymark-right">Read the Docs &raquo;</a>', 'waymark'), Waymark_Helper::site_url('docs/shortcodes'), Waymark_Helper::site_url('docs/collections'));				
			}
					
			//Map Posts List
			if($this->current_screen->base == 'edit' && $this->current_screen->post_type == 'waymark_map') {
				$title = esc_html__('Maps', 'waymark');
				$description = sprintf(__('Create Maps here, then add them to your content using the <a href="%s">Shortcode</a>. <a href="%s" class="button waymark-right">Watch the Video &raquo;</a>', 'waymark'), Waymark_Helper::site_url('docs/shortcodes'), Waymark_Helper::site_url('docs/getting-started'));				
			}
			
			$map_posts = get_posts(array(
				'post_type' => 'waymark_map'
			));	

			//Map New/Edit Page (not Collections... not pages)
			if($this->current_screen->post_type == 'waymark_map' && ! $this->current_screen->taxonomy && strpos($this->current_screen->id, 'waymark_page') === false) {
				global $post;
				
				//Waymark_Helper::debug($current_screen, false);
				
				//Add
				if($this->current_screen->action == 'add') {
					//No Maps created yet					
					//if(true || sizeof($map_posts) == 0) {
					if(sizeof($map_posts) == 0) {
						$title = esc_html__('Creating a Map', 'waymark');
						$description = sprintf(__('<span class="waymark-lead">Use the Map <a href="%s">Editor</a> to place Markers, draw Lines/Shapes and display Photos. Each can be given a <a target="_blank" href="%s">Type</a>, title, description and an image.</span><br /><br />You can add the Map to your content using the %s <a href="%s">Shortcode</a>. <a class="button waymark-right" href="%s">Watch the Video &raquo;</a>', 'waymark'), Waymark_Helper::site_url('docs/editor'), Waymark_Helper::site_url('docs/types'), '<code style="font-size:10px">[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;' . $post->ID . '&quot;]</code>', Waymark_Helper::site_url('docs/shortcodes'),  Waymark_Helper::site_url('docs/editor'));
					}	else {
						$title = esc_html__('New Map', 'waymark');
						$description = sprintf(__('You can add the Map to your content using the %s <a href="%s">Shortcode</a>. <a class="button waymark-right" href="%s">Watch the Video &raquo;</a>', 'waymark'), '<code style="font-size:10px">[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;' . $post->ID . '&quot;]</code>', Waymark_Helper::site_url('docs/shortcodes'),  Waymark_Helper::site_url('docs/editor'));
					}
				} elseif($this->current_screen->base == 'post') {
					$title = esc_html__('Edit Map', 'waymark');
					$description = sprintf(__('You can add the Map to your content using the %s <a href="%s">Shortcode</a>. <a class="button waymark-right" href="%s">Watch the Video &raquo;</a>', 'waymark'), '<code style="font-size:10px">[' . Waymark_Config::get_item('shortcode') . ' map_id=&quot;' . $post->ID . '&quot;]</code>', Waymark_Helper::site_url('docs/shortcodes'),  Waymark_Helper::site_url('docs/editor'));
				}				
			}									
		}

		if($title || $description) {
			echo '<div id="waymark-admin-container" class="wrap">' . "\n";
			echo '	<div class="card">' . "\n";
			echo '		<h1>' . $title  . '</h1>' . "\n";												
			echo '		<p>' . $description .'</p>' . "\n";												
			echo '	</div>' . "\n";								
			echo '</div>' . "\n";											
		}		
	}

	//Thanks to https://kristarella.blog/2009/04/add-image-exif-metadata-to-wordpress/
	function add_gps_exif($meta, $file, $sourceImageType) {
		//Required the PHP EXIF extension
		if(! function_exists('exif_read_data')) {
			return $meta;			
		}
		
		$exif_data = exif_read_data($file);
				
		if(is_array($exif_data)) {
			//Latitude
			if(array_key_exists('GPSLatitude', $exif_data) && array_key_exists('GPSLatitudeRef', $exif_data)) {
				$meta['GPSLatitude'] = $exif_data['GPSLatitude'];
				$meta['GPSLatitudeRef'] = $exif_data['GPSLatitudeRef'];
				
				//Make decimal
				$meta['GPSLatitudeNum'] = Waymark_Helper::exif_gps_to_gps_float($exif_data['GPSLatitude'], $exif_data['GPSLatitudeRef']);
			}		
			
			//Longitude
			if(array_key_exists('GPSLongitude', $exif_data) && array_key_exists('GPSLongitudeRef', $exif_data)) {
				$meta['GPSLongitude'] = $exif_data['GPSLongitude'];
				$meta['GPSLongitudeRef'] = $exif_data['GPSLongitudeRef'];
				
				//Make decimal
				$meta['GPSLongitudeNum'] = Waymark_Helper::exif_gps_to_gps_float($exif_data['GPSLongitude'], $exif_data['GPSLongitudeRef']);
			}			
		}
		
		return $meta;		
	}	

	function upload_mimes($existing_mimes) {
		//Don't do this for admin pages not related to Waymark
		if($this->current_screen && $this->current_screen->post_type != 'waymark_map') {
			return $existing_mimes;
		}
	
		//Returning limited subset
		$upload_mimes = [];
		
		//Images only
		foreach($existing_mimes as $mime_key => $mime_string) {
			if(strpos($mime_string, 'image/') === 0) {
				$upload_mimes[$mime_key] = $mime_string;
			}
		} 

		//Files
		foreach(Waymark_Config::get_item('mimes', 'file') as $ext => $mime) {
			if(is_array($mime)) {
				$upload_mimes[$ext] = $mime[0];					
			} elseif(is_string($mime)) {
				$upload_mimes[$ext] = $mime;			
			}
		}

		return $upload_mimes;
	}

	function wp_check_filetype_and_ext( $check, $file, $filename, $mimes ) {
		//Check file type
		$filetype = wp_check_filetype($filename, Waymark_Config::get_item('mimes', 'file'));

		//File type we are interested in
		if(array_key_exists('type', $filetype) && $filetype['type']) {
			//Allow
			return array(
				'ext' => $filetype['ext'],
				'type' => $filetype['type'],
				'proper_filename' => $filename
			);
		}
	
		return $check;
	}
// 	function wp_check_filetype_and_ext( $check, $file, $filename, $mimes ) {
// 		//Check file type
// 		$filetype = wp_check_filetype($filename, Waymark_Config::get_item('mimes', 'file'));
// 
// 		//File type we are interested in
// 		if(array_key_exists('type', $filetype) && $filetype['type']) {
// 			//Allow
// 			return array(
// 				'ext' => $filetype['ext'],
// 				'type' => $filetype['type'],
// 				'proper_filename' => $filename
// 			);
// 		}
// 	
// 		return $check;
// 	}
}	
new Waymark_Admin;
