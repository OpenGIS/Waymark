<?php

class Waymark_Instance extends Waymark_Class {
	protected $parameters = [
		'type' => 'viewer',
		'basemap' => null,
		'add_class' => null,
 		'bounds' => null,			
	];
	
	function __construct($params_in = []) {
		parent::__construct($params_in);
	}	
	
	function add_js() {
		//Create new Map object
		switch($this->get_parameter('type')) {
			case 'viewer' :
			default :
				Waymark_JS::add_call('var Waymark_Map_Viewer = window.Waymark_Map_Factory.viewer()');
			
				break;
		}

		//Config
		Waymark_JS::add_call('var waymark_user_config = ' . json_encode(Waymark_Config::get_map_config()) . ';');				
// 		Waymark_JS::add_call('waymark_user_config.map_height = 600;');				

		if($editor_basemap = $this->get_parameter('basemap')) {
			Waymark_JS::add_call('waymark_user_config.map_init_basemap = "' . $editor_basemap . '"');					
		}
		
		//Go!
		Waymark_JS::add_call('Waymark_Map_Viewer.init(waymark_user_config)');

		if($bounds = $this->get_parameter('bounds')) {		
			Waymark_JS::add_call('Waymark_Map_Viewer.map.fitBounds(' . $bounds . ')');
		}			
	
	}
	
	function get_html() {
		return '<div id="waymark-map" class="waymark-map ' . $this->get_parameter('add_class') . '"></div>';
	}
}