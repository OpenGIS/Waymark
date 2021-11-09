<?php
	
class Waymark_Submission {

	private $allowed;
	private $all_features = ['draw', 'photo', 'file', 'title', 'meta'];
	
	private $data;
	private $Map;
	
	private $user;
	private $status;
	private $alert;	
	private $features = [];	
	
	private $redirect_data;
	private $redirect_url;	
	
	public function __construct($data = array()) {	
		$this->data = $data;	
	
		//Default
		$this->allowed = false;

		//Enabled?
		if(Waymark_Config::get_setting('submission', 'global', 'submission_enable')) {
			// ======= Check User ========
		
			$this->user = wp_get_current_user();

			//Signed-in user	
			if(sizeof($this->user->roles)) {			
				//Admin
				if(in_array('administrator', $this->user->roles)) {
					$this->allowed = true;
				
					$this->status = 'publish';
				
					//Admin get all features
					$this->features = $this->all_features;
				//Current user can
				} elseif($this->user_can_submit()) {
					$this->allowed = true;

					$this->status = Waymark_Config::get_setting('submission', 'from_users', 'submission_status');
					$this->alert = Waymark_Config::get_setting('submission', 'from_users', 'submission_alert');

					$this->features = Waymark_Config::get_setting('submission', 'from_users', 'submission_features');				
				//Treat as public?
				} elseif(Waymark_Config::get_setting('submission', 'from_public', 'submission_public')) {
					$this->allowed = true;

					$this->status = Waymark_Config::get_setting('submission', 'from_public', 'submission_status');				
					$this->alert = Waymark_Config::get_setting('submission', 'from_public', 'submission_alert');
				
					$this->features = Waymark_Config::get_setting('submission', 'from_public', 'submission_features');
				//Curent user can not
				} else {
					$this->allowed = false;		
				}
			//Guest
			} else {
				//Public submissions allowed	
				if(Waymark_Config::get_setting('submission', 'from_public', 'submission_public')) {
					$this->allowed = true;

					$this->status = Waymark_Config::get_setting('submission', 'from_public', 'submission_status');				
					$this->alert = Waymark_Config::get_setting('submission', 'from_public', 'submission_alert');		

					$this->features = Waymark_Config::get_setting('submission', 'from_public', 'submission_features');					
				//NO Public submissions!
				} else {
					$this->allowed = false;
				}		
			}

			// ======= Set redirect =======
		
			//Valid URL provided
			if(array_key_exists('waymark_redirect', $this->data) && filter_var($this->data['waymark_redirect'], FILTER_VALIDATE_URL)) {
				$this->redirect_url = $this->data['waymark_redirect'];
			//Default to home
			} else {
				$this->redirect_url = get_option('home');		
			}
		
		}
			
		//Load
		if($this->allowed) {
			Waymark_Helper::require('Admin/Waymark_AJAX.php');		
		}
	}
	
	public function get_allowed() {
		return $this->allowed;
	}

	public function get_status() {
		return $this->status;
	}

	public function get_alert() {
		return $this->alert;
	}

	public function get_features() {
		return $this->features;
	}

	private function user_can_submit() {
		//Guest
		if(! sizeof($this->user->roles)) {
			return false;
		}

		//Admin
		if(in_array('administrator', $this->user->roles)) {
			return true;
		}
		
		//Other user
		$submission_roles = Waymark_Config::get_setting('submission', 'from_users', 'submission_roles');
		//Current role can
		if(is_array($submission_roles) && sizeof(array_intersect($this->user->roles, $submission_roles))) {
			return true;
		}
		
		return false;
	}

	public function render_front($data = array()) {
		//Load CSS/Scripts for rich text editor
		wp_enqueue_editor();

		$content = '<!-- START Waymark Submission -->' . "\n";
		$content .= '<div id="waymark-submission">' . "\n";
	
		//Ensure Submissions allowed
		if(! $this->allowed) {
			$content .= '</div>' . "\n";
			$content .= '<!-- END Waymark Submission -->' . "\n";
			
			return $content;
		}

		global $post;
				
		//Messages
		if(array_key_exists('waymark_status', $_REQUEST)) {
			switch($_REQUEST['waymark_status']) {
				case 'error' :
					$content .= '<div class="waymark-message waymark-error">';

					//Custom message?
					if(isset($_REQUEST['waymark_message'])) {
						$content .= $_REQUEST['waymark_message'];
					} else {
						$content .= __('There was an error with your submission.', 'waymark');					
					}

					$content .= '</div>' . "\n";					

					break;
				case 'draft' :
					$content .= '	<div class="waymark-message waymark-success">';
					$content .= __('Your submission has been received and is awaiting moderation.', 'waymark');
					$content .= '	</div>' . "\n";					

					break;
				case 'publish' :
					$content .= '	<div class="waymark-message waymark-success">';

					//Custom message?
					if(isset($_REQUEST['waymark_map_id'])) {
						$content .= sprintf(__('Your submission has been <a href="%s">published</a>.', 'waymark'), get_permalink($_REQUEST['waymark_map_id']));
					} else {
						$content .= __('Your submission has been published.', 'waymark');
					}

					$content .= '	</div>' . "\n";					

					break;
			}
		}

		//Create new Map object
		Waymark_JS::add_call('var Waymark_Map_Editor = window.Waymark_Map_Factory.editor()');

		//Default view
		if($default_latlng = Waymark_Config::get_setting('misc', 'map_options', 'map_default_latlng')) {
			//We have a valid LatLng
			if($default_latlng_array = Waymark_Helper::latlng_string_to_array($default_latlng)) {
				Waymark_JS::add_call('Waymark_Map_Editor.fallback_latlng = [' . $default_latlng_array[0] . ',' . $default_latlng_array[1] . ']');					
			}
		}
		if($default_zoom = Waymark_Config::get_setting('misc', 'map_options', 'map_default_zoom')) {
			Waymark_JS::add_call('Waymark_Map_Editor.fallback_zoom = ' . $default_zoom);		
		}

		//Map Div
		$content .= '	<div id="waymark-map" class="waymark-submission"></div>' . "\n";

		//Output Config
		Waymark_JS::add_chunk('var waymark_settings  = ' . Waymark_Config::get_settings_js());			

		//Get Map config
		$map_config = Waymark_Config::get_map_config();

		//Each Overlay Type
		foreach(['marker', 'line', 'shape'] as $overlay_name) {
			$submission_types = [];
			//Only include Types set for Submissions
			foreach($map_config[$overlay_name . '_types'] as $type) {
				if($type[$overlay_name . '_submission']) {
					$submission_types[] = $type;
				}	
			}
			
			//If none (i.e. no Types set to Submission in Settings)
			if(! sizeof($submission_types)) {
				//Create blank
				$blank = [];
				foreach(array_keys($type) as $key) {
					switch($key) {
						case 'fill_opacity':
							$value = '0.5';
							
							break;
						case 'line_colour' :
						case 'marker_colour' :
							$value = '#000';

							break;
						default:
							$value = '';
							
							break;
					}
					$blank[$key] = $value;
				}
				$submission_types[] = $blank;
			}
			
			//Update Config
			$map_config[$overlay_name . '_types'] = $submission_types;
		}
		
		Waymark_JS::add_call('var waymark_user_config = ' . json_encode($map_config) . ';');				
		Waymark_JS::add_call('waymark_user_config.map_height = 600;');				

		//Set basemap
		if($editor_basemap = Waymark_Config::get_setting('misc', 'editor_options', 'editor_basemap')) {
			Waymark_JS::add_call('waymark_user_config.map_init_basemap = "' . $editor_basemap . '"');		
		}

		//Go!
		Waymark_JS::add_call('Waymark_Map_Editor.init(waymark_user_config)');
		
		//Disable certain featureS?
		$disable_features = array_diff($this->all_features, $this->features);
		foreach($disable_features as $disable) {
			Waymark_JS::add_call("
				Waymark_Map_Editor.jq_map_container.addClass('waymark-disable-$disable');
			");
		}

		$content .= '	<form action="' . Waymark_Helper::http_url() . '" method="post" id="waymark-map-add" class="waymark-map-add">' . "\n";
		$content .= '		<input type="hidden" name="waymark_action" value="public_add_map" />' . "\n";
		$content .= '		<input type="hidden" name="waymark_security" value="' . wp_create_nonce(Waymark_Config::get_item('nonce_string')) . '" />' . "\n";
		$content .= '		<input type="hidden" name="waymark_redirect" value="' . get_permalink($post) . '" />' . "\n";
	
		//Title
		if(in_array('title', $this->features)) {
			$content .= '		<div class="waymark-control-group waymark-control-type-text" id="map_date-container">' . "\n";
			$content .= '			<label class="waymark-control-label" for="map_date">' . __('Title', 'waymark') . '</label>' . "\n";
			$content .= '			<div class="waymark-controls">' . "\n";
			$content .= '				<input class="waymark-input" type="text" name="map_title" id="map_title" value="">' . "\n";
			$content .= '			</div>' . "\n";
			$content .= '		</div>' . "\n";		
		}

		//Create Form
		$Map = new Waymark_Map;		
		$content .= $Map->create_form();		

		$content .= '		<input type="submit" value="' . __('Submit', 'waymark') . '" class="button button-primary button-large" />' . "\n";

		$content .= '	</form>' . "\n";

		$content .= '</div>' . "\n";
		$content .= '<!-- END Waymark Submission -->' . "\n";

		return $content;		
	}	
	
	public function create_map() {
		//Ensure Submissions allowed
		if(! $this->allowed) {
			return false;
		}

		//Data checks
		if(! array_key_exists('map_data', $this->data) || ! $this->data['map_data']) {
			$this->status = 'error';
			$this->redirect_data['waymark_message'] = __('Your Map was empty.', 'waymark');
			
			return false;		
		}
		
		//If no Title provided
		if(! array_key_exists('map_title', $this->data) || ! $this->data['map_title']) {
			$this->data['map_title'] = esc_html__('Submission', 'waymark') . ' ' . substr(md5(rand(0,999999)), 0, 5);
		}		

		//Create Map
		$this->Map = new Waymark_Map;
		$this->Map->set_data($this->data);				
		
		$this->Map->create_post($this->data['map_title'], array(
			'post_status' => $this->status
		));		

		$this->redirect_data['waymark_map_id'] = $this->Map->post_id;
		
		return $this->Map->post_id;
	}
	
	public function do_redirect() {
		$this->redirect_data['waymark_status'] = $this->status;

		if(sizeof($this->redirect_data)) {
			//Append query string
			$this->redirect_url .= (strpos($this->redirect_url, '?') === false) ? '?' : '&';

			$this->redirect_url .= http_build_query($this->redirect_data);				
			
			$this->redirect_url .= '#waymark-submission';
		}
	
		wp_redirect($this->redirect_url);
	}
}