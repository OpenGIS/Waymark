function waymark_setup_map_export() {
	//Each Export field
	jQuery('.waymark-map-export').each(function() {
		var export_container = jQuery(this);
		
		//Container
		var export_parent = export_container.parents('.waymark-meta-export_data');
	
		//Not for touch devices	
		if(waymark_is_touch_device()) {
			//Hide it
			export_parent.hide();
		//All other devices
		} else if(export_container) {
			var export_link = jQuery('a', export_container);
			var export_select = jQuery('select', export_container);
		
			//Hide Export row if no data to export
			if(typeof Waymark !== 'undefined') {
				Waymark.map.on('layerremove layeradd', function() {
					var active_layer_count = 0;
					Waymark.map_data.eachLayer(function(layer) {
						//If visible
						if(Waymark.map.hasLayer(layer)) {	
							active_layer_count++;
						}				
					});

					if(active_layer_count > 0) {
						export_parent.show();
					} else {
						export_parent.hide();			
					}
				});			
			}		
		
			//When clicked
			export_link.on('click', function(e) {
				var export_format = export_select.val() ? export_select.val() : 'geojson';
		
				//Get data layer from Leaflet		
				var map_export_layers = Waymark_L.layerGroup();
				Waymark.map_data.eachLayer(function(layer) {
					//If visible
					if(Waymark.map.hasLayer(layer)) {	
						for(key in layer.feature.properties) {
							//If we have something
							if(typeof layer.feature.properties[key] != 'undefined') {
								if(key == 'title' && export_format == 'kml') {
									layer.feature.properties['name'] = layer.feature.properties[key];
									delete layer.feature.properties[key];
								}
							//Nothing here
							} else {
								//Get rid of it
								delete layer.feature.properties[key];						
							}
						}
						//console.log(layer.feature.properties)
							
						map_export_layers.addLayer(layer);
					}
				});
				var map_data_geojson = map_export_layers.toGeoJSON();		
					
				//return false;
					
				switch(export_select.val()) {
					//GPX
					case 'gpx' :
						//Convert to GPX 
						//Thanks! https://github.com/tyrasd/togpx
						var map_data_gpx = togpx(map_data_geojson);
						var map_data_export = 'application/gpx+xml;charset=utf-8,' + encodeURIComponent(map_data_gpx);
						var file_extension = 'gpx';
				
						break;
				
					//KML
					case 'kml' :
						//Convert to KML 
						//Thanks! https://github.com/mapbox/tokml & https://github.com/maphubs/tokml
						var map_data_kml = tokml(map_data_geojson);
						var map_data_export = 'application/vnd.google-earth.kml+xml;charset=utf-8,' + encodeURIComponent(map_data_kml);
						var file_extension = 'kml';
				
						break;
			
					//GeoJSON
					default:
					case 'geojson' :
						var map_data_export = 'application/geo+json;charset=utf-8,' + encodeURIComponent(JSON.stringify(map_data_geojson));
						var file_extension = 'geojson';
									
						break;			
				}
		
				//Attatch data to link and download it
				jQuery(this).attr({
					'href': 'data:' + map_data_export,
					'download': export_container.data('map_slug') + '-' + export_container.data('map_id') + '.' + file_extension
				});			
			});
		}
	});
}

//Tooltips
function waymark_setup_parameter_tooltips() {
	jQuery('a.waymark-tooltip').on({
    mouseenter: function(e) {
		  var title = jQuery(this).data('title');
		  jQuery('<p id="waymark-tooltip-active"></p>').text(title).appendTo('body').fadeIn('slow');
    },
    mouseleave: function(e) {
		  jQuery('#waymark-tooltip-active').remove();
    },
    mousemove: function(e) {
			if(waymark_is_touch_device()) {
			  var mousex = e.pageX - 250;			
			} else {
			  var mousex = e.pageX - 220;				
			}

		  var mousey = e.pageY + 5;
		  jQuery('#waymark-tooltip-active').css({ top: mousey, left: mousex });
    }	
	});
}

//Touch device?	
//Thanks https://stackoverflow.com/questions/4817029/whats-the-best-way-to-detect-a-touch-screen-device-using-javascript/4819886#4819886
function waymark_is_touch_device() {
  var prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');
  var mq = function(media_qry) {
    return window.matchMedia(media_qry).matches;
  }

  if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
    return true;
  }

  // include the 'heartz' as a way to have a non matching MQ to help terminate the join
  // https://git.io/vznFH
  var media_qry = ['(', prefixes.join('touch-enabled),('), 'heartz', ')'].join('');
  return mq(media_qry);
}

function waymark_setup_accordions() {
	var accordion_container = jQuery('.waymark-accordion-container');
	
	if(! accordion_container.length) {
		return;
	}
	
	accordion_container.addClass('waymark-self-clear');
	
	//For each accordion
	accordion_container.each(function() {
	  //Hide all but first initially
	  var group_index = 0;
		
		//Each group
	  jQuery('.waymark-accordion-group', jQuery(this)).each(function() {
	  	jQuery(this).addClass('waymark-self-clear');
	  	jQuery(this).data('waymark-index', group_index);
			
			var group_content = jQuery('.waymark-accordion-group-content', jQuery(this));
			
			//Show first
		  if(group_index == 0) {	  	
		  	jQuery(this).addClass('waymark-first');
			  group_content.show().addClass(group_index);
			//Hide others
			} else {
			  group_content.hide().addClass(group_index);
		  }
			
			//Each legend
			jQuery('legend', jQuery(this)).each(function() {
				//Append text to legend
				var legend_text = jQuery(this).html();
				if(legend_text.indexOf('[+]') == -1) {
					jQuery(this).html(legend_text + ' <span>[+]</span>');			
				}
				
				//Slide
				jQuery(this).click(function() { 	
					var clicked_group_index = jQuery(this).parents('.waymark-accordion-group').data('waymark-index');

					//For each parameter group
					jQuery('.waymark-accordion-group', jQuery(this).parents('.waymark-accordion-container')).each(function() {
						//If this was clicked
						if(jQuery(this).data('waymark-index') == clicked_group_index) {
							jQuery('.waymark-accordion-group-content', jQuery(this)).slideDown();		  			
						} else {
							jQuery('.waymark-accordion-group-content', jQuery(this)).slideUp();		  							
						}
					})
				});				
			});
		  
		  group_index++;
	  });		
	});
}

jQuery(document).ready(function() {
	waymark_setup_parameter_tooltips();
	waymark_setup_map_export();
	waymark_setup_accordions();	
});