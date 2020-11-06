<?php

class Waymark_Input {
	
	private static $username_bad = array('.', "\$", '!', '*');
	private static $username_good = array('__dot__', '__dollar__', '__bang__', '__star__');
	
	static public function create_field($field, $set_value = null, $show_label = true) {
		$out = "\n" . '<!-- START ' . $field['id'] . ' Input -->' . "\n";



		//Default type
		if(! array_key_exists('type', $field)) {
			$field['type'] = 'text';
		}

		//Boolean
		if($field['type'] == 'boolean') {
			if(! array_key_exists('class', $field)) {
				$field['class'] = 'waymark-short-input';
			} else {
				$field['class'] .= ' waymark-short-input';				
			}
					
			if(! array_key_exists('options', $field) || ! is_array($field['options'])) {
				$field['options'] = array(
					'1' => esc_attr__('Yes', 'waymark-plugin'),
					'0' => esc_attr__('No', 'waymark-plugin')
				);
			}
		}		
		
		//Add class?
		$add_class = (array_key_exists('class', $field)) ? ' ' . $field['class'] : '';
			
		//Container
		$out .= '<div class="waymark-control-group waymark-control-type-' . $field['type'] . $add_class . '" id="' . $field['id'] . '-container">' . "\n";
	
		//Label
		if($show_label) {
			$out .= '	<label class="waymark-control-label" for="' . $field['name'] . '">' . $field['title'] .  '</label>' . "\n";		
		}
		$out .= '	<div class="waymark-controls">' . "\n";				
	
		//Create input
		$out .= self::create_input($field, $set_value);
	
		//Tip
		if(array_key_exists('tip', $field)) {
			$out .= ' <a data-title="' . $field['tip'] . '';
			if(array_key_exists('tip_link', $field)) {
				$out .= ' ' . esc_attr__('Click here for more details.', 'waymark-plugin') . '" href="' . $field['tip_link'] . '" target="_blank" class="waymark-tooltip waymark-link"';					
			} else {
				$out .= '" href="#" onclick="return false;" class="waymark-tooltip"';
			}
			$out .= '>?</a>';
		}
		
		$out .= '	</div>' . "\n";								
		$out .= '</div>' . "\n";
		$out .= '<!-- END ' . $field['id'] . ' Input -->' . "\n";
				
		return $out;
	}			


	/**
	 * ====================================
	 * ============== PRIVATE =============
	 * ====================================
	 */			
	private static function create_input($field, $set_value = null) {
		$out = '';
		
		if(! array_key_exists('type', $field)) {
			$field['type'] = 'text';
		}
		
		//Default
		if(array_key_exists('default', $field)) {
			if(is_array($field['default'])) {
				$field['default'] = implode(Waymark_Config::get_item('multi_value_seperator'), self::process_output($field, $field['default']));				
			} else {
				$field['default'] = self::process_output($field, $field['default']);				
			}
		}
		
		//Process set value?
		if($set_value !== null) {
			if(is_array($set_value)) {
				$set_value = implode(Waymark_Config::get_item('multi_value_seperator'), self::process_output($field, $set_value));				
			} else {
				$set_value = self::process_output($field, $set_value);			
			}
		}
		
		switch($field['type']) {
			case 'boolean' :
			case 'select' :
				//Do we have a value for this post?
				if($set_value === null && array_key_exists('default', $field)) {
					$set_value = $field['default'];
				}
							
				$out .= '		<select data-multi-value="' . $set_value . '" class="waymark-input" name="' . $field['name'] . '" id="' . $field['id'] . '">' . "\n";
				if(isset($field['options'])) {
					foreach($field['options'] as $value => $description) {
						//Always use strings
						$value = (string)$value;
					
						$out .= '			<option value="' . $value . '"';
						//Has this value already been set
						if($set_value === $value) {
							$out .= ' selected="selected"';
						//Do we have a default?
						}	elseif($set_value == null && (array_key_exists('default', $field) && $field['default'] == $value)) {
							$out .= ' selected="selected"';				
						}		
						$out .= '>' . $description . '</option>' . "\n";
					}
				}
				$out .= '		</select>' . "\n";
				
				break;			
			case 'select_multi' :
				//Decode if JSON
				$set_value = (json_decode($set_value)) ? json_decode($set_value) : $set_value;
				//Use default if no set value
				if(! $set_value && isset($field['default'])) {
					$set_value = $field['default'];
				}
				//Is multi?
				if(is_string($set_value) && strpos($set_value, ',')) {
					$set_value = explode(',', $field['default']);
				}				
				
				$out .= '		<select multiple="multiple" class="waymark-input" name="' . $field['name'] . '[]" id="' . $field['id'] . '">' . "\n";
				
				//If we have options
				if(isset($field['options'])) {
					foreach($field['options'] as $value => $description) {
						//Always use strings
						$value = (string)$value;

						//Waymark_Helper::debug($set_value);
					
						$out .= '			<option value="' . $value . '"';
									
						//Has this value already been set
						if(is_array($set_value) && in_array($value, $set_value)) {
							$out .= ' selected="selected"';
						} elseif($value == $set_value) {
							$out .= ' selected="selected"';
						}						

						$out .= '>' . $description . '</option>' . "\n";
					}
				}
				$out .= '		</select>' . "\n";

				break;					
			case 'textarea' :
				$out .= '		<textarea class="waymark-input" name="' . $field['name'] . '" id="' . $field['id'] . '">';
				//Do we have a value for this post?
				if($value = htmlspecialchars($set_value)) {
					$out .= $value;
				//Do we have a default?
				}	elseif(array_key_exists('default', $field)) {
					$out .= $field['default'];
				}
				$out .= '</textarea>' . "\n";
				
				break;
			case 'textarea_rich' :
				//Content
				$content = $set_value;
				if(! $content && array_key_exists('default', $field)) {
					$content = $field['default'];
				}
				$content = htmlspecialchars_decode($content);
				
				
				//Markup
				//$out .= '		<textarea class="waymark-input" name="' . $field['name'] . '" id="' . $field['id'] . '"></textarea>' . "\n";
				
				//Setup rich editor			
				ob_start();	
				wp_editor($content, $field['id'], array(
					'media_buttons' => false,
					'drag_drop_upload' => false,
					'textarea_name' => $field['name'],
					'textarea_rows' => 5,
					'teeny' => false,
					'quicktags' => false
				));
				$out .= ob_get_clean();
				
				break;				
			case 'submit' :
				$value = explode(' ', $field['title'])[0];
				$out .= '		<input type="submit" name="' . $field['name'] . '" value="' . $value . '" id="' . $field['id'] . '" class="waymark-input button-secondary" />' . "\n";
				
				break;				
			case 'file' :
				$out .= '		<input class="waymark-input" type="file" name="' . $field['name'] . '" id="' . $field['id'] . '" />' . "\n";
				
				break;
			case 'text' :
			default :
				$out .= '		<input class="waymark-input" type="text" name="' . $field['name'] . '" id="' . $field['id'] . '"';
				//Do we have a value for this post?
				if($set_value !== null) {
					$out .= ' value="' . $set_value . '"';
//					$out .= ' value="' . htmlspecialchars($set_value) . '"';
				//Do we have a default?
				}	elseif(array_key_exists('default', $field)) {
					$value = $field['default'];
					
					$out .= ' value="' . $value . '"';			
				}
				$out .= ' />' . "\n";
				
				break;
		}	
		
		return $out;
	}	
	
	static function create_parameter_groups($post_type, $fields, $groups, $data = array(), $input_name_format = null) {				
		$out = '<!-- START Parameter Container -->' . "\n";
		$out .= '<div class="waymark-parameters-container waymark-self-clear" id="waymark-parameters-' . $post_type . '">' . "\n";
		
		$current_group = false;
		foreach($fields as $group_id => $fields) {
			if(array_key_exists($group_id, $groups) && $group_id != false) {
				$group = $groups[$group_id];			
			} else {
				$group = array();
			}
			
			//Output group?
			if($current_group != $group) {
				//Close previous fieldset?
				if($current_group !== false) {			
					$out .= '	</div>' . "\n";
					$out .= '</div>' . "\n";					
					$out .= '<!-- END Parameter Group -->' . "\n";										
				}
				$out .= '<!-- START Parameter Group -->' . "\n";										
				$out .= '	<div class="waymark-parameter-group waymark-self-clear waymark-parameter-group-' . $group_id . '" id="waymark-parameter-group-' . $group_id . '">' . "\n";					
				$out .= '		<legend title="Click to expand">' . $group['name'] . '</legend>' . "\n";
				$out .= '		<div class="waymark-parameter-group-content">' . "\n";
				if(array_key_exists('description', $group)) {			
					$out .= '			<p class="waymark-parameter-group-description">' . $group['description'] . '</p>' . "\n";
				}
				$current_group = $group;
			}
			
			foreach($fields as $field) {
				//Set value?
				if(array_key_exists($field['name'], $data)) {
					$set_value = $data[$field['name']];
				} else {
					$set_value = null;
				}	
				
				//Name format (widgets)
				if($input_name_format) {
					$field['name'] = sprintf($input_name_format, $field['name']);
				}					

				//Create input
				$out .= self::create_field($field, $set_value);			
			}
		}
		
		//As long as some groups were output
		if($current_group !== false) {
			$out .= '		</div>' . "\n";
			$out .= '	</div>' . "\n";			
			$out .= '<!-- END Parameter Group -->' . "\n";													
		}

		$out .= '</div>' . "\n";
		$out .= '<!-- END Parameter Container -->' . "\n";
		
		return $out;		
	}
	
	static function process_input($param_def, $param_value) {
		//Do processing
		if(array_key_exists('input_processing', $param_def)) {
			$param_value = self::eval_processes_on_param_value($param_def['input_processing'], $param_value);
		}		
		
		return $param_value;
	}

	static function process_output($param_def, $param_value) {
		//Do processing
		if(array_key_exists('output_processing', $param_def)) {
			$param_value = self::eval_processes_on_param_value($param_def['output_processing'], $param_value);
		}
					
		return $param_value;
	}
	
	static function eval_processes_on_param_value($processes, $param_value) {
		if(is_array($processes)) {
			foreach($processes as $process) {
				//Values stored in array
				if(is_array($param_value)) {
					//Waymark_Helper::debug($param_value, false);

					//Single Value
					if(sizeof($param_value) == 1) {
						//Make string
						$param_value = array_pop($param_value);
			
						//Process
						$param_value = trim($param_value);			
						eval("\$param_value = $process;");				
			
						//Back into array
						$param_value = array($param_value);											
					//Multiple values
					} else {
						//Each
						$param_value_out = array();
						$param_values = $param_value;
						foreach($param_values as $param_value) {
							//Process each value
							$param_value = trim($param_value);									
							eval("\$param_value = $process;");	
			
							$param_value_out[] = $param_value;
						}
									
						//Make string
						$param_value = $param_value_out;
					}					
				//Single value stored in string
				} else {	
					$param_value = trim($param_value);			
					eval("\$param_value = $process;");						
				}	
			}
		}		
		
		return $param_value;
	}
	
	//Thanks to: https://code.tutsplus.com/articles/attaching-files-to-your-posts-using-wordpress-custom-meta-boxes-part-1--wp-22291
// 	static function upload_file($file) {
// 		//Get the file type of the upload
// 		$filetype = wp_check_filetype(basename($file['name']));
// 		 
// 		//File type is supported
// 		if(array_key_exists('type', $filetype) && $filetype['type']) {
// 	    // Use the WordPress API to upload the file
// 	    $upload = wp_upload_bits($file['name'], null, file_get_contents($file['tmp_name']));
// 	
// 	    if(isset($upload['error']) && $upload['error'] != 0) {
// 	    	//!!! - Better error handling
// 	    	wp_die(esc_html__('File upload error.', 'waymark-plugin') . ' (' . $upload['error'] . ')');
// 	    } else {
//         return $upload;     
// 	    }
// 		} else {
// 			//!!! - Better error handling
// 			wp_die(esc_html__('The file type uploaded is not supported.', 'waymark-plugin'));
// 		}
// 	}

	static function get_file_contents($file) {
		//Get the file type of the upload
		$mimes = array(
			'gpx' => 'application/gpx+xml',
			'kml' => 'application/vnd.google-earth.kml+xml',
			'kmz' => 'application/vnd.google-earth.kmz',
			'json' => 'application/geo+json',
			'geojson' => 'application/geo+json'
		);
		$filetype = wp_check_filetype(basename($file['name']), $mimes);
				 
		//File type is supported
		if(array_key_exists('type', $filetype) && $filetype['type']) {
	    return array(
				'file_type' => $filetype['ext'],
				'file_contents' => file_get_contents($file['tmp_name'])
			);
		} else {
			return array(
				'error' => esc_html__('The file type uploaded is not supported.', 'waymark-plugin')
			);
		}
	}	
}
