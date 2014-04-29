=== Geo Tag ===
Contributors: peter2322
Donate link: http://www.worldtravelblog.com/code/geo-tag-plugin
Tags: google, maps, latitude, longitude, coordinates, geotag, location, geoJson
Requires at least: 3.0.1
Tested up to: 3.4.1
Stable tag: 0.9.6

This plugin adds location information to your posts and pages.

== Description ==

The Geo Tag plugin allows your posts and pages to contain location information which includes latitude, longitude, region, and country. This plugin has an easy and simple interface that can detect your current location or you can choose your location by searching for an address or clicking on the Google map. The location information is saved to the post or page and is included in the RSS feed and meta tags. The location information is available for use in your theme with the get_post_meta method. The plugin can also display a Google Map of the current post or a list of posts through the shortcode [geotag /]. The shortcode also exposes posts as geoJson.   

== Installation ==

1. Upload the plugin to your plugins folder
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Open a new or existing post/page and add the location information
4. \[Optional\] In the [Google API Console](https://code.google.com/apis/console "Google API Console") click on 'Services' and turn on 'Google Maps API v3'. Then click on 'API Access' and update your Google Latitude History plugin settings with your Simple API key. The api key tracks usuage and is not necessary. If your website uses a SSL certificate then set Google Maps to use SSL.

== Usage ==

* The data can be used in your theme by accessing it with the following methods: 
* $region = get_post_meta($post_id, "geotag_region", true);
* $country = get_post_meta($post_id, "geotag_country", true);
* $address = get_post_meta($post_id, "geo_address", true); 
* location is $region.",".$country
* $latitude = get_post_meta($post_id, "geo_latitude", true);
* $longitude = get_post_meta($post_id, "geo_longitude", true);  

== Shortcode Options ==

* [geotag /]
* The shortcode without any options will inject a [geoJson](http://en.wikipedia.org/wiki/GeoJSON) representation of your geo tagged posts
* If currentpostmap or postsmap option is true, a map representing the geo tagged posts will be created
* height = height of the map in pixels
* width = width of the map in pixels
* order = order of the posts by ascending or descending (ASC, DESC)			
* limit = number of post (default 10)
* currentpostmap = show map of current post (true or false)			
* postsmap = show map of multiple posts (true or false)
* country = show map of posts from the country
* markercolor = color of the map markers ( HTML color codes like <span style="color: #0000FF">#0000FF</span> )
* maptype = Google map type ( HYBRID, ROADMAP, SATELLITE, TERRAIN )
* zoom = map zoom level

== Frequently Asked Questions == 

= What is geoJson? =

[GeoJson](http://en.wikipedia.org/wiki/GeoJSON) is a javascript object representation of the posts' geo information.
 
= What is the geoJson format for this plugin =
 
Please check out the admin settings page for an example.

== Screenshots ==

1. Geo Tag interface close-up
2. Geo Tag interface in add new post page

== Changelog ==
 
 = 0.9.6 =
 
 * added search by country
 
 = 0.9.5 =
 
 * Changed plugin to use WordPress's geo post meta keys
 * Migrated geo post meta keys
 * Changed map shortcode to pull back only public posts
 
= 0.9.4 =

* Added Overlapping Marker Spiderfier for markers on the same position
* fixed admin javascript collision

= 0.9.3 =

* added shortcode to display geo tag posts on Google Maps and inject data as geoJson
* added ability to set Google API key and use Google Maps over SSL
* updated google maps url

= 0.9.2 = 

* fixed loading of javascript/css to only the edit post/page screens instead the entire admin module  

= 0.9.1 = 

* fixed donate link
* added required javascript dependencies

= 0.9 = 

* Initial checkin

== Upgrade Notice ==

