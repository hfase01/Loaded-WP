=== Geo Tag ===
Contributors: peter2322
Donate link: http://www.worldtravelblog.com/code/geo-tag-plugin
Tags: google, maps, latitude, longitude, coordinates, geotag, location
Requires at least: 3.1.3
Tested up to: 3.2.1
Stable tag: 0.9.2

This plugin adds location information to your posts and pages.

== Description ==

The Geo Tag plugin allows your posts and pages to contain location information which includes latitude, longitude, region, and country. This plugin has an easy and simple interface that can detect your current location or you can choose your location by searching for an address or clicking on the Google map. The location information is saved to the post or page and is included in the RSS feed and meta tags. The location information is available for use in your theme with the get_post_meta method.  

== Installation ==

1. Upload the plugin to your plugins folder
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Open a new or existing post/page and add the location information

== Usage ==

* The data can be used in your theme by accessing it with the following methods: 
* $region = get_post_meta($post_id, "geotag_region", true);
* $country = get_post_meta($post_id, "geotag_country", true);
* $location = get_post_meta($post_id, "geotag_location", true); 
* location is $region.",".$country
* $latitude = get_post_meta($post_id, "geotag_latitude", true);
* $longitude = get_post_meta($post_id, "geotag_longitude", true);  

== Frequently Asked Questions == 

== Screenshots ==

1. Geo Tag interface close-up
2. Geo Tag interface in add new post page

== Changelog ==

= 0.9.2 = 

* fixed loading of javascript/css to only the edit post/page screens instead the entire admin module  

= 0.9.1 = 

* fixed donate link
* added required javascript dependencies

= 0.9 = 

* Initial checkin

== Upgrade Notice ==

