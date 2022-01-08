<?php

require_once('Waymark_Object.php');
	
class Waymark_Query extends Waymark_Object {
	
	public $post_type = 'waymark_query';
	
	function __construct($post_id = null) {
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
				'default' => Waymark_Config::get_setting('query', 'defaults', 'query_area'),
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
				'title' => 'Overpass Turbo Query',
				'default' => Waymark_Config::get_setting('query', 'defaults', 'query_overpass'),
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
//				'class' => 'waymark-hidden'
			)			
		);

		parent::__construct($post_id);
	}		
	
	function create_form() {
		if(! is_admin()) {
			return;
		}
		
		//Initial load - no data yet
		if(! sizeof($this->data)) {
			$query_overpass = Waymark_Config::get_setting('query', 'defaults', 'query_overpass');
			$query_area_array = explode(',', Waymark_Config::get_setting('query', 'defaults', 'query_area'));		
		//We have data
		} else {
			//Load existing data		
			if(isset($this->data['query_data']) && Waymark_GeoJSON::get_feature_count($this->data['query_data'])) {
				Waymark_JS::add_call('Waymark_Map_Viewer.load_json(' . $this->data['query_data'] . ', false);');								
			}

			//New request?
			if(isset($this->data['query_overpass']) && isset($this->data['query_area'])) {
				$query_overpass = $this->data['query_overpass'];
				$query_area_array = explode(',', $this->data['query_area']);
				$query_overpass_string = $query_area_array[1] . ',' . $query_area_array[0] . ',' . $query_area_array[3] . ',' . $query_area_array[2];				
				
				//Build request
				$Request = new Waymark_Overpass_Request();							
				$Request->set_config('bounding_box', $query_overpass_string);
				$Request->set_config('cast_overlay', $this->data['query_cast_overlay']);
				$Request->set_parameters(array(
					'data' => html_entity_decode($query_overpass)
				));

				//Execute request
				$response = $Request->get_processed_response();
				
				//Message?
				if(array_key_exists('message', $response)) {
					$class = '';
					if(array_key_exists('status', $response)) {
						if($response['status'] == 'success') {
							$class .= ' notice-success';
						} elseif($response['status'] == 'error') {
							$class .= ' notice-error';						
						}
					}
					echo '<div class="notice' . $class . '">' . "\n";
					echo '	<p>' . $response['message'] . '</p>' . "\n";
					echo '</div>' . "\n";
				}
		
				//Raw Output
		// 		if(! array_key_exists('error', $response) && array_key_exists('raw', $response)) {
				if(array_key_exists('raw', $response)) {
					$response_geojson = [];						

					//Output Raw Response
					echo Waymark_Input::create_field([
		// 				'input_types' => array('meta'),
						'name' => 'response_raw',
						'id' => 'response_raw',
						'type' => 'textarea',				
		// 				'tip' => 'Overpass Turbo Query. {{bbox}} will be replaced by the Map area.',
						'group' => '',
						'title' => 'Overpass Response',
						'default' => json_encode($response['raw'])
					]);
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
					$this->save_meta();
			
					Waymark_JS::add_call('Waymark_Map_Viewer.load_json(' . $this->data['query_data'] . ', false);');											
				}
			}
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