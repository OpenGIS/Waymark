<?php

class Waymark_Types {
	private $types;

	function __construct() {
		$this->types = array(
			//Map
			'waymark_map' => array(
				'label' => esc_html__('Map', 'waymark'),
				'description' => '',
				'labels' => array(
					'name' => esc_html__('Maps', 'waymark'),
					'singular_name' => esc_html__('Map', 'waymark'),
					'menu_name' => esc_html__('Maps', 'waymark'),
					'name_admin_bar' => esc_html__('Map', 'waymark'),
					'archives' => esc_html__('Map Archives', 'waymark'),
					'attributes' => esc_html__('Map Attributes', 'waymark'),
					'parent_item_colon' => esc_html__('Parent Map:', 'waymark'),
					'all_items' => esc_html__('All Maps', 'waymark'),
					'add_new_item' => esc_html__('Add New Map', 'waymark'),
					'add_new' => esc_html__('Add New', 'waymark'),
					'new_item' => esc_html__('New Map', 'waymark'),
					'edit_item' => esc_html__('Edit Map', 'waymark'),
					'update_item' => esc_html__('Update Map', 'waymark'),
					'view_item' => esc_html__('View Map', 'waymark'),
					'view_items' => esc_html__('View Maps', 'waymark'),
					'search_items' => esc_html__('Search Map', 'waymark'),
					'not_found' => esc_html__('Not found', 'waymark'),
					'not_found_in_trash' => esc_html__('Not found in Trash', 'waymark'),
					'featured_image' => esc_html__('Featured Image', 'waymark'),
					'set_featured_image' => esc_html__('Set featured image', 'waymark'),
					'remove_featured_image' => esc_html__('Remove featured image', 'waymark'),
					'use_featured_image' => esc_html__('Use as featured image', 'waymark'),
					'insert_into_item' => esc_html__('Insert into Map', 'waymark'),
					'uploaded_to_this_item' => esc_html__('Uploaded to this Map', 'waymark'),
					'items_list' => esc_html__('Map list', 'waymark'),
					'items_list_navigation' => esc_html__('Maps list navigation', 'waymark'),
					'filter_items_list' => esc_html__('Filter Map list', 'waymark'),
				),
				// Default - overridden below
				'supports' => array('title'),
				'hierarchical' => false,
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => false,
				'show_in_rest' => true,
				'rest_base' => Waymark_Config::get_setting('misc', 'permalinks', 'permalink_slug_map'),
				'menu_position' => 5,
				'show_in_admin_bar' => true,
				'show_in_nav_menus' => true,
				'can_export' => true,
				'has_archive' => false,
				'exclude_from_search' => false,
				'publicly_queryable' => true,
				'rewrite' => array(
					'slug' => Waymark_Config::get_setting('misc', 'permalinks', 'permalink_slug_map'),
					'with_front' => false,
				),
				'capability_type' => 'post',
			),
		);

		// Custom post support
		$supports = Waymark_Config::get_setting('misc', 'post', 'supports');
		if (is_array($supports) && sizeof($supports)) {
			// Override default supports
			$this->types['waymark_map']['supports'] = [];

			foreach ($supports as $support) {
				$this->types['waymark_map']['supports'][] = $support;
			}
		}

		//Show if debug
		if (Waymark_Config::get_setting('misc', 'advanced', 'debug_mode') == true) {
			$this->types['waymark_map']['supports'][] = 'custom-fields';
		}

		//Add Featured Image Support
		add_theme_support('post-thumbnails', array('waymark_map'));

		add_action('init', array($this, 'register_types'), 0);
	}

	function register_types() {
		$types = array();

		foreach ($this->types as $type_id => $type_data) {
			$types[] = $type_id;

			register_post_type($type_id, $type_data);
		}

		Waymark_Config::set_item('custom_types', $types);
	}

	function delete_posts() {
		//For each custom type
		foreach ($this->types as $type_id => $type_data) {
			//Get posts
			$posts = get_posts(array(
				'post_type' => $type_id,
			));

			//For each post
			foreach ($posts as $post) {
				//Force delete post
				wp_delete_post($post->ID, true);
			}
		}
	}
}
new Waymark_Types;
