<?php

class Waymark_Taxonomies {
	private $taxonomies;

	function __construct() {
		$this->taxonomies = array(
			array(
				'key' => 'waymark_collection',
				'type' => array('waymark_map'),
				'args' => array(
					'labels' => array(
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
						'slug' => Waymark_Config::get_setting('misc', 'permalinks', 'permalink_slug_collection'),
					),
					'show_ui' => true,
					'show_admin_column' => true,
					'show_in_nav_menus' => true,
					'show_tagcloud' => true,
					'show_in_rest' => true,
					'rest_base' => Waymark_Config::get_setting('misc', 'permalinks', 'permalink_slug_collection'),
				),
			),
		);

		add_action('init', array($this, 'register_taxonomies'));
	}

	function register_taxonomies() {
		foreach ($this->taxonomies as $taxonomy) {
			register_taxonomy($taxonomy['key'], $taxonomy['type'], $taxonomy['args']);
		}
	}
}
new Waymark_Taxonomies;
