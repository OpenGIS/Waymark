<?php
	
class Waymark_Types {
	private $types;
	
	function __construct() {
		$this->types = array(
			//Map
			'waymark_map' => array(
				'label'                 => esc_html__('Map', 'waymark-plugin'),
				'description'           => '',
				'labels'                => array(
					'name'                  => esc_html__('Maps', 'waymark-plugin'),
					'singular_name'         => esc_html__('Map', 'waymark-plugin'),
					'menu_name'             => esc_html__('Maps', 'waymark-plugin'),
					'name_admin_bar'        => esc_html__('Map', 'waymark-plugin'),
					'archives'              => esc_html__('Map Archives', 'waymark-plugin'),
					'attributes'            => esc_html__('Map Attributes', 'waymark-plugin'),
					'parent_item_colon'     => esc_html__('Parent Map:', 'waymark-plugin'),
					'all_items'             => esc_html__('All Maps', 'waymark-plugin'),
					'add_new_item'          => esc_html__('Add New Map', 'waymark-plugin'),
					'add_new'               => esc_html__('Add New', 'waymark-plugin'),
					'new_item'              => esc_html__('New Map', 'waymark-plugin'),
					'edit_item'             => esc_html__('Edit Map', 'waymark-plugin'),
					'update_item'           => esc_html__('Update Map', 'waymark-plugin'),
					'view_item'             => esc_html__('View Map', 'waymark-plugin'),
					'view_items'            => esc_html__('View Maps', 'waymark-plugin'),
					'search_items'          => esc_html__('Search Map', 'waymark-plugin'),
					'not_found'             => esc_html__('Not found', 'waymark-plugin'),
					'not_found_in_trash'    => esc_html__('Not found in Trash', 'waymark-plugin'),
					'featured_image'        => esc_html__('Featured Image', 'waymark-plugin'),
					'set_featured_image'    => esc_html__('Set featured image', 'waymark-plugin'),
					'remove_featured_image' => esc_html__('Remove featured image', 'waymark-plugin'),
					'use_featured_image'    => esc_html__('Use as featured image', 'waymark-plugin'),
					'insert_into_item'      => esc_html__('Insert into Map', 'waymark-plugin'),
					'uploaded_to_this_item' => esc_html__('Uploaded to this Map', 'waymark-plugin'),
					'items_list'            => esc_html__('Map list', 'waymark-plugin'),
					'items_list_navigation' => esc_html__('Maps list navigation', 'waymark-plugin'),
					'filter_items_list'     => esc_html__('Filter Map list', 'waymark-plugin'),
				),
				'supports'              => array('title', 'author', 'revisions'),
				'hierarchical'          => false,
				'public'                => true,
				'show_ui'               => true,
				'show_in_menu'          => false,
				'menu_position'         => 5,
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => true,
				'can_export'            => true,
				'has_archive'           => false,
				'exclude_from_search'   => false,
				'publicly_queryable'    => true,
				'rewrite'               => array('slug' => 'map'),
				'capability_type'       => 'post'
			)					
		);

		//Show if debug
		if(Waymark_Config::get_setting('misc', 'advanced', 'debug_mode') == true) {
			$this->types['waymark_map']['supports'][] = 'custom-fields';
		}
	
		add_action('init', array($this, 'register_types'), 0);			
	}	

	function register_types() {
		$types = array();
		
		foreach($this->types as $type_id => $type_data) {
			$types[] = $type_id;
						
			register_post_type($type_id, $type_data);			
		}

		Waymark_Config::set_item('custom_types', $types);			
	}

	function delete_posts() {
		//For each custom type
		foreach($this->types as $type_id => $type_data) {
			//Get posts
			$posts = get_posts(array(
				'post_type' => $type_id
			));
			
			//For each post
			foreach($posts as $post) {
				//Force delete post
				wp_delete_post($post->ID, true);
			}
		}
	}
}
new Waymark_Types;	
