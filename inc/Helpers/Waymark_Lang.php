<?php

class Waymark_Lang {
	public static function get_js_lang() {
		return [
			//Viewer
			'action_fullscreen_activate' => esc_attr__('View Fullscreen', 'waymark'),
			'action_fullscreen_deactivate' => esc_attr__('Exit Fullscreen', 'waymark'),
			'action_locate_activate' => esc_attr__('Show me where I am', 'waymark'),
			'action_zoom_in' => esc_attr__('Zoom in', 'waymark'),
			'action_zoom_out' => esc_attr__('Zoom out', 'waymark'),
			'label_total_length' => esc_attr__('Total Length: ', 'waymark'),
			'label_max_elevation' => esc_attr__('Max. Elevation: ', 'waymark'),
			'label_min_elevation' => esc_attr__('Min. Elevation: ', 'waymark'),
			'label_ascent' => esc_attr__('Total Ascent: ', 'waymark'),
			'label_descent' => esc_attr__('Total Descent: ', 'waymark'),

			//Editor
			'add_line_title' => esc_attr__('Draw a Line', 'waymark'),
			'add_photo_title' => esc_attr__('Upload an Image', 'waymark'),
			'add_marker_title' => esc_attr__('Place a Marker', 'waymark'),
			'add_rectangle_title' => esc_attr__('Draw a Rectangle', 'waymark'),
			'add_polygon_title' => esc_attr__('Draw a Polygon', 'waymark'),
			'add_circle_title' => esc_attr__('Draw a Circle', 'waymark'),
			'upload_file_title' => esc_attr__('Read Lines and Markers from file (GPX/KML/GeoJSON supported, which most apps should Export to)', 'waymark'),
			'action_duplicate' => esc_attr__('Duplicate', 'waymark'),
			'action_delete' => esc_attr__('Delete', 'waymark'),
			'action_edit' => esc_attr__('Edit', 'waymark'),
			'action_edit_done' => esc_attr__('Finish editing', 'waymark'),
			'action_upload_image' => esc_attr__('Upload Image', 'waymark'),
			'object_title_placeholder' => esc_attr__('Title', 'waymark'),
			'object_image_placeholder' => esc_attr__('Image URL', 'waymark'),
			'object_description_placeholder' => esc_attr__('Description', 'waymark'),
			'object_type_label' => esc_attr__('Type', 'waymark'),
			'marker_latlng_label' => esc_attr__('Lat,Lng', 'waymark'),
			'action_delete_confirm' => esc_attr__('Are you sure you want to delete this', 'waymark'),
			'action_search_placeholder' => esc_attr__('Search...', 'waymark'),
			'object_label_marker' => esc_attr__('Marker', 'waymark'),
			'object_label_line' => esc_attr__('Line', 'waymark'),
			'object_label_shape' => esc_attr__('Shape', 'waymark'),
			'object_label_marker_plural' => esc_attr__('Markers', 'waymark'),
			'object_label_line_plural' => esc_attr__('Lines', 'waymark'),
			'object_label_shape_plural' => esc_attr__('Shapes', 'waymark'),
			'error_message_prefix' => esc_attr__('Waymark Error', 'waymark'),
			'info_message_prefix' => esc_attr__('Waymark Info', 'waymark'),
			'error_file_type' => esc_attr__('This file type is not supported.', 'waymark'),
			'error_file_conversion' => esc_attr__('Could not convert this file to GeoJSON.', 'waymark'),
			'error_file_upload' => esc_attr__('File upload error.', 'waymark'),
			'error_photo_meta' => esc_attr__('Could not retrieve Image metadata.', 'waymark'),
			'info_exif_yes' => esc_attr__('Image location metadata (EXIF) detected!', 'waymark'),
			'info_exif_no' => esc_attr__('Image location metadata (EXIF) NOT detected.', 'waymark'),
			'no_direction' => esc_attr__('No Direction', 'waymark'),
			'show_direction' => esc_attr__('Show Direction', 'waymark'),
			'reverse_direction' => esc_attr__('Reverse Direction', 'waymark'),
		];
	}
}