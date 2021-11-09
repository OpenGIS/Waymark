# Waymark #
**Contributors:** [morehawes](https://profiles.wordpress.org/morehawes)  
**Tags:** Create interactive Maps, Share geographical data, Add custom Markers, Display geotagged photos, Track elevation graph, Embed GPX KML GeoJSON, Hiking route maps, Collaborate on mapping projects, Display OpenStreetMap Basemaps, No technical knowledge required  
**Requires at least:** 4.6  
**Tested up to:** 5.7  
**Requires PHP:** 5.2  
**Stable tag:** 0.9.17.1  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Create and share customisable interactive Maps. Embed OpenStreetMap, add Overlays like location aware Photos and Tracks with elevation profiles.

## Description ##

* <a href="https://www.waymark.dev/demo/">See the Demo &raquo;</a>
* <a href="https://www.waymark.dev/try/">Try the Editor &raquo;</a>
* <a href="https://www.waymark.dev/docs/">Read the Docs &raquo;</a>
* <a href="https://github.com/morehawes/waymark">View on GitHub &raquo;</a>

Features:

* Easy to use, customisable and developer friendly.
* Use the <a href="https://www.waymark.dev/docs/editor/">Editor</a> to create Maps containing Overlays (Markers, Lines and Shapes).
* Every Overlay can be given a title, image and description; which is displayed once the Overlay is clicked.
* Set styles and options once using <a href="https://www.waymark.dev/docs/types/">Types</a>, then simply select the appropriate Type when adding an Overlay.
* Add Maps to your content using the Waymark <a href="https://www.waymark.dev/docs/shortcodes/">Shortcode</a>, or link to the <a href="https://www.waymark.dev/map/route-map/">Map Details</a> page. 
* <a href="https://www.openstreetmap.org/fixthemap">OpenStreetMap</a> is the default <a href="https://www.waymark.dev/docs/basemaps/">Basemap</a>, with support for multiple Basemaps.
* Images uploaded with location data (EXIF) will have a Marker placed where the photo was taken.
* Import from GPX, KML and GeoJSON formats. You can export Maps in the same formats and let your visitors do it too.
* An interactive profile chart can be displayed for Tracks uploaded with elevation data.
* Group Maps together using <a href="https://www.waymark.dev/docs/collections/">Collections</a> and display multiple Maps at once.
* Add extra information to your Maps using <a href="https://www.waymark.dev/docs/meta/">Meta</a>; these are customisable form inputs that allow you to add additional data to your Maps. 
* For the developers: Maps are stored using the custom post type `waymark_map`, Meta is stored as Custom Fields and Collections use the `waymark_collection` Taxonomy. Most elements can be styled using CSS and have sensibly named `waymark-` classes.

**Try the <a href="https://www.waymark.dev/forums/topic/development-release/">Development Release</a>!**

Currently <a href="https://www.waymark.dev/forums/topic/v0-9-18-b1-submissions/">v0.9.18-b1</a>

* **<a href="https://www.waymark.dev/docs/submissions/">Submissions</a>**
    * Allow registered users, or guests to create Maps from the front-end of your site. You can control who can Submit Maps, what editor features are available and whether submissions should be approved before they are published. Originally suggested <a href="https://wordpress.org/support/topic/feature-request-add-marker-from-frontend/">here</a>.
* Fixed a bug where Shortcode Headers were not displaying on Collection archive pages. Thanks to <a href="https://wordpress.org/support/users/meggsico/">meggsico</a> for <a href="https://wordpress.org/support/topic/maybe-a-bug-7/#post-14815812">bringing this to my attention</a>.
* Lots of back-end fixes and improvements.

Waymark is free, with no features to "unlock". I try to keep the plugin well supported, so please feel free to <a href="https://wordpress.org/support/plugin/waymark/#new-topic-0">reach out</a> with any questions or feedback.

## Installation ##

With Waymark enabled, click on the "Maps" link in the sidebar to create and edit Maps. Once you are happy with your Map, copy the Waymark shortcode and add it to your content.

<a href="https://www.waymark.dev/docs/">Read the Docs &raquo;</a>

## Frequently Asked Questions ##

### Is There a Demo? ###

Yes, <a href="https://www.waymark.dev/demo/">here</a>.

### Can I Get More Help? ###

Yes, please view the <a href="https://www.waymark.dev/docs/">Documentation</a>.

### Does Waymark Support Google Maps? ###

While Google Basemaps can be added to Waymark, Waymark does not use the Google Maps API (it uses <a href="https://leafletjs.com/">Leaflet</a>). There are other plugins available that are made specifically for Google Maps.

### Can I Translate the Plugin? ###

Please! Waymark is localization ready, <a href="https://translate.wordpress.org/projects/wp-plugins/waymark/">translation contributions</a> are greatly appreciated.

### Acknowledgements? ###

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

## Screenshots ##

### 1. Add Overlays (Markers, Lines and Shapes) to create detailed interactive Maps. You can import/export from GPX/KML/GeoJSON. ###
![Add Overlays (Markers, Lines and Shapes) to create detailed interactive Maps. You can import/export from GPX/KML/GeoJSON.](https://ps.w.org/waymark/assets/screenshot-1.jpg)

### 2. Every Overlay can be given a title, image and description. Marker images can be displayed as a gallery. ###
![Every Overlay can be given a title, image and description. Marker images can be displayed as a gallery.](https://ps.w.org/waymark/assets/screenshot-2.jpg)

### 3. Waymark features a clean, intuative Editor for creating and editing your Maps. Overlays are customisable using Types, which allow you set styles once (colours/icons etc.), so you can simply select it when you are adding to the Map.  ###
![Waymark features a clean, intuative Editor for creating and editing your Maps. Overlays are customisable using Types, which allow you set styles once (colours/icons etc.), so you can simply select it when you are adding to the Map. ](https://ps.w.org/waymark/assets/screenshot-3.jpg)

### 4. If you have more than one Basemap, you can switch between them when viewing the Map. Overlays can be shown/hidden by Type. ###
![If you have more than one Basemap, you can switch between them when viewing the Map. Overlays can be shown/hidden by Type.](https://ps.w.org/waymark/assets/screenshot-4.jpg)

### 5. Use Meta to provide extra information about your Maps. Meta inputs are customisable and can be grouped.  ###
![Use Meta to provide extra information about your Maps. Meta inputs are customisable and can be grouped. ](https://ps.w.org/waymark/assets/screenshot-5.jpg)

### 6. The Map Details page displays an image gallery, elevation profile, export options, featured image and all Meta provided for the Map. ###
![The Map Details page displays an image gallery, elevation profile, export options, featured image and all Meta provided for the Map.](https://ps.w.org/waymark/assets/screenshot-6.jpg)

### 7. Add Maps to your content using the Waymark Shortcode. You can choose which Meta is displayed. ###
![Add Maps to your content using the Waymark Shortcode. You can choose which Meta is displayed.](https://ps.w.org/waymark/assets/screenshot-7.jpg)

### 8. Organise Maps with Collections and display multiple Maps at once using the Shortcode. Collections can be nested and Maps can be associated with multiple Collections. ###
![Organise Maps with Collections and display multiple Maps at once using the Shortcode. Collections can be nested and Maps can be associated with multiple Collections.](https://ps.w.org/waymark/assets/screenshot-8.jpg)

### 9. Waymark was designed to be very flexible, with lots of Settings to choose from. ###
![Waymark was designed to be very flexible, with lots of Settings to choose from.](https://ps.w.org/waymark/assets/screenshot-9.jpg)


## Changelog ##

### 0.9.17.1 ###

* Fixed a bug where the Export feature did not work correctly when embedding multiple Maps. Thanks to <a href="https://github.com/shenki">shenki</a> on <a href="https://github.com/morehawes/waymark/issues/3">GitHub</a>.
* Waymark now has it's own <a href="https://www.waymark.dev/">website</a>! URLs updated.

### 0.9.17 ###

* **Maps**
    * Maps now support Featured Images; if provided this will be displayed on the Map Details page, as well as by the Shortcode. Thanks to David for the suggestion!
* **Markers**
    * Marker Icons can now be custom text or HTML (in addition to Font icons from Ionic Icons and Font Awesome). Thanks for the <a href="https://wordpress.org/support/topic/feature-request-custom-markers/">suggestion</a>!
    * Map Exports are now available on mobile devices.
    * Updated Acknowledgements.
* **Bug fixes**
    * Settings JavaScript fix.
    * Settings repeatable dropdown bug.
    * Settings input sanitisation.

A <b>BIG</b> thank you to the following users for their help with testing:

* <a href="https://wordpress.org/support/users/dalehopp/">dalehopp</a>
* <a href="https://wordpress.org/support/users/coloursign/">coloursign</a>
* <a href="https://wordpress.org/support/users/nhathaway/">nhathaway</a>

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
* Created <a href="https://www.waymark.dev/docs/">Waymark Documentation</a>.
* Updated the <a href="https://www.waymark.dev/docs/">Demo</a>.
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
