/*
	==================================
	============= QUERY =============
	==================================
*/

function Waymark_Map_Query() {	

	this.build_content = function(layer_type, feature, layer, data_layer = 'map_data') {
		Waymark = this;

		//Build output
		var content = jQuery('<div />');

		switch(data_layer) {

			// ====================================			
			// ============ Query Data ============
			// ====================================

									
			case 'query_data' :
			
				var list = jQuery('<ul />')
					.addClass('waymark-info waymark-query-info waymark-' + layer_type + '-info');

				for(key in Waymark.config[layer_type + '_data_defaults']) {			
					var ele = null;
		
					switch(key) {
						case 'title':
							var title = feature.properties.title;

							//We have a title
							if(title) {
								ele = jQuery('<strong />').html(feature.properties.title)
							//No description
							} else {
								ele = jQuery('<strong />').html('&nbsp;')
								list.addClass('waymark-no-title');
							}
					
				
							break;
						case 'type':
							if(Waymark.config.map_options.show_type_labels != '1') {
								break;	
							}
									
							//Get type
							var type = Waymark.get_type(layer_type, feature.properties.type);
							if(type) {
								ele = Waymark.type_to_text(layer_type, type, 'small');						
							}			
					
							break;

						case 'description':
							var description = feature.properties.description;
					
							//We have a description
							if(description) {
								//HTML
								if(description.indexOf('<') === 0) {
									ele = description;						
								//Plain text
								} else {
									ele = jQuery('<p />').html(description);
								}
							//No description
							} else {
								list.addClass('waymark-no-description');
							}
				
							break;					
						case 'image_large_url':
								//We have an image
								if(typeof feature.properties.image_large_url !== 'undefined') {
									//Use Medium if we have it
									var thumb_url = feature.properties.image_large_url;
									if(typeof feature.properties.image_medium_url !== 'undefined') {
										var thumb_url = feature.properties.image_medium_url;
									}

									ele = jQuery('<a />')
										.attr({
											'href': feature.properties.image_large_url,
											'target': '_blank',		
											'style': 'background-image:url(' + thumb_url + ')'	
										})
									;
								//We don't have an image
								} else {
									list.addClass('waymark-no-image');							
								}
																													
							break;										
					}
			
					if(ele) {
						list.append(jQuery('<li />').addClass('waymark-info-' + key + ' waymark-' + layer_type + '-info-' + key).append(ele));				
					}
				}

				if(list.children().length) {
					content.append(list);
				}				
			
				break;
		}

		return content;		
	}

/*
	==================================
	============ QUERIES ============
	==================================
*/

	//Add Query GeoJSON
	this.load_query_json = function(query_json, query_index = 1) {
		Waymark = this;

		//Valid Data
		if(typeof query_json == 'object') {		
			//Remove existing?
			if(typeof Waymark.queries_data[query_index] !== 'undefined') {
				Waymark.map.removeLayer(Waymark.queries_data[query_index]);
				
				Waymark.query_data_group.removeLayer(Waymark.queries_data[query_index]);			
			}

			//Create New Query data layer
			Waymark.queries_data[query_index] = Waymark_L.geoJSON(null, {
				pointToLayer: function(feature, latlng) {
					return Waymark.create_marker(latlng, {
						draggable: false
					});
				},
				onEachFeature: function(feature, layer) {
					Waymark.setup_query_data_feature(feature, layer);
				}
			});
			
			//Add JSON
			Waymark.queries_data[query_index].addData(query_json);		 	
			
			//Add to Map
			Waymark.queries_data[query_index].addTo(Waymark.map);
			
			//Add to Group
			Waymark.query_data_group.addLayer(Waymark.queries_data[query_index]);			
			
			//Expand bounds
			var bounds = Waymark.query_data_group.getBounds();
			if(bounds.isValid()) {
				Waymark.map.fitBounds(bounds);
			}		
			
			Waymark.save_query_data();
		} 		
	}
	
	this.save_query_data = function() {
		//Map Data	
		var query_data_container = jQuery('.waymark-input-query_data').first();
		var query_data_string = JSON.stringify(Waymark.query_data_group.toGeoJSON());

		//Update custom field form
		query_data_container.html(query_data_string);		
	}
}