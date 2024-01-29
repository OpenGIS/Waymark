/*
	==================================
	============= VIEWER =============
	==================================
*/

function Waymark_Map_Viewer() {
	this.gallery_images = [];

	this.pre_map_setup = function () {
		Waymark = this;

		Waymark.mode = "view";
	};

	this.init_done = function () {
		Waymark = this;

		//Show!
		Waymark.jq_map_container.show();
		Waymark.map.invalidateSize();
		Waymark.config.map_width = Waymark.jq_map_container.width();

		//Gallery
		Waymark.setup_gallery();

		//Elevation
		Waymark.setup_elevation();

		//Clustering
		Waymark.setupCluster();

		jQuery(Waymark.map.getContainer()).addClass("waymark-is-viewer");

		//Hidden? (i.e. display:none)
		Waymark.setup_hidden_checker();
	};

	this.setupCluster = () => {
		if (!Waymark.config.show_cluster) {
			return;
		}

		// Add Cluster Class
		Waymark.jq_map_container.addClass("waymark-has-cluster");

		//Fix - https://github.com/Leaflet/Leaflet.markercluster/issues/611#issuecomment-277670244
		Waymark.map._layersMaxZoom = 18;

		// Create Marker Cluster
		Waymark.marker_cluster = L.markerClusterGroup({
			animate: false,
			showCoverageOnHover: false,
			removeOutsideVisibleBounds: true,
			iconCreateFunction: this.clusterIconFunction,
			spiderfyOnMaxZoom: false,
			disableClusteringAtZoom: Waymark.get_property(
				waymark_settings,
				"misc",
				"cluster_options",
				"cluster_threshold",
			),
			maxClusterRadius: Waymark.get_property(
				waymark_settings,
				"misc",
				"cluster_options",
				"cluster_radius",
			),
		});

		// Make Marker Cluster the parent group for all markers
		Waymark.marker_parent_group = Waymark.marker_cluster;

		// Add Cluster to Map
		Waymark.map.addLayer(Waymark.marker_cluster);
	};

	this.clusterIconFunction = (cluster) => {
		const clusterTypes = new Map([]);

		//Each Marker
		cluster.getAllChildMarkers().forEach((marker) => {
			//Get data
			const typeKey = marker.feature.properties.type;
			const typeData = Waymark.get_type("marker", typeKey);
			typeData.iconData = Waymark.build_icon_data(typeData);

			//Keep Count
			if (!clusterTypes.has(typeKey)) {
				typeData.typeCount = 1;
			} else {
				typeData.typeCount++;
			}

			clusterTypes.set(typeKey, typeData);
		});

		//Build HTML
		let clusterHTML = ``;

		let index = 0;
		let maxWidth = 0;
		let maxHeight = 0;

		// Iterate over each cluster type, ordered by count
		[...clusterTypes.entries()]
			.sort((a, b) => b[1].typeCount - a[1].typeCount)
			.forEach(([typeKey, typeData]) => {
				const count = typeData.typeCount;
				const data = typeData.iconData;
				const indexReversed = clusterTypes.size - index;

				maxWidth = data.iconSize[0] > maxWidth ? data.iconSize[0] : maxWidth;
				maxHeight = data.iconSize[0] > maxHeight ? data.iconSize[0] : maxHeight;

				//Append count?
				if (count > 1) {
					data.html += `<div class="waymark-cluster-count">${count}</div>`;
				}

				const left = index * 8;

				clusterHTML += `
				<div 
					class="${data.className}" 
					style="
						left:${left}px;
						z-index:${indexReversed};
						height:${data.iconSize[1]}px;
						width:${data.iconSize[0]}px;
						height:${data.iconSize[1]}px;
					"
				>
					${data.html}					
				</div>
			`;

				index++;
			});

		return L.divIcon({
			className: "waymark-cluster",
			html: clusterHTML,
			iconSize: [maxWidth, maxHeight],
		});
	};

	//Initally hidden?
	this.setup_hidden_checker = function () {
		let hidden_checker = setInterval(function () {
			is_hidden = Waymark.jq_map_container.is(":hidden");

			if (!is_hidden) {
				Waymark.reset_map_view();

				clearInterval(hidden_checker);
			}
		}, 100);
	};

	this.create_buttons = function () {};

	//Add GeoJSON to map
	this.load_json = function (json, reset_view = true) {
		Waymark = this;

		//Must be a vaid object with features
		if (typeof json === "object" && typeof json.features !== "undefined") {
			//Add data
			Waymark.map_data.addData(json);

			//Reset view?
			if (reset_view) {
				Waymark.reset_map_view();
			}
		}
	};

	this.reset_map_view = function () {
		Waymark = this;

		//No view specified
		if (
			Waymark.config.map_init_latlng === undefined &&
			Waymark.config.map_init_zoom === undefined
		) {
			//Use data layer bounds (if we have)
			var bounds = Waymark.map_data.getBounds();
			if (typeof bounds === "object" && bounds.isValid()) {
				Waymark.map.invalidateSize();

				Waymark.map.fitBounds(bounds);
			}
			//Both zoom AND centre specified
		} else if (
			Waymark.config.map_init_latlng !== undefined &&
			Waymark.config.map_init_zoom !== undefined
		) {
			//Use them
			Waymark.map.setView(
				Waymark.config.map_init_latlng,
				Waymark.config.map_init_zoom,
			);
			//Either zoom or centre specified
		} else {
			//Centre specified
			if (Waymark.config.map_init_latlng !== undefined) {
				Waymark.map.setView(Waymark.config.map_init_latlng);

				//Use data layer for zoom
				Waymark.map.setZoom(
					Waymark.map.getBoundsZoom(Waymark.map_data.getBounds()),
				);
			}

			//Zoom specified
			if (Waymark.config.map_init_zoom !== undefined) {
				Waymark.map.setZoom(Waymark.config.map_init_zoom);

				//Use data layer for centre
				Waymark.map.setView(Waymark.map_data.getBounds().getCenter());
			}
		}
	};

	this.build_content = function (layer_type, feature) {
		Waymark = this;

		var content = jQuery("<div />");
		var list = jQuery("<ul />").addClass(
			"waymark-info waymark-" + layer_type + "-info",
		);

		//Expected Waymark properties
		for (key in Waymark.config[layer_type + "_data_defaults"]) {
			var ele = null;

			switch (key) {
				case "title":
					var title = feature.properties.title;

					//We have a title
					if (title) {
						ele = jQuery("<strong />").html(feature.properties.title);
						//No description
					} else {
						ele = jQuery("<strong />").html("&nbsp;");
						list.addClass("waymark-no-title");
					}

					break;
				case "type":
					if (Waymark.config.map_options.show_type_labels != "1") {
						break;
					}

					//Get type
					var type = Waymark.get_type(layer_type, feature.properties.type);
					if (type) {
						ele = Waymark.type_to_text(layer_type, type, "small");
					}

					break;

				case "description":
					var description = feature.properties.description;

					//We have a description
					if (description) {
						//HTML
						if (description.indexOf("<") === 0) {
							ele = description;
							//Plain text
						} else {
							ele = jQuery("<p />").html(description);
						}
						//No description
					} else {
						list.addClass("waymark-no-description");
					}

					break;
				case "image_large_url":
					//We have an image
					if (typeof feature.properties.image_large_url !== "undefined") {
						//Use Medium if we have it
						var thumb_url = feature.properties.image_large_url;
						if (typeof feature.properties.image_medium_url !== "undefined") {
							var thumb_url = feature.properties.image_medium_url;
						}

						ele = jQuery("<a />").attr({
							href: feature.properties.image_large_url,
							target: "_blank",
							style: "background-image:url(" + thumb_url + ")",
						});
						//We don't have an image
					} else {
						list.addClass("waymark-no-image");
					}

					break;
			}

			if (ele) {
				list.append(
					jQuery("<li />")
						.addClass(
							"waymark-info-" + key + " waymark-" + layer_type + "-info-" + key,
						)
						.append(ele),
				);
			}
		}

		if (list.children().length) {
			content.append(list);
		}

		return content;
	};

	this.info_window = function (layer_type, feature, layer) {
		Waymark = this;

		//Show elevation for Line?
		if (Waymark.config.show_elevation && layer_type == "line") {
			//Has elevation data, but nothing displayed yet
			if (
				Waymark.config.elevation_initial &&
				Waymark.line_has_elevation_data(feature) &&
				!Waymark.elevation_container.is(":visible")
			) {
				//Show it!
				Waymark.elevation_container.show();
				Waymark.elevation_control.loadData(feature);
			}

			layer.on("click", function (e) {
				var feature = e.target.feature;

				//Clear chart
				Waymark.elevation_control.clear();

				//Clear Map layer
				if (typeof Waymark.elevation_control.layer !== "undefined") {
					Waymark.elevation_control.layer.removeFrom(Waymark.map);
				}

				//Feature has elevation data
				Waymark.elevation_container.hide();
				if (Waymark.line_has_elevation_data(feature)) {
					Waymark.elevation_container.show();

					Waymark.elevation_control.loadData(feature);
				}
			});
		}

		//If only Title to display
		if (
			typeof feature.properties.description == "undefined" &&
			typeof feature.properties.image_large_url == "undefined"
		) {
			//Don't show info window
			return;
		}

		//Build content
		var content = Waymark.build_content(layer_type, feature, layer);
		var title = feature.properties.title;

		//Bind content to info window
		layer.bindPopup(content.get(0)).openPopup();
	};

	this.line_has_elevation_data = function (feature) {
		if (feature.geometry.type == "MultiLineString") {
			//Each line
			for (var i in feature.geometry.coordinates) {
				//Each point
				for (var j in feature.geometry.coordinates[i]) {
					//If has elevation data
					if (feature.geometry.coordinates[i][j].length == 3) {
						return true;
					}
				}
			}
		} else {
			//Each point
			for (var j in feature.geometry.coordinates) {
				//If has elevation data
				if (feature.geometry.coordinates[j].length == 3) {
					return true;
				}
			}
		}

		return false;
	};

	this.setup_elevation = function () {
		Waymark = this;

		if (!Waymark.config.show_elevation) {
			return;
		}

		//Localize
		Waymark_L.registerLocale("waymark", {
			"Total Length: ": waymark_js.lang.label_total_length,
			"Max Elevation: ": waymark_js.lang.label_max_elevation,
			"Min Elevation: ": waymark_js.lang.label_min_elevation,
			"Total Ascent: ": waymark_js.lang.label_ascent,
			"Total Descent: ": waymark_js.lang.label_descent,
		});
		Waymark_L.setLocale("waymark");

		//Create config
		var config = {
			theme: "magenta-theme",
			detached: true,
			followMarker: false,
			width: Waymark.config.map_width,
		};

		//Container
		if (typeof Waymark.config.elevation_div_id !== "undefined") {
			config.elevationDiv = "#" + Waymark.config.elevation_div_id;
			Waymark.elevation_container = jQuery(config.elevationDiv);
			Waymark.elevation_container.hide();

			//Close
			var elevation_close = jQuery("<span />")
				.addClass("waymark-elevation-close")
				.text("x")
				.on("click", { W: Waymark }, function (e) {
					var W = e.data.W;

					W.elevation_control.clear();
					if (typeof W.elevation_control.layer !== "undefined") {
						W.elevation_control.layer.removeFrom(W.map);
					}

					W.elevation_container.hide();
				});
			Waymark.elevation_container.append(elevation_close);
		}

		//Units
		if (
			typeof Waymark.config.elevation_units !== "undefined" &&
			Waymark.config.elevation_units == "imperial"
		) {
			config.imperial = true;
		}

		//Create elevation control
		Waymark.elevation_control = Waymark_L.control
			.elevation(config)
			.addTo(Waymark.map);

		//Close elevation?
		Waymark.map.on("overlayremove", function (e) {
			if (typeof Waymark.elevation_control.layer !== "undefined") {
				var lat_lngs = null;

				//Active Elevation Line
				Waymark.elevation_control.layer.eachLayer(function (layer) {
					lat_lngs = JSON.stringify(layer.getLatLngs());
				});

				//Valid Line to compare
				if (lat_lngs) {
					//Each layer being closed
					e.layer.eachLayer(function (layer) {
						if (typeof layer.getLatLngs !== "undefined") {
							//Compare against active elevation
							if (lat_lngs == JSON.stringify(layer.getLatLngs())) {
								Waymark.elevation_control.clear();
								Waymark.elevation_control.layer.removeFrom(Waymark.map);
								Waymark.elevation_container.hide();
							}
						}
					});
				}
			}
		});
	};

	this.setup_gallery = function () {
		Waymark = this;

		if (!Waymark.config.show_gallery) {
			return;
		}

		//Create gallery
		Waymark.gallery = jQuery("<div />")
			.attr("id", Waymark.config.gallery_div_id)
			.css("width", Waymark.config.map_width)
			.addClass("waymark-gallery-container")
			.html("");

		//Needed to open marker info
		Waymark_L.DomEvent.disableClickPropagation(Waymark.gallery.get(0));

		jQuery(Waymark.map.getContainer()).append(Waymark.gallery);

		Waymark.size_gallery();

		//Redraw on pan zoom
		var W = Waymark;
		Waymark.map.on(
			"moveend",
			function () {
				W.render_gallery();
			},
			{ W: W },
		);

		//Redraw when layers are added/removed
		Waymark.map.on(
			"layerremove layeradd",
			function () {
				W.render_gallery();
			},
			{ W: W },
		);
	};

	this.size_gallery = function () {
		Waymark = this;

		//Only size gallery if there is one
		if (typeof Waymark.gallery === "undefined" || !Waymark.gallery.length) {
			return;
		}

		gallery_padding = Waymark.gallery.css("paddingRight");
		gallery_padding = gallery_padding.replace("px", "");
		gallery_padding = parseInt(gallery_padding);
		var gallery_width = Waymark.config.map_width - 2 * gallery_padding;

		Waymark.gallery.css("width", gallery_width);
	};

	this.add_to_gallery = function (layer) {
		Waymark = this;

		//Ensure we have an image
		if (
			typeof layer.feature.properties.image_thumbnail_url === "undefined" ||
			typeof layer.feature.properties.image_large_url === "undefined"
		) {
			return false;
		}

		//Clone data
		var image = Object.assign({}, layer.feature.properties);

		//Set data
		image.latlng = layer.feature.geometry.coordinates;
		image.marker = layer;

		//Add to gallery
		Waymark.gallery_images.push(image);
	};

	this.render_gallery = function () {
		Waymark = this;

		var in_bounds_count = 0;

		//Only create gallery if there is a container
		if (typeof Waymark.gallery === "undefined" || !Waymark.gallery.length) {
			return;
		}

		//Empty first
		Waymark.gallery.html("");
		Waymark.gallery.hide();
		jQuery(Waymark.map.getContainer()).removeClass("waymark-has-gallery");

		//If we have images
		if (Waymark.gallery_images.length) {
			for (i = 0; i < Waymark.gallery_images.length; i++) {
				var image = Waymark.gallery_images[i];

				// Check the Map by default
				let checkLayer = Waymark.map;

				// If we are clustering
				if (
					Waymark.config.show_cluster &&
					typeof Waymark.marker_cluster === "object"
				) {
					// Check that
					checkLayer = Waymark.marker_cluster;
				}

				//If visible AND active on map
				if (
					Waymark.map.getBounds().contains(image.marker.getLatLng()) &&
					checkLayer.hasLayer(image.marker)
				) {
					in_bounds_count++;

					var div = jQuery("<div />")
						.addClass("waymark-image")

						//When a gallery image is clicked
						.on("click", { marker: image.marker }, function (e) {
							var marker = e.data.marker;

							//Zoom in
							Waymark.map.setView(marker.getLatLng(), 16);

							//Open popup at marker
							marker.openPopup();
						});

					var img = jQuery("<img />").attr({
						src: image.image_thumbnail_url,
					});
					div.append(img);

					Waymark.gallery.append(div);
				}
			}

			//If not empty
			if (in_bounds_count) {
				Waymark.gallery.show();
				jQuery(Waymark.map.getContainer()).addClass("waymark-has-gallery");
			}
		}
	};
}
