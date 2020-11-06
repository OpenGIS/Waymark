<?php
	
class Waymark_Taxonomies {
	private $taxonomies;

	function __construct() {
		$this->taxonomies = array(
				array(
					'key' =>	'waymark_collection',
					'type' => array('waymark_map'),
					'args' => array(
						'labels'=> array(
							'name' => esc_html__('Collections', 'waymark-plugin'),
							'singular_name' => esc_html__('Collection', 'waymark-plugin'),
							'menu_name' => esc_html__('Collection', 'waymark-plugin'),
							'all_items' => esc_html__('All Collections', 'waymark-plugin'),
							'parent_item' => esc_html__('Parent', 'waymark-plugin'),
							'parent_item_colon' => esc_html__('Parent Collection:', 'waymark-plugin'),
							'new_item_name' => esc_html__('New Collection Name', 'waymark-plugin'),
							'add_new_item' => esc_html__('Create Collection', 'waymark-plugin'),
							'edit_item' => esc_html__('Edit Collection', 'waymark-plugin'),
							'update_item' => esc_html__('Update Collection', 'waymark-plugin'),
							'view_item' => esc_html__('View Collection', 'waymark-plugin'),
							'separate_items_with_commas' => esc_html__('Separate Collections with commas', 'waymark-plugin'),
							'add_or_remove_items' => esc_html__('Add or remove Collections', 'waymark-plugin'),
							'choose_from_most_used' => esc_html__('Choose from the most used', 'waymark-plugin'),
							'popular_items' => esc_html__('Popular Collections', 'waymark-plugin'),
							'search_items' => esc_html__('Search Collections', 'waymark-plugin'),
							'not_found' => esc_html__('Not Found', 'waymark-plugin'),
							'no_terms' => esc_html__('No Collections', 'waymark-plugin'),
							'items_list' => esc_html__('Collection list', 'waymark-plugin'),
							'items_list_navigation' => esc_html__('Collection list navigation', 'waymark-plugin'),
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
					)			
				)
			);
		
		add_action('init', array($this, 'register_taxonomies'));			
	}	

	function register_taxonomies() {
		foreach($this->taxonomies as $taxonomy) {
			register_taxonomy($taxonomy['key'], $taxonomy['type'], $taxonomy['args']);			
		}
	}
}
new Waymark_Taxonomies;	
