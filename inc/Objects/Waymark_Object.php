<?php

class Waymark_Object {
	public $post_type  = null;
	public $post_id    = null;
	public $post_title = null;
	public $data       = [];

	protected $input_type        = null;
	protected $input_name_format = null;

	protected $parameter_groups = [];
	protected $parameters       = [];

	protected $meta_prefix = 'waymark_';

	function __construct($post_id = null) {
		//Set defaults
// 		foreach($this->parameters as $parameter) {
// 			if(array_key_exists('default', $parameter)) {
// 				$this->data[$parameter['name']] = $parameter['default'];
// 			}
// 		}

		//If post ID set
		if ($post_id) {
			//Valid post ID
			if ($post = get_post($post_id)) {
				//If it has been deleted
				if (in_array($post->post_status, ['trash'])) {
					$post_id = null;
				}
				//Invalid post ID
			} else {
				$post_id = null;
			}

			if ($post_id != null) {
				$this->post_id = $post_id;
				$this->post_title = $post->post_title;

				$this->set_data(get_post_meta($post_id));
			}
		}
	}

	function set_data($data_in = []) {
		if (! sizeof($data_in) || ! is_array($data_in)) {
			return;
		}

		//For each of the incoming data
		foreach ($data_in as $parameter_key => $parameter_value) {
			//Get correct value
			if (is_array($parameter_value)) {
				if (sizeof($parameter_value) == 1 && array_key_exists(0, $parameter_value)) {
					$parameter_value = $parameter_value[0];
				} else {
					$parameter_value = wp_json_encode($parameter_value);
				}
			}

			//Always check to see if key is prefixed
			$parameter_key = $this->unprefix($parameter_key);

			//Is this an allowable parameter of this object?
			if (array_key_exists($parameter_key, $this->parameters)) {

				//Process output?
// 				if(array_key_exists('output_processing', $this->parameters[$parameter_key])) {
// 					$parameter_value = Waymark_Input::process_output($this->parameters[$parameter_key], $parameter_value);
// 				}

				$this->data[$parameter_key] = $parameter_value;
			}
		}

		//Waymark_Helper::debug($this->data);
	}

	function prefix($str) {
		return $this->meta_prefix . $str;
	}

	function unprefix($str) {
		//Is the key prefixed?
		if (strpos($str, $this->meta_prefix) === 0) {
			$str = substr($str, strlen($this->meta_prefix));
		}

		return $str;
	}

	function set_data_item($data_key, $data_value) {
		$this->data[$data_key] = $data_value;
	}

	function get_data_item($data_key) {
		if (array_key_exists($data_key, $this->data)) {
			return $this->data[$data_key];
		} else {
			return null;
		}
	}

	function get_data() {
		return $this->data;
	}

	function get_post_type() {
		return $this->post_type;
	}

	function set_input_type($input_type) {
		$this->input_type = $input_type;
	}

	function set_input_name_format($input_name_format) {
		$this->input_name_format = $input_name_format;
	}

	function get_parameters() {
		return $this->parameters;
	}

	function get_parameter($key) {
		if (array_key_exists($key, $this->parameters)) {
			return $this->parameters[$key];
		} else {
			return false;
		}
	}

	function create_form() {
		$out = Waymark_Input::create_parameter_groups($this->parameters, $this->parameter_groups, $this->data, $this->input_name_format, 'waymark-parameters-' . $this->post_type);
		$out .= wp_nonce_field('create_form', Waymark_Config::get_item('nonce_string'), false, false);

		return $out;
	}

	//This gets fired when the post is created, saved and *TRASHED*
	function save_meta($post_id = null) {
		if (! $post_id) {
			$post_id = $this->post_id;
		}

		$post_data = wp_unslash($_POST);

		// Check nonce
		if (array_key_exists(Waymark_Config::get_item('nonce_string'), $post_data) && wp_verify_nonce($post_data[Waymark_Config::get_item('nonce_string')], 'create_form')) {
			// Not Public Submission
			if (! isset($_POST['waymark_action']) || $_POST['waymark_action'] !== 'public_add_map') {
				//Only if we are saving a post (i.e. not when trashing)
				if (! isset($_POST['action']) || $_POST['action'] != 'editpost') {
					return;
				}
			}
		}

		//Iterate over each parameter
		foreach ($this->parameters as $param_definition) {

			//Only inputs
			if (! array_key_exists('input_types', $param_definition)) {
				continue;
			}

			//Ensure value exists and is not blank EXCEPT where blank values are allowed
			if (isset($this->data[$param_definition['name']]) && (trim($this->data[$param_definition['name']]) !== '' || (array_key_exists('allow_blank', $param_definition) && $param_definition['allow_blank'] == true))) {
				$param_value = $this->data[$param_definition['name']];

				//Process input
				$param_value = Waymark_Input::process_input($param_definition, $param_value);

				// Filter before update
				$param_value = apply_filters('waymark_pre_update_post_meta', $param_value, $param_definition);

				update_post_meta($post_id, $this->prefix($param_definition['name']), $param_value);
				//No value exists
			} else {
				delete_post_meta($post_id, $this->prefix($param_definition['name']));
			}
		}
	}

	function get_posts() {
		if (! $this->post_type) {
			return null;
		}

		$wp_query = new WP_Query([
			'post_type' => $this->post_type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'ID',
			'order' => 'ASC',
		]);

		$posts = $wp_query->get_posts();

		wp_reset_query();

		return $posts;
	}

	function get_posts_by_meta($key, $value, $limit = -1) {
		$wp_query = new WP_Query(
			[
				'post_type' => $this->post_type,
				'meta_key' => $key,
				'posts_per_page' => $limit,
				'meta_query' => [
					[
						'key' => $key,
						'value' => $value,
					],
				],
			]
		);

		$posts = $wp_query->get_posts();

		wp_reset_query();

		return $posts;
	}

	function get_list() {
		$list = [];

		//Get posts
		$posts = $this->get_posts();

		//Iterate over each
		if (is_array($posts)) {
			foreach ($posts as $p) {
				//Get depth
				$parent_id = $p->post_parent;

				$depth = 0;
				while ($parent_id > 0) {
					$page = get_page($parent_id);

					$parent_id = $page->post_parent;

					$depth++;
				}

				$list[$p->ID] = str_repeat('-', $depth) . ' ' . $p->post_title;
			}
		}

		return $list;
	}

	function create_post($title, $extra_args = []) {
		//Insert Post
		$args = [
			'post_type' => $this->post_type,
			'post_title' => $title,
			'post_status' => 'publish',
		];
		$this->post_id = wp_insert_post(array_merge($args, $extra_args));

		//Set meta
		$this->save_meta($this->post_id);

		return $this->post_id;
	}

	function update_post_title($title = null) {
		if ($title) {
			wp_update_post([
				'ID' => $this->post_id,
				'post_title' => $title,
			]);
		}
	}

	function duplicate_post() {
		global $wpdb;

		//Get the post
		$post = get_post($this->post_id);
		if (! is_object($post)) {
			return false;
		}

		//Get the post meta
		$post_meta = get_post_meta($this->post_id, '', true);

		//Parse
		foreach ($post_meta as $meta_key => $meta_value) {
			//Delete WP specific post meta
			if (strpos($meta_key, '_') === 0) {
				unset($post_meta[$meta_key]);
				//Waymark Meta
			} else {
				//Flatten sub-array
				$meta_value = $meta_value[0];

				//Fix quote escape bug
				if ($meta_key == 'waymark_map_data') {
					//Remove newlines
					$meta_value = str_replace('\n', '', $meta_value);

					$map_data = Waymark_GeoJSON::string_to_feature_collection($meta_value);
					$map_data = Waymark_GeoJSON::clean_feature_descriptions($map_data);

					//Keep Unicode
					$meta_value = wp_json_encode($map_data, JSON_UNESCAPED_UNICODE);
				}

				$post_meta[$meta_key] = $meta_value;
			}
		}

		//Create new post WITH data
		$args = [
			'post_status' => 'publish',
			'post_title' => $post->post_title . ' Copy',
			'post_type' => $post->post_type,
			'meta_input' => $post_meta,
		];

		//Create post
		$new_post_id = wp_insert_post($args);

		//If success
		if ($new_post_id) {
			//Copy Collections
			$terms = wp_get_object_terms($this->post_id, 'waymark_collection', ['fields' => 'ids']);
			if ($terms) {
				wp_set_object_terms($new_post_id, $terms, 'waymark_collection');
			}
		}

		return $new_post_id;
	}

	function relationship_field($relationship_type = 'one', $Object_Name = '', $field_name = '', $group = '', $tip = '') {
		$out = [
			'input_types' => ['meta'],
			'name' => $field_name,
			'id' => $field_name,
			'tip' => $tip,
			'group' => $group,
		];

		if ($relationship_type == 'one') {
			$out['type'] = 'select';
			$out['title'] = $Object_Name;
			$out['options'] = $this->relationship_options($Object_Name, true);
		} elseif ($relationship_type == 'many') {
			$out['type'] = 'select_multi';
			$out['title'] = $Object_Name . 's';
			$out['options'] = $this->relationship_options($Object_Name);
		}

		return $out;
	}

	function relationship_options($Object_Name, $add_none = false) {
		$Object_Name = 'Waymark_' . $Object_Name;

		$Object = new $Object_Name;

		if ($add_none) {
			$out = ['' => ''];
			return $out + $Object->get_list();
		} else {
			return $Object->get_list();
		}
	}
}