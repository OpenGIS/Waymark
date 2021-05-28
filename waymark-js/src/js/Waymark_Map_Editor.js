/*
	==================================
	============= EDITOR =============
	==================================
*/

function Waymark_Map_Editor() {	
	/*
		==================================
		============= ABSTRACT ===========
		==================================
	*/

	this.init_done = function() {	
		Waymark = this;

		//This is the editor
		Waymark.mode = 'edit';
		jQuery(Waymark.map.getContainer()).addClass('waymark-is-editor');

		//Every time a layer is created
		Waymark.map.on('editable:drawing:commit', function (e) {
      layer = e.layer;

			//Initialize feature
			if(typeof layer.feature == 'undefined') {
				layer.feature = {
					type: "Feature",
					properties: {}
				}; 
			}
			
			//Use default type data
			switch(true) {
				case layer instanceof Waymark_L.Circle : 
					layer.feature.properties = Waymark.config.shape_data_defaults;
					
					//Set radius					
					layer.feature.properties.radius = layer.getRadius();
					
					break;
				case layer instanceof Waymark_L.Rectangle :
					layer.feature.properties = Waymark.config.shape_data_defaults;
					
					//Remember that this is a rectangle, not any old polygon				
					layer.feature.properties.rectangle = true;

					break;
				case layer instanceof Waymark_L.Polygon :
					layer.feature.properties = Waymark.config.shape_data_defaults;

					break					
					
				case layer instanceof Waymark_L.Polyline :
					layer.feature.properties = Waymark.config.line_data_defaults;

					break					
			}	
				
			//Add to data layer
			Waymark.map_data.addData(layer.toGeoJSON());

			//We're done with this now
			Waymark.map.removeLayer(layer);
			
			//Save
			Waymark.save_data_layer();
			Waymark.map_was_edited();		   
		});
		
		//Every time a layer is edited
		Waymark.map.on('editable:editing', function (e) {
      layer = e.layer;

	    //Circle?
	    if(typeof layer.feature !== 'undefined' && layer.feature.properties.radius) {
	    	
		    layer.feature.properties.radius = layer.getRadius();
	    }
	    
			Waymark.save_data_layer();
			Waymark.map_was_edited();			
		});	

		//Add rich text editor
		Waymark.map.on('popupopen', function(e) {
			var feature = e.popup._source.feature;
			
			//Delay required
			setTimeout(function() {
				wp.editor.initialize('waymark-info-description', {
					tinymce: {
						toolbar1: 'styleselect | bullist numlist | link image',
						setup: function (editor) {
							editor.on('change', function(e) {
								//Update properties
								feature.properties.description = wp.editor.getContent('waymark-info-description');

								Waymark.save_data_layer();
								Waymark.map_was_edited();
							});
						}
					}  				
				});			
			}, 250);		
		});

		//Remove rich text editor
		Waymark.map.on('popupclose', function(e) {
			wp.editor.remove('waymark-info-description');
			jQuery('#waymark-info-description').show();				
		});
	}		
	
	this.create_marker = function(latlng) {
		Waymark = this;

		//Create marker										  
		var marker = Waymark_L.marker(latlng, { draggable: true });
		
		//Dragged
		marker.on('moveend', function(e) {
			var layer = e.target;
			var feature = layer.feature;			
			
			//Update feature with new coordinates
			feature.geometry.coordinates = [ layer._latlng.lng.toFixed(6), layer._latlng.lat.toFixed(6) ];

			//Update content to reflect change in position
			Waymark.info_window('marker', feature, layer);										
		
			Waymark.save_data_layer();
			Waymark.map_was_edited();
		});
		
		return marker;	
	}

	//Update meta field
	this.save_data_layer = function() {
		Waymark = this;
	
		var map_data_container = jQuery('#map_data');
		var map_data_string = JSON.stringify(Waymark.map_data.toGeoJSON());

		//Update custom field form
		map_data_container.html(map_data_string);	
	}

	//Something was edited
 	this.map_was_edited = function() {
 	
 	}
		
	this.create_buttons = function() {
		Waymark = this;
	
		//Geocoder
		var geocoder = Waymark_L.Control.geocoder({
			'position': 'bottomright',
		  'defaultMarkGeocode': false,
		  'placeholder' : waymark_js_lang.action_search_placeholder
		});
		geocoder.on('markgeocode', function(e) {
			Waymark.map.fitBounds(e.geocode.bbox);
		});
		geocoder.addTo(Waymark.map);
		
		//Edit Toolbar
		var edit_toolbar_control = Waymark_L.Control.extend({
			options: {
				position: 'bottomleft' 
			},
			onAdd: function (map) {
				var toolbar = Waymark_L.DomUtil.create('div', 'leaflet-bar leaflet-control waymark-leaflet-control waymark-edit-toolbar');

				//Line
				var button = Waymark_L.DomUtil.create('a', 'waymark-icon waymark-edit-button waymark-edit-line', toolbar);
				button.setAttribute('title', waymark_js_lang.add_line_title);
				button.onclick = function() {
        	Waymark.map.editTools.startPolyline();
				}		

				//Image Upload
				var button = Waymark_L.DomUtil.create('a', 'waymark-edit-button waymark-edit-image', toolbar);
				button.innerHTML = '<i class="ion ion-image"></i>';
				button.setAttribute('title', waymark_js_lang.add_photo_title);
				button.onclick = function() {
					if(! typeof wp) {
						return false;
					}
					
					//Create a Marker (use map center to begin with
					var map_center = Waymark.map.getCenter();		
										
			    //Thanks to: https://mycyberuniverse.com/integration-wordpress-media-uploader-plugin-options-page.html
			    wp.media.editor.send.attachment = function(props, attachment) {
				    var marker_json = {
				      "geometry": {
				        "type": "Point", 
				        "coordinates": [ map_center.lng, map_center.lat ]
			        }, 
				      "type": "Feature", 
							"properties": Object.assign({}, Waymark.config.marker_data_defaults)
				    };
			    						    
						//SET URLs
						
						//Thumb
						marker_json.properties.image_thumbnail_url = attachment.url;
				    if(typeof attachment.sizes.thumbnail !== 'undefined') {
							marker_json.properties.image_thumbnail_url = attachment.sizes.thumbnail.url;
				    }

						//Medium
						marker_json.properties.image_medium_url = attachment.url;
				    if(typeof attachment.sizes.medium !== 'undefined') {
							marker_json.properties.image_medium_url = attachment.sizes.medium.url;
				    }
						
						//Large
						marker_json.properties.image_large_url = attachment.url;				    												    						    
				    if(typeof attachment.sizes.large !== 'undefined') {
							marker_json.properties.image_large_url = attachment.sizes.large.url;
				    }

						//Full
						marker_json.properties.image_full_url = attachment.url;
				    if(typeof attachment.sizes.full !== 'undefined') {
							marker_json.properties.image_full_url = attachment.sizes.full.url;
				    }
							    				    
						//Get Photo EXIF
						var form_data = new FormData();
						form_data.append('waymark_security', waymark_ajax_security);			
						form_data.append('action', 'waymark_get_attatchment_meta');			
						form_data.append('attachment_id', attachment.id);			
						
						jQuery.ajax({
						  type: "POST",
						  url: ajaxurl,
						  data: form_data,
							dataType: 'json',
							processData: false,
							contentType: false,
						  success: function(response) {				
							  
							  if(response === null) {
									console.log(waymark_js_lang.error_message_prefix + ': ' + waymark_js_lang.error_photo_meta);					  
									
									return;
							  }

							  //Location EXIF exists
							  if(response.GPSLatitudeNum && !isNaN(response.GPSLatitudeNum) && response.GPSLongitudeNum && !isNaN(response.GPSLongitudeNum)) {
									console.log(waymark_js_lang.info_message_prefix + ': Image location metadata (EXIF) detected!');

								  //Get latlng
								  var marker_latlng = [ response.GPSLatitudeNum, response.GPSLongitudeNum ];
								  
								  //Setup Marker
								  marker_json.geometry.coordinates = [ marker_latlng[1], marker_latlng[0] ];

									//Center on it 
									Waymark.map.setView(marker_latlng);								  
							  //No Location EXIF
							  } else {
									console.log(waymark_js_lang.info_message_prefix + ': Image location metadata (EXIF) not detected.');							  
							  }				  

								//Output to console
								console.log(response);

								//Add Marker
								Waymark.map_data.addData(marker_json);
										
							  //Save
								Waymark.save_data_layer();
								Waymark.map_was_edited();									
							}
						});
			    }
//			    wp.media.editor.open(jQuery(this));
			    wp.media.editor.open();
			    
			    return false;		
				}	

				//Marker
				var button = Waymark_L.DomUtil.create('a', 'waymark-edit-button waymark-edit-marker', toolbar);
				button.innerHTML = '<i class="ion ion-location"></i>';
				button.setAttribute('title', waymark_js_lang.add_marker_title);
				button.onclick = function() {
					var map_center = Waymark.map.getCenter();		
				
			    var marker_json = {
			      "geometry": {
			        "type": "Point", 
			        "coordinates": [ map_center.lng, map_center.lat ]
		        }, 
			      "type": "Feature", 
			      "properties": Object.assign({}, Waymark.config.marker_data_defaults)
			    };
					
					//Add Marker
					Waymark.map_data.addData(marker_json);

				  //Save
					Waymark.save_data_layer();
					Waymark.map_was_edited();						
				}					
						
				//Rectangle
				var button = Waymark_L.DomUtil.create('a', 'waymark-edit-button waymark-edit-rectangle', toolbar);
				button.innerHTML = '<i class="ion ion-android-checkbox-outline-blank"></i>';				
				button.setAttribute('title', waymark_js_lang.add_rectangle_title);
				button.onclick = function() {
        	Waymark.map.editTools.startRectangle();
				}		

				//Polygon
				var button = Waymark_L.DomUtil.create('a', 'waymark-edit-button waymark-edit-polygon', toolbar);
				button.innerHTML = '<i class="ion ion-android-star-outline"></i>';				
				button.setAttribute('title', waymark_js_lang.add_polygon_title);
				button.onclick = function() {
        	Waymark.map.editTools.startPolygon();
				}	

				//Circle
				var button = Waymark_L.DomUtil.create('a', 'waymark-edit-button waymark-edit-circle', toolbar);
				button.innerHTML = '<i class="ion ion-ios-circle-outline"></i>';								
				button.setAttribute('title', waymark_js_lang.add_circle_title);
				button.onclick = function() {
        	Waymark.map.editTools.startCircle();
				}					

				//File Upload

				//Use Media Library?				
				if(Waymark.get_property(waymark_settings, 'misc', 'editor_options', 'media_library_uploads')) {
					var button = Waymark_L.DomUtil.create('a', 'waymark-edit-button waymark-edit-upload', toolbar);
					jQuery(button).append(input);
					button.innerHTML = '<i class="ion ion-document"></i><i class="ion ion-arrow-up-c"></i>';								
					button.setAttribute('title', waymark_js_lang.upload_file_title);
					button.onclick = function() {

						if(! typeof wp) {
							return false;
						}
										
						//Thanks to: https://mycyberuniverse.com/integration-wordpress-media-uploader-plugin-options-page.html
						wp.media.editor.send.attachment = function(props, attachment) {
							if(Waymark.get_property(waymark_settings, 'misc', 'advanced', 'debug_mode')) {
								console.log(attachment);		  	
							}						

							jQuery.ajax({
								type: "GET",
								url: attachment.url,
								dataType: 'text',
								success: function(response) {
									switch(attachment.mime) {
										case 'application/gpx+xml' :
											Waymark.load_file_contents(response, 'gpx');
	
											break;

										case 'application/vnd.google-earth.kml+xml' :
											Waymark.load_file_contents(response, 'kml');

											break;
																			
										case 'application/geo+json' :
											Waymark.load_file_contents(response, 'geojson');

											break;
										
										default :
											console.log(waymark_js_lang.error_message_prefix + ': ' + waymark_js_lang.error_file_upload);					  

											break;						  									  									  									  			
									}
								}
							});
						}

						wp.media.editor.open();
					
						return false;	
					};				
				//Don't use media library - just read and delete
				} else {
					//Thanks to: https://stackoverflow.com/a/24939229
					var input = jQuery('<input />')
						.attr({
							'type': 'file',
							'name': 'add_file'
						})
						.css('display', 'none')
						.change(function() {
							Waymark.handle_file_upload(jQuery(this));
						});		
										
					var button = Waymark_L.DomUtil.create('a', 'waymark-edit-button waymark-edit-upload', toolbar);
					jQuery(button).append(input);
					button.innerHTML = '<i class="ion ion-document"></i><i class="ion ion-arrow-up-c"></i>';								
					button.setAttribute('title', waymark_js_lang.upload_file_title);
					button.onclick = function() {
						//Fire the form
						input.trigger('click');
					
						//Weird circle bug fix...
						Waymark.map.editTools.stopDrawing();					
					};
				}
				
				return toolbar;
			},
		});
		Waymark.map.addControl(new edit_toolbar_control());	
	},
	
	this.handle_file_upload = function(input) {
		Waymark = this;

		//Create form data
		var form_data = new FormData();
		form_data.append('waymark_security', waymark_ajax_security);			
		form_data.append('action', 'waymark_read_file');			
		form_data.append(input.attr('name'), input[0].files[0]);			
		
		jQuery.ajax({
		  type: "POST",
		  url: ajaxurl,
		  data: form_data,
			dataType: 'json',
			processData: false,
			contentType: false,
		  success: function(response) {		
				if(Waymark.get_property(waymark_settings, 'misc', 'advanced', 'debug_mode')) {
					console.log(response);		  	
				}
		  
			  if(response === null) {
					console.log(waymark_js_lang.error_message_prefix + ': ' + waymark_js_lang.error_file_upload);					  
					
					return;
			  } else if(response.error) {
					console.log(waymark_js_lang.error_message_prefix + ': ' + response.error);			
					
					return;		  			  
			  }
			  
				Waymark.load_file_contents(response.file_contents, response.file_type);  
				Waymark.map_was_edited();								
			}
		});												
	},	 
	
	this.build_content = function(layer_type, feature, layer) {
		Waymark = this;

		//Build output
		var content = jQuery('<div />');
		var list = jQuery('<ul />')
			.addClass('waymark-info');

		//Edit button
		if(layer_type == 'line' || layer_type == 'shape') {		
			var ele = jQuery('<button />')
				.html('<i class="ion-edit"></i>')
				.addClass('button')			
				.attr('title', waymark_js_lang.action_edit + ' ' + Waymark.title_case(waymark_js_lang['object_label_' + layer_type]))			
				.on('click', function(e) {
					e.preventDefault();
					
					//Get the element we need
					var button = jQuery(this);
					var icon = jQuery('i', button);
										
					//Finish
					if(layer.editEnabled()) {
						//Disable edit
						layer.disableEdit();	
						
						//Change title
						button.attr('title', waymark_js_lang.action_edit + ' ' + Waymark.title_case(waymark_js_lang['object_label_' + layer_type]));
						
						//Change icon
						icon.attr('class', 'ion-edit');			
						
						//Callback?
						if(typeof Waymark.config.handle_edit_callback == 'function') {
							Waymark.config.handle_edit_callback(false);
						}
					//Start
					} else {
						//Enable edit
						layer.enableEdit();					
	
						//Close popup
						layer.closePopup();	

						//Change title
						button.attr('title', waymark_js_lang.action_edit_done);

						//Change icon
						icon.attr('class', 'ion-android-done');		

						//Callback?
						if(typeof Waymark.config.handle_edit_callback == 'function') {
							Waymark.config.handle_edit_callback(true);
						}									
					}		
	
					return false;
				});		
			list.append(jQuery('<li />').addClass('waymark-info-button waymark-info-edit waymark-' + layer_type + '-edit').append(ele));
		}

		//Duplicate button
		var ele = jQuery('<button />')
			.html('<i class="ion-ios-copy"></i>')
			.addClass('button')
			.attr('title', waymark_js_lang.action_duplicate + ' ' + Waymark.title_case(waymark_js_lang['object_label_' + layer_type]))						
			.on('click', function(e) {
				e.preventDefault();
				
				//Clone				
				Waymark.load_json(Object.assign({}, layer.feature));
				
				Waymark.map_was_edited();

				return false;
			});		
		list.append(jQuery('<li />').addClass('waymark-info-button waymark-info-delete waymark-' + layer_type + '-delete').append(ele));

		//Delete button
		var ele = jQuery('<button />')
			.html('<i class="ion-trash-a"></i>')
			.addClass('button')
			.attr('title', waymark_js_lang.action_delete + ' ' + Waymark.title_case(waymark_js_lang['object_label_' + layer_type]))						
			.on('click', function(e) {
				e.preventDefault();
				
				//Confirm delete...
				if(Waymark.config.editor_options.confirm_delete == '1') {
					if(! confirm(waymark_js_lang.action_delete_confirm + " " + Waymark.title_case(waymark_js_lang['object_label_' + layer_type]) + "?")) {
						return false;
					}
				}

				//Remove from Map
				Waymark.map.removeLayer(layer);
				//Remove from data later
				Waymark.map_data.removeLayer(layer);

				Waymark.save_data_layer();
				Waymark.map_was_edited();
				
				//Callback?
				if(typeof Waymark.config.handle_delete_callback == 'function') {
					Waymark.config.handle_delete_callback(feature);
				}
				
				return false;
			});		
		list.append(jQuery('<li />').addClass('waymark-info-button waymark-info-delete waymark-' + layer_type + '-delete').append(ele));

		//Type
		var types = Waymark.config[layer_type + '_types'];
		
		//Types
		var ele = jQuery('<select />');

		ele.append(
			jQuery('<option />')
				.attr({
					'disabled': 'disabled'
				})
				.text(Waymark.title_case(waymark_js_lang['object_label_' + layer_type]) + ' ' + waymark_js_lang.object_type_label + ':')
		);

		//Pre-defined types
		for(i in types) {
			var type_key = Waymark.make_key(types[i][layer_type + '_title']);

			ele.append(jQuery('<option />').val(type_key).text(types[i][layer_type + '_title']));
		}
		
		//Handling custom types?
		if(typeof Waymark.config.handle_custom_type_callback == 'function') {
			//Seperator
			ele.append(
				jQuery('<option />')
					.attr('disabled', 'disabled')
					.text('──────────')
			);
			//'Custom'
			var custom_option = jQuery('<option />')
				.attr('id', 'custom_type')
				.val('{}')
				.text("Custom")
			;
			ele.append(custom_option);
		}

		//On change
		ele.change(function() {		
			var selected_input = jQuery('option:selected', jQuery(this));

			//Custom
			if(selected_input.attr('id') == 'custom_type') {
				if(typeof Waymark.config.handle_custom_type_callback == 'function') {
					Waymark.config.handle_custom_type_callback(layer_type, layer, selected_input);
				}
			//Pre-defined		
			} else {
				if(typeof Waymark.config.handle_custom_type_callback == 'function') {
					Waymark.config.handle_custom_type_callback(layer_type, layer, selected_input, 'hide');
				}
				
				//Get type value
				var selected_type = jQuery(this).val();
				
				//Update data layer
				feature.properties.type = selected_type;
				
				//Predefined
				if(typeof selected_type != 'object') {
					var type = Waymark.get_type(layer_type, feature.properties.type);		
				}

				//Change live style
				switch(layer_type) {
					case 'line' :
						layer.setStyle({
							color: type.line_colour,
							weight: type.line_weight							
						});				

						break;
					case 'shape' :
						layer.setStyle({
							color: type.shape_colour,
							fillOpacity: type.fill_opacity
						});
							
						break;
					case 'marker' :
						//Create Icon								
						layer.setIcon(
							L.divIcon(Waymark.build_icon_data(type))
						);				

						break;								
				}

				Waymark.save_data_layer();
				Waymark.map_was_edited();			
			}
		});
		list.append(jQuery('<li />').addClass('waymark-info-type waymark-' + layer_type + '-type').append(ele));		

		//Set selected
		//Custom object
		if(typeof feature.properties.type == 'object') {
			if(typeof Waymark.config.handle_custom_type_callback == 'function') {
				Waymark.config.handle_custom_type_callback(layer_type, layer, custom_option, 'show');			

				custom_option.attr('selected', 'selected');
			}
		//Type used
		} else {
			if(typeof Waymark.config.handle_custom_type_callback == 'function') {
				Waymark.config.handle_custom_type_callback(layer_type, layer, custom_option, 'hide');			
			}
	
			jQuery('option', ele).filter(function() {
				return jQuery(this).val() == Waymark.make_key(feature.properties.type);
			}).attr('selected', 'selected');			
		}	
	
		//Data
		for(key in Waymark.config[layer_type + '_data_defaults']) {			
			var ele = null;
			
			switch(key) {
				case 'title':
					var ele = jQuery('<input />')
						.attr({
							'type': 'text',
							'value': feature.properties.title,
							'placeholder': Waymark.title_case(waymark_js_lang['object_label_' + layer_type]) + ' ' + waymark_js_lang.object_title_placeholder													
						})
						.on('change', function() {
							//Update properties
							feature.properties.title = jQuery(this).val()

							Waymark.save_data_layer();
							Waymark.map_was_edited();
						});
				
					break;
				case 'description':
					var ele_id = 'waymark-info-description';
					
					var ele = jQuery('<textarea />')
						.attr({
							'id': ele_id,
							'class': 'wp-editor',							
							'placeholder': Waymark.title_case(waymark_js_lang['object_label_' + layer_type]) + ' ' + waymark_js_lang.object_description_placeholder
						})
						.val(feature.properties.description)
						.on('change', function() {
							//Update properties
							feature.properties.description = jQuery(this).val()

							Waymark.save_data_layer();
							Waymark.map_was_edited();
						});
				
					break;					
				case 'image_large_url':
					var img_input = jQuery('<input />')
						.attr({
							'value': feature.properties.image_large_url,
							'placeholder': waymark_js_lang.object_image_placeholder
						})
						.on('change', function() {
							//Update properties
							feature.properties.image_large_url = jQuery(this).val()

							Waymark.save_data_layer();
							Waymark.map_was_edited();
						});
						
						if(typeof feature.properties.image_thumbnail_url !== 'undefined') {
							var thumb_url = feature.properties.image_thumbnail_url;
						} else {
							var thumb_url = feature.properties.image_large_url;							
						}
						
						//Image Preview
						var img_ele = jQuery('<img />')
							.attr({
								'src' : thumb_url,
								'width' : 160
							});									
						img_input.on('change', function() {
							img_ele.attr('src', jQuery(this).val());
						});
						
						var img_view = jQuery('<a />').
							attr({
								'href': feature.properties.image_large_url,
								'target': '_blank',																	
							})
							.append(img_ele)
							.hover(
								function() {
									jQuery(this).addClass('waymark-hover');
								},
								function() {
									jQuery(this).removeClass('waymark-hover');
								}								
							)
												
						var img_add = jQuery('<button />')
							.text(waymark_js_lang.action_upload_image)
							.attr({
								'type': 'submit',
								'name': 'add_photo',
								'class': 'waymark-input button button-small',
								'id': 'add_photo'
						}).on('click', function(e) {
							e.preventDefault();
					    
					    //Thanks to: https://mycyberuniverse.com/integration-wordpress-media-uploader-plugin-options-page.html
					    wp.media.editor.send.attachment = function(props, attachment) {
								//Update data
								feature.properties.image_thumbnail_url = attachment.url;
								feature.properties.image_medium_url = attachment.url;
								feature.properties.image_large_url = attachment.url;

						    if(typeof attachment.sizes.thumbnail !== 'undefined') {
									feature.properties.image_thumbnail_url = attachment.sizes.thumbnail.url;
						    }

						    if(typeof attachment.sizes.medium !== 'undefined') {
									feature.properties.image_medium_url = attachment.sizes.medium.url;
						    }
						    												    						    
						    if(typeof attachment.sizes.large !== 'undefined') {
									feature.properties.image_large_url = attachment.sizes.large.url;
						    }
						    					    							  
							  //Update preview
							  img_view.attr('href', feature.properties.image_large_url);
								jQuery('img', img_view).attr('src', feature.properties.image_thumbnail_url);
							  						  
							  //Update input
							  img_input.val(feature.properties.image_large_url);			

							  //Save
								Waymark.save_data_layer();
								Waymark.map_was_edited();							  			    	
					    }
					    wp.media.editor.open(jQuery(this));
					    
					    return false;				
						});
	
						var ele = jQuery('<div />').append(img_view, img_input, img_add);
										
					break;										
			}

			if(ele) {
				list.append(jQuery('<li />').addClass('waymark-info-' + key + ' waymark-' + layer_type + '-info-' + key).append(ele));				
			}			
		}		

		//Position
		if(layer_type == 'marker' && typeof layer.feature.geometry.coordinates != 'undefined') {
			var latlng = layer.feature.geometry.coordinates;
			var lat = layer.feature.geometry.coordinates[1];
			var lng = layer.feature.geometry.coordinates[0];
			//Round if numeric
			if(typeof lat == 'number') {
				lng = lng.toFixed(6);
				lat = lat.toFixed(6);
			}
			
			//Output
			ele = jQuery('<small>').html('<b>' + waymark_js_lang.marker_latlng_label + '</b>: ' + lat + ',' + lng);
			list.append(jQuery('<li />').addClass('waymark-info-latlng waymark-marker-info-latlng').append(ele));				
		}			

		//Content
		if(list.children().length) {
			content.append(list);
		}
		
		return content;		
	}

	this.info_window = function(layer_type, feature, layer) {
		Waymark = this;

		//Build content
		var title = Waymark.title_case(waymark_js_lang.action_edit + ' ' + layer_type);

		//Custom handle content
		if(typeof Waymark.config.handle_content_callback == 'function') {
			//Bind content to info window
			layer
				.on('click', function() {
					var content = Waymark.build_content(layer_type, feature, layer);
					Waymark.config.handle_content_callback(content.get(0), title, Waymark.mode);
				});		
		//Default handle content
		} else {	
			var content = Waymark.build_content(layer_type, feature, layer);
			var content_html = content.get(0);
			
			var popup_options = {
//				maxWidth: 400
			}

			//Bind content to info window
			layer
				.bindPopup(content_html, popup_options).openPopup()
				.on('click', function() {
					//console.log(marker.getLatLng());
				});		
		}				
	}

	this.load_file_contents = function(file_contents, file_type) {	
		Waymark = this;
		
		//The DOMParser doesn't like whitespace
		file_contents = file_contents.trim();
	
		switch(file_type) {
			case 'gpx' :
				var gpx_doc = (new DOMParser()).parseFromString(file_contents, 'text/xml');
				var geo_json = toGeoJSON.gpx(gpx_doc);
				
				break;
			case 'kml' :
				var kml_doc = (new DOMParser()).parseFromString(file_contents, 'text/xml');
				var geo_json = toGeoJSON.kml(kml_doc);
				
				break;				
			case 'json' :
			case 'geojson' :
				var geo_json = JSON.parse(file_contents);
				
				break;
			default:
				console.log(waymark_js_lang.error_message_prefix + ': ' + waymark_js_lang.error_file_type);
		}
		
		//Valid data		
		if(typeof geo_json !== 'undefined') {
			//Only keep specific properties
			//var keep_properties = ['title', 'name', 'description', 'photos'];
// 			var keep_properties = ['title', 'name', 'description'];
// 			//Each feature
// 			for(i in geo_json.features) {
// 				//Each property
// 				for(key in geo_json.features[i].properties) {
// 					//We want this
// 					if(keep_properties.includes(key)) {
// 						switch(key) {
// 							case 'photos' :
// 								console.log(geo_json.features[i].properties[key]);
// 
// 								break;
// 						}					
// 					//We don't want this
// 					} else {
// 						//Delete
// 						delete geo_json.features[i].properties[key];						
// 					}
// 				}
// 			}		
			
			//Add to map	
			this.load_json(geo_json);			
		//Invalid data
		} else {
			console.log(waymark_js_lang.error_message_prefix + ': ' + waymark_js_lang.error_file_conversion);
		}
	}		
	
/*
	==================================
	============ OVERLOAD ============
	==================================
*/	

	//Add GeoJSON to map	
	this.load_json = function(json) {
		Waymark = this;
	
		if(typeof json === 'object') {
			//Add JSON
			Waymark.map_data.addData(json);		 	
			
			//Make all editable
/*
			Waymark.map_data.eachLayer(function(layer) {
	    	Waymark.drawn_items.addLayer(layer);
			});
*/
			
			//Save
			Waymark.save_data_layer();
			
			//Update map bounds (if we have)
			var bounds = Waymark.map_data.getBounds();
			if(bounds.isValid()) {
				Waymark.map.fitBounds(bounds);
			}
		} 		
	}	
}