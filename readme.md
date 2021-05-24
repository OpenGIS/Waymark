# Waymark #
**Contributors:** [morehawes](https://profiles.wordpress.org/morehawes)  
**Tags:** mapping, leaflet, openstreetmap, topographical, elevation  
**Requires at least:** 4.6  
**Tested up to:** 5.7  
**Requires PHP:** 5.2  
**Stable tag:** 0.9.16  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Mapping with WordPress Made Easy! Create interactive Maps with your choice of Basemaps and customisable Overlays like Markers, location aware Photos and Lines with elevation profiles.

## Description ##

Add Overlays (Markers, Lines and Shapes) to create detailed interactive Maps. Simply add them to your content using the Waymark <a href="https://www.joesway.ca/waymark/docs/shortcodes/">Shortcode</a>.

* <a href="https://www.joesway.ca/waymark/demo/">See the Demo &raquo;</a>
* <a href="https://www.joesway.ca/waymark/docs/">Read the Docs &raquo;</a>
* <a href="https://github.com/morehawes/waymark">View on GitHub &raquo;</a>

Using the <a href="https://www.joesway.ca/waymark/docs/editor/">Editor</a>, each Overlay you add to a Map can be given a title, description, image and categorised using <a href="https://www.joesway.ca/waymark/docs/types/">Types</a>. Waymark can also read Markers and Lines from file (GPX/KML/GeoJSON supported), allowing you to easily embed data stored in these formats.

Waymark was designed to be very customisable, allowing you to change icons, colours and styles, as well as the ability to add multiple <a href="https://www.joesway.ca/waymark/docs/basemaps/">Basemaps</a>.

### Gallery ###

An image gallery can be displayed at the bottom of the Map for Markers that have images associated with them. Clicking on an image will display the relevant Marker. Photos are added through the WordPress Media Library and if Waymark detects location metadata (EXIF), photos are added to the Map where they were taken. 

You can see an example of the image gallery <a href="https://www.joesway.ca/map/route-map/">here</a>.

### Line Elevations ###

Display an interactive elevation graph for Lines that have elevation data. Minimum/Maximum elevation and total length statistics are also displayed. Customise in Waymark > Settings > Misc. > Elevation Options. 

You can see an example of a Line with elevation data <a href="https://www.joesway.ca/map/route-map/">here</a>.

### Meta ###

<a href="https://www.joesway.ca/waymark/docs/meta/">Meta</a> allows you to add information about your Map. By default this is a "Description" field which displays under the Map Editor. You can customise the input fields that are displayed alongside the editor in Waymark > Settings > Meta. Any Meta added will be displayed on the <a href="https://www.joesway.ca/map/route-map/">Map Details</a> page, and can also be displayed through the <a href="https://www.joesway.ca/waymark/docs/shortcodes/">Shortcode</a>. Meta can be organised into Groups, with support for Text, Textarea, Rich Text, Select and Multi-Select input types.

### Collections ###

<a href="https://www.joesway.ca/waymark/docs/collections/">Collections</a> allow you to organise your Maps, as well as the ability to display multiple Maps at once using the <a href="https://www.joesway.ca/waymark/docs/shortcodes/">Shortcode</a>. Collections can be nested and Maps can be associated with multiple Collections, meaning you can organise your Maps however best suits you.

### Overlay Filter ###

When enabled, users can filter which Markers, Lines and Shapes are currently visible on the Map by clicking on the layer icon. You can also choose to show/hide certain Types when the Map initially loads.

### Export ###

Download the Overlays added to a Map in GeoJSON, GPX and KML formats while editing a Map. With the "Public Export" Setting enabled, site visitors can also download the Overlays currently displayed on the Map Details page. This can be used in conjunction with the Overlay Filter to select which Overlays are downloaded.

### Translations ###

Waymark is localization ready! <a href="https://translate.wordpress.org/projects/wp-plugins/waymark/">Translation contributions</a> greatly appreciated.

### Acknowledgements ###

Waymark relies on input from it's users, thank you to everyone for providing feedback :)

A big thank you to the following projects and their contributors. Without their work this plugin would not be possible:

* <a href="https://wordpress.org/">WordPress</a>
* <a href="https://www.openstreetmap.org/">OpenStreetMap</a>
* <a href="https://leafletjs.com/">Leaflet</a>
* <a href="https://jquery.com/">jQuery</a>
* <a href="https://github.com/mapbox/togeojson">mapbox/togeojson</a>
* <a href="https://ionic.io/ionicons/v2/cheatsheet.html">Ionicons</a>
* <a href="https://fontawesome.com/v4.7.0/">Font Awesome</a>
* <a href="https://github.com/Leaflet/Leaflet.Editable">Leaflet.Editable</a>
* <a href="https://github.com/CliffCloud/Leaflet.Sleep">Leaflet.Sleep</a>
* <a href="https://github.com/perliedman/leaflet-control-geocoder">leaflet-control-geocoder</a>
* <a href="https://github.com/Raruto/leaflet-elevation">leaflet-elevation</a>
* <a href="https://github.com/maphubs/tokml">tokml</a>
* <a href="https://github.com/tyrasd/togpx">togpx</a>

### Links ###
 
* <a href="https://www.joesway.ca/waymark/demo/">See the Demo &raquo;</a>
* <a href="https://www.joesway.ca/waymark/docs/">Read the Docs &raquo;</a>
* <a href="https://github.com/morehawes/waymark">View on GitHub &raquo;</a>

## Installation ##

With Waymark enabled, click on the "Maps" link in the sidebar to create and edit Maps. Once you are happy with your Map, copy the Waymark shortcode and add it to your content.

## Frequently Asked Questions ##

### Is There a Demo? ###

Yes, <a href="https://www.joesway.ca/waymark/demo/">here</a>.

### Can I Get More Help? ###

Yes, please view the <a href="https://www.joesway.ca/waymark/docs/">documentation</a>.

### Does Waymark Support Google Maps? ###

While Google Basemaps can be added to Waymark, Waymark does not use the Google Maps API (it uses <a href="https://leafletjs.com/">Leaflet</a>). There are other plugins available that are made specifically for Google Maps.

## Screenshots ##

### 1. A Map displaying an image gallery and elevation profile. ###
![A Map displaying an image gallery and elevation profile.](https://ps.w.org/waymark/assets/screenshot-1.jpg)

### 2. Editing the Map using the Waymark editor. ###
![Editing the Map using the Waymark editor.](https://ps.w.org/waymark/assets/screenshot-2.jpg)

### 3. Switch between multiple Basemaps and choose which Overlays to display. ###
![Switch between multiple Basemaps and choose which Overlays to display.](https://ps.w.org/waymark/assets/screenshot-3.jpg)

### 4. Manage your Maps in WordPress admin. ###
![Manage your Maps in WordPress admin.](https://ps.w.org/waymark/assets/screenshot-4.jpg)

### 5. Waymark is very customisable. ###
![Waymark is very customisable.](https://ps.w.org/waymark/assets/screenshot-5.jpg)


## Changelog ##

### 0.9.17-b1 ###

* Updated Acknowledgements

### 0.9.16 ###

* **Markers**
    * Markers can now be different shapes! Markers, Circles or Squares in three different sizes: Small, Medium and Large.
    * Markers can now have any colour background, selected using the colour picker or by entering a hexadecimal color (i.e. #RRGGBB)
    * Markers now support Font Awesome icons as well as Ionic Icons.
* **Types**
    * Type labels displayed by popups now match the colours of their respective Marker, Line or Shape.
* **Media Library Uploads**
    * By default Waymark does not save any files uploaded through the Editor. Using this option you can use the Media Library to store and import GPX/KML/GeoJSON files into your Map.
* **Bug fixes**
	* Improved location accuracy when relying on device GPS. Thanks to <a href="https://wordpress.org/support/users/coloursign/">coloursign</a> and <a href="https://wordpress.org/support/users/edwinfr/">edwinfr</a> for <a href="https://wordpress.org/support/topic/more-accurate-location-possible/">bringing this to my attention.</a>
	* Waymark_Helper `asset_url()` fix. Thanks to <a href="https://wordpress.org/support/topic/filter-by-markers-category/">pwrdp</a> for bringing this to my attention.
	* Updated Ion Icons v2 URLs. Thanks to <a href="https://wordpress.org/support/users/gudulemartens/">gudulemartens</a> for <a href="https://wordpress.org/support/topic/ion-icons/">letting me know about this</a>.

### 0.9.15.2 ###

* Localization fixes. Thanks to <a href="https://profiles.wordpress.org/tobifjellner/">tobifjellner</a> for the help via Slack.

### 0.9.15.1 ###

* Bug fix. Thanks to <a href="https://wordpress.org/support/topic/warning-error-by-new-update/">cameraspots1</a> for bringing this to my attention.

### 0.9.15 ###

* **Meta**
    * Meta can now be organised into groups. Meta in the same group will be displayed together when editing and viewing Maps. Groups can be managed in Settings > Meta > Groups.
    * New layout for Meta to incorporate Meta Groups on Map Details page.
* **Shortcode**
    * Meta can now be displayed using the Shortcode by clicking "Details" in the Shortcode Header. Use the Settings > Meta > "In Shortcode?" option to specify which Meta is displayed. Note: this feature requires the Shortcode Header be set to "Show" in Settings > Misc. > Shortcode Options.
    * The Export option is now available through the Shortcode, if the "Public Export" option is enabled (Settings > Misc. > Map Options).
* **Editor** 
    * Overlay descriptions now use a rich text editor.
    * Added the ability to duplicate Markers/Lines/Shapes in the Editor.
    * Editor circle bug fix.
* Gallery JavaScript bug fix.
* Waymark is now localization ready! <a href="https://translate.wordpress.org/projects/wp-plugins/waymark/">Translation contributions</a> greatly appreciated :)
* Waymark is now <a href="https://github.com/morehawes/waymark">on GitHub</a>.

### 0.9.14 ###

* **Line Elevations** - Display an interactive elevation graph for Lines that have elevation data. Minimum/Maximum elevation and total length statistics are also displayed. Customise in Settings > Misc. > Elevation Options. Thanks to <a href="https://github.com/Raruto/leaflet-elevation">leaflet-elevation</a>!
* Cyrillic alphabet fix.
* GPX parsing fix.
* Updated user capabilities for plugin features.
* Other bug fixes.

A big thank you to everyone who provided input for this release. Extra special thanks goes to <a href="https://wordpress.org/support/users/coloursign/">coloursign</a>, <a href="https://wordpress.org/support/users/meggsico/">meggsico</a>, <a href="https://wordpress.org/support/users/davinian/">davinian</a>, <a href="https://wordpress.org/support/users/snake-plissken/">snake-plissken</a>, <a href="https://wordpress.org/support/users/sunlight1976/">sunlight1976</a> and <a href="https://wordpress.org/support/users/bumbar/">bumbar</a> for their help with testing.

### 0.9.13 ###

* **Overlay Filter** - This allows your visitors to filter which Markers, Lines and Shapes are currently visible on the Map by clicking on the layer icon. You can choose to show/hide certain Types when the Map initially loads. Enable using the global setting (Settings > Misc. > Map Options), or using the Shortcode.
* **Export** - There is now an Export function when editing a Map. With "Public Export" enabled (Settings > Misc. > Map Options) site visitors can download the Overlays (Markers, Lines and Shapes) currently displayed on the Map Details page. Can be used in conjunction with the Overlay Filter to select which Overlays to download. GeoJSON, GPX and KML formats  supported.
* **Meta** - Added Rich Text, Select and Multi-Select input types.
* Bug fixes.

Thanks to <a href="https://wordpress.org/support/users/neilhorton740/">neilhorton740</a>, <a href="https://wordpress.org/support/users/carldavidcarlstrom/">carldavidcarlstrom</a>, and <a href="https://wordpress.org/support/users/coloursign/">coloursign</a> for  testing and feedback.

### 0.9.12 ###

* **Gallery** - Added the option to display an image gallery for Markers that have images associated with them. Can be changed in Settings > Misc. > Map Options. Can be enabled using the show_gallery="1" Shortcode option.
* Created <a href="https://www.joesway.ca/waymark/docs/">Waymark Documentation</a>.
* Updated the <a href="https://www.joesway.ca/waymark/docs/">Demo</a>.
* First steps towards <a href="https://developer.wordpress.org/plugins/internationalization/localization/">Localization</a>.
* Added the ability to change Map author. Thanks to <a href="https://wordpress.org/support/topic/feature-request-shortcode-download-link-author-change/">andreasrns</a> for the suggestion.
* Maps now support Revisions.
* Added "Header for Admin" to Settings > Shortcode Options. Use this Setting to always show the Shortcode Header when signed in as admin, useful for quickly navigating to embedded Maps.
* Fixed a bug where circles were being added as Markers.
* Removed undocumented shortcode button in Classic Editor.
* Fixed Leaflet JS conflict when other instances of Leaflet exist. Thanks to <a href="https://wordpress.org/support/topic/blank-map-6/">jmruas</a> for bringing this to my attention.
* Fixed a bug where Types were not being saved for Markers/Lines imported from file.
* Other minor improvements.

A big thanks to <a href="https://wordpress.org/support/users/neilhorton740/">neilhorton740</a> and <a href="https://wordpress.org/support/users/carldavidcarlstrom/">carldavidcarlstrom</a> for bug finding and testing.

### 0.9.11 ###

* **Collections** - Can now be given a description and viewed as an archive (lists Maps in that Collection) on the front-end. A link is displayed on the Map Details page (Collection links support permalinks)
* Map URLs now support permalinks
* Added the ability to duplicate Maps
* In the Editor, Markers now display their Lat,Lng
* Lots of other improvements and bug fixes

Thanks to <a href="https://wordpress.org/support/users/neilhorton740/">neilhorton740</a> and <a href="https://wordpress.org/support/users/carldavidcarlstrom/">carldavidcarlstrom</a> for suggestions and testing.

### 0.9.10 ###

* **Shortcodes** – Added the ability to specify initial Map centre and zoom through the Shortcode: [Waymark map_centre="<lat>,<lng>" map_zoom="<0-18>"]. Shortcodes no longer require a Map ID/Collection ID. Added Using Shortcodes section to Help page.
* **Maps** – Added setting for Default Centre/Zoom when a Map has no data to display.
* **Collections** – Added the option to display a link to Map Details pages from Collections
* Improved Settings input sanitisation
* Lots more performance improvements and bug fixes

Another big thanks to <a href="https://wordpress.org/support/users/neilhorton740/">neilhorton740</a> for suggestions and testing.

### 0.9.9 ###

* Release date: December 17th, 2019

Added the ability to specify a default basemap through shortcode: [Waymark map_id="1234" basemap="Basemap Name"]. Which Basemap to use as the Map Editor default can also be specified in Settings > Misc. > Editor Options. Thanks again to <a href="https://wordpress.org/support/users/neilhorton740/">neilhorton740</a> for the suggestion and testing.

### 0.9.8 ###

* Release date: December 5th, 2019

Added Line Weight setting for Lines and the ability to override the Shortcode Header setting through the shortcode. A HUGE thank you to <a href="https://wordpress.org/support/users/neilhorton740/">neilhorton740</a> for the <a href="https://wordpress.org/support/topic/great-design-and-well-thought-out/">suggestions and testing</a>.

### 0.9.7 ###

* Release date: November 8th, 2019

Added support for Meta, which allows you to customise the input fields that display under the Map Editor. Any data you input will display on the Map Details page. Plus some minor improvements and bug fixes.

### 0.9.6 ###

* Release date: October 28rd, 2019

Added support for Collections, which allow you to organise your Maps and display multiple Maps at once. Also more bug fixes.

### 0.9.5 ###

* Release date: October 23rd, 2019

Integrated <a href="https://github.com/perliedman/leaflet-control-geocoder">leaflet-control-geocoder</a>, thanks for the <a href="https://wordpress.org/support/topic/search-for-place-in-admin-section/">suggestion</a>! Also minor fixes and tweaks.

### 0.9.4 ###

* Release date: October 11th, 2019

Bug fixes and improvements. Thanks again to rottensod!

### 0.9.3 ###

* Release date: September 4th, 2019

Bug fixes and minor tweaks. Thanks to rottensod for your bug finding efforts!

### 0.9.2 ###

* Release date: July 26th, 2019

Integrated Leaflet.Sleep into Waymark for better zoom/scroll event handling. Thanks to numeeja for this suggestion!

### 0.9.1 ###

* Release date: July 25th, 2019

Minor bug fix.

### 0.9 ###

* Release date: July 23rd, 2019

First public release of Waymark!
