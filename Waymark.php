<?php
	
/*
Plugin Name: Waymark
Plugin URI: https://www.waymark.dev/
Description: Mapping with WordPress made easy. With Waymark enabled, click on the "Maps" link in the sidebar to create and edit Maps. Once you are happy with your Map, copy the Waymark shortcode and add it to your content.
Version: 0.9.25-A
Text Domain: waymark
Author: Joe Hawes
Author URI: https://www.morehawes.co.uk/
License: GPLv2
*/

//Base
require_once('inc/Waymark_Config.php');
require_once('inc/Waymark_Types.php');
require_once('inc/Waymark_Install.php');

//Helpers
require_once('inc/Helpers/Waymark_Helper.php');
require_once('inc/Helpers/Waymark_Input.php');
require_once('inc/Helpers/Waymark_GeoJSON.php');

//Objects
require_once('inc/Objects/Waymark_Map.php');
require_once('inc/Objects/Waymark_Collection.php');
require_once('inc/Objects/Waymark_Class.php');
require_once('inc/Objects/Waymark_Query.php');

//Taxonomies
require_once('inc/Taxonomies/Waymark_Collection_Taxonomy.php');
require_once('inc/Taxonomies/Waymark_Query_Taxonomy.php');

//Front
require_once('inc/Waymark_Front.php');

//Admin
require_once('inc/Waymark_Admin.php');
