<?php
	
/*
Plugin Name: Waymark
Plugin URI: https://www.joesway.ca/waymark/demo/
Description: Mapping with WordPress made easy. With Waymark enabled, click on the "Maps" link in the sidebar to create and edit Maps. Once you are happy with your Map, copy the Waymark shortcode and add it to your content.
Version: 0.9.14
Author: Joe Hawes
Author URI: https://www.josephhawes.co.uk/
License: GPL2
*/

//Base
require_once('inc/Waymark_Config.php');
require_once('inc/Waymark_Types.php');
require_once('inc/Waymark_Taxonomies.php');
require_once('inc/Waymark_Install.php');

//Objects
require_once('inc/Objects/Waymark_Map.php');
require_once('inc/Objects/Waymark_Collection.php');

//Helpers
require_once('inc/Helpers/Waymark_Helper.php');
require_once('inc/Helpers/Waymark_Input.php');

//Front
require_once('inc/Waymark_Front.php');

//Admin
require_once('inc/Waymark_Admin.php');
