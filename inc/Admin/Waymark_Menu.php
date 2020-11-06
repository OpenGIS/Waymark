<?php

class Waymark_Menu {
	
	private $admin_url_request;
	private $menu_slug = 'edit.php?post_type=waymark_map';
	
	function __construct() {
		$this->admin_url_request = basename($_SERVER['REQUEST_URI']);
		    
		//Top-level
		add_menu_page(Waymark_Config::get_name(true), Waymark_Config::get_name(true), 'edit_posts', $this->menu_slug, '', 'none', 21);		

		//Maps
    add_submenu_page($this->menu_slug, esc_html__('Maps', 'waymark-plugin'), esc_html__('Maps', 'waymark-plugin'), 'edit_posts', 'edit.php?post_type=waymark_map'); 
    add_submenu_page($this->menu_slug, esc_html__('New Map', 'waymark-plugin'), esc_html__('New Map', 'waymark-plugin'), 'edit_posts', 'post-new.php?post_type=waymark_map'); 

		//Collections
    add_submenu_page($this->menu_slug, esc_html__('Collections', 'waymark-plugin'), esc_html__('Collections', 'waymark-plugin'), 'manage_categories', 'edit-tags.php?taxonomy=waymark_collection&post_type=waymark_map'); 

		//Help
		add_submenu_page($this->menu_slug, esc_html__('Help', 'waymark-plugin'), esc_html__('Help', 'waymark-plugin'), 'edit_posts', 'waymark-help', array($this, 'help_page_content'));
	
		//Settings
		add_submenu_page($this->menu_slug, esc_html__('Settings', 'waymark-plugin'), esc_html__('Settings', 'waymark-plugin'), 'manage_options', 'waymark-settings', array(new Waymark_Settings, 'content_admin_page'));					
		
		add_action('admin_menu', array($this, 'modify_menu'), 1000);
		
			Waymark_CSS::add_chunk('
.wp-has-submenu.toplevel_page_waymark-top ul.wp-submenu li.wp-first-item {
	display: none;
}
			');					
	}
	
// 	function modify_menu() {
//     global $menu, $submenu, $pagenow;   
// 
// 		//Waymark menu
// 		foreach($menu  as &$m) {
// 			if($m[2] == $this->menu_slug) {
// 				if($pagenow == 'post.php' && array_key_exists('post', $_GET))  {
// 					$post_type = get_post_type($_GET['post']);
// 					if(Waymark_Config::is_custom_type($post_type)) {
// 						$m[4] .= ' wp-has-current-submenu';						
// 					}
// 				}
// 			}
// 		}
// 		
// 		//Waymark sub menu
// 		if(array_key_exists($this->menu_slug, $submenu)) {	
// 			foreach($submenu[$this->menu_slug] as &$sub_menu) {					
// 				//Hide New Type links
// 				if(in_array($sub_menu[0], array('New Map'))) {
// 					$sub_menu[4] = 'hidden';
// 				}					
// 				
// 				//Make "Object" link active when adding new
// 				if($sub_menu[0] == 'Maps' && $this->admin_url_request == 'post-new.php?post_type=waymark_map') {
// 					$sub_menu[4] = 'current';											
// 				}
// 			}
// 		}		
// 	}

	function modify_menu() {
    global $menu, $submenu, $pagenow;   
    
		//Waymark menu
		foreach($menu  as &$m) {
			if($m[2] == $this->menu_slug) {
				//Collections
				if($pagenow == 'edit-tags.php' && array_key_exists('taxonomy', $_GET) && $_GET['taxonomy'] == 'waymark_collection') {
//					$m[4] .= ' wp-has-current-submenu';						
				}
				
				//Map Posts				
				if($pagenow == 'post.php' && array_key_exists('post', $_GET))  {
					$post_type = get_post_type($_GET['post']);
					if(Waymark_Config::is_custom_type($post_type)) {
						$m[4] .= ' wp-has-current-submenu';						
					}
				}
			}
		}
		
		//Waymark sub menu
		if(array_key_exists($this->menu_slug, $submenu)) {	
			foreach($submenu[$this->menu_slug] as &$sub_menu) {					
				//Hide New Type links
				if(in_array($sub_menu[0], array(esc_html__('New Map', 'waymark-plugin')))) {
					$sub_menu[4] = 'hidden';
				}					
				
				//Make "Object" link active when adding new
				if(
					$sub_menu[0] == esc_html__('Maps', 'waymark-plugin') && $this->admin_url_request == 'post-new.php?post_type=waymark_map'
					||
					$sub_menu[0] == esc_html__('Collections', 'waymark-plugin') && (strpos($this->admin_url_request, 'edit-tags.php?taxonomy=waymark_collection') !== false)
					) {
					$sub_menu[4] = 'current';											
				}
			}
		}		
	}

	function help_page_content() {
		echo '<div id="waymark-admin-container" class="wrap">' . "\n";

		echo Waymark_Helper::plugin_about();

		echo '	<div class="card">' . "\n";	
		echo '		<h1>' . esc_html__('Help', 'waymark-plugin') . '</h1>' . "\n";

		//Required the PHP EXIF extension
		if(! function_exists('exif_read_data')) {
			echo '<div class="waymark-notice notice notice-warning is-dismissible">' . "\n";
			echo '	<p><b>' . esc_html__('Photo Location Detection Not Supported!', 'waymark-plugin') . '</b></p>';
			echo '	<p>' . __(sprintf('Your hosting environment does not currently have the <a href="%s">PHP EXIF Extension</a> enabled, which is required to read Photo location metadata. Try asking your host to enable it.', 'https://www.php.net/manual/en/book.exif.php'), 'waymark-plugin') . '</p>';		
			echo '</div>' . "\n";
		}	

		echo '			<p style="text-align:right"><a class="button" href="' . Waymark_Helper::site_url('docs/') . '">' . esc_html__('Read the Docs') . ' &raquo;</a></p>' . "\n";

		echo '			<hr />' . "\n";
		echo '			<h2 id="getting-started">' . esc_html__('Getting Started', 'waymark-plugin') . '</h2>' . "\n";
		echo '			<iframe src="https://player.vimeo.com/video/349575095" width="520" height="292" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>' . "\n";

		echo '			<h2 id="using-shortcodes">' . esc_html__('Using Shortcodes', 'waymark-plugin') . '</h2>' . "\n";

		echo '			<p class="lead">' . esc_html__('The Waymark shortcode allows you to embed Maps in your content.', 'waymark-plugin') . '</p>' . "\n";

		echo '			<p>' . __(sprintf('Once created, <a href="%s">Map</a> and <a href="%s">Collection</a> Data can be displayed by copying the provided shortcode and pasting it into your content. For example, this shortcode will display the Map with an ID of 1234:', admin_url('edit.php?post_type=waymark_map'), admin_url('edit-tags.php?taxonomy=waymark_collection&post_type=waymark_map')), 'waymark-plugin') . '</p>';		
		echo '			<p></p>' . "\n";
		echo '			<p><code>[Waymark map_id="1234"]</code></p>' . "\n";

		echo '			<p>' . esc_html__('To display a Basemap without any data, simply provide centre coordinates and zoom level, like this:', 'waymark-plugin') . '</p>' . "\n";
		echo '			<p><code>[Waymark map_centre="54.526814,-3.017289" map_zoom="16"]</code></p>' . "\n";

		echo '			<p>' . esc_html__('The following shortcode options are available:', 'waymark-plugin') . '</p>' . "\n";
		echo '			<table>' . "\n";

		echo '				<tr>' . "\n";
		echo '					<th>' . esc_html__('Parameter', 'waymark-plugin') . '</th>' . "\n";
		echo '					<th>' . esc_html__('Value', 'waymark-plugin') . '</th>' . "\n";
		echo '					<th>' . esc_html__('Description', 'waymark-plugin') . '</th>' . "\n";
		echo '				</tr>' . "\n";

		echo '				<tr>' . "\n";
		echo '					<td width="20%"><code>map_id</code></td>' . "\n";
		echo '					<td width="30%">' . esc_html__('Map ID', 'waymark-plugin') . '</td>' . "\n";
		echo '					<td>' . __(sprintf('Display a single Map. You can create Maps (and obtain Map IDs) <a href="%s">here</a>.', admin_url('edit.php?post_type=waymark_map')), 'waymark-plugin') . '</td>';		
		echo '				</tr>' . "\n";

		echo '				<tr>' . "\n";
		echo '					<td><code>collection_id</code></td>' . "\n";
		echo '					<td>' . esc_html__('Collection ID', 'waymark-plugin') . '</td>' . "\n";
		echo '					<td>' . __(sprintf('Display all Maps in a Collection. You can create Collections (and obtain Collection IDs) <a href="%s">here</a>.', admin_url('edit-tags.php?taxonomy=waymark_collection&post_type=waymark_map')), 'waymark-plugin') . '</td>';		
		echo '				</tr>' . "\n";

		echo '				<tr>' . "\n";
		echo '					<td><code>map_centre</code></td>' . "\n";
		echo '					<td>' . esc_html__('Latitude,Longitude', 'waymark-plugin') . '</td>' . "\n";
		echo '					<td>' . esc_html__('The initial centre coordinates of the Map. By default Waymark centres the Map so all Data is visible.', 'waymark-plugin') . '</td>' . "\n";
		echo '				</tr>' . "\n";	
		
		echo '				<tr>' . "\n";
		echo '					<td><code>map_zoom</code></td>' . "\n";
		echo '					<td>0-18</td>' . "\n";
		echo '					<td>' . esc_html__('The initial zoom of the Map. By default Waymark zooms the Map so all Data is visible.', 'waymark-plugin') . '</td>' . "\n";
		echo '				</tr>' . "\n";	

		echo '				<tr>' . "\n";
		echo '					<td><code>basemap</code></td>' . "\n";
		echo '					<td>' . esc_html__('Basemap Name', 'waymark-plugin') . '</td>' . "\n";
		echo '					<td>' . __(sprintf('Which Basemap to display when the Map first loads (instead of the default). The value must match a Basemap Name listed <a href="%s">here</a>.', admin_url('edit.php?post_type=waymark_map&page=waymark-settings&tab=tiles')), 'waymark-plugin') . '</td>';		
		echo '				</tr>' . "\n";	

		echo '				<tr>' . "\n";
		echo '					<td><code>map_height</code></td>' . "\n";
		echo '					<td>' . esc_html__('Number of pixels', 'waymark-plugin') . '</td>' . "\n";
		echo '					<td>' . __(sprintf('Specify the desired height of the Map. The default is %s and can be changed <a href="%s">here</a>.', Waymark_Config::get_setting('misc', 'map_options', 'map_height'), admin_url('edit.php?post_type=waymark_map&page=waymark-settings&tab=misc')), 'waymark-plugin') . '</td>';		
		echo '				</tr>' . "\n";

		echo '				<tr>' . "\n";
		echo '					<td><code>shortcode_header</code></td>' . "\n";
		echo '					<td>0 or 1</td>' . "\n";
		echo '					<td>' . __(sprintf('Whether to display the Shortcode Header (if Meta exists for the Map). The default is %s and can be changed <a href="%s">here</a>.', Waymark_Config::get_setting('misc', 'shortcode_options', 'shortcode_header'), admin_url('edit.php?post_type=waymark_map&page=waymark-settings&tab=misc')), 'waymark-plugin') . '</td>';		
		echo '				</tr>' . "\n";	

		echo '				<tr>' . "\n";
		echo '					<td><code>show_gallery</code></td>' . "\n";
		echo '					<td>0 or 1</td>' . "\n";
		echo '					<td>' . __(sprintf('Whether to display the Image Gallery for Markers that have images associated with them. The default is %s and can be changed <a href="%s">here</a>.', Waymark_Config::get_setting('misc', 'map_options', 'show_gallery'), admin_url('edit.php?post_type=waymark_map&page=waymark-settings&tab=misc')), 'waymark-plugin') . '</td>';		
		echo '				</tr>' . "\n";	

		echo '				<tr>' . "\n";
		echo '					<td><code>show_filter</code></td>' . "\n";
		echo '					<td>0 or 1</td>' . "\n";
		echo '					<td>' . __(sprintf('Whether to display the Overlay Filter. This enables visitors to filter for which Markers, Lines and Shapes are currently visible on the Map. The default is %s and can be changed <a href="%s">here</a>.', Waymark_Config::get_setting('misc', 'map_options', 'show_filter'), admin_url('edit.php?post_type=waymark_map&page=waymark-settings&tab=misc')), 'waymark-plugin') . '</td>';		
		echo '				</tr>' . "\n";	


		echo '			</table>' . "\n";

		echo '			<hr />' . "\n";
		echo '			<p style="text-align:right"><a class="button" href="' . Waymark_Helper::site_url('docs/') . '">' . esc_html__('Read the Docs') . ' &raquo;</a></p>' . "\n";

		echo '		</div>' . "\n";	
		echo '	</div>' . "\n";		
	}
	
	function show_setup_menu($show = true) {
		//Show
		if($show) {
			Waymark_CSS::add_chunk('
.wp-has-submenu.toplevel_page_waymark-top ul.wp-submenu li {
	display: none;
}
.wp-has-submenu.toplevel_page_waymark-top ul.wp-submenu li.wp-first-item {
	display: block;
}
			');			
		//Hide
		} else {
			Waymark_CSS::add_chunk('
.wp-has-submenu.toplevel_page_waymark-top ul.wp-submenu li.wp-first-item {
	display: none;
}
			');			
		}
	}
}
new Waymark_Menu;
