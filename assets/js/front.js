function waymark_setup_map_shortcode() {
	jQuery('.waymark-shortcode').each(function() {
		var shortcode = jQuery(this);
		
		//Header
		jQuery('.waymark-header', shortcode).each(function() {
			var header = jQuery(this);
			var open = jQuery('.waymark-link', header).first();
			var meta = jQuery('.waymark-meta', header);
			
			//Do we have shortcode meta?
			if(meta.length) {
				var has_export = jQuery('.waymark-map-export', meta).length;		
				var meta_items = jQuery('.waymark-meta-item', meta);
				
				//Is export the only meta being displayed?
				var export_only = has_export && (meta_items.length === 1);
				
				//Only export on mobile
// 				if(export_only && waymark_is_touch_device()) {
// 					return;
// 				}
				
				//Open button
				open.on('click', function() {
					meta.show();
				
					return false;
				});
				jQuery('i', open).attr('class', 'ion ion-map')
				
				//Setup Meta
				meta.css('height', shortcode.height() + 'px');	
			
				//Close button
				var close = jQuery('<i />')
					.attr({
						'class': 'waymark-meta-close ion ion-close'
					})
					.on('click', function() {
						meta.hide();
					});		
				meta.append(close);
			
			}			
		});
	});
}

jQuery(document).ready(function() {
	waymark_setup_map_shortcode();
});