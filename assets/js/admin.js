function waymark_setup_colour_pickers() {
	jQuery('.waymark-colour-picker .waymark-input').wpColorPicker();	
}

function waymark_setup_repeatable_sections() {	
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
				input.addClass('waymark-' + input.attr('id'));

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
					clones[i][input.attr('id')] = values[i];
				}				
			});
						
			//Each clone
			for(i = 0; i < clones.length; i++) {
				var clone = form.clone();
				
				//Create input
				for(j in clones[i]) {
					var set_value = clones[i][j];
					var input = jQuery('#' + j, clone);
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

						switch(input.attr('id')) {
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
			var class_string = 'waymark-dropdown-' + jQuery(this).attr('id') + '-';			

			//Add new
			class_string += jQuery(this).val();
			container.addClass(class_string);
			
			//On Change
			jQuery(this).on('change', function() {			
				//Prefix
				var class_string = 'waymark-dropdown-' + jQuery(this).attr('id') + '-';			
				
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

function waymark_setup_query() {
	var query_form = jQuery('body.wp-admin.taxonomy-waymark_query form#addtag');
	
	if(! query_form.length) {
		return;
	}
	
	var add_button = jQuery('#submit', query_form);
	add_button.attr({
		'disabled' : 'disabled'
	});
	
	var preview_button = jQuery('<input />')
		.attr({
			'type' : 'button',
			'value' : 'Preview'
		})		
		.on('click', function(e) {
			e.preventDefault();
		
			Waymark.debug(waymark_admin_js.ajaxurl);

			var form_data = new FormData();
			form_data.append('waymark_security', waymark_admin_js.security);			
			form_data.append('action', 'waymark_get_query_data');			

			jQuery.ajax({
				type: "POST",
				url: waymark_admin_js.ajaxurl,
				data: form_data,
				dataType: 'json',
				processData: false,
				contentType: false,
				success: function(response) {				
					Waymark.debug(response);						
				}
			});			
		
			return false;
		})
	;
	query_form.append(preview_button);
	
	return;

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
jQuery(document).ready(function() {
	waymark_setup_repeatable_sections();
	waymark_setup_marker_tab();
	waymark_setup_colour_pickers();
	waymark_setup_external_links();
	waymark_setup_select_meta_type();
	waymark_setup_select_icon_type();
	waymark_setup_dropdowns();
	waymark_setup_query();	
});