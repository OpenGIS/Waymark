<?php

class Waymark_AJAX {

	function __construct() {
		//Public
		add_action('wp_ajax_nopriv_waymark_read_file', array($this, 'public_handle_read_file'));

		//User
		add_action('wp_ajax_waymark_read_file', array($this, 'handle_read_file'));
		add_action('wp_ajax_waymark_get_attatchment_meta', array($this, 'get_attatchment_meta'));

		//Add nonce
		Waymark_JS::add_chunk('var waymark_security = "' . wp_create_nonce(Waymark_Config::get_item('nonce_string')) . '";');
	}

	//Only crunch the sizes we want
	function intermediate_image_sizes_advanced($sizes) {
		foreach ($sizes as $size_key => $size_data) {
			if (!in_array($size_key, Waymark_Config::get_item('media_library_sizes'))) {
				unset($sizes[$size_key]);
			}
		}

		return $sizes;
	}

	function get_attatchment_meta() {
		check_ajax_referer(Waymark_Config::get_item('nonce_string'), 'waymark_security');

		$response = [
			'error' => esc_html__('No image metadata available.', 'waymark'),
		];

		//Get image metadata
		if (array_key_exists('attachment_id', $_POST) && is_numeric($_POST['attachment_id'])) {
			$attachment_metadata = wp_get_attachment_metadata($_POST['attachment_id']);

			if (array_key_exists('image_meta', $attachment_metadata) && is_array($attachment_metadata['image_meta'])) {
				$response = $attachment_metadata['image_meta'];
			}
		}

		header('Content-Type: text/javascript');
		echo json_encode($response);
		die;
	}

	//Thanks! https://wordpress.stackexchange.com/a/290275 & https://stackoverflow.com/a/45507980
	function public_upload_dir($dir_data) {
		$custom_dir = 'waymark_submission';

		//Custom directory (create or already exists)
		if (wp_mkdir_p($dir_data['basedir'] . '/' . $custom_dir)) {
			foreach ($dir_data as $data_key => &$data_value) {
				if (!in_array($data_key, ['error'])) {
					//Replace with our custom sub-directory
					$data_value = str_replace(trim($dir_data['subdir'], '/'), $custom_dir, $data_value);
				}
			}
		}

		return $dir_data;
	}

	//Public Submission
	function public_handle_read_file() {
		check_ajax_referer(Waymark_Config::get_item('nonce_string'), 'waymark_security');

		//Change upload location? (empty string means use default Media Library directory)
		if (Waymark_Config::get_setting('submission', 'from_public', 'submission_upload_dir')) {
			add_filter('upload_dir', array($this, 'public_upload_dir'));
		}

		//Only crunch the sizes we want
		add_filter('intermediate_image_sizes_advanced', array($this, 'intermediate_image_sizes_advanced'));

		$this->handle_read_file();
	}

	//Perform additional checks	to ensure user is allowed to do this
	function handle_read_file() {
		check_ajax_referer(Waymark_Config::get_item('nonce_string'), 'waymark_security');

		//Back-end
		if (strpos(wp_get_referer(), get_admin_url()) !== false) {
			//Let's read some files!
			$this->read_file();
			//Front-end
		} else {
			//This is a submission
			Waymark_Helper::inc('Front/Waymark_Submission.php');
			$Submission = new Waymark_Submission;

			$response = [];

			//If we have files
			if (sizeof($_FILES)) {
				//Each file
				foreach ($_FILES as $file_key => $file_data) {
					//If no WP error
					if (!$file_data['error']) {
						switch ($file_key) {
						//Read from file
						case 'add_file':
							//Ensure feature allowed
							if (!in_array('file', $Submission->get_features())) {
								//Not allowed
								$response['error'] = esc_html__('Operation not allowed.', 'waymark');
							}

							break;
						//Image upload
						case 'marker_photo':
						case 'add_photo':
							//Ensure feature allowed
							if (!in_array('photo', $Submission->get_features())) {
								//Not allowed
								$response['error'] = esc_html__('Operation not allowed.', 'waymark');
							}

							break;
						}
						//WP Error
					} else {
						//Not allowed
						$response['error'] = esc_html__('Operation not allowed.', 'waymark');
					}
				}
				//No files
			} else {
				//Not allowed
				$response['error'] = $file_data['error'];
			}

			//Error?
			if (isset($response['error'])) {
				//Do not continue
				header('Content-Type: text/javascript');
				echo json_encode($response);

				die;
				//Good to continue
			} else {
				//Let's read some files!
				$this->read_file();
			}
		}
	}

	function read_file() {
		$response = [];

		//If we have files
		if (sizeof($_FILES)) {
			//Each file
			foreach ($_FILES as $file_key => $file_data) {
				$response = $file_data;

				//If no WP error
				if (!$file_data['error']) {
					switch ($file_key) {
					//Read file contents
					case 'add_file':
						//Attempt to read file
						$file_contents = Waymark_Input::get_file_contents($file_data);

						//Good to proceed
						if ($file_contents) {

							// If GeoJSON
							if ($file_contents['file_type'] == 'geojson' || $file_contents['file_type'] == 'json') {
								// Valid, with features
								$feature_collection = Waymark_GeoJSON::string_to_feature_collection($file_contents['file_contents']);
								if ($feature_collection && Waymark_GeoJSON::get_feature_count($feature_collection)) {
									// Process Import
									$feature_collection = Waymark_GeoJSON::process_import($feature_collection);
									$file_contents['file_contents'] = Waymark_GeoJSON::feature_collection_to_string($feature_collection);
								}
							}

							// Add to response
							$response = array_merge($response, $file_contents);

							//Unknown error
						} else {
							$response['error'] = esc_html__('Could not read the file.', 'waymark');
						}

						break;
					case 'marker_photo':
					case 'add_photo':
						//Upload
						$upload_response = media_handle_upload($file_key, 0);

						//Success
						if (!is_wp_error($upload_response)) {
							$attachment_id = $upload_response;
							$response['id'] = $attachment_id;

							//Get URL
							$attachment_url = wp_get_attachment_url($attachment_id);
							$response['url'] = $attachment_url;

							//Meta?
							$attachment_metadata = wp_get_attachment_metadata($attachment_id);

							//Only when adding an image
							if ($file_key == 'add_photo') {
								//Image Meta
								if (array_key_exists('image_meta', $attachment_metadata) && is_array($attachment_metadata['image_meta'])) {
									//Location EXIF
									if (array_key_exists('GPSLatitudeNum', $attachment_metadata['image_meta']) && array_key_exists('GPSLongitudeNum', $attachment_metadata['image_meta'])) {
										$response = array_merge($response, array(
											'GPSLatitudeNum' => $attachment_metadata['image_meta']['GPSLatitudeNum'],
											'GPSLongitudeNum' => $attachment_metadata['image_meta']['GPSLongitudeNum'],
										));
									}
								}
							}

							//Sizes
							if (array_key_exists('sizes', $attachment_metadata) && is_array($attachment_metadata['sizes'])) {
								//Each size
								foreach ($attachment_metadata['sizes'] as $size_key => &$size) {
									//Add URL
									$size['url'] = wp_get_attachment_image_url($attachment_id, $size_key);
								}

								$response = array_merge($response, array(
									'sizes' => $attachment_metadata['sizes'],
								));
							}
							//Error
						} else {
							//WP Error
							if ($upload_response->has_errors()) {
								//Use it
								$response['error'] = $upload_response->get_error_message();
							}
						}

						break;
					}
					//WP error
				}
			}
		}

		//No response?
		if (!sizeof($response)) {
			$response['error'] = esc_html__('Unknown file upload error.', 'waymark');
		}

		header('Content-Type: text/javascript');
		echo json_encode($response);
		die;
	}
}
new Waymark_AJAX;