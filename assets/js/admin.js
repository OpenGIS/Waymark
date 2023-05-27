function waymark_setup_colour_pickers() {
	jQuery('.waymark-colour-picker .waymark-input').wpColorPicker();	
}

function waymark_setup_repeatable_settings() {	
	//Each container
	jQuery('.waymark-settings-tab .waymark-repeatable').each(function() {
		var container = jQuery(this);
				
		//Each form table
		jQuery('.form-table', container).each(function() {
			var form = jQuery(this);
			var clones = [];
			
			form.remove();

			//Each input
			jQuery('.waymark-input', form).each(function() {
				var input = jQuery(this);
				//Copy ID to class
				input.addClass('waymark-' + input.data('id'));

				//Get values
				if(input.get(0).nodeName != 'SELECT') {
					var values = input.val();
				} else {
					var values = input.data('multi-value');				
				}

				//Ensure is string
				if(typeof values != 'string') {
					values = values.toString();				
				}
				
				//Determine clone values
				values = values.split(waymark_multi_value_seperator);
				for(i in values) {
					if(typeof clones[i] !== 'object') {
						clones[i] = {};						
					}
					clones[i][input.data('id')] = values[i];
				}				
			});
						
			//Each clone
			for(i = 0; i < clones.length; i++) {
				var clone = form.clone();
				
				//Create input
				for(var j in clones[i]) {
					var set_value = clones[i][j];
					
					console.log(input);
					
					var input = jQuery('.waymark-input-' + j, clone);
					input.attr('name', input.attr('name') + '[' + i + ']');
					
					//This is a Select without a valid option
					if((input.get(0).nodeName == 'SELECT') && (! jQuery("option[value='" + clones[i][j] + "']", input).length)) {
						//Use first as default
						set_value = jQuery("option", input).first().val();
					}
					
					//Set value
					input
						.attr('value', set_value)
						.val(set_value)
					;
					
					//Make uneditable
					if(input.parents('.waymark-control-group').hasClass('waymark-uneditable')) {
						input.attr('readonly', 'readonly');
					}										
				}

				//Delete button
				var delete_button = jQuery('<div />')
					.text('x')
					.attr('title', waymark_php_lang.repeatable_delete_title)
					.addClass('waymark-delete')						
					.on('click', function(e) {
						e.preventDefault();
		
						var form = jQuery(this).parents('.form-table');
						form.remove();
						
						return false;
					});		
				clone.append(delete_button);

				container.append(clone);
				container.attr('data-count', i);					
				waymark_setup_parameter_tooltips();
			}
	
			var add_button = jQuery('<button />')
				.html('<i class="ion ion-plus"></i>')
				.addClass('button waymark-add')
				.on('click', function(e) {
					e.preventDefault();
	
					//Increment count
					var container = jQuery(this).parents('.waymark-repeatable');
					var count_old = parseInt(container.attr('data-count'));
					var count_new = count_old + 1;
					container.attr('data-count', count_new);
					
					//Modify clone
					var clone = form.clone();				
					jQuery('.waymark-input', clone).each(function() {
						var input = jQuery(this);
						var input_name = input.attr('name') + '[' + count_new + ']';									
																				
						//Update
						input.attr('name', input_name);
						input.attr('placeholder', '');					
					
						//Clear text inputs
						if(input.get(0).nodeName != 'SELECT') {
							input.val('');						
						}

						switch(input.data('id')) {
							case 'line_colour' :
							case 'shape_colour' :
							case 'icon_colour' :
							case 'marker_colour' :
								input.wpColorPicker();
								
								break;

							case 'meta_options' :
								input.parents('tr').hide();
								
								break;
						}

						
					});
					
					jQuery(this).before(clone);
					waymark_setup_parameter_tooltips();
					waymark_setup_select_meta_type();
					waymark_setup_select_icon_type();

					return false;
				})
			;
			
			container.append(add_button);
			//form.wrap(container);
			container.sortable();
		});
	});
}

function waymark_setup_marker_tab() {
	//Icon name
	var marker_icon_inputs = jQuery('.waymark-input.waymark-marker_icon');	
	marker_icon_inputs.each(function() {
		var input = jQuery(this);
		
		//Icon class
		var icon_class = '';
		if(input.val().indexOf('ion-') === 0) {
			icon_class += 'ion ' + input.val();
		} else if(input.val().indexOf('fa-') === 0) {
			icon_class += 'fa ' + input.val();							
		} else {
			icon_class += 'ion ion-' + input.val();
		}

		var preview = jQuery('<i />').addClass(icon_class);
		
		input.parents('.waymark-controls').prepend(preview);			
	});
	
	//Marker Colour
	var marker_colour_inputs = jQuery('.waymark-input.waymark-marker_colour');

	//Convert old colours (using Waymark_Map Object)
 	if(typeof Waymark_Map === 'function') {
	 	var Map_Object = new Waymark_Map;

		marker_colour_inputs.each(function() {
			jQuery(this).val(Map_Object.get_marker_background(jQuery(this).val()));
			
			jQuery(this).wpColorPicker();
		}); 	
 	}	
}



// function waymark_setup_marker_colour_input(input) {
// 	//input.hide();
// 
// 	var options = ['red', 'darkred', 'orange', 'green', 'darkgreen', 'blue', 'purple', 'darkpurple', 'cadetblue', 'white', 'black'];
// 	
// 	var swatch_container = jQuery('<div />').addClass('waymark-swatch-container waymark-self-clear')	
// 	
// 	for(i in options) {			
// 		var value = options[i];
// 		
// 		var swatch = jQuery('<div />')
// 			.addClass('waymark-swatch waymark-swatch-' + value)
// 			.attr({
// 				'data-value': value,
// 				'title': value
// 			})
// 			.text(' ')
// /*
// 			.on('hover', function() {
// 				jQuery('.waymark-swatch', swatch_container).each(function() {
// 					jQuery(this).removeClass('waymark-selected');
// 				})
// 			})
// */
// 			.on('click', { 'input' : input }, function(e) {
// 				var swatch = jQuery(this);
// 				
// 				//Clear selected
// 				jQuery('.waymark-swatch', swatch_container).each(function() {
// 					jQuery(this).removeClass('waymark-selected');
// 				});					
// 				
// 				input.val(swatch.data('value'));				
// 				swatch.addClass('waymark-selected');
// 			});			
// 
// 			//Set selected
// 			if(value == input.val()) {
// 				swatch.addClass('waymark-selected');
// 			}
// 			
// 			swatch_container.append(swatch);
// 	};
// 
// 	input.parents('.waymark-controls').append(swatch_container);		
// }

function waymark_setup_external_links() {
	//Each link in each Settings tab
	jQuery('.waymark-settings-tab a,#waymark_map_meta a' ).each(function() {
		//If External
		if(! (location.hostname === this.hostname || !this.hostname.length)) {
			//If does not have target attr
			if(typeof jQuery(this).attr('target') === 'undefined') {
				//Add as blank
				jQuery(this).attr('target', '_blank');
			}
		}
	});
}

function waymark_setup_select_meta_type() {
	jQuery('select.waymark-meta_type').each(function() {
		var select = jQuery(this);	
		var container = select.parents('.form-table');
		var options_row = jQuery('.waymark-input.waymark-meta_options', container).parents('tr');

		//On load
		if(select.val() == 'select' || select.val() == 'select_multi') {
			options_row.show();
		} else {
			options_row.hide();			
		}
		
		//On change		
		select.change(function() {
			if(select.val() == 'select' || select.val() == 'select_multi') {
				options_row.show();
			} else {
				options_row.hide();			
			}
		});
	});
}

function waymark_setup_select_icon_type() {
	jQuery('select.waymark-icon_type').each(function() {
		var select = jQuery(this);	

		var container = select.parents('.form-table');
		var colour_row = jQuery('.waymark-input.waymark-icon_colour', container).parents('tr');
		var icon_input = jQuery('.waymark-input.waymark-marker_icon', container);
		var icon_content_row = icon_input.parents('tr');
		var icon_name_text = jQuery('.waymark-icon-type', icon_content_row);
		var icon_preview = jQuery('.waymark-controls i', icon_content_row);
		var icon_help = jQuery('.waymark-icons-help', icon_content_row);

		var icon_tip = jQuery('.waymark-tooltip', icon_content_row);
		var icon_tips = icon_tip.attr('data-title').split('|');
		
		//Update logic
		var update_row = function(type) {
			switch(type) {
				case 'icon' :
					icon_preview.show();
					icon_help.show();
					colour_row.show();					
					icon_input.css('maxWidth', 'unset');		
					icon_name_text.text(waymark_php_lang.marker_icon_icon_label);
					icon_tip.data('title', icon_tips[0]);
					
					break;
				case 'text' :
					icon_preview.hide();
					icon_help.hide();
					colour_row.show();
					icon_input.css('maxWidth', '45px');
					icon_name_text.text(waymark_php_lang.marker_icon_text_label);
					icon_tip.data('title', icon_tips[1]);
										
					break;					
				case 'html' :
					icon_preview.hide();				
					icon_help.hide();	
					colour_row.hide();
					icon_input.css('maxWidth', 'unset');								
					icon_name_text.text(waymark_php_lang.marker_icon_html_label);
					icon_tip.data('title', icon_tips[2]);
										
					break;
			}	

			//Update tooltips
			waymark_setup_parameter_tooltips();			
		};	
		
		//On load
		update_row(select.val());
		
		//On change		
		select.change(function() {
			update_row(select.val());
		});
	});
}

function waymark_setup_dropdowns() {
	jQuery('.waymark-parameters-container').each(function() {
		var container = jQuery(this);
		
		jQuery('select', container).each(function() {
			//Prefix
			var class_string = 'waymark-dropdown-' + jQuery(this).data('id') + '-';			

			//Add new
			class_string += jQuery(this).val();
			container.addClass(class_string);
			
			//On Change
			jQuery(this).on('change', function() {			
				//Prefix
				var class_string = 'waymark-dropdown-' + jQuery(this).data('id') + '-';			
				
				//Remove old
				jQuery('option', jQuery(this)).each(function() {
					container.removeClass(class_string + jQuery(this).attr('value'))
				});

				//Add new
				class_string += jQuery(this).val();
				container.addClass(class_string);
			});
		});			
	});
}

function waymark_setup_map_query() {
	var query_index = 1;	

	jQuery('.waymark-query-form.waymark-map-query .waymark-parameters-container').each(function() {
		jQuery(this).css('background', 'red');
		jQuery(this).attr('data-index', query_index);
		
		//Update on change
		var inputs = jQuery('.waymark-input', jQuery(this));
		inputs.each(function() {
			var input = jQuery(this);
		
			//Execute on change
			input.on('change', function() {
				waymark_execute_query(jQuery(this).parents('.waymark-parameters-container'));	
			});
		});
		
		//Inital view
		inputs.last().trigger('change');		
		
		//Render Map Query
		jQuery(this).hover(
			function() {
				waymark_render_map_query(jQuery(this))
			},
			function() {
				waymark_unrender_map_query(jQuery(this))
			}		
		);

		query_index++;	
	});			
}

function waymark_setup_tax_query() {
	//Taxonomy Add / Edit forms
	jQuery('body.taxonomy-waymark_query .waymark-query-form.waymark-tax-query').each(function() {
		//The form
		var container = jQuery(this);
	
		//Change
		jQuery('.waymark-parameters-container .waymark-input', container).each(function() {
			jQuery(this).change(function() {
				waymark_execute_query(jQuery(this).parents('.waymark-parameters-container'));	
			});
		});
		
		//Initial
		jQuery('.waymark-input', container).first().trigger('change');
	});
	
	//Setup Tax
	var container = jQuery('.postbox#tax_queries_content');
	if(container.length) {
		//Initial Bounds
		jQuery('.waymark-input-query_area_bounds', container).each(function() {
			//Get Meta value
			var query_area_input = jQuery('#waymark-parameters-waymark_map .waymark-input-query_area_bounds').first();
			if(query_area_string = query_area_input.val()) {
				jQuery(this).val(query_area_string);
			}
		});

		//Initial Polygon
		jQuery('.waymark-input-query_area_polygon', container).each(function() {
			//Get Meta value
			var query_area_input = jQuery('#waymark-parameters-waymark_map .waymark-input-query_area_polygon').first();
			if(query_area_string = query_area_input.val()) {
				jQuery(this).val(query_area_string);
			}						
		});

		
		//Map Add / Edit
		var tax_query_container = jQuery('body.post-type-waymark_map #tagsdiv-waymark_query'); 
		if(tax_query_container.length) {
			var tax_query_area_select = jQuery('<select />')
				.addClass('waymark-input')
				.append(
					jQuery('<option />')
						.attr('value', 'polygon')
						.text('Polygon'),						
					jQuery('<option />')
						.attr('value', 'bounds')
						.text('Bounds')				
				)
				.change(function() {
					var area_type = jQuery(this).val();

					var waymark_container = jQuery('.waymark-instance').first();
					var Waymark_Instance = waymark_container.data('Waymark');

					Waymark_Instance.undraw_selectors();
					Waymark_Instance.unedit_bounds_selector();

					//Update Meta
					jQuery('#waymark-parameters-waymark_map .waymark-input-query_area_type')
						.first()
						.val(area_type)
					;
				
					jQuery('.postbox#tax_queries_content .waymark-input-query_area_type').each(function() {
						jQuery('option', jQuery(this)).each(function() {
							if(jQuery(this).val() == area_type) {
								jQuery(this).attr('selected', 'selected');
							} else {
								jQuery(this).removeAttr('selected');
							}
						});
					
						waymark_execute_query(jQuery(this).parents('.waymark-parameters-container'));	
					});
				})
			;
			tax_query_container.append(tax_query_area_select);

			//Set initial
			var query_area_type = jQuery('#waymark-parameters-waymark_map .waymark-input-query_area_type').first().val();			
			jQuery('option[value="' + query_area_type + '"]', tax_query_container).first().attr('selected', 'selected');			

			var tax_query_area_button = jQuery('<button />')
				.html('<i class="ion-edit"></i>')
				.addClass('waymark-input')
				.click('click', 
					function(e) {
						e.preventDefault();

						var area_selector_type = jQuery('#tagsdiv-waymark_query select.waymark-input').val();
										
						var waymark_container = jQuery('.waymark-instance').first();
						var Waymark_Instance = waymark_container.data('Waymark');

						if(! Waymark_Instance.is_bounds_editing()) {
							jQuery(this).html('<i class="ion-android-done"></i>');
	
							var query_area_input = jQuery('#waymark-parameters-waymark_map .waymark-input-query_area_' + area_selector_type).first();
													
							if(! (query_area_string = query_area_input.val())) {
								query_area_string = Waymark_Instance.bounds_to_string(Waymark_Instance.get_default_bounds());
							}
							
							Waymark_Instance.draw_bounds_selector(area_selector_type, query_area_string);
							Waymark_Instance.edit_bounds_selector();
						} else {
							jQuery(this).html('<i class="ion-edit"></i>');						

							switch(area_selector_type) {
								case 'bounds' :
									var bounds = Waymark_Instance.bounds_selector_layer.getBounds();
									var query_area_string = Waymark_Instance.bounds_to_string(bounds);
				
									break;

								case 'polygon' :
									var latlng_array = Waymark_Instance.bounds_selector_layer.getLatLngs();						
									var query_area_string = Waymark_Instance.polygon_array_to_string(latlng_array);
					
									break;
							}
														
							//Save as Meta
							var query_area_input = jQuery('#waymark-parameters-waymark_map .waymark-input-query_area_' + area_selector_type).first();
							query_area_input.val(query_area_string);
							
							//Update Queries
							jQuery('.postbox#tax_queries_content .waymark-input-query_area_' + area_selector_type).each(function() {
								jQuery(this).val(query_area_string);
								
								waymark_execute_query(jQuery(this).parents('.waymark-parameters-container'));
							});
														
							Waymark_Instance.undraw_selectors();
							Waymark_Instance.unedit_bounds_selector();
						}
					}				
				)
			;		
			tax_query_container.append(tax_query_area_button);

			//Trigger initial Query
			tax_query_area_select.trigger('change');			
		}
	}
}

function waymark_execute_query(container) {
	//Must be jQuery
	if(! container instanceof jQuery) {
		return;
	}
	
	var request_data = {};
	
	var form_data = new FormData();
	form_data.append('waymark_security', waymark_admin_js.security);			
	form_data.append('action', 'waymark_get_query_data');
	
	if(query_index = container.data('index')) {
		form_data.append('query_index', query_index);		
	}
	

	//Each input
	var form_data_count = 0;
	var inputs = jQuery('.waymark-input', container);
	inputs.each(function() {
		var input = jQuery(this);
		var input_value = jQuery(this).val();

		switch(input.data('id')) {
			case 'query_overpass_request' :
			
				if(! input_value) {
					console.log('No Overpass Query');
				
					return false;
				}
				
				break;

			case 'query_area_bounds' :
				
				//Update
				if(! input_value) {
					console.log('No Query Area');
					
					return false;
				}
			
				break;
		}
	
		//Add data to form
		form_data.append(jQuery(this).data('id'), jQuery(this).val());
		form_data_count++;

		request_data[jQuery(this).data('id')] = jQuery(this).val();
	});
	
	//If we have data
	if(form_data_count) {
		//Do request
		jQuery.ajax({
			type: "POST",
			url: waymark_admin_js.ajaxurl,
			data: form_data,
			dataType: 'json',
			processData: false,
			contentType: false,
			success: function(response) {				
				if(typeof response.status !== 'undefined') {
					switch(response.status) {
						case 'success' :
							if(typeof response.overpass_request !== 'undefined') {
								var encoded_request = encodeURIComponent(response.overpass_query);
								encoded_request.replace(' ', '+');
							
								var message = '<a href="https://overpass-turbo.eu/?Q=' + encoded_request + '&R">Run on Overpass Turbo &raquo;</a>'
								waymark_admin_message(message, 'info', '.waymark-query-form');
							}		
													
							//Valid data
							if(query_data = JSON.parse(response.query_data)) {
								var waymark_container = jQuery('.waymark-instance').first();
								var Waymark_Instance = waymark_container.data('Waymark');
						
								//Features to display
								if(query_data.features.length) {
									//Index?
									if(query_index = this.data.get('query_index')) {
										Waymark_Instance.load_query_json(query_data, query_index);										
									} else {
										Waymark_Instance.load_query_json(query_data);											
									}
								}				
							
							}
						
							break;
							
						//Error
						case 'error' :
							if(response.message) {
								waymark_admin_message(response.message, 'error');
							}				
						
							break;							
					}
				}
				
			}
		});			
	}
}

function waymark_setup_settings_nav() {
	var nav_container = jQuery('body.wp-admin.waymark_page_waymark-settings #waymark-settings-nav');
	
	if(! nav_container) {
		return false;
	}

	var admin_container = jQuery('#waymark-admin-container');
	var form = jQuery('form', admin_container);

	var tabs = jQuery('.waymark-settings-tab', admin_container);
	var init_tab_key = nav_container.data('init_tab_key');

	//Change
	var select = jQuery('select', nav_container);
	select.hover(function() {
		jQuery(this).attr('size', jQuery('option', jQuery(this)).length);
  },
  function() {
    jQuery(this).removeAttr('size');
  });
	
	select.change(function () {
	  select.removeAttr('size');
	
		var selected_content_id = jQuery(this).val();
		admin_container.attr('class', '');
		
		//Update form redirect
		var redirect_input = jQuery('input[name="_wp_http_referer"]', form);
		var redirect_to = document.location.toString();
		if(redirect_to.indexOf('content=') > 0) {
			redirect_to = redirect_to.replace('content=' + init_tab_key, 'content=' + selected_content_id);
		} else {
			redirect_to = redirect_to + '&content=' + selected_content_id;
		}
		redirect_input.val(redirect_to);
	
		var show_content = jQuery('.' + selected_content_id).first();
		
		//Each Tab
		jQuery('.waymark-settings-tab').each(function() {
			var tab = jQuery(this);
			tab.hide();
			
			//Entire Tab
			if(selected_content_id.indexOf('settings-tab')) {
				//Selected
				if(tab.hasClass(selected_content_id)) {
					tab.show();
					admin_container.addClass('waymark-active-' + selected_content_id);
					
					//Specific Tabs					
// 					switch(true) {
// 						case tab.hasClass('waymark-settings-tab-query') :
// 							
// 							break;
// 					}
				}
			}
			
			//Each Section
			jQuery('.waymark-settings-section', tab).each(function() {
				var section = jQuery(this);			
				
				if(selected_content_id.indexOf('settings-tab') > 0) {
					section.show();		
				} else if(selected_content_id.indexOf('settings-section') > 0) {
					section.hide();

					//Selected
					if(section.hasClass(selected_content_id)) {
						tab.show();		
						section.show();
						admin_container.addClass('waymark-active-' + selected_content_id);						
					}
				}					
			});					
		});
	});	
	select.trigger('change');
}

function waymark_render_map_query(query_container = false) {
	//Must be jQuery
	if(! query_container instanceof jQuery) {
		return;
	}

	var waymark_container = jQuery('.waymark-instance').first();
	var Waymark_Instance = waymark_container.data('Waymark');					

	//Not while editing
	if(Waymark_Instance.is_bounds_editing()) {
		return;					
	}

	query_container.addClass('waymark-active');		

	//Get data
	var area_type_input = jQuery('.waymark-input-query_area_type', query_container).first();
	var area_type = area_type_input.val();

	var area_bounds_input = jQuery('.waymark-input-query_area_bounds', query_container).first();
	var area_polygon_input = jQuery('.waymark-input-query_area_polygon', query_container).first();

	switch(area_type) {
		case 'bounds' :
			var area_val = area_bounds_input.val();
	
			break;

		case 'polygon' :
			var area_val = area_polygon_input.val();

			break;
	}

	//Create Layer	
	var selector_layer = Waymark_Instance.draw_bounds_selector(area_type, area_val);
	
	//On edit
	var data = {
		'Waymark': Waymark,
		'area_type': area_type,
		'area_bounds_input' : area_bounds_input,
		'area_polygon_input' : area_polygon_input
	}
	selector_layer.on('editable:vertex:dragend', function() {
		//Update
		switch(this.area_type) {
			case 'bounds' :
				var bounds = selector_layer.getBounds();
				
				this.area_bounds_input.val(this.Waymark.bounds_to_string(bounds));	
				this.area_bounds_input.trigger('change');	

				break;

			case 'polygon' :
				var latlng_array = selector_layer.getLatLngs();						
				
				this.area_polygon_input.val(this.Waymark.polygon_array_to_string(latlng_array));
				this.area_polygon_input.trigger('change');	
					
				break;
		}
	}, data);

	//Add Edit button
	var edit_button = jQuery('<button />')
		.html('<i class="ion ion-edit"></i>')
		.addClass('button waymark-edit')
		.on('click', data, function(e) {
			e.preventDefault();

			if(! e.data.Waymark.is_bounds_editing()) {
				Waymark.edit_bounds_selector();
				jQuery(this).html('<i class="ion ion-android-done"></i>')							
			} else {
				Waymark.unedit_bounds_selector();
				jQuery(this).html('<i class="ion ion-edit"></i>')																			
			}							
		})
	;

	query_container.append(edit_button);
}

function waymark_unrender_map_query(query_container = false) {
	//Must be jQuery
	if(! query_container instanceof jQuery) {
		return;
	}	

	var waymark_container = jQuery('.waymark-instance').first();
	var Waymark_Instance = waymark_container.data('Waymark');		

	//Not while editing
	if(! Waymark_Instance.is_bounds_editing()) {
		//Remove Edit button
		jQuery('.waymark-edit', query_container).remove();
		
		Waymark_Instance.undraw_selectors();

		query_container.removeClass('waymark-active');		
	}
}

function waymark_handle_repeatable_clone(clone) {
	//Must be jQuery
	if(! clone instanceof jQuery) {
		return;
	}	

	//Get context
	var form = clone.parents('.waymark-form');
	
	//Map Queries
	if(form && form.hasClass('waymark-map-query')) {
		var waymark_container = jQuery('.waymark-instance').first();
		var Waymark_Instance = waymark_container.data('Waymark');		
		
		var default_bounds = Waymark_Instance.get_default_bounds();


		var area_bounds_input = jQuery('.waymark-input-query_area_bounds', clone).first();
		area_bounds_input.val(Waymark_Instance.bounds_to_string(default_bounds))

		var area_polygon_input = jQuery('.waymark-input-query_area_polygon', clone).first();
		var latlng_array = Waymark.latlng_bounds_to_latlng_array(default_bounds);
		area_polygon_input.val(Waymark_Instance.polygon_array_to_string(latlng_array));
		
		clone.hover(function() {
			waymark_render_map_query(jQuery(this));		
		},
		function() {
			waymark_unrender_map_query(jQuery(this));		
		});	
	}

	return clone;
}

// function waymark_handle_repeatable_template(template) {
// 	//Must be jQuery
// 	if(! template instanceof jQuery) {
// 		return;
// 	}	
// 	
// 	//Get context
// 	var form = template.parents('.waymark-form');
// 	
// 	//Map Queries
// 	if(form && form.hasClass('waymark-map-query')) {
// 	
// 	}	
// 	
// 	return template;
// }

function waymark_setup_repeatable_parameters() {
	jQuery('.waymark-repeatable-container').each(function() {
		var repeatable_container = jQuery(this);
		var repeatable_count = repeatable_container.data('count');
		
		var template = jQuery('.waymark-repeatable-template', repeatable_container);
		template.removeClass('waymark-repeatable-template');

		//Do stuff to template (while it's still in the DOM)...			
// 		template = waymark_handle_repeatable_template(template);		
		
		template.remove();

		//Each
		jQuery('.waymark-parameters-container', repeatable_container).each(function() {
			var parameter_container = jQuery(this);
			
			var delete_button = jQuery('<button />')
				.html('<i class="ion ion-android-delete"></i>')
				.addClass('button waymark-delete')
				.on('click', function(e) {
					e.preventDefault();

					parameter_container.remove();						
				})
			;
			parameter_container.append(delete_button);		
		});

		//Add		
		var add_button = jQuery('.waymark-repeatable-add', repeatable_container).first();
		add_button.on('click', function(e) {
			e.preventDefault();
	
			var clone = template.clone();
			
			//Update inputs
			jQuery('.waymark-input', clone).each(function() {
				var input = jQuery(this);
			
				input.attr('name', input.attr('name').replace('__count__', repeatable_count));
			});	

			jQuery('.waymark-control-label', clone).each(function() {
				var label = jQuery(this);
			
				label.attr('for', label.attr('for').replace('__count__', repeatable_count));
			});							

			//Add		
			add_button.before(clone);

			//Do stuff to clone (now it's in the DOM)...			
			clone = waymark_handle_repeatable_clone(clone);
			
			waymark_setup_dropdowns();
			
			//Update count
			repeatable_container.data('count', ++repeatable_count);
			
			return false;
		});
	});
}

/*
function waymark_setup_settings_maps() {
	var form = jQuery('body.wp-admin.waymark_page_waymark-settings #waymark-admin-container form');

	if(! form) {
		return;
	}

 	// ======== Default Centre ========
 	
	var default_latlng_container = jQuery('.map_default_latlng-container .waymark-controls', form).first();
	var default_latlng_input = jQuery('.waymark-input-map_default_latlng', default_latlng_container).first();
	
	//
	var edit_button = jQuery('<button />')
		.html('<i class="ion ion-edit"></i>')
		.addClass('button waymark-edit')
		.on('click', function(e) {
			e.preventDefault();
			
			var map_container = jQuery('#waymark-map-latlng_selector');
			
			if(map_container.hasClass('waymark-hidden')) {
				jQuery(this).html('<i class="ion ion-android-done"></i>');
			
				map_container.removeClass('waymark-hidden');
			} else {
				jQuery(this).html('<i class="ion ion-edit"></i>');
				
				map_container.addClass('waymark-hidden');			
			}
			
			var waymark_instance = map_container.data('Waymark');
			waymark_instance.map.invalidateSize();
			waymark_instance.draw_latlng_selector(default_latlng_input);

			return false;
		})
	;
	default_latlng_container.append(edit_button);

 	// ======== Default Query Area ========
 	
	var map_container = jQuery('#waymark-map-bounds_selector');
	var Waymark_Instance = map_container.data('Waymark');	
	
	if(typeof Waymark_Instance === 'object') {
		var query_area_container = jQuery('.query_area_bounds-container .waymark-controls', form).first();
		var query_area_input = jQuery('.waymark-input-query_area_bounds', query_area_container).first();

		//Create Layer	
		var selector_layer = Waymark_Instance.draw_bounds_selector('bounds', query_area_input.val());
		Waymark_Instance.edit_bounds_selector();
				
		//On edit
		var data = {
			'map_container': map_container,
			'Waymark_Instance': Waymark_Instance,
			'query_area_input' : query_area_input
		}
		selector_layer.on('editable:vertex:dragend', function() {
			var bounds = selector_layer.getBounds();
			this.query_area_input.val(this.Waymark_Instance.bounds_to_string(bounds));	
		}, data);
	
		//
		var edit_button = jQuery('<button />')
			.html('<i class="ion ion-edit"></i>')
			.addClass('button waymark-edit')
			.on('click', data, function(e) {
				e.preventDefault();
			
				if(e.data.map_container.hasClass('waymark-hidden')) {
					jQuery(this).html('<i class="ion ion-android-done"></i>');
							
					e.data.map_container.removeClass('waymark-hidden');
					e.data.Waymark_Instance.map.invalidateSize();			
					e.data.Waymark_Instance.map.fitBounds(Waymark_Instance.bounds_selector_layer.getBounds());
				} else {
					jQuery(this).html('<i class="ion ion-edit"></i>');
				
					e.data.map_container.addClass('waymark-hidden');			
				}

				return false;
			})
		;
		query_area_container.append(edit_button);	
	}	
}
*/

function waymark_admin_message(text = null, type = 'info', container_selector = '#wpbody-content') {
	if(text) {
		var prefix = '';
		
		//Prefix available?
		if(typeof waymark_admin_js.lang[type + '_message_prefix'] !== 'undefined') {
			prefix = waymark_admin_js.lang[type + '_message_prefix'];		
		}
				
		switch(type) {
// 			case 'error' :
// 				
// 				break;
			default:
// 			case 'info' :

				break;			
		}
		
		if(prefix) {
			prefix = '<b>[' + prefix + ']</b> ';
		}
		
		var message = prefix + text;

		//Get container
		var container = jQuery(container_selector).first();

		//Container exists
		if(container.length) {
			//Remove existing
			jQuery('.waymark-notice', container).each(function() {
				jQuery(this).remove();
			});

			var notice_div = jQuery('<div />')
				.attr({
					'class' : 'waymark-notice notice notice-' + type
				})
			;
		
			var notice_p = jQuery('<p />')
				.html(message)
			;
		
			//Put together
			notice_div.append(notice_p);
		
			//Display
			container.prepend(notice_div);	
		}	else {
			alert(message);			
		}
	}
}

jQuery(document).ready(function() {
	waymark_setup_settings_nav();
	waymark_setup_repeatable_settings();
// 	waymark_setup_repeatable_parameters();
	waymark_setup_marker_tab();
// 	setTimeout(waymark_setup_settings_maps, 100);	
	waymark_setup_colour_pickers();
	waymark_setup_external_links();
	waymark_setup_select_meta_type();
	waymark_setup_select_icon_type();
	waymark_setup_dropdowns();
	waymark_setup_map_query();		
	setTimeout(waymark_setup_tax_query, 500);	
});