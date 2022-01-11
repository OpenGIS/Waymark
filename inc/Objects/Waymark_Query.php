<?php

require_once('Waymark_Object.php');
	
class Waymark_Query extends Waymark_Object {
	
	public $post_type = 'waymark_query';
	
	function __construct($post_id = null, $query_area = false) {
		if($query_area) {
			$qa = explode(',', $query_area);
			$query_overpass_string = $qa[1] . ',' . $qa[0] . ',' . $qa[3] . ',' . $qa[2];				
			
			$this->set_data_item('query_area', $query_overpass_string);
		}
		
 		$marker_types = Waymark_Helper::get_object_types('marker', 'marker_title', true);
 		$default_marker_type = array_keys($marker_types)[0];

		$line_types = Waymark_Helper::get_object_types('line', 'line_title', true);
		$default_line_type = array_keys($line_types)[0];
	
		//Query Data
		$this->parameters = array(
			'query_area' => array(
 				'input_types' => array('meta'),
				'name' => 'query_area',
				'id' => 'query_area',
				'type' => 'text',				
//				'tip' => 'Query Area.',
				'group' => '',
				'title' => 'query_area',
// 				'default' => Waymark_Config::get_setting('query', 'defaults', 'query_area'),
				'class' => 'waymark-hidden'
			),					
			'query_overpass' => array(
				'input_types' => array('meta'),
				'name' => 'query_overpass',
				'id' => 'query_overpass',
				'type' => 'textarea',				
				'tip' => 'OverpassQL Query.',
				'tip_link' => 'https://osm-queries.ldodds.com/tutorial/',				
				'group' => '',
				'title' => 'Overpass QL Query',
				'default' => Waymark_Config::get_setting('query', 'defaults', 'query_overpass'),
				'output_processing' => array(
					'html_entity_decode($param_value)'
				)				
			),
			'query_overpass_response' => array(
				'input_types' => array('meta'),
				'name' => 'query_overpass_response',
				'id' => 'query_overpass_response',
				'type' => 'textarea',				
// 				'tip' => 'OverpassQL Response.',
// 				'tip_link' => 'https://osm-queries.ldodds.com/tutorial/',				
				'group' => '',
				'title' => 'Overpass Turbo Response',
				'output_processing' => array(
					'html_entity_decode($param_value)'
				)				
			),
			'query_cast_overlay' => array(
				'input_types' => array('meta'),
				'name' => 'query_cast_overlay',
				'id' => 'query_cast_overlay',
				'type' => 'select',				
				'tip' => 'Marker/Line/Shape',
				'group' => '',
				'title' => 'Overlay Type',
				'default' => Waymark_Config::get_setting('query', 'defaults', 'query_cast_overlay'),
				'options' => [
					'marker' => 'Marker',
					'line' => 'Line',
//					'shape' => 'Shape'										
				]
			),
			'query_cast_marker_type' => array(
				'input_types' => array('meta'),
				'name' => 'query_cast_marker_type',
				'id' => 'query_cast_marker_type',
				'type' => 'select',				
				'tip' => 'Cast to Type',
				'group' => '',
				'title' => 'Marker Type',
				'default' => $default_marker_type,
				'options' => Waymark_Helper::get_object_types('marker', 'marker_title', true)
			),
			'query_cast_line_type' => array(
				'input_types' => array('meta'),
				'name' => 'query_cast_line_type',
				'id' => 'query_cast_line_type',
				'type' => 'select',				
				'tip' => 'Cast to Type',
				'group' => '',
				'title' => 'Line Type',
				'default' => $default_line_type,
				'options' => Waymark_Helper::get_object_types('line', 'line_title', true)
			),											
			//!!!
			'query_data' => array(
				'input_types' => array('meta'),
				'name' => 'query_data',
				'id' => 'query_data',
				'type' => 'textarea',				
				'group' => '',
				'title' => 'Query Data',
				'class' => (Waymark_Config::get_setting('misc', 'advanced', 'debug_mode')) ? '' : 'waymark-hidden'
			)			
		);

		parent::__construct($post_id);
		
		//Waymark_Helper::debug($this->data, false);	
		
		//Execute?
		if($this->can_execute()) {
			$this->do_execute();
		}
	}	
	
	function can_execute() {
		return isset($this->data['query_overpass']) && isset($this->data['query_area']);
	}	
	
	function do_execute() {
		if(! $this->can_execute()) {
			return false;
		}	

		//Build request
		$Request = new Waymark_Overpass_Request();							
		$Request->set_config('bounding_box', $this->get_data_item('query_area'));
		$Request->set_config('cast_overlay', $this->data['query_cast_overlay']);
		$Request->set_parameters(array(
			'data' => html_entity_decode($this->data['query_overpass'])
		));

		//Execute request
		$response = $Request->get_processed_response();

	
	
		//Message?
// 				if(array_key_exists('message', $response)) {
// 					$class = '';
// 					if(array_key_exists('status', $response)) {
// 						if($response['status'] == 'success') {
// 							$class .= ' notice-success';
// 						} elseif($response['status'] == 'error') {
// 							$class .= ' notice-error';						
// 						}
// 					}
// 					echo '<div class="notice' . $class . '">' . "\n";
// 					echo '	<p>' . $response['message'] . '</p>' . "\n";
// 					echo '</div>' . "\n";
// 				}

		//Raw Output
		if(array_key_exists('raw', $response)) {
			$this->set_data_item('query_overpass_response', $response['raw']);
		}

		//We have GeoJSON to display
		if(array_key_exists('geojson', $response)) {
			//What kind of Overlay?
			switch($this->data['query_cast_overlay']) {
				//Markers
				case 'marker' :
					$response['geojson'] = Waymark_GeoJSON::update_feature_property($response['geojson'], 'type', $this->data['query_cast_marker_type']);						

					break;

				//Lines
				case 'line' :
					$response['geojson'] = Waymark_GeoJSON::update_feature_property($response['geojson'], 'type', $this->data['query_cast_line_type']);

					break;
			}		

			$this->data['query_data'] = json_encode($response['geojson']);

//					Waymark_JS::add_call('Waymark_Map_Viewer.load_json(' . $this->data['query_data'] . ', false);');											
//				}
//			}
			$this->save_meta();
		}		
	}
	
	function create_form() {
		if(! is_admin()) {
			return;
		}

		$feature_count = 0;
		if(isset($this->data['query_data'])) {
			$query_data = json_decode($this->data['query_data'], null, 512, JSON_OBJECT_AS_ARRAY);
			$feature_count = Waymark_GeoJSON::get_feature_count($query_data);
		}

		//Load existing data		
		if($feature_count) {
			Waymark_JS::add_call('Waymark_Map_Viewer.load_json(' . json_encode($query_data) . ', false);');								

			$query_area_array = explode(',', $this->data['query_area']);
		//No data (yet)
		} else {
			$query_area_array = explode(',', Waymark_Config::get_setting('query', 'defaults', 'query_area'));		
		}

		//Area
		$query_leaflet_string = '[[' . $query_area_array[1] . ',' . $query_area_array[0] . '],[' . $query_area_array[3] . ',' . $query_area_array[2] . ']]';

		//Bounding Box
		Waymark_JS::add_call('
	//Query
	var bounds = ' . $query_leaflet_string . ';
	var rectangle = L.rectangle(bounds, {
		color: "#ff7800",
		weight: 1
	}).addTo(Waymark_Map_Viewer.map);
	rectangle.enableEdit();
	Waymark_Map_Viewer.map.on(\'editable:vertex:dragend\', function(e) {
		jQuery(\'#query_area\').val(e.layer.getBounds().toBBoxString());
	});
	Waymark_Map_Viewer.map.fitBounds(bounds)');	
		
		echo Waymark_Helper::build_query_map_html();

		return parent::create_form();	
	}
}