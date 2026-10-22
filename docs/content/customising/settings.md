---
title: Settings
description: Configure Basemaps, Markers, Lines, Shapes, Meta, Submissions and other Waymark Map options.
---

::video-figure{src="/assets/video/settings.mp4" poster="/assets/video/posters/settings.jpg" label="Waymark - Settings"}
::

<a id="tiles"></a>

## Basemaps

### Basemaps

Waymark uses the excellent [OpenStreetMap](https://www.openstreetmap.org/fixthemap) as its default Basemap and supports many [other providers](https://leaflet-extras.github.io/leaflet-providers/preview/). <br /> <br /> [Thunderforest](https://www.thunderforest.com/) and [Mapbox](https://www.mapbox.com/maps/) are examples of providers that offer easy access to beautiful Basemaps (including satellite imagery). They require registration, but have a free usage tier.

| Title               | Type   | Default                                                                                                  | Tip                                                                                                                                                                                                                                                                |
| ------------------- | ------ | -------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Basemap Name        | `text` | Open Street Map                                                                                          | The Layer Name will appear in a dropdown list shown by the Map when multiple Basemaps have been entered. You can change the default basemap in the shortcode: \[Waymark map_id="1234" basemap="Basemap Name"\]                                                     |
| Basemap URL         | `text` | https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png                                                       | Many mapping services support the Slippy Map format. Waymark requires URLs that contain {z} (zoom level) and {x}/{y} (tile coordinates). For example the OpenCycleMap URL is https<wbr>://tile.thunderforest.com/cycle/{z}/{x}/{y}@2x.png?apikey=\[your_api_key\]. |
| Basemap Attribution | `text` | © &lt;a href="https<wbr>://www<wbr>.openstreetmap.org/copyright"&gt;OpenStreetMap&lt;/a&gt; contributors | Mapping services often have the requirement that attribution is displayed by the map. Text and HTML links are supported.                                                                                                                                           |
| Basemap Max Zoom    | `text` | 18                                                                                                       | Set a maximum zoom level for this Basemap, the default is 18.                                                                                                                                                                                                      |

<a id="markers"></a>

## Markers

### Marker Types

Customise how Markers are displayed on the Map. Set these styles once, then select the appropriate Type when you add to the Map.

| Title                 | Type      | Default    | Tip                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |
| --------------------- | --------- | ---------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Marker Label          | `text`    | Photo      | What kind of Marker is this? E.g. "Photo", "Grocery Store", "Warning!". Once saved, Marker labels can not be edited. The Marker Label is displayed in the Tooltip (when hovering over the Marker) and in the Info Window (once the Marker is clicked). Hide in Settings &gt; Map &gt; Misc. &gt; Type Labels.                                                                                                                                                                                                                                                                                                                                     |
| Marker Shape          | `select`  | marker     | Which shape of Marker to use. Circles and Squares are centered at the specified location, Markers point down to that location.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| Marker Size           | `select`  | large      | Which size of Marker to use.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      |
| Marker Background     | `text`    | white      | The Marker background colour. Click "Select Colour" to select.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| Marker Show Initially | `boolean` | 1          | When using the Overlay Filter you can choose to show/hide certain Types when the Map initially loads.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| Marker Submissions?   | `boolean` | 1          | Make this Type available in front-end Submissions?                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                |
| IconType              | `select`  | icon       | Font Icons are available from Font Awesome and Ionic Icons. Simple Text or Emojis are supported, as well as custom HTML. So you can pretty much use anything you like!                                                                                                                                                                                                                                                                                                                                                                                                                                                                            |
| IconName              | `text`    | ion-camera | The desired icon name from Ionicons or Font Awesome, e.g. "ion-camera", or "fa-camera". Click the links to see the full list of icons available.\|Text to display inside the Marker, in the chosen colour. Space is very limited! Pro Tip: adjust text size using CSS; for all Markers: .waymark-icon-text{font-size: 18px}, or by Type: .waymark-marker-photo .waymark-icon-text{...}. Use your browser's inspector to dig for Type class names.\|The HTML entered will be added inside each Marker. Pro Tip! HTML Entities supported (e.g. &amp;cross; as well as Unicode and Emojis!), or provide HTML to integrate with other Icon providers. |
| IconColour            | `text`    | #475260    | The colour of the icon. Click "Select Colour" to select.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          |

<a id="lines"></a>

## Lines

### Line Types

Customise how Lines are displayed on the Map. Set these styles once, then select the appropriate Type when you add to the Map.

| Title               | Type      | Default | Tip                                                                                                                                                                                                                  |
| ------------------- | --------- | ------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Line Label          | `text`    | Red     | What kind of Line is this? E.g. "Easy", "Walking Only", "Dark Red". The Line Label is displayed in the Tooltip (when hovering over the Line) and in the Line Info Window. Once saved, Line labels can not be edited. |
| Line Colour         | `text`    | #d84848 | The colour of the Line. Click "Select Colour" to select.                                                                                                                                                             |
| Line Weight         | `text`    | 3       | The width of the Line, in pixels.                                                                                                                                                                                    |
| Line Opacity        | `text`    | 0.7     | The opacity of the Line, between 0.0 and 1.0 (e.g. "0.5").                                                                                                                                                           |
| Line Show Initially | `boolean` | 1       | When using the Overlay Filter you can choose to show/hide certain Types when the Map initially loads.                                                                                                                |
| Line Submissions?   | `boolean` | 1       | Make this Type available in front-end Submissions?                                                                                                                                                                   |

<a id="shapes"></a>

## Shapes

<a id="shapes-shape_types"></a>

### Shape Types

Customise how Shapes are displayed on the Map. Set these styles once, then select the appropriate Type when you add to the Map.

| Title                | Type      | Default | Tip                                                                                                                                                                                                                    |
| -------------------- | --------- | ------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Shape Label          | `text`    | Red     | What kind of Shape is this? E.g. "Park", "Danger!", "Light Blue". The Shape Label is displayed in the Tooltip (when hovering over the Shape) and in the Shape Info Window. Once saved, Shape labels can not be edited. |
| Shape Colour         | `text`    | #d84848 | The colour of the Shape. Click "Select Colour" to select.                                                                                                                                                              |
| Shape Fill Opacity   | `text`    | 0.5     | The opacity of the inside of the shape, between 0.0 and 1.0 (e.g. "0.5").                                                                                                                                              |
| Shape Show Initially | `boolean` | 1       | When using the Overlay Filter you can choose to show/hide certain Types when the Map initially loads.                                                                                                                  |
| Shape Submissions?   | `boolean` | 1       | Make this Type available in front-end Submissions?                                                                                                                                                                     |

<a id="meta"></a>

## Meta

### Meta

Create additional input fields that appear underneath the Map Editor. Any Meta that has been input is displayed on the Map Details page, and can also be displayed by the Shortcode.

| Title                | Type      | Default                                                                   | Tip                                                                                                                                                                                 |
| -------------------- | --------- | ------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Meta Title           | `text`    | Description                                                               | The title appears next to the input field.                                                                                                                                          |
| Meta Default         | `text`    |                                                                           | The default value for the input field. For Select and Multi-Select enter the option/comma-separated options to be selected by default.                                              |
| Meta Tip             | `text`    | The Description you enter here will be displayed on the Map Details page. | A tip provides additional information about an input field... just like this!                                                                                                       |
| Meta Type            | `select`  | textarea_rich                                                             | The type of input field to use.                                                                                                                                                     |
| Meta Options         | `text`    |                                                                           | A comma-separated list of options for the input.                                                                                                                                    |
| Meta Group           | `select`  |                                                                           | Which group this Meta belongs to (if any). Meta in the same group will be displayed together when editing and viewing Maps. Meta not in a group will be displayed above any groups. |
| Meta In Shortcode?   | `boolean` | 0                                                                         | Whether this content should be displayed when embedding a Map using the Shortcode.                                                                                                  |
| Meta In Submissions? | `boolean` | 0                                                                         | Make this Meta available in front-end Submissions?                                                                                                                                  |

<a id="meta-groups"></a>

### Groups

Create groups to organise your Map Meta. Meta in the same group will be displayed together when editing and viewing Maps.

| Title       | Type   | Default | Tip                                             |
| ----------- | ------ | ------- | ----------------------------------------------- |
| Group Title | `text` |         | The title appears above the Meta in that group. |

<a id="properties"></a>

## Properties

<a id="properties-props"></a>

### Properties

Read **[GeoJSON](https://geojson.org/)** feature properties when importing. <br /> <br /> If Waymark finds data for the property keys below it will stored when it is imported. These can be automatically appended to the Overlay Description, or accessed programatically the `layer.feature.properties` Object.

| Title          | Type   | Default | Tip                                                                                                                         |
| -------------- | ------ | ------- | --------------------------------------------------------------------------------------------------------------------------- |
| Property Key   | `text` |         | This is the key associated with the data you are trying to access, i.e. "properties": {"property_key": "Some content here"} |
| Property Title | `text` |         | The value for this property will be added to the Overlay Description under this title.                                      |

<a id="properties-options"></a>

### Options

Append listed Properties to the Overlay Description.

| Title  | Type      | Default | Tip                                                   |
| ------ | --------- | ------- | ----------------------------------------------------- |
| Append | `boolean` | 0       | Append the property value to the Overlay Description. |

<a id="submission"></a>

## Submissions

<a id="submission-global"></a>

### Front-End Submissions

Use the `[Waymark content="submission"]` Shortcode to allow Map submissions from the front-end of your site.

| Title             | Type      | Default | Tip                                                                                                                                                        |
| ----------------- | --------- | ------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Allow Submissions | `boolean` | 0       | Submissions will be available only to site administrators by default, but can also be allowed for registered users or even to guests without registration. |

<a id="submission-from_users"></a>

### User Submissions

Allow registered users to create Maps from the front-end.

| Title              | Type           | Default | Tip                                                                                                                                                                                                                                                             |
| ------------------ | -------------- | ------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Allow From         | `select_multi` |         | Users with the selected roles will be able to make Submissions through the front-end                                                                                                                                                                            |
| Editor Features    | `select_multi` |         | What features to offer in the Editor. Important! Uploaded images are added to the Media Library, reading from file does not keep a copy of the file on the server. Whether an individual Meta input is displayed can be set in the Settings &gt; Map &gt; Meta. |
| Default Status     | `select`       |         | This is the initial status of the submitted Map. Note! Publish means that the Map (including any images added) will be \*immediately\* visible on your site.                                                                                                    |
| Default Collection | `select`       |         | If specified, user submissions will be automatically added to this Collection.                                                                                                                                                                                  |

<a id="submission-from_public"></a>

### Public Submissions

This will allow Submissions from **any visitor**, without registration.

| Title              | Type           | Default            | Tip                                                                                                                                                                                                                                                                                         |
| ------------------ | -------------- | ------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Public Submissions | `boolean`      | 0                  | Allow \*anyone\* to submit Maps to your site, without registration.                                                                                                                                                                                                                         |
| Editor Features    | `select_multi` |                    | What features to offer in the Editor. Important! Uploaded images are added to the Media Library (see Upload Location below), reading from file does not keep a copy of the file on the server. Whether an individual Meta input is displayed can be set in the Settings &gt; Map &gt; Meta. |
| Upload Location    | `select`       | waymark_submission | Images upload by non-registered users can be stored seperately from other Media Library uploads, to aid with moderation. All uploaded images will be stored in a single directory (/waymark_submission) found in the upload root.                                                           |
| Default Status     | `select`       |                    | This is the initial status of the submitted Map. Note! Publish means that the Map (including any images added) will be \*immediately\* visible on your site.                                                                                                                                |
| Default Collection | `select`       |                    | If specified, user submissions will be automatically added to this Collection.                                                                                                                                                                                                              |

<a id="misc"></a>

## Misc.

### Map Options

Use these options to change how Maps are displayed.

| Title          | Type      | Default            | Tip                                                                                                                                                                                                                                              |
| -------------- | --------- | ------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Default Centre | `text`    | 51.38436,-68.74923 | Waymark centres the Map automatically when displaying data. These coordinates (Latitude,Longitude) will be used when there is no data available.                                                                                                 |
| Map Height     | `text`    | 450                | Specify the desired height of the Map (in pixels). Pro Tip! This will affect all Maps, but you can change the height (and width) of an individual Map through the Shortcode: \[Waymark map_id="1234" map_height="640"\]                          |
| Default Zoom   | `text`    | 9                  | Waymark zooms the Map automatically when displaying data. This zoom level (0-18) will be used when there is no data available.                                                                                                                   |
| Image Gallery  | `boolean` | 1                  | Whether to display an image gallery for Markers that have images associated with them. Pro Tip! This will affect all Maps, but you can choose to show/hide the gallery of an individual Map through the shortcode: \[Waymark show_gallery="1"\]  |
| Overlay Filter | `boolean` | 1                  | Allow the user to filter which Markers, Lines and Shapes are currently visible on the Map. Pro Tip! This will affect all Maps, but you can choose to show/hide the filter for individual Maps through the shortcode: \[Waymark show_filter="1"\] |
| Public Export  | `boolean` | 0                  | Offer visitors the ability to Download all Collection/Map Overlays in the Shortcode Header and on the Map Details page. GeoJSON, GPX and KML formats supported.                                                                                  |
| Show Scale     | `boolean` | 0                  | Show a distance scale (km and miles) on the Map.                                                                                                                                                                                                 |

<a id="misc-interaction_options"></a>

### Sleep Options

Waymark Maps will zoom when the user scrolls. This can cause some unexpected/annoying behaviour when scrolling a page. <br /> <br /> **Sleeping** the Map initially and **Waking** upon user interaction (i.e. hovering/clicking/tapping) may create a better experience.

| Title                | Type      | Default                | Tip                                                                                                                                                                                                                                              |
| -------------------- | --------- | ---------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Hover Wake Time      | `text`    | 2                      | How many seconds before scroll zoom is enabled. 0 seconds will mean no delay (disabling this feature). A large number of seconds like 3600 (an hour) will esentially \*disable hover to wake\*, meaning the user will need to \*click\* to wake. |
| Display Message      | `boolean` | 0                      | This message will be displayed by the Map while scroll zoom is disabled.                                                                                                                                                                         |
| Display Message Text | `text`    | Click or Hover to Wake |                                                                                                                                                                                                                                                  |

<a id="misc-cluster_options"></a>

### Cluster Options

With Clustering enabled, Markers will be grouped together when they are close together. This can help to reduce clutter on the Map.

| Title             | Type      | Default | Tip                                                                                                                                                                                            |
| ----------------- | --------- | ------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Marker Clustering | `boolean` | 0       | Whether to cluster (stack) Markers that are close together. Pro Tip! This will affect all Maps, but you can also enable/disable clustering through the Shortcode: \[Waymark show_cluster="0"\] |
| Cluster Threshold | `text`    | 14      | Markers will not be clustered above this zoom level.                                                                                                                                           |
| Cluster Radius    | `text`    | 80      | The maximum radius that a cluster will cover from the central marker (in pixels). Decreasing will make more, smaller clusters. Default 80.                                                     |

<a id="misc-shortcode_options"></a>

### Shortcode Options

How Maps are embedded into your content using the shortcode.

| Title            | Type     | Default | Tip                                                                                                                                                                                                                                                            |
| ---------------- | -------- | ------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Shortcode Header | `select` | 1       | The shortcode header displays the title and link to the Map or Collection. Pro Tip! This will affect all shortcodes, but you can override the setting through the shortcode: \[Waymark map_id="1234" shortcode_header="1"\] (the value must be either 0 or 1). |
| Header for Admin | `select` | 0       | Use this Setting to always show the Shortcode Header when signed in as admin, useful for quickly navigating to embeded Maps.                                                                                                                                   |

<a id="misc-elevation_options"></a>

### Elevation Options

Lines with elevation data.

| Title             | Type      | Default | Tip                                                                                                                                                                                                                                                               |
| ----------------- | --------- | ------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Elevation Profile | `select`  | 2       | Display an interactive elevation profile graph below the Map for Lines that have elevation data. Pro Tip! You can choose to show/hide the elevation graph of an individual Map through the shortcode: \[Waymark map_id="1234" show_elevation="1"\]                |
| Elevation Units   | `select`  | metric  | Display elevation data in metric (m/km) or imperial (ft/mi) units.                                                                                                                                                                                                |
| Elevation Colour  | `text`    | #b42714 | The colour of the elevation graph and associated Line.                                                                                                                                                                                                            |
| Show Initially?   | `boolean` | 1       | Whether to show the elevation profile when the Map loads. If set to No, the user must click on a Line in order to display the elevation data. If there are multiple Lines with elevation data, the one added to the editor first will be the one shown initially. |

<a id="misc-collection_options"></a>

### Collection Options

How Collections are displayed.

| Title            | Type     | Default | Tip                                                                                                                                                                                                                                                                                  |
| ---------------- | -------- | ------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Shortcode Method | `select` | fetch   | Whether to load multiple Maps in the Background (uses AJAX to improve page load) when embedding with the Shortcode, or to Embed them within the page. Embedding may be a bad idea for LARGE COLLECTIONS, but can resolve some issues where Collections are not displaying correctly. |

<a id="misc-editor_options"></a>

### Editor Options

Customising the Map Editor.

| Title           | Type      | Default         | Tip                                                                                                                                                                                                      |
| --------------- | --------- | --------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Confirm Delete? | `boolean` | 1               | Whether to show a confirmation dialog before deleting Markers/Lines/Shapes from the Map. Pro Tip! Even if you accidentally delete something, changes are not saved until the "Update" button is clicked. |
| Default Basemap | `select`  | Open Street Map | Which Basemap to use as the editor default.                                                                                                                                                              |

<a id="misc-permalinks"></a>

### Permalinks

Customise your Map and Collection URLs.

| Title           | Type   | Default    | Tip                                                                                                                                                                                |
| --------------- | ------ | ---------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Map Slug        | `text` | map        | The URL slug that will be used for links to your Maps, i.e. example.com/\[map-slug\]/example-map/. Only alpha-numeric characters and hyphens (-) are allowed.                      |
| Collection Slug | `text` | collection | The URL slug that will be used for links to your Collections, i.e. example.com/\[collection-slug\]/example-collection/. Only alpha-numeric characters and hyphens (-) are allowed. |

<a id="misc-post"></a>

### Post Type

Enable Custom Post Type features.

| Title    | Type           | Default | Tip                                                                       |
| -------- | -------------- | ------- | ------------------------------------------------------------------------- |
| Supports | `select_multi` | title   | Enable or disable various WordPress features of the Map Custom Post Type. |

<a id="misc-advanced"></a>

### Debug

| Title      | Type      | Default | Tip                                                                                                                                                                                                                                     |
| ---------- | --------- | ------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Debug Mode | `boolean` | 0       | With debug mode enabled, the plugin will output Map and Settings data in Admin Dashboard. This may come in handy if you need to report a bug. Pro Tip! Check the browser console for Waymark output when signed in as an administrator. |
