<?php

//Thanks!
//https://www.smashingmagazine.com/2015/12/how-to-use-term-meta-data-in-wordpress/

class Waymark_Query_Taxonomy {

	private $parameters = array();
	private $parameter_groups = [
		'test1' => [
			'group_title' => 'Test 1',
		],
		'test2' => [
			'group_title' => 'Test 2',
		]
	];
		
	function __construct() {
 		$marker_types = Waymark_Helper::get_object_types('marker', 'marker_title', true);
 		$default_marker_type = array_keys($marker_types)[0];

		$line_types = Waymark_Helper::get_object_types('line', 'line_title', true);
		$default_line_type = array_keys($line_types)[0];
		
		//Area passed?
// 		if($query_area) {
// 			$default_query_area = $query_area;
// 		} else {
// 			$default_query_area = Waymark_Config::get_setting('query', 'defaults', 'query_area');
// 		}
		
		$this->parameters['query_overpass_request'] = array(
			'id' => 'query_overpass_request',
			'type' => 'textarea',				
			'tip' => 'OverpassQL Query.',
			'tip_link' => 'https://osm-queries.ldodds.com/tutorial/',				
			'group' => 'test1',
			'title' => 'Overpass QL Query',
			'default' => Waymark_Config::get_setting('query', 'defaults', 'query_overpass_request'),
			'output_processing' => array(
				'html_entity_decode($param_value)'
			)				
		);
			
		$this->parameters['query_cast_overlay'] = array(
			'id' => 'query_cast_overlay',
			'type' => 'select',				
			'tip' => 'Marker/Line/Shape',
			'group' => 'test1',
			'title' => 'Overlay Type',
			'default' => Waymark_Config::get_setting('query', 'defaults', 'query_cast_overlay'),
			'options' => [
				'marker' => 'Marker',
				'line' => 'Line',
//					'shape' => 'Shape'										
			]
		);
			
		$this->parameters['query_cast_marker_type'] = array(
			'name' => 'query_cast_marker_type',
			'id' => 'query_cast_marker_type',
			'type' => 'select',				
			'tip' => 'Cast to Type',
			'group' => 'test2',
			'title' => 'Marker Type',
			'default' => $default_marker_type,
			'options' => Waymark_Helper::get_object_types('marker', 'marker_title', true)
		);
			
		$this->parameters['query_cast_line_type'] = array(
			'name' => 'query_cast_line_type',
			'id' => 'query_cast_line_type',
			'type' => 'select',				
			'tip' => 'Cast to Type',
			'group' => 'test2',
			'title' => 'Line Type',
			'default' => $default_line_type,
			'options' => Waymark_Helper::get_object_types('line', 'line_title', true)
		);		
		
		add_action('init', array($this, 'register_taxonomy'));
		
		add_action('waymark_query_add_form_fields', array($this, 'add_form_append'), 10, 2);					
		add_action('waymark_query_edit_form', array($this, 'edit_form_append'), 10, 2);
		add_action('created_waymark_query', array($this, 'save_query_meta'), 10, 2);
		add_action('edited_waymark_query', array($this, 'update_query_meta'), 10, 2);
		add_filter('manage_edit-waymark_query_columns', array($this, 'add_query_column'));		
		add_filter('manage_waymark_query_custom_column', array($this, 'add_query_column_content'), 10, 3);		

// 		add_filter('manage_edit-waymark_query_sortable_columns', array($this, 'add_query_column_sortable'));
	}	

	function register_taxonomy() {
		$taxonomy = [
			'key' =>	'waymark_query',
			'type' => array('waymark_map'),
			'args' => array(
				'labels'=> array(
					'name' => esc_html__('Queries', 'waymark'),
					'singular_name' => esc_html__('Query', 'waymark'),
					'menu_name' => esc_html__('Query', 'waymark'),
					'all_items' => esc_html__('All Queries', 'waymark'),
					'parent_item' => esc_html__('Parent', 'waymark'),
					'parent_item_colon' => esc_html__('Parent Query:', 'waymark'),
					'new_item_name' => esc_html__('New Query Name', 'waymark'),
					'add_new_item' => esc_html__('Create Query', 'waymark'),
					'edit_item' => esc_html__('Edit Query', 'waymark'),
					'update_item' => esc_html__('Update Query', 'waymark'),
					'view_item' => esc_html__('View Query', 'waymark'),
					'separate_items_with_commas' => esc_html__('Separate Queries with commas', 'waymark'),
					'add_or_remove_items' => esc_html__('Add or remove Queries', 'waymark'),
					'choose_from_most_used' => esc_html__('Choose from the most used', 'waymark'),
					'popular_items' => esc_html__('Popular Queries', 'waymark'),
					'search_items' => esc_html__('Search Queries', 'waymark'),
					'not_found' => esc_html__('Not Found', 'waymark'),
					'no_terms' => esc_html__('No Queries', 'waymark'),
					'items_list' => esc_html__('Query list', 'waymark'),
					'items_list_navigation' => esc_html__('Query list navigation', 'waymark'),
				),
				'hierarchical' => false,
				'public' => false,
// 						'rewrite' => array(
// 							'slug' => 'collection'
// 						),				
				'show_ui' => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tagcloud' => true,					
			)
		];
				
		register_taxonomy($taxonomy['key'], $taxonomy['type'], $taxonomy['args']);			
	}
	
	function add_form_append($taxonomy) {
		$out = '<div id="waymark-query-add" class="waymark-self-clear">' . "\n";
		$out .= Waymark_Input::create_parameter_groups($this->parameters, $this->parameter_groups);
		$out .= '	<div id="waymark-map" class="waymark-map waymark-query-preview"></div>';
		$out .= '</div>' . "\n";		

		//Create new Map object
		Waymark_JS::add_call('var Waymark_Map_Viewer = window.Waymark_Map_Factory.viewer()');

		//Output Config
		Waymark_JS::add_call('var waymark_user_config = ' . json_encode(Waymark_Config::get_map_config()) . ';');				
// 		Waymark_JS::add_call('waymark_user_config.map_height = 600;');				
		
		//Set basemap
		if($editor_basemap = Waymark_Config::get_setting('misc', 'editor_options', 'editor_basemap')) {
			Waymark_JS::add_call('waymark_user_config.map_init_basemap = "' . $editor_basemap . '"');		
		}

		//Go!
		Waymark_JS::add_call('Waymark_Map_Viewer.init(waymark_user_config)');

		//GeoJSON set?
// 		if(array_key_exists('waymark_map_data', $data)) {
// 			Waymark_JS::add_call('Waymark_Map_Editor.load_json(' . $data['waymark_map_data'] . ');');			
// 		}
		
		echo $out;
	}

	function edit_form_append($term, $taxonomy) {
		echo 'edit_form_append';
	
		//Get existing data
		$data = [];
		foreach($this->parameters as $param) {
			$data[$param['id']] = get_term_meta($term->term_id, $param['id']);
		}

		$out = '<div id="waymark-query-edit" class="waymark-self-clear">' . "\n";
		$out .= Waymark_Input::create_parameter_groups($this->parameters, $this->parameter_groups, $data);
		$out .= '	<div id="waymark-query-map"></div>';
		$out .= '</div>' . "\n";		
		
		echo $out;
	}

	function save_query_meta($term_id, $tt_id){
		foreach($this->parameters as $param) {
			if(isset($_POST[$param['id']]) && $_POST[$param['id']]){
				add_term_meta($term_id, $param['id'], sanitize_text_field($_POST[$param['id']]), true);
			}
		}
	}		
	
	function update_query_meta($term_id, $tt_id){
		foreach($this->parameters as $param) {
			if(isset($_POST[$param['id']]) && $_POST[$param['id']]){
				update_term_meta($term_id, $param['id'], sanitize_text_field($_POST[$param['id']]));
			}
		}
	}	
	
	function add_query_column($columns){
		//Unwanted
		unset($columns['description']);
		unset($columns['slug']);
		
		//Renamed
		unset($columns['name']);

		return array_merge([
			'name' => __('Title', 'waymark'),
			'query_overlay' => __('Overlay Type', 'waymark'),
			'query_cast' => __('Cast To', 'waymark')
		], $columns);
	}	
	
	function add_query_column_content($content, $column_name, $term_id){
		
		switch($column_name) {
			case 'query_overlay' :
				$content .= get_term_meta($term_id, 'query_cast_overlay', true);

				break;
			case 'query_cast' :
				$cast_overlay = get_term_meta($term_id, 'query_cast_overlay', true);
				
				switch($cast_overlay) {
					case 'marker' :
					case 'line' :
						$content .= get_term_meta($term_id, 'query_cast_' . $cast_overlay . '_type', true);

						break;
				}			

				break;				
		}
		
		return $content;
	}		
// 	
// 	function add_query_column_sortable($sortable){
// 		$sortable['query_overlay'] = 'query_overlay';
// 		$sortable['query_cast'] = 'query_cast';	
// 
// 		return $sortable;
// 	}		
}
new Waymark_Query_Taxonomy;