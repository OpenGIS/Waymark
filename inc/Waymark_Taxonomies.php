<?php
	
class Waymark_Taxonomies {
	private $taxonomies;

	function __construct() {
		$this->taxonomies = array(
			array(
				//Collection
				'key' =>	'waymark_collection',
				'type' => array('waymark_map'),
				'args' => array(
					'labels'=> array(
						'name' => esc_html__('Collections', 'waymark'),
						'singular_name' => esc_html__('Collection', 'waymark'),
						'menu_name' => esc_html__('Collection', 'waymark'),
						'all_items' => esc_html__('All Collections', 'waymark'),
						'parent_item' => esc_html__('Parent', 'waymark'),
						'parent_item_colon' => esc_html__('Parent Collection:', 'waymark'),
						'new_item_name' => esc_html__('New Collection Name', 'waymark'),
						'add_new_item' => esc_html__('Create Collection', 'waymark'),
						'edit_item' => esc_html__('Edit Collection', 'waymark'),
						'update_item' => esc_html__('Update Collection', 'waymark'),
						'view_item' => esc_html__('View Collection', 'waymark'),
						'separate_items_with_commas' => esc_html__('Separate Collections with commas', 'waymark'),
						'add_or_remove_items' => esc_html__('Add or remove Collections', 'waymark'),
						'choose_from_most_used' => esc_html__('Choose from the most used', 'waymark'),
						'popular_items' => esc_html__('Popular Collections', 'waymark'),
						'search_items' => esc_html__('Search Collections', 'waymark'),
						'not_found' => esc_html__('Not Found', 'waymark'),
						'no_terms' => esc_html__('No Collections', 'waymark'),
						'items_list' => esc_html__('Collection list', 'waymark'),
						'items_list_navigation' => esc_html__('Collection list navigation', 'waymark'),
					),
					'hierarchical' => true,
					'public' => true,
					'rewrite' => array(
						'slug' => 'collection'
					),				
					'show_ui' => true,
					'show_admin_column' => true,
					'show_in_nav_menus' => true,
					'show_tagcloud' => true,
				),
				
				//Query
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
			)
		);
		
		add_action('init', array($this, 'register_taxonomies'));
		add_action('waymark_query_add_form_fields', array($this, 'add_form_append'), 10, 2);					
		add_action('waymark_query_edit_form', array($this, 'edit_form_append'), 10, 2);
		add_action('created_waymark_query', array($this, 'save_query_meta'), 10, 2);
		add_action('edited_waymark_query', array($this, 'update_query_meta'), 10, 2);
	}	

	function register_taxonomies() {
		foreach($this->taxonomies as $taxonomy) {
			register_taxonomy($taxonomy['key'], $taxonomy['type'], $taxonomy['args']);			
		}
	}
	
	function add_form_append($taxonomy) {
		$out = '<div class="waymark-query form-field">' . "\n";

		$out .= Waymark_Input::create_field([
			'name' => 'query_overpass',
			'id' => 'query_overpass',
			'type' => 'textarea',
// 			'class' => '',							
			'title' => esc_html__('Query Overpass', 'waymark'),
//			'default' => 'marker',
//			'tip' => esc_attr__('What features to offer in the Editor. Important! Uploaded images are added to the Media Library, reading from file does not keep a copy of the file on the server. Whether an individual Meta input is displayed can be set in the Settings > Meta.', 'waymark'),
		]);
		
		$out .= Waymark_Input::create_field([
			'name' => 'query_type',
			'id' => 'query_type',
			'type' => 'select',
// 			'class' => '',							
			'title' => esc_html__('Query Type', 'waymark'),
			'default' => 'marker',
//			'tip' => esc_attr__('What features to offer in the Editor. Important! Uploaded images are added to the Media Library, reading from file does not keep a copy of the file on the server. Whether an individual Meta input is displayed can be set in the Settings > Meta.', 'waymark'),
			'options' => array(
				'marker' => esc_attr__('Marker', 'waymark'),
				'line' => esc_attr__('Line', 'waymark')
			)
		]);

		$out .= '</div>' . "\n";		
		
		echo $out;
	}

	function edit_form_append($term, $taxonomy) {
		$out = '<div class="waymark-query form-field">' . "\n";

		$out .= Waymark_Input::create_field([
			'name' => 'query_overpass',
			'id' => 'query_overpass',
			'type' => 'textarea',
// 			'class' => '',							
			'title' => esc_html__('Query Overpass', 'waymark'),
			'default' => get_term_meta($term->term_id, 'query_overpass', true),
//			'tip' => esc_attr__('What features to offer in the Editor. Important! Uploaded images are added to the Media Library, reading from file does not keep a copy of the file on the server. Whether an individual Meta input is displayed can be set in the Settings > Meta.', 'waymark'),
		]);
		
		$out .= Waymark_Input::create_field([
			'name' => 'query_type',
			'id' => 'query_type',
			'type' => 'select',
// 			'class' => '',							
			'title' => esc_html__('Query Type', 'waymark'),
			'default' => get_term_meta($term->term_id, 'query_type', true),
//			'tip' => esc_attr__('What features to offer in the Editor. Important! Uploaded images are added to the Media Library, reading from file does not keep a copy of the file on the server. Whether an individual Meta input is displayed can be set in the Settings > Meta.', 'waymark'),
			'options' => array(
				'marker' => esc_attr__('Marker', 'waymark'),
				'line' => esc_attr__('Line', 'waymark')
			)
		]);

		$out .= '</div>' . "\n";		
		
		echo $out;
	}

	function save_query_meta($term_id, $tt_id){
		foreach(['query_overpass', 'query_type'] as $input_key) {
			if(isset($_POST[$input_key]) && $_POST[$input_key]){
				add_term_meta($term_id, $input_key, sanitize_text_field($_POST[$input_key]), true);
			}
		}
	}		
	
	function update_query_meta($term_id, $tt_id){
		foreach(['query_overpass', 'query_type'] as $input_key) {
			if(isset($_POST[$input_key]) && $_POST[$input_key]){
				update_term_meta($term_id, $input_key, sanitize_text_field($_POST[$input_key]));
			}
		}
	}			
}
new Waymark_Taxonomies;	
