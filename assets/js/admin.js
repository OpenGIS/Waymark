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
				for(j in clones[i]) {
					var set_value = clones[i][j];
					
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
					.attr('title', waymark_admin_js.lang.repeatable_delete_title)
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

function waymark_setup_repeatable_forms() {	
	//Each container
	jQuery('.waymark-form.waymark-repeatable').each(function() {

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
					icon_name_text.text(waymark_admin_js.lang.marker_icon_icon_label);
					icon_tip.data('title', icon_tips[0]);
					
					break;
				case 'text' :
					icon_preview.hide();
					icon_help.hide();
					colour_row.show();
					icon_input.css('maxWidth', '45px');
					icon_name_text.text(waymark_admin_js.lang.marker_icon_text_label);
					icon_tip.data('title', icon_tips[1]);
										
					break;					
				case 'html' :
					icon_preview.hide();				
					icon_help.hide();	
					colour_row.hide();
					icon_input.css('maxWidth', 'unset');								
					icon_name_text.text(waymark_admin_js.lang.marker_icon_html_label);
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
	jQuery('body.post-type-waymark_map #publish').on('click', function() {
		//Iterate inputs
		jQuery('.waymark-query-form.waymark-map-query .waymark-parameters-container .waymark-input').each(function() {
			var input = jQuery(this);
		
			switch(input.data('id')) {
				case 'query_area_bounds' :
					if(! input.val()) {
						Waymark.debug('//No query_area provided');
						
						var waymark_container = jQuery('.waymark-instance').first();
						var Waymark_Instance = waymark_container.data('Waymark');				
					
						//Instance
						if(Waymark_Instance) {
							//Area type
							switch(jQuery('.waymark-input-query_area_type', jQuery(this).parents('.waymark-parameters-container')).val()) {
								//Bounds
								case 'bounds' :
									//Map Data?
									if(Waymark_Instance.map_data.getLayers().length) {
										Waymark.debug('//Use Map Data bounds');
										input_value = Waymark_Instance.map_data.getBounds().toBBoxString();							
									} else {
										Waymark.debug('//Use Map View bounds');
										input_value = Waymark_Instance.map.getBounds().toBBoxString();								
									}
								
									//Update
									jQuery(this).val(input_value);
								
									break;
							}										
						}
					}
				
					break;
			}
		});	
	});	
	

	jQuery('.waymark-query-form.waymark-map-query').each(function() {
		var form_container = jQuery(this);

		//Each Query
		jQuery('.waymark-parameters-container', form_container).each(function() {
			var query_container = jQuery(this);
			
			var inputs = jQuery('.waymark-input', query_container);
			inputs.each(function() {
				jQuery(this).change(function() {
					waymark_execute_query(jQuery(this).parents('.waymark-parameters-container'));	
				});
			});
			inputs.last().trigger('change');
			
			//Expand on hover
			query_container.hover(
				function() {
					var query_container = jQuery(this);
					
					query_container.addClass('waymark-active');

					var waymark_container = jQuery('.waymark-instance').first();
					var Waymark_Instance = waymark_container.data('Waymark');
					
					var area_type_input = jQuery('.waymark-input-query_area_type', query_container).first();

					switch(area_type_input.val()) {
						case 'bounds' :

							var query_area_bounds = jQuery('.waymark-input-query_area_bounds', query_container).first().val();
							query_area_bounds = query_area_bounds.split(',');
							console.log(query_area_bounds);

							var bounds = [[query_area_bounds[1], query_area_bounds[0]] , [query_area_bounds[3], query_area_bounds[2]]];
							Waymark_Instance.query_preview = L.rectangle(bounds, {
								color: "#ff7800",
								weight: 1
							}).addTo(Waymark_Instance.map);
// 							rectangle.enableEdit();
// 							Waymark_Instance.map.on('editable:vertex:dragend', function(e) {
// 								console.log(e.layer.getBounds().toBBoxString());
// 							});
						
							break;
						case 'polygon' :
							break;
					}
				},
				function() {
					jQuery(this).removeClass('waymark-active');		

					var waymark_container = jQuery('.waymark-instance').first();
					var Waymark_Instance = waymark_container.data('Waymark');
					
					if(typeof Waymark_Instance.query_preview !== 'undefined') {
						Waymark_Instance.map.removeLayer(Waymark_Instance.query_preview);
					}
				}		
			);
		});			
	});
}

function waymark_setup_tax_query() {
	//Both Add and Edit forms
	jQuery('.waymark-query-form.waymark-tax-query').each(function() {
		//The form
		var container = jQuery(this);
	
		//Change
		jQuery('.waymark-parameters-container .waymark-input', container).each(function() {
			jQuery(this).change(function() {
				waymark_execute_query(jQuery(this).parents('.waymark-parameters-container'));	
			});
		});
		
		//Initial
		jQuery('.waymark-input', container).trigger('change');
	});
	//Prettify JSON and output to console
// 	jQuery('#waymark_query_meta textarea#query_overpass_response, #waymark_query_meta textarea#query_data').each(function() {
// 		var textarea = jQuery(this);
// 		
// 		//Only if visible (i.e. debug mode)
// 		if(textarea.is(':visible')) {
// 			try {
// 				if(json = JSON.parse(textarea.val())) {
// 					console.log(json);
// 					textarea.val(JSON.stringify(json, null, 2));			
// 				}
// 			} catch (e) {}		
// 		
// 		}
// 	});
}

function waymark_execute_query(container) {
	var request_data = {};
	
	var form_data = new FormData();
	form_data.append('waymark_security', waymark_admin_js.security);			
	form_data.append('action', 'waymark_get_query_data');			

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
				Waymark.debug(response.features.length + ' Features');						

				var waymark_container = jQuery('.waymark-instance').first();
				var Waymark_Instance = waymark_container.data('Waymark');

/*
				//Editor
				if(waymark_container.hasClass('waymark-editor')) {
					Waymark_Instance.query_data.eachLayer(function(layer) {
						Waymark_Instance.map.removeLayer(layer);			
					});											
					Waymark_Instance.query_data.clearLayers();			
				//Viewer
				} else {
// 					Waymark_Instance.map_data.eachLayer(function(layer) {
// 						Waymark_Instance.map.removeLayer(layer);			
// 					});																	
				}
*/	
				
				if(response.features.length) {
					Waymark_Instance.load_query_json(JSON.stringify(request_data), response);						
				}
			}
		});			
	}
}

function waymark_setup_settings_nav() {
	var nav_container = jQuery('#waymark-settings-nav');
	
	if(! nav_container) {
		return false;
	}

	var admin_container = jQuery('#waymark-admin-container');
	var form = jQuery('form', admin_container);

	var tabs = jQuery('.waymark-settings-tab', admin_container);
	var init_tab_key = nav_container.data('init_tab_key');
	
	//Initial
	tabs.each(function() {
		var tab = jQuery(this);

		var is_default_tab = (tab.attr('class').indexOf('tab-' + init_tab_key)) > 0;
		
		if(is_default_tab) {
			tab.show();			
		} else {
			tab.hide();			
		}
	});
	
	//Change
	var select = jQuery('select', nav_container);
	select.change(function () {
		var selected_tab_key = jQuery(this).val();
		
		//Update form redirect
		var redirect_input = jQuery('input[name="_wp_http_referer"]', form);
		var redirect_to = document.location.toString();
		if(redirect_to.indexOf('tab=') > 0) {
			redirect_to = redirect_to.replace('tab=' + init_tab_key, 'tab=' + selected_tab_key);
		} else {
			redirect_to = redirect_to + '&tab=' + selected_tab_key;
		}
		
		redirect_input.val(redirect_to);
		
		//Show selected
		tabs.each(function() {
			var tab = jQuery(this);

			var is_selected_tab = (tab.attr('class').indexOf('tab-' + selected_tab_key)) > 0;
			
			if(is_selected_tab) {
				tab.show();			
			} else {
				tab.hide();			
			}
		});
	});
}

function waymark_setup_repeatable_parameters() {
	jQuery('.waymark-repeatable-container').each(function() {
		var repeatable_container = jQuery(this);
		var repeatable_count = repeatable_container.data('count');
		
		var template = jQuery('.waymark-repeatable-template', repeatable_container);
		template.remove();
		
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

//				clone.attr('name', );
			//Add		
			add_button.before(clone);
			
			//Update count
			repeatable_container.data('count', ++repeatable_count);
			
			return false;
		});
	});
}

jQuery(document).ready(function() {
	waymark_setup_settings_nav();
	waymark_setup_repeatable_settings();
	waymark_setup_repeatable_parameters();
	waymark_setup_marker_tab();
	waymark_setup_colour_pickers();
	waymark_setup_external_links();
	waymark_setup_select_meta_type();
	waymark_setup_select_icon_type();
	waymark_setup_dropdowns();
	waymark_setup_tax_query();	
	waymark_setup_map_query();		
});