/*
	==================================
	========== LOCALIZATION ==========
	==================================
*/

var waymark_js_localize = {
	//Viewer
	action_fullscreen_activate: "View Fullscreen",
	action_fullscreen_deactivate: "Exit Fullscreen",
	action_locate_activate: "Show me where I am",
	action_zoom_in: "Zoom in",
	action_zoom_out: "Zoom out",
	label_total_length: "Total Length: ",
	label_max_elevation: "Max. Elevation: ",
	label_min_elevation: "Min. Elevation: ",
	label_ascent: "Total Ascent: ",
	label_descent: "Total Descent: ",
	//Editor
	add_line_title: "Draw a Line",
	add_photo_title: "Upload an Image",
	add_marker_title: "Place a Marker",
	add_rectangle_title: "Draw a Rectangle",
	add_polygon_title: "Draw a Polygon",
	add_circle_title: "Draw a Circle",
	upload_file_title:
		"Read Lines and Markers from file (GPX/KML/GeoJSON supported, which most apps should Export to)",
	action_duplicate: "Duplicate",
	action_delete: "Delete",
	action_edit: "Edit",
	action_edit_done: "Finish editing",
	action_upload_image: "Upload Image",
	object_title_placeholder: "Title",
	object_image_placeholder: "Image URL",
	object_description_placeholder: "Description",
	object_type_label: "Type",
	marker_latlng_label: "Lat,Lng",
	action_delete_confirm: "Are you sure you want to delete this",
	action_search_placeholder: "Search...",
	object_label_marker: "Marker",
	object_label_line: "Line",
	object_label_shape: "Shape",
	object_label_marker_plural: "Markers",
	object_label_line_plural: "Lines",
	object_label_shape_plural: "Shapes",
	error_message_prefix: "Waymark Error",
	info_message_prefix: "Waymark Info",
	debug_message_prefix: "Waymark Debug",
	error_file_type: "This file type is not supported.",
	error_file_conversion: "Could not convert this file to GeoJSON.",
	error_file_upload: "File upload error.",
	error_photo_meta: "Could not retrieve Image metadata.",
	info_exif_yes: "Image location metadata (EXIF) detected!",
	info_exif_no: "Image location metadata (EXIF) NOT detected.",
	error_no_wpmedia: "WordPress Media Library not found",
	no_direction: "No Direction",
	show_direction: "Show Direction",
	reverse_direction: "Reverse Direction",
};

if (typeof waymark_js === "undefined") {
	var waymark_js = {
		lang: {},
	};
}
for (key in waymark_js_localize) {
	if (typeof waymark_js.lang[key] === "undefined") {
		waymark_js.lang[key] = waymark_js_localize[key];
	}
}

/*
	==================================
	=============== MAP ==============
	==================================
*/

function Waymark_Map() {
	this.fallback_latlng = [51.38436, -68.74923];
	this.fallback_zoom = 9;

	this.init = function (user_config) {
		Waymark = this;

		//Start timer
		Waymark.start_time = new Date().getTime();

		Waymark.jq_map_container = null;

		//Default config
		Waymark.config = {
			map_div_id: "waymark-map",
			map_options: {
				show_type_labels: 1,
				button_position: "bottomright",
				max_zoom: 0,
			},
			map_height: 400,
			map_width: null,
			map_init_zoom: undefined,
			map_init_latlng: undefined,
			map_init_basemap: undefined,
			show_gallery: 0,
			show_filter: 0,
			show_elevation: 0,
			show_cluster: 1,
			elevation_div_id: "waymark-elevation",
			elevation_units: "metric",
			elevation_initial: 1,
			tile_layers: {},
			line_types: {},
			shape_types: {},
			marker_types: {},
			marker_data_defaults: {
				title: undefined,
				type: undefined,
				image_thumbnail_url: undefined,
				image_medium_url: undefined,
				image_large_url: undefined,
				description: undefined,
			},
			line_data_defaults: {
				type: undefined,
				title: undefined,
				image_thumbnail_url: undefined,
				image_medium_url: undefined,
				image_large_url: undefined,
				description: undefined,
				direction: undefined,
			},
			shape_data_defaults: {
				type: undefined,
				title: undefined,
				image_thumbnail_url: undefined,
				image_medium_url: undefined,
				image_large_url: undefined,
				description: undefined,
			},
			editor_options: {
				confirm_delete: 1,
			},
			media_library_sizes: ["thumbnail", "medium", "large", "full"],
		};

		//Load user config
		for (config_key in Waymark.config) {
			if (typeof user_config[config_key] !== "undefined") {
				Waymark.config[config_key] = user_config[config_key];
			}
		}

		//Set defaults
		var default_line_type = Waymark.get_type("line");
		var default_line_type_key = Waymark.make_key(default_line_type.line_title);
		Waymark.config.line_data_defaults.type = default_line_type_key;

		var default_shape_type = Waymark.get_type("shape");
		var default_shape_type_key = Waymark.make_key(
			default_shape_type.shape_title,
		);
		Waymark.config.shape_data_defaults.type = default_shape_type_key;

		var default_marker_type = Waymark.get_type("marker");
		var default_marker_type_key = Waymark.make_key(
			default_marker_type.marker_title,
		);
		Waymark.config.marker_data_defaults.type = default_marker_type_key;

		//Groups
		Waymark.marker_parent_group = Waymark_L.layerGroup();
		Waymark.marker_sub_groups = {};
		Waymark.line_parent_group = Waymark_L.layerGroup();
		Waymark.line_sub_groups = {};
		Waymark.shape_parent_group = Waymark_L.layerGroup();
		Waymark.shape_sub_groups = {};

		//Setup...
		Waymark.pre_map_setup();
		Waymark.setup_map();
		Waymark.handle_resize();
		Waymark.init_done();
	};

	//Thanks! https://stackoverflow.com/questions/2631001/test-for-existence-of-nested-javascript-object-key
	this.get_property = function (obj, ...args) {
		return args.reduce((obj, level) => obj && obj[level], obj);
	};

	/**
	 *
	 * Output debugging content
	 * 	- Only if debug_mode is enabled (Settings > Misc > Advanced)
	 *
	 * @param  {string} thing  Thing to debug
	 * @param  {string} output Output method (console|alert)
	 * @return {void}
	 * @since  0.9
	 *
	 * @example
	 * Waymark.debug('Hello World');
	 * Waymark.debug({foo: 'bar'});
	 * Waymark.debug({foo: 'bar'}, 'alert');
	 *
	 */
	this.debug = function (thing, output = "console") {
		if (
			this.get_property(waymark_settings, "misc", "advanced", "debug_mode") ==
			true
		) {
			//String

			if (typeof thing === "string") {
				this.message(thing, "debug", output);

				//Object
			} else {
				// Ensure if plain object
				if (typeof thing === "object" && thing !== null) {
					thing = JSON.parse(JSON.stringify(thing));
				}

				// Console
				if (output == "console") {
					console.debug(thing);

					//Alert
				} else {
					this.message(JSON.stringify(thing), "debug", output);
				}
			}
		}
	};

	this.message = function (text = null, type = "info", output = "console") {
		if (text) {
			var prefix = "";

			switch (type) {
				case "debug":
				case "error":
					prefix = waymark_js.lang[type + "_message_prefix"];

					break;
				default:
				case "info":
					prefix = waymark_js.lang.info_message_prefix;

					break;
			}

			if (prefix) {
				prefix = "[" + prefix + "] ";
			}

			if (output == "console") {
				if (type == "error") {
					console.error(prefix + text);
				} else if (type == "debug") {
					console.debug(prefix + text);
				} else {
					console.log(prefix + text);
				}
			} else {
				alert(prefix + text);
			}
		}
	};

	this.title_case = function (str) {
		return str.replace(/(?:^|\s)\w/g, function (match) {
			return match.toUpperCase();
		});
	};

	//Cyrillic to latin
	//Thanks! https://stackoverflow.com/a/11404121
	this.transliterate = function (word) {
		var a = {
			Ё: "YO",
			Й: "I",
			Ц: "TS",
			У: "U",
			К: "K",
			Е: "E",
			Н: "N",
			Г: "G",
			Ш: "SH",
			Щ: "SCH",
			З: "Z",
			Х: "H",
			Ъ: "'",
			ё: "yo",
			й: "i",
			ц: "ts",
			у: "u",
			к: "k",
			е: "e",
			н: "n",
			г: "g",
			ш: "sh",
			щ: "sch",
			з: "z",
			х: "h",
			ъ: "'",
			Ф: "F",
			Ы: "I",
			В: "V",
			А: "a",
			П: "P",
			Р: "R",
			О: "O",
			Л: "L",
			Д: "D",
			Ж: "ZH",
			Э: "E",
			ф: "f",
			ы: "i",
			в: "v",
			а: "a",
			п: "p",
			р: "r",
			о: "o",
			л: "l",
			д: "d",
			ж: "zh",
			э: "e",
			Я: "Ya",
			Ч: "CH",
			С: "S",
			М: "M",
			И: "I",
			Т: "T",
			Ь: "'",
			Б: "B",
			Ю: "YU",
			я: "ya",
			ч: "ch",
			с: "s",
			м: "m",
			и: "i",
			т: "t",
			ь: "'",
			б: "b",
			ю: "yu",
		};

		return word
			.split("")
			.map(function (char) {
				return a[char] || char;
			})
			.join("");
	};

	this.make_key = function (str) {
		if (!str) {
			return str;
		}

		//No cyrillic
		str = this.transliterate(str);

		//No underscores
		str = str.replace(/[^a-z0-9+]+/gi, "");

		//Lower
		str = str.toLowerCase();

		return str;
	};

	this.get_feature_overlay_type = function (feature) {
		if (typeof feature.geometry.type == "undefined") {
			return false;
		}

		switch (feature.geometry.type) {
			// CIRCLES & MARKERS

			case "Point":
				//Circle
				if (feature.properties.radius) {
					return "shape";

					//Marker
				} else {
					return "marker";
				}

			// LINES
			case "LineString":
			case "MultiLineString":
				return "line";

			// Polygon & Rectangle
			case "Polygon":
				//Is this a retangle?
				//				if(feature.properties.rectangle) {}

				return "shape";

				break;
		}

		return false;
	};

	/*
	==================================
	========= COMMOM METHODS =========
	==================================
*/

	this.setup_map = function () {
		Waymark = this;

		Waymark.jq_map_container = jQuery("#" + Waymark.config.map_div_id);
		Waymark.jq_map_container.addClass("waymark-map-container");
		Waymark.jq_map_container.css("height", Waymark.config.map_height + "px");

		//Create Map
		var map_options = {
			fullscreenControl: false,
			attributionControl: false,
			editable: true,
			zoomControl: false,
			sleep: false,
		};

		// === Viewer ===

		if (Waymark.mode == "view") {
			//START Sleep
			//  			map_options.scrollWheelZoom = false;
			//Let Sleep enable this on Wake
			map_options.dragging = false;

			//Sleep
			map_options.sleep = true;
			map_options.wakeTime =
				this.get_property(
					waymark_settings,
					"misc",
					"interaction_options",
					"delay_seconds",
				) * 1000;

			//If Sleep Note
			var do_message = this.get_property(
				waymark_settings,
				"misc",
				"interaction_options",
				"do_message",
			);
			if (do_message === "1") {
				map_options.sleepNote = true;
				map_options.wakeMessage = this.get_property(
					waymark_settings,
					"misc",
					"interaction_options",
					"wake_message",
				);
				//No Sleep Note
			} else {
				map_options.sleepNote = false;
				map_options.wakeMessage = false;
			}

			// 	    hoverToWake: false,
			map_options.sleepOpacity = 1;
			//END Sleep

			//Max Zoom
			if ((max_zoom = Waymark.config.map_options.max_zoom)) {
				map_options.maxZoom = max_zoom;
			}

			// === Editor ===
		} else {
			//Sleep not used, enable
			map_options.dragging = true;
		}

		Waymark.map = Waymark_L.map(Waymark.config.map_div_id, map_options);
		Waymark_L.control
			.attribution({
				prefix:
					'<a href="https://www.waymark.dev/" title="Share your way">Waymark</a>',
			})
			.addTo(Waymark.map);

		//Show scale?
		if (
			this.get_property(
				waymark_settings,
				"misc",
				"map_options",
				"show_scale",
			) == true
		) {
			Waymark_L.control.scale().addTo(Waymark.map);
		}

		//Add reference
		Waymark.jq_map_container.data("Waymark", Waymark);

		//View
		if (Waymark.config.map_init_latlng !== undefined) {
			Waymark.map.setView(Waymark.config.map_init_latlng);
		} else {
			Waymark.map.setView(Waymark.fallback_latlng);
		}
		if (Waymark.config.map_init_zoom !== undefined) {
			Waymark.map.setZoom(Waymark.config.map_init_zoom);
		} else {
			Waymark.map.setZoom(Waymark.fallback_zoom);
		}

		//Set default style
		Waymark_L.Path.mergeOptions({
			color: "#b42714",
		});

		//Zoom Control
		Waymark_L.control
			.zoom({
				position: Waymark.config.map_options.button_position,
				zoomInTitle: waymark_js.lang.action_zoom_in,
				zoomOutTitle: waymark_js.lang.action_zoom_out,
			})
			.addTo(Waymark.map);

		//Locate Button
		Waymark_L.control
			.locate({
				position: "bottomright",
				icon: "ion ion-android-locate",
				drawCircle: false,
				strings: {
					title: waymark_js.lang.action_locate_activate,
				},
				locateOptions: {
					enableHighAccuracy: true,
				},
			})
			.addTo(Waymark.map);

		//Fullscreen Control
		Waymark_L.control
			.fullscreen({
				position: Waymark.config.map_options.button_position,
				title: {
					false: waymark_js.lang.action_fullscreen_activate,
					true: waymark_js.lang.action_fullscreen_deactivate,
				},
			})
			.addTo(Waymark.map);

		//Add parent groups to map
		Waymark.marker_parent_group.addTo(Waymark.map);
		Waymark.line_parent_group.addTo(Waymark.map);
		Waymark.shape_parent_group.addTo(Waymark.map);

		//Setup
		Waymark.setup_layers();
		Waymark.create_data_layer();
		Waymark.create_buttons();
	};

	this.create_data_layer = function () {
		Waymark = this;

		//Create data layer
		Waymark.map_data = Waymark_L.geoJSON(null, {
			pointToLayer: function (feature, latlng) {
				if (
					typeof feature.properties !== "undefined" &&
					feature.properties.radius
				) {
					return new Waymark_L.Circle(
						latlng,
						parseFloat(feature.properties.radius),
					);
				} else {
					return Waymark.create_marker(latlng);
				}
			},
			onEachFeature: function (feature, layer) {
				switch (feature.geometry.type) {
					// CIRCLES & MARKERS

					case "Point":
						//Circle
						if (feature.properties.radius) {
							//Build Waymark data
							feature.properties = Waymark.parse_layer_data(
								"shape",
								feature.properties,
							);

							//Set style
							var type = Waymark.get_type("shape", feature.properties.type);
							layer.setStyle({
								color: type.shape_colour,
								fillOpacity: type.fill_opacity,
							});

							//Set info window
							Waymark.info_window("shape", feature, layer);

							//Set title tooltip
							Waymark.tooltip("shape", feature, layer);

							//Add to group
							Waymark.add_to_group("shape", layer);
							//Marker
						} else {
							//Build Waymark data
							feature.properties = Waymark.parse_layer_data(
								"marker",
								feature.properties,
							);

							//Set marker style
							var type = Waymark.get_type("marker", feature.properties.type);

							//Create Icon
							layer.setIcon(Waymark_L.divIcon(Waymark.build_icon_data(type)));

							//Add any photos to photo gallery
							if (typeof Waymark.gallery_images !== "undefined") {
								Waymark.add_to_gallery(layer);
							}

							//Set info window
							Waymark.info_window("marker", feature, layer);

							//Set title tooltip
							Waymark.tooltip("marker", feature, layer);

							//Add to group
							Waymark.add_to_group("marker", layer);
						}

						break;

					// LINES

					case "LineString":
					case "MultiLineString":
						//Build Waymark data
						feature.properties = Waymark.parse_layer_data(
							"line",
							feature.properties,
						);

						//Set line style
						var type = Waymark.get_type("line", feature.properties.type);
						layer.setStyle({
							color: type.line_colour,
							weight: type.line_weight,
							opacity: type.line_opacity,
						});

						//Set info window
						Waymark.info_window("line", feature, layer);

						//Set title tooltip
						Waymark.tooltip("line", feature, layer);

						//Line direction, shown initially?
						var show_initially = parseInt(type.line_display);
						Waymark.draw_line_direction(layer, show_initially);

						//Add to group
						Waymark.add_to_group("line", layer);

						break;

					// Polygon & Rectangle

					case "Polygon":
						//Build Waymark data
						feature.properties = Waymark.parse_layer_data(
							"shape",
							feature.properties,
						);

						//Is this a retangle?
						if (feature.properties.rectangle) {
							//...
						}

						//Set shape style
						var type = Waymark.get_type("shape", feature.properties.type);
						layer.setStyle({
							color: type.shape_colour,
							fillOpacity: type.fill_opacity,
						});

						//Set info window
						Waymark.info_window("shape", feature, layer);

						//Set title tooltip
						Waymark.tooltip("shape", feature, layer);

						//Add to group
						Waymark.add_to_group("shape", layer);

						break;
				}
			},
		});
	};

	this.draw_line_direction = function (layer, show_initially = true) {
		var feature = layer.feature;
		var direction = feature.properties.direction;
		var type = Waymark.get_type("line", feature.properties.type);

		if (typeof layer.direction_layer === "object") {
			Waymark.map.removeLayer(layer.direction_layer);
		}

		//Valid direction
		if (
			typeof direction === "string" &&
			(direction == "default" || direction == "reverse")
		) {
			var head_angle = 45;
			//Reverse
			if (direction == "reverse") {
				head_angle = 360 - head_angle;
			}

			var decorator = Waymark_L.polylineDecorator(layer, {
				patterns: [
					{
						// 	            	offset: 25,
						repeat: 100,
						symbol: L.Symbol.arrowHead({
							pixelSize: 15,
							headAngle: head_angle,
							polygon: true,
							pathOptions: {
								color: "#fff",
								fillColor: type.line_colour,
								opacity: "0.7",
								stroke: true,
								fillOpacity: 0.7,
								weight: 2,
							},
						}),
					},
				],
			});

			if (show_initially) {
				decorator.addTo(Waymark.map);
			}

			layer.direction_layer = decorator;
		}
	};

	this.setup_layers = function () {
		Waymark = this;

		Waymark.layer_control = Waymark_L.control.layers();

		var basemaps = [];
		var initial_basemap_index = 0;

		//Determine initial basemap
		//Set by name?
		if (typeof Waymark.config.map_init_basemap !== "undefined") {
			//Search
			for (var i in Waymark.config.tile_layers) {
				var init_basemap_name = Waymark.config.map_init_basemap.toUpperCase();
				var this_basemap_name =
					Waymark.config.tile_layers[i].layer_name.toUpperCase();

				//Found
				if (init_basemap_name === this_basemap_name) {
					//Use
					initial_basemap_index = i;
				}
			}
		}

		//For each tile layer
		for (var i in Waymark.config.tile_layers) {
			//Append URL?
			if (typeof Waymark.config.tile_layers[i].append !== "undefined") {
				Waymark.config.tile_layers[i].layer_url +=
					Waymark.config.tile_layers[i].append;
			}

			//Create key
			var basemap_key = Waymark.config.tile_layers[i].layer_name.replace(
				/ /g,
				"",
			);

			//Create tile layer
			var layer_options = {
				id: basemap_key,
				attribution: Waymark.config.tile_layers[i].layer_attribution,
			};

			//Max zoom?
			var layer_max_zoom = parseInt(
				Waymark.config.tile_layers[i].layer_max_zoom,
			);
			if (layer_max_zoom) {
				layer_options.maxZoom = layer_max_zoom;
			}

			var basemap = Waymark_L.tileLayer(
				Waymark.config.tile_layers[i].layer_url,
				layer_options,
			);
			basemaps[Waymark.config.tile_layers[i].layer_name] = basemap;

			//Set initial basemap
			if (i == initial_basemap_index) {
				basemap.addTo(Waymark.map);
			}
		}

		//More than one tile layer
		if (i >= 1) {
			//Layer control
			Waymark.layer_control.addTo(Waymark.map);
			for (basemap_name in basemaps) {
				Waymark.layer_control.addBaseLayer(
					basemaps[basemap_name],
					basemap_name,
				);
			}
		}
	};

	this.get_type = function (layer_type, type_key) {
		Waymark = this;

		var type = null;

		//Iterate over all types
		for (var i in Waymark.config[layer_type + "_types"]) {
			//Use first as default
			if (i == 0) {
				type = Waymark.config[layer_type + "_types"][i];
			}

			//Grab title
			var type_title =
				Waymark.config[layer_type + "_types"][i][layer_type + "_title"];

			//Has title
			if (type_title) {
				//Found (run both through make_key, just to be on safe side)
				if (Waymark.make_key(type_key) == Waymark.make_key(type_title)) {
					type = Waymark.config[layer_type + "_types"][i];
				}
			}
		}

		//Set key
		type = Waymark.parse_type(type, layer_type);

		return type;
	};

	//Checks for types
	this.parse_type = function (type = {}, layer_type = "marker") {
		Waymark = this;

		if (typeof type === "undefined" || type === null) {
			type = {};
		}

		switch (layer_type) {
			case "line":
				//Checks
				var required = [
					{
						key: "line_colour",
						default: "#b42714",
					},
					{
						key: "line_weight",
						default: "3",
					},
					{
						key: "line_opacity",
						default: "0.7",
					},
				];

				for (var i in required) {
					//If undefined
					if (typeof type[required[i]["key"]] !== "string") {
						//Set default
						type[required[i]["key"]] = required[i]["default"];
					}
				}

				break;
		}

		type.type_key = Waymark.make_key(type[layer_type + "_title"]);

		return type;
	};

	this.handle_resize = function () {
		Waymark = this;

		jQuery(window).on("resize", function () {
			Waymark.config.map_height = Waymark.jq_map_container.height();
			Waymark.config.map_width = Waymark.jq_map_container.width();

			if (typeof Waymark.size_gallery === "function") {
				Waymark.size_gallery();
			}
		});
	};

	this.tooltip = function (layer_type, feature, layer) {
		Waymark = this;

		var text = "";

		//Displaying Type?
		if (Waymark.config.map_options.show_type_labels == "1") {
			var type = Waymark.get_type(layer_type, feature.properties.type);

			if (type) {
				var title = type[layer_type + "_title"];

				if (title) {
					text = "[" + title + "] ";
				}
			}
		}

		//Title
		if (feature.properties.title) {
			text += feature.properties.title;
		}

		if (!text) {
			return;
		}

		layer.bindTooltip(text);

		layer.on("mouseover", function (e) {
			var tooltip = e.target.getTooltip();
			tooltip.setLatLng(e.latlng);
			tooltip.openTooltip();
		});

		layer.on("mousemove", function (e) {
			var tooltip = e.target.getTooltip();
			tooltip.setLatLng(e.latlng);
		});
	};

	this.get_data_defaults = function (layer_type) {
		return Object.assign({}, Waymark.config[layer_type + "_data_defaults"]);
	};

	this.parse_layer_data = function (layer_type, data_in) {
		Waymark = this;

		//Start with defaults
		var data_out = Waymark.get_data_defaults(layer_type);

		//Check for stored properties
		if (typeof data_in === "object") {
			//Iterate
			for (key in data_out) {
				//If we have something
				if (typeof data_in[key] != "undefined" && data_in[key]) {
					//Use it
					data_out[key] = data_in[key];
				}
			}
		}

		//Migrate some parameters

		//Iterate
		for (key in data_in) {
			//Has value
			if (data_in[key]) {
				switch (key) {
					case "name":
						data_out.title = data_in[key];

						break;
					case "desc":
					case "notes":
						data_out.description = data_in[key];

						break;
					// 				case 'photos':
					// 					waymark_data.type = 'photo';
					//
					// 					for(var i in feature.properties[prop]) {
					// 						//Set thumb
					// 						if(typeof feature.properties[prop][i]['web_url'] !== 'undefined') {
					// 							waymark_data.image_thumbnail_url = feature.properties[prop][i]['web_url'];
					// 						}
					//
					// 						//Set large
					// 						if(typeof feature.properties[prop][i]['web_url'] !== 'undefined') {
					// 							waymark_data.image_large_url = feature.properties[prop][i]['scaled_url'];
					// 						}
					// 					}
					//
					// 					break;
					case "radius":
						data_out[key] = parseFloat(data_in[key]);

						break;
				}
			}
		}

		//Importing Overlay Properties?
		var overlay_properties = this.get_property(
			waymark_settings,
			"overlay",
			"properties",
		);
		var properties_keys = Object.keys(overlay_properties);

		// Valid properties?
		if (properties_keys.length && properties_keys.pop()) {
			var properties_html = "";

			for (i in overlay_properties) {
				var key = overlay_properties[i]["property_key"];

				var title = overlay_properties[i]["property_title"];
				var value = data_in[key];

				if (typeof value !== "undefined") {
					properties_html +=
						'<p class="waymark-property waymark-property-' +
						key +
						'"><b>' +
						title +
						"</b><br />" +
						value +
						"</p>";

					Waymark.debug("Importing " + title + " (" + key + ") ==> " + value);
				}
			}

			if (properties_html) {
				if (typeof data_out.description === "undefined") {
					data_out.description = "";
				}

				data_out.description += properties_html;
			}
		}

		return data_out;
	};

	this.add_to_group = function (layer_type, layer) {
		var feature = layer.feature;

		//If we have a type
		if (typeof feature.properties.type !== "undefined") {
			//Get Type
			var type_key = feature.properties.type;
			var type = Waymark.get_type(layer_type, type_key);

			if (
				typeof Waymark[layer_type + "_sub_groups"][type.type_key] == "undefined"
			) {
				//Create the sub-group
				var group = Waymark_L.featureGroup.subGroup(
					Waymark[layer_type + "_parent_group"],
				);

				//Add to groups
				Waymark[layer_type + "_sub_groups"][type.type_key] = group;

				//Add to Map
				if (
					Waymark.mode == "view" &&
					typeof type[layer_type + "_display"] !== "undefined"
				) {
					if (type[layer_type + "_display"] == "1") {
						group.addTo(Waymark.map);
					}
				} else {
					group.addTo(Waymark.map);
				}
			}

			//Add Layer to group
			layer.addTo(Waymark[layer_type + "_sub_groups"][type.type_key]);
			//Direction layer?
			if (layer_type == "line" && typeof layer.direction_layer === "object") {
				layer.direction_layer.addTo(
					Waymark[layer_type + "_sub_groups"][type.type_key],
				);
			}

			//If Overlay Filter is enabled
			if (Waymark.config.show_filter && Waymark.mode == "view") {
				//Ensure the control is added
				Waymark.layer_control.addTo(Waymark.map);

				//Redraw in layer Control
				Waymark.layer_control.removeLayer(
					Waymark[layer_type + "_parent_group"],
				);
				Waymark.layer_control.addOverlay(
					Waymark[layer_type + "_parent_group"],
					"<b>" +
						waymark_js_localize["object_label_" + layer_type + "_plural"] +
						"</b>",
				);

				Waymark_L.stamp(Waymark[layer_type + "_parent_group"]);
				for (key in Waymark[layer_type + "_sub_groups"]) {
					var this_type = Waymark.get_type(layer_type, key);
					var group = Waymark[layer_type + "_sub_groups"][key];

					//(Re-?)add to control
					Waymark.layer_control.removeLayer(group);
					Waymark.layer_control.addOverlay(
						group,
						Waymark.type_to_text(layer_type, this_type),
					);
				}
			}
			//No type key - just add to Map
		} else {
			layer.addTo(Waymark[layer_type + "_parent_group"]);
		}
	};

	//Represent Type as text
	this.type_to_text = function (layer_type = "", type = {}, ele = "span") {
		var preview_class = "waymark-type-text waymark-" + layer_type + "-type";
		var preview_style = "";

		switch (layer_type) {
			case "marker":
				preview_style += "color:" + type.icon_colour + ";";
				preview_style +=
					"background:" + Waymark.get_marker_background(type.marker_colour);

				break;
			case "line":
				preview_style += "background:" + type.line_colour;

				break;
			case "shape":
				preview_style += "background:" + type.shape_colour;

				break;
		}

		return (
			"<" +
			ele +
			' class="' +
			preview_class +
			'" style="' +
			preview_style +
			'">' +
			type[layer_type + "_title"] +
			"</" +
			ele +
			">"
		);
	};

	//Create marker
	this.create_marker = function (latlng) {
		return Waymark_L.marker(latlng);
	};

	this.build_icon_data = function (type) {
		var icon_data = {
			className: "waymark-marker waymark-marker-" + type.type_key,
		};

		//Shape
		if (
			typeof type.marker_shape !== "undefined" &&
			typeof type.marker_size !== "undefined"
		) {
			icon_data.className += " waymark-marker-" + type.marker_shape;
			icon_data.className += " waymark-marker-" + type.marker_size;

			switch (type.marker_shape) {
				//Markers & Circles
				case "rectangle":
				case "circle":
				case "marker":
					//Size
					switch (type.marker_size) {
						case "small":
							icon_data.iconSize = [16, 16];

							break;
						case "medium":
							icon_data.iconSize = [25, 25];

							break;
						default:
						case "large":
							icon_data.iconSize = [32, 32];

							break;
					}

					break;
			}

			//Marker only
			if (type.marker_shape == "marker") {
				icon_data.iconAnchor = [
					icon_data.iconSize[0] / 2,
					icon_data.iconSize[1] * 1.25,
				];
			}
		}

		//CSS Styles
		var background_css =
			"background:" + Waymark.get_marker_background(type.marker_colour) + ";";
		var icon_css = "color:" + type.icon_colour + ";";

		//HTML
		icon_data.html =
			'<div class="waymark-marker-background" style="' +
			background_css +
			'"></div>';

		//Classes
		var icon_class = "waymark-marker-icon";

		//Text, HTML or Icon Name
		switch (type.icon_type) {
			//Text
			case "text":
				icon_class += " waymark-icon-text";

				icon_data.html +=
					'<div style="' +
					icon_css +
					'" class="' +
					icon_class +
					'">' +
					type.marker_icon +
					"</div>";

				break;

			//HTML
			case "html":
				icon_class += " waymark-icon-html";

				//Decode HTML entities using jQuery
				var icon_html = jQuery("<div/>").html(type.marker_icon).text();

				icon_data.html +=
					'<div class="' + icon_class + '">' + icon_html + "</div>";

				break;

			//Icon Name
			case "icon":
			default:
				icon_class += " waymark-icon-icon";

				//If Ionic Icons
				if (type.marker_icon.indexOf("ion-") === 0) {
					icon_class += " ion ";
					icon_class += " " + type.marker_icon;
					//Font Awesome
				} else if (type.marker_icon.indexOf("fa-") === 0) {
					icon_class += " fa";
					icon_class += " " + type.marker_icon;
					//Default to Ionic
				} else {
					icon_class += " ion";
					icon_class += " ion-" + type.marker_icon;
				}

				icon_data.html +=
					'<i style="' + icon_css + '" class="' + icon_class + '"></i>';

				break;
		}

		return icon_data;
	};

	this.build_type_heading = function (
		overlay_type = "marker",
		type_key = "photo",
	) {
		//Get Type
		const type = Waymark.get_type(overlay_type, type_key);

		if (!type) {
			Waymark.message("Type not found: " + type_key, "error");
		}

		// Defaults
		let text_color = "inherit";
		let background_color = type.shape_colour;

		// Switch
		switch (overlay_type) {
			case "marker":
				background_color = Waymark.get_marker_background(type.marker_colour);

				break;
			case "line":
				background_color = type.line_colour;

				break;

			case "shape":
				background_color = type.shape_colour;

				break;
		}

		if (type) {
			heading = `
				<div class="waymark-type-heading" style="background:${background_color}">
					${Waymark.type_preview(overlay_type, type)}
					${Waymark.type_to_text(overlay_type, type)}
				</div>
			`;
		}

		return heading;
	};

	this.type_preview = function (
		overlay_type = "marker",
		type = {},
		ele = "div",
	) {
		let out = `<${ele} class="waymark-type-preview waymark-${overlay_type}-type waymark-${overlay_type}-${type.type_key}">`;

		// By overlay type
		switch (overlay_type) {
			case "marker":
				// Get Icon Data
				const icon_data = Waymark.build_icon_data(type);

				// Add Icon
				out += `<div class="${icon_data.className}">${icon_data.html}</div>`;

				break;
			case "line":
				out += Waymark.type_to_text(overlay_type, type, "span");

				break;

			case "shape":
				out += Waymark.type_to_text(overlay_type, type, "span");

				break;
		}

		out += `</${ele}>`;

		return out;
	};

	this.get_marker_background = function (colour) {
		var old_background_options = [
			"red",
			"darkred",
			"orange",
			"green",
			"darkgreen",
			"blue",
			"purple",
			"darkpurple",
			"cadetblue",
			"white",
			"black",
		];

		//Already hex
		// 		if(colour.indexOf('#' === 0)) {
		// 			return colour;
		// 		}

		//Convert
		if (old_background_options.includes(colour)) {
			switch (colour) {
				case "red":
					return "#da3d20";
					break;
				case "darkred":
					return "#a43233";
					break;
				case "orange":
					return "#f9960a";
					break;
				case "green":
					return "#70af00";
					break;
				case "darkgreen":
					return "#72820d";
					break;
				case "blue":
					return "#2aabe1";
					break;
				case "purple":
					return "#d553bd";
					break;
				case "darkpurple":
					return "#5c3a6e";
					break;
				case "cadetblue":
					return "#416979";
					break;
				case "white":
					return "#fbfbfb";
					break;
				case "black":
					return "#303030";
					break;
			}
		}

		return colour;
	};

	this.create_marker_json = function (lat_lng, properties = {}) {
		var marker_properties = Object.assign(
			{},
			Waymark.config.marker_data_defaults,
			properties,
		);

		var marker_json = {
			geometry: {
				type: "Point",
				coordinates: [lat_lng.lng, lat_lng.lat],
			},
			type: "Feature",
			properties: marker_properties,
		};

		return marker_json;
	};

	this.get_exif_latlng = function (data) {
		if (
			data.GPSLatitudeNum &&
			!isNaN(data.GPSLatitudeNum) &&
			data.GPSLongitudeNum &&
			!isNaN(data.GPSLongitudeNum)
		) {
			Waymark.debug(waymark_js.lang.info_exif_yes);

			return L.latLng(data.GPSLatitudeNum, data.GPSLongitudeNum);
		} else {
			Waymark.debug(waymark_js.lang.info_exif_no);
		}

		return false;
	};

	this.get_image_sizes = function (data, fallback) {
		Waymark = this;

		var image_sizes = {};

		//Grab these
		for (var i in Waymark.config.media_library_sizes) {
			//Use fallback
			image_sizes["image_" + Waymark.config.media_library_sizes[i] + "_url"] =
				fallback;

			//We have the data we want
			if (
				typeof data[Waymark.config.media_library_sizes[i]] !== "undefined" &&
				typeof data[Waymark.config.media_library_sizes[i]]["url"] !==
					"undefined"
			) {
				//Use it
				image_sizes["image_" + Waymark.config.media_library_sizes[i] + "_url"] =
					data[Waymark.config.media_library_sizes[i]]["url"];
			}
		}

		return image_sizes;
	};

	this.build_overlay_content = function (
		feature = [],
		feature_type = "",
		type_data = [],
	) {
		Waymark = this;

		const has_title = feature.properties.title
			? "waymark-overlay-has-title"
			: "";
		const has_desc = feature.properties.description
			? "waymark-overlay-has-desc"
			: "";
		const has_image = feature.properties.image_large_url
			? "waymark-overlay-has-image"
			: "";

		var content = `<div class="waymark-overlay-content ${has_title} ${has_desc} ${has_image} waymark-overlay-${feature_type}">`;

		//Expected Waymark properties
		const property_keys = ["type", "title", "description", "image_large_url"];
		//Iterate over each

		for (index in property_keys) {
			var property_key = property_keys[index];

			//Wrap in div
			content += `<div class="waymark-overlay-property waymark-overlay-property-${property_key}">`;

			switch (property_key) {
				//Type
				case "type":
					content += Waymark.type_to_text(feature_type, type_data);

					break;

				//Title
				case "title":
					content += feature.properties.title
						? feature.properties.title
						: "<em>Untitled " +
							type_data[feature_type + "_title"] +
							" " +
							Waymark.title_case(feature_type) +
							"</em>";

					break;

				//Description
				case "description":
					var description = feature.properties.description;

					//We have a description
					if (description) {
						//HTML
						if (description.indexOf("<") === 0) {
							content += description;
							//Plain text
						} else {
							content += `<p>${description}</p>`;
						}
					}

					break;

				//Image
				case "image_large_url":
					//We have an image
					if (!feature.properties.image_large_url) {
						break;
					}

					// Perform basic URL validation, must start with http:// or https://
					if (!feature.properties.image_large_url.match(/^(https?:\/\/)/)) {
						break;
					}

					//We have an image
					if (feature.properties.image_large_url) {
						//Use Medium if we have it
						var thumb_url = feature.properties.image_large_url;
						if (feature.properties.image_medium_url) {
							thumb_url = feature.properties.image_medium_url;
						}

						content += `<a href="${feature.properties.image_large_url}" target="_blank" style="background-image:url(${thumb_url})"></a>`;
					}

					break;
			}

			content += "</div>";
		}

		content += "</div>";

		return content;
	};

	this.load_done = function () {
		Waymark = this;

		// Calculate execution time
		let end_time = new Date().getTime();
		let execution_time = (end_time - Waymark.start_time) / 1000;

		Waymark.debug(
			"Waymark Loaded in " + execution_time.toFixed(3) + " seconds",
		);
		// Waymark.debug(this);

		// Check for callback waymark_loaded_callback
		if (typeof waymark_loaded_callback === "function") {
			Waymark.debug(
				"Global Callback detected! waymark_loaded_callback(waymark_instance)",
			);

			// Call it
			waymark_loaded_callback(Waymark);
		} else {
			Waymark.debug("No Global Callback detected.");
		}
	};

	/*
	==================================
	======== ABSTRACT METHODS ========
	==================================
*/

	this.pre_map_setup = function () {};
	this.init_done = function () {};
	this.info_window = function (layer_type, feature, layer) {};
	this.build_content = function (layer_type, feature) {};
}
