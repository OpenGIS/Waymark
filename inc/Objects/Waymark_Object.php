<?php

class Waymark_Object {
	public $post_type = null;
	public $post_id = null;
	public $post_title = null;	
	public $data = array();

	protected $input_type = null;
	protected $input_name_format = null;

	protected $parameter_groups = array();
	protected $parameters = array();

	protected $meta_prefix = 'waymark_';

	protected $instance_data = array();
	protected $feed_data = array();
	protected $layout_data = array();
	
	function __construct($post_id = null) {
		//Set defaults
// 		foreach($this->parameters as $parameter) {
// 			if(array_key_exists('default', $parameter)) {
// 				$this->data[$parameter['name']] = $parameter['default'];					
// 			}
// 		}	
		
		//If post ID set
		if($post_id) {
			//Valid post ID
			if($post = get_post($post_id)) {
				//If it has been deleted
				if(in_array($post->post_status, array('trash'))) {
					$post_id = null;
				}
			//Invalid post ID
			} else {
				$post_id = null;
			}
			
			if($post_id != null) {
				$this->post_id = $post_id;
				$this->post_title = $post->post_title;
				
				$this->set_data(get_post_meta($post_id));							
			}
		}
	}
	
	function set_data($data_in = array()) {
		if(! sizeof($data_in) || ! is_array($data_in)) {
			return;
		}
		
		//For each of the incoming data
		foreach($data_in as $parameter_key => $parameter_value) {
			//Get correct value
			if(is_array($parameter_value)) {
				if(sizeof($parameter_value) == 1 && array_key_exists(0, $parameter_value)) {
					$parameter_value = $parameter_value[0];					
				} else {
					$parameter_value = json_encode($parameter_value);										
				}
			}
				
			//Always check to see if key is prefixed
			$parameter_key = $this->unprefix($parameter_key);
			
			//Is this an allowable parameter of this object?
			if(array_key_exists($parameter_key, $this->parameters)) {

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
		if(strpos($str, $this->meta_prefix) === 0) {
			$str = substr($str, strlen($this->meta_prefix));
		}
		
		return $str;
	}	
	
	function set_data_item($data_key, $data_value) {
		$this->data[$data_key] = $data_value;
	}

	function get_data_item($data_key) {
		if(array_key_exists($data_key, $this->data)) {
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
		if(array_key_exists($key, $this->parameters)) {
			return $this->parameters[$key];
		} else {
			return false;
		}
	}	
	
	function create_form() {
		return Waymark_Input::create_parameter_groups($this->post_type, $this->get_fields(), $this->parameter_groups, $this->data, $this->input_name_format);
	}
	
	function get_fields($by_group = true, $by_type = false) {		
		$fields = array();
		
		foreach($this->parameters as $parameter_key => $parameter_data) {
			
			if(! $by_type || ($by_type && array_key_exists('input_types', $parameter_data) && in_array($this->input_type, $parameter_data['input_types']))) {
				if($by_group) {
					$fields[$parameter_data['group']][$parameter_key] = $parameter_data;					
				} else {
					$fields[$parameter_key] = $parameter_data;
				}
			}
		}
		
		return $fields;	
	}	
	
	function save_meta($post_id = null) {
		if(! $post_id) {
			$post_id = $this->post_id;
		}
		
		//Iterate over each parameter
		foreach($this->parameters as $param_defition) {
			
			//Only inputs
			if(! array_key_exists('input_types', $param_defition)) {
				continue;
			}
						
			//Ensure value exists and is not blank EXCEPT where blank values are allowed
			if(isset($this->data[$param_defition['name']]) && (trim($this->data[$param_defition['name']]) !== '' || (array_key_exists('allow_blank', $param_defition) && $param_defition['allow_blank'] == true))) {
				$param_value = $this->data[$param_defition['name']];
				
				//Process input
				$param_value = Waymark_Input::process_input($param_defition, $param_value);
				
				update_post_meta($post_id, $this->prefix($param_defition['name']), $param_value);
			//No value exists
			} else {
				delete_post_meta($post_id, $this->prefix($param_defition['name']));
			}
		}
	}
	
	function delete_all_meta() {
		global $wpdb;

		$wpdb->query("
			DELETE FROM " . $wpdb->postmeta . "
			WHERE `meta_key` LIKE '" . $this->meta_prefix . "%'
		");
	}
	
	function get_posts() {
		if(! $this->post_type) {
			return null;
		}
		
		$wp_query = new WP_Query(array(
	    'post_type' => $this->post_type,
	    'post_status' => 'publish',
			'posts_per_page' => -1,
	    'orderby' => 'ID',
	    'order'   => 'ASC',			
		));
		
		$posts = $wp_query->get_posts();
		
		wp_reset_query();		
		
		return $posts;
	}		
	
	function get_posts_by_meta($key, $value, $limit = -1) {
		$wp_query = new WP_Query(
			array(
		    'post_type' => $this->post_type,
				'meta_key'   => $key,
				'posts_per_page' => $limit,
				'meta_query' => array(
					array(
						'key'     => $key,
						'value'   => $value
					)
				)
			)			
		);
		
		$posts = $wp_query->get_posts();		
		
		wp_reset_query();		
		
		return $posts;		
	}

	function get_list() {
		$list = array();
		
		//Get posts
		$posts = $this->get_posts();
		
		//Iterate over each
		if(is_array($posts)) {
			foreach($posts as $p) {
				//Get depth
		    $parent_id = $p->post_parent;
		    
		    $depth = 0;
		    while($parent_id > 0){
	        $page = get_page($parent_id);
	        
	        $parent_id = $page->post_parent;
	        
	        $depth++;
		    }

				$list[$p->ID] = str_repeat('-', $depth) . ' ' . $p->post_title;
			}
		}
		
		return $list;
	}		
	
	function create_post($title, $extra_args = array()) {
		//Insert Post
		$args = array(
		  'post_type' => $this->post_type,
		  'post_title' => $title,
		  'post_status' => 'publish'
		);
		$post_id = wp_insert_post(array_merge($args, $extra_args));
		
		//Set meta
		$this->save_meta($post_id);
		
		return $post_id;
	}	
	
	function update_post_title($title = null) {
		if($title) {
		  wp_update_post(array(
	      'ID' => $this->post_id,
	      'post_title' => $title
		  ));					
		}
	}

	function duplicate_post() {
		global $wpdb;
		
		//Get the post
		$post = get_post($this->post_id);
		if(! is_object($post)) {		
			return false;	
		}
		
		//Get the post meta
		$post_meta = get_post_meta($this->post_id, '', true);
		
		//Parse
		foreach($post_meta as $meta_key => $meta_value) {
			//Delete WP specific post meta
			if(strpos($meta_key, '_') === 0) {
				unset($post_meta[$meta_key]);
			//Flatten sub-array
			} else {
				$post_meta[$meta_key] = $meta_value[0];
			}
		}

		//Create new post WITH data
		$args = array(
			'post_status'    => 'publish',
			'post_title'     => $post->post_title . ' Copy',
			'post_type'      => $post->post_type,
			'meta_input'		 => $post_meta
		);
			
		//Create post
		$new_post_id = wp_insert_post($args);
		
		//If success
		if($new_post_id) {
			//Copy Collections
			$terms = wp_get_object_terms($this->post_id, 'waymark_collection', array('fields' => 'ids'));		
			if($terms) {
				wp_set_object_terms($new_post_id, $terms, 'waymark_collection');
			}
		}
		
		return $new_post_id;
	}	
	
	function relationship_field($relationship_type = 'one', $Object_Name = '', $field_name = '', $group = '', $tip = '') {
		$out = array(
			'input_types' => array('meta'),
			'name' => $field_name,
			'id' => $field_name,
			'tip' => $tip,
			'group' => $group
		);	
		
		if($relationship_type == 'one') {
			$out['type'] = 'select';
			$out['title'] = $Object_Name;			
			$out['options'] = $this->relationship_options($Object_Name, true);
		} elseif($relationship_type == 'many') {
			$out['type'] = 'select_multi';
			$out['title'] = $Object_Name . 's';
			$out['options'] = $this->relationship_options($Object_Name);
		}
		
		return $out;
	}
	
	function relationship_options($Object_Name, $add_none = false) {
		$Object_Name = 'Waymark_' . $Object_Name;
		
		$Object = new $Object_Name;
		
		if($add_none) {
			$out = array('' => '');			
			return $out + $Object->get_list();			
		} else {
			return $Object->get_list();
		}			
	}		
}