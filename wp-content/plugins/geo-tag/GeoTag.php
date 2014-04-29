<?php
/*
Plugin Name: Geo Tag
Plugin URI: http://www.worldtravelblog.com/code/geo-tag-plugin
Description: This plugin adds location information to your posts and pages.
Version: 0.9.6
Author: Peter Rosanelli
Author URI: http://www.worldtravelblog.com
Minimum WordPress Version Required: 3.0.1
*/

/**
* LICENSE
* This file is part of Geo Tag.
*
* Geo Tag is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*
* @package    geo-tag
* @author     Peter Rosanelli <peter@worldtravelblog.com>
* @copyright  Copyright 2011 Peter Rosanelli
* @license    http://www.gnu.org/licenses/gpl.txt GPL 2.0
* @version    0.9.6
* @link       http://www.worldtravelblog.com/code/geo-tag-plugin
*/

require_once('GeoTagMap.php');
require_once('GeoTagSettings.php');


add_action('wp_enqueue_scripts', array('GeoTag', 'wpEnqueueScripts'));
add_action('admin_enqueue_scripts', array('GeoTag', 'adminEnqueueScripts'));

add_action('admin_init', array('GeoTag', 'checkVersion'));
add_action('admin_print_scripts-post-new.php', array('GeoTag', 'printEditPageScripts'));
add_action('admin_print_scripts-post.php', array('GeoTag', 'printEditPageScripts'));
add_action('admin_print_styles-post-new.php', array('GeoTag', 'printEditPageStyles'));
add_action('admin_print_styles-post.php', array('GeoTag', 'printEditPageStyles'));
add_action('admin_menu', array('GeoTag', 'adminMenuHook'));
add_action('admin_print_styles-settings_page_geo-tag-menu', array('GeoTag', 'adminPrintStyles'));

add_action('save_post', array('GeoTag', 'savePostHook'));
add_action('rss2_ns', array('GeoTag', 'rssNsHook'));
add_action('rss2_item', array('GeoTag', 'rssItemHook'));
add_action('wp_head', array('GeoTag', 'wpHeadHook'));
add_action('wp_footer', array('GeoTag', 'wpFooter'));
add_shortcode('geotag', array('GeoTag', 'shortcodeHandler'));


class GeoTag {
		
	// post meta keys
	// http://codex.wordpress.org/Geodata
	const POST_META_REGION = 'geotag_region';
	const POST_META_COUNTRY = 'geotag_country';
	const POST_META_ADDRESS = 'geo_address';
	const POST_META_LATITUDE = 'geo_latitude';
	const POST_META_LONGITUDE = 'geo_longitude';
	const POST_META_PUBLIC = 'geo_public';
	
	// saved in wp_options
	const GEO_TAG_VERSION = 'geo_tag_version';
	
	// current plugin version
	static $version = '0.9.6';
	
	// determines if shortcode has been used on a page, if so then print the associated javascript
	static $showPostMap;
	
	// count of how many times the shortcode has been used on a screen
	static $instanceCount = 0;
		
	function checkVersion() {
		
		$version = get_option(self::GEO_TAG_VERSION);
		
		if($version != self::$version) {
			self::updatePostMetaData();
			update_option(self::GEO_TAG_VERSION, self::$version);
		}
	}
	
	
	/**
	 * Migrates geotag post meta data to use wordpress's own geo data options
	 *
	 */
	function updatePostMetaData() {
		
		global $wpdb;
 		
 		$query = "SELECT DISTINCT(post_id) FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'geotag_latitude' OR meta_key = 'geotag_longitude' OR meta_key = 'geotag_location'"; 		
 		$results = $wpdb->get_results($query);		
  		foreach($results as $result) {
  			
  			// update latitude
			$latitude = get_post_meta($result->post_id, 'geotag_latitude', true);   			
			update_post_meta($result->post_id, self::POST_META_LATITUDE, $latitude);
			delete_post_meta($result->post_id, 'geotag_latitude');
			
  			// update latitude
			$longitude = get_post_meta($result->post_id, 'geotag_longitude', true);   			
			update_post_meta($result->post_id, self::POST_META_LONGITUDE, $longitude);
			delete_post_meta($result->post_id, 'geotag_longitude');

  			// update address
			$location = get_post_meta($result->post_id, 'geotag_location', true);   			
			update_post_meta($result->post_id, self::POST_META_ADDRESS, $location);
			delete_post_meta($result->post_id, 'geotag_location');

			// add geo data is public
			update_post_meta($result->post_id, self::POST_META_PUBLIC, 1);
  		}
	}
	
	/**
	* Generates the HTML for the Geo Tag interface 
	*/
	function displayEditPostForm() {
		global $wpdb, $post;
		$post_id = $post->ID;
		$region = get_post_meta($post_id, self::POST_META_REGION, true);
		$country = get_post_meta($post_id, self::POST_META_COUNTRY, true);
		$lat = get_post_meta($post_id, self::POST_META_LATITUDE, true);
		$lng = get_post_meta($post_id, self::POST_META_LONGITUDE, true);
	
		echo '
			<table style="float:left;">
				<tr style="text-align:left;">
					<th>Region</th>
					<th>Country</th>
					<th></th>
				</tr>
				<tr>
					<td>
						<input type="text" name="region" id="region" value="'.$region.'" style="width:15em;" />&nbsp;&nbsp;
					</td>
					<td>
						<input type="text" name="country" id="country" size="35" value="'.$country.'" />';
						foreach(self::getCountryList() as $country) {
							echo '<input type="hidden" class="countries" value="'.$country.'" />';
						}
		echo '		</td>
                    <td style="padding-right:10px;">
						<input type="button" id="get_coords" onclick="return false" style="width:15em;" value="Search" class="button" />
					</td>
				</tr>
			</table>
            <table style="float:left;">
            	<tr style="text-align:left;">
					<th>Latitude</th>
					<th>Longitude</th>
					<th></th>
                </tr>
                <tr>
					<td>
						<input type="text" name="geo_latitude" id="lat" size="10" style="width:10em;" value="'.$lat.'"/>&nbsp;&nbsp;
					</td>
					<td>
						<input type="text" name="geo_longitude" id="lng" size="10" style="width:10em;" value="'.$lng.'" />&nbsp;&nbsp;&nbsp;
					</td>
					<td>
						<input type="button" id="current_location" onclick="return false" value="Current Location" class="button" />
					</td>
				</tr>
			</table>
			<br style="clear:both;" />
			<div id="map_address" style="padding: 5px 0 5px 5px; ">Map Address: </div>
			<div id="map" style="height:400px; width:100%; padding:0px; margin:0px;"></div>';
	}
	
	/**
	* Save post and page hook that saves the Geo Tag data in the post meta fields
	* @param post id 
	*/
	function savePostHook($post_id) {
		
		if($_POST) {
		
			update_post_meta($post_id, self::POST_META_REGION, trim($_POST['region']));
			$location = trim($_POST['region']);
	
			update_post_meta($post_id, self::POST_META_COUNTRY, trim($_POST['country']));
			if($location != "") {
			        $location .= ", ";
			}		    
			$location .= trim($_POST['country']);
			update_post_meta($post_id, self::POST_META_ADDRESS, trim($location));
			 			
			update_post_meta($post_id, self::POST_META_LATITUDE, trim($_POST['geo_latitude']));
	        update_post_meta($post_id, self::POST_META_LONGITUDE, trim($_POST['geo_longitude']));		
		    update_post_meta($post_id, self::POST_META_PUBLIC, 1);
		}
	}
	
	function adminMenuOptions() {
		
		global $wpdb;
		
		echo '<h2>Geo Tag Settings</h2>';
		
		echo '<br/>';
		
		wp_nonce_field('update-options');

		if(isset($_POST['submit']) && $_POST['submit'] == 'Update Options') {

			update_option('geotag_google_api_key', $_POST['google_api_key']);				

			if(isset($_POST['use_google_api_key']) && $_POST['use_google_api_key'] == 'true') {
				update_option('geotag_use_google_api_key', $_POST['use_google_api_key']);	
			} else {
				update_option('geotag_use_google_api_key', '');
			}
			
			if(isset($_POST['google_api_ssl']) && $_POST['google_api_ssl'] == 'true') {
				update_option('geotag_google_api_ssl', $_POST['google_api_ssl']);	
			} else {
				update_option('geotag_google_api_ssl', '');
			}
					
			echo '<div class="updated"><p>Settings Updated</p></div><br/>';
		}
		
		GeoTagSettings::settingsPage();		
	}
	
	/**
	* Adds the Geo Tag interface to the edit post and edit page pages  
	*/
	function adminMenuHook() {		
		add_meta_box('geotag', 'Geo Tag', array('GeoTag', 'displayEditPostForm'), 'post', 'normal', 'high');
		add_meta_box('geotag', 'Geo Tag', array('GeoTag', 'displayEditPostForm'), 'page', 'normal', 'high');
		
		add_options_page('GeoTag Settings', 'GeoTag', 'manage_options', 'geo-tag-menu', array('GeoTag', 'adminMenuOptions'));
	}
	
	/*
	 * Adds namespace for geo related rss tags
	 */
	function rssNsHook() {
		echo ' xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#" ';
		echo ' xmlns:georss="http://www.georss.org/georss" ';
	}

	/*
	 * Adds geo related rss tags
	 */
	function rssItemHook() {	
		global $post;
		
		$lat = get_post_meta($post->ID, self::POST_META_LATITUDE, true);
		$lng = get_post_meta($post->ID, self::POST_META_LONGITUDE, true);
		
		if(isset($lat) && is_numeric($lat) && isset($lng) && is_numeric($lng)) {
			echo '<geo:lat>'.$lat.'</geo:lat><geo:long>'.$lng.'</geo:long>';
			echo '<georss:point>'.$lat.' '.$lng.'</georss:point>'; 			
		}
		
		$location = get_post_meta($post->ID, self::POST_META_ADDRESS, true);
		if(isset($location) && $location != '') {
			echo '<georss:featurename>'.$location.'</georss:featurename>';
		}
	}
	
	/*
	 * Adds geo related meta data to a single page or post header
	 */
	function wpHeadHook(){
		global $post;
		$id = $post->ID;
		if(is_home() || is_front_page()){
			return;
		}
	
		$lat = get_post_meta($post->ID, self::POST_META_LATITUDE, true);
		$lng = get_post_meta($post->ID, self::POST_META_LONGITUDE, true);
			
		if(isset($lat) && is_numeric($lat) && isset($lng) && is_numeric($lng)) {
			echo '<meta name="ICBM" content="'.$lat.', '.$lng.'" />'."\n";
			echo '<meta name="geo.position" content="'.$lat.', '.$lng.'" />'."\n"; 			
		}
			
		$location = get_post_meta($post->ID, self::POST_META_ADDRESS, true);
		if(isset($location) && $location != '') {
			echo '<meta name="geo.placename" content="'.$location.'" />'."\n";
		}
	}
	
	/*
	 * shortcode: [geotag /]
	 * @param attributes passed from the shortcode
	 */
	function shortcodeHandler($attrs) {
		
		global $wpdb;
		
		self::$instanceCount++;
		
		$attributes = shortcode_atts( array(
			'order' => 'DESC', // ASC, DESC
			'limit' => 10, // number of posts
			'currentpostmap' => 'false', // true/false 
			'postsmap' => 'false', // true/false
			'height' => 400, // pixel height of map
			'width'	=> 600, // pixel width of map
			'markercolor' => '#6699ff', // CSS3 colors in HEX
			'maptype' => 'TERRAIN', // HYBRID, ROADMAP, SATELLITE, TERRAIN
			'zoom' => 0,
			'country' => null
		), $attrs);
		
		foreach($attributes as $key => $value) {
			$lcValue = strtolower($value);
			if( $lcValue == 'true' ) {
				$attributes[$key] = true;
			} elseif( $lcValue == 'false' ) {
				$attributes[$key] = false;
			}
		}
		
		$query = "SELECT a.post_id, a.meta_value lat, b.meta_value lng, c.meta_value region, d.meta_value country, e.meta_value location, p.ID id, p.post_title title, p.post_date date ";
		$query .= "FROM (SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = '".self::POST_META_LATITUDE."' ) a ";
		$query .= "LEFT JOIN (SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = '".self::POST_META_LONGITUDE."') b ";
		$query .= "ON a.post_id = b.post_id ";
		$query .= "LEFT JOIN (SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = '".self::POST_META_REGION."') c ";
		$query .= "ON a.post_id = c.post_id ";
		$query .= "LEFT JOIN (SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = '".self::POST_META_COUNTRY."') d ";
		$query .= "ON a.post_id = d.post_id ";
		$query .= "LEFT JOIN (SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = '".self::POST_META_ADDRESS."') e ";
		$query .= "ON a.post_id = e.post_id ";
		$query .= "LEFT JOIN ".$wpdb->prefix."posts p ";
		$query .= "ON a.post_id = p.ID ";
		$query .= "WHERE p.post_status = 'publish' AND a.meta_value != '' AND b.meta_value != '' ";
		if($attributes['currentpostmap']) {
			$query .= " AND p.ID = ".$post->ID." ";
		}
		if($attributes['country']) {
			$query .= " AND UPPER(d.meta_value) = '".strtoupper($attributes['country'])."' ";
		}
		$query .= "ORDER BY p.post_date ".$attributes['order']." ";
		$query .= "LIMIT ".$attributes['limit'].";";

		$rows = $wpdb->get_results($query);		
		foreach($rows as $row) {
			$geoJson['features'][] = array(
				'type' => 'Feature',
				'geometry' => array(
					'type' => 'Point',
					'coordinates' => array(
						$row->lat,
						$row->lng
					)
				),
				'properties' => array(
					'id' => $row->id,
					'title' => $row->title,
					'date' => date('F j, Y', strtotime($row->date)),
					'url' => get_permalink($row->id),
					'region' => $row->region,
					'country' => $row->country,
					'location' => $row->location
				)
			);
		}
		
		if($attributes['postsmap'] || $attributes['currentpostmap']) {
			self::$showPostMap = true;
		} else {
			self::$showPostMap = false;
		}
		
		return GeoTagMap::generateMap($attributes, $geoJson, self::$showPostMap, self::$instanceCount - 1);
	}
	
	
	function adminEnqueueScripts() {
		
		wp_register_script('google_maps', 'http://maps.googleapis.com/maps/api/js?sensor=false', false, '3', true);
		wp_register_script('jquery_autocomplete', plugins_url('js/jquery.autocomplete.min.js', __FILE__), array('jquery'), false, true);
		wp_register_script('geoinfo', plugins_url('js/geo-tag.geoinfo.js', __FILE__), array('jquery', 'google_maps', 'jquery_autocomplete'), '0.9.6', true);
		
		wp_register_style('jquery_autocomplete_css', plugins_url('css/jquery.autocomplete.css', __FILE__), false, '0.9.6', 'screen');	
		wp_register_style('geotag_admin', plugins_url('css/admin.css', __FILE__), array(), '0.9.6', 'screen');
	}
	
	function wpEnqueueScripts() {
		
		wp_register_script('google_maps', 'http://maps.googleapis.com/maps/api/js?sensor=false', false, '3', true);
		wp_register_script('spiderfier', plugins_url('js/oms.min.js', __FILE__), array('google_maps'), '0.2.5', true);
		wp_register_script('styled-marker', plugins_url('js/StyledMarker.js', __FILE__), array('google_maps'), '0.5', true);
		wp_register_script('geotag-postmap', plugins_url('js/geo-tag.postmap.js', __FILE__), array('jquery', 'google_maps', 'spiderfier', 'styled-marker'), '0.9.4', true);
		
		wp_register_style('geotag-postmap', plugins_url('css/geo-tag.postmap.css', __FILE__), array(), '0.9.6', 'screen');
	}
	
	function wpFooter() {
		if (!self::$showPostMap)
		return;
		
		self::configureGoogleMaps();
		
		// used print instead of enqueue b/c it doesnt work when printing the js on the bases of a variable 
		wp_print_scripts('google_maps');
		wp_print_scripts('spiderfier');
		wp_print_scripts('styled-marker');
		wp_print_scripts('geotag-postmap');
		
		wp_print_styles('geotag-postmap');
	}
	
	function configureGoogleMaps() {
		
		if(get_option('geotag_google_api_ssl') == 'true' || get_option('geotag_use_google_api_key') == 'true') {

			wp_deregister_script('google_maps');
			
			$protocol = 'http';
			if(get_option('geotag_google_api_ssl') == 'true') {
				$protocol = 'https';								
			}
			
			$urlKey = '';
			if(get_option('geotag_use_google_api_key') == 'true') {
				$urlKey = '&key='.get_option('geotag_google_api_key');
			}
			
			$googleMapApiUrl = $protocol.'://maps.googleapis.com/maps/api/js?sensor=false'.$urlKey;
			
			wp_register_script('google_maps', $googleMapApiUrl, false, '3', true);
		}
	}
	
	/**
	* Adds required javascript to the edit post/page screen 
	*/
	function printEditPageScripts() {		

		self::configureGoogleMaps();
		
		wp_enqueue_script('google_maps');
		wp_enqueue_script('jquery_autocomplete');
		wp_enqueue_script('geoinfo');
	}
	
	/**
	 * 
	 * Adds required css to the edit post/page screen
	 */
	function printEditPageStyles() {				
		wp_enqueue_style('jquery_autocomplete_css');
	}
	
	function adminPrintStyles() {
		wp_enqueue_style('geotag_admin');
	}

	/*
	 * Returns a list of countries for the autocomplete country field
	 */
	function getCountryList() {
		
		$countries = array(
			'Afghanistan',
			'Albania',
			'Algeria',
			'American Samoa',
			'Andorra',
			'Angola',
			'Anguilla',
			'Antarctica',
			'Antigua and Barbuda',
			'Argentina',
			'Armenia',
			'Aruba',
			'Australia',
			'Austria',
			'Azerbaijan',
			'Bahamas',
			'Bahrain',
			'Bangladesh',
			'Barbados',
			'Belarus',
			'Belgium',
			'Belize',
			'Benin',
			'Bermuda',
			'Bhutan',
			'Bolivia',
			'Bosnia and Herzegovina',
			'Botswana',
			'Bouvet Island',
			'Brazil',
			'British Indian Ocean Territory',
			'British Virgin Islands',
			'Brunei Darussalam',
			'Bulgaria',
			'Burkina Faso',
			'Burundi',
			'Cambodia',
			'Cameroon',
			'Canada',
			'Cape Verde',
			'Cayman Islands',
			'Central African Republic',
			'Chad',
			'Chile',
			'China',
			'Christmas Island',
			'Cocos Keeling Islands',
			'Colombia',
			'Comoros',
			'Congo',
			'Congo',
			'Cook Islands',
			'Costa Rica',
			'Croatia',
			'Cuba',
			'Cyprus',
			'Czech Republic',
			'Denmark',
			'Djibouti',
			'Dominica',
			'Dominican Republic',
			'Ecuador',
			'Egypt',
			'El Salvador',
			'Equatorial Guinea',
			'Eritrea',
			'Estonia',
			'Ethiopia',
			'Falkland Islands Malvinas',
			'Faroe Islands',
			'Fiji',
			'Finland',
			'France',
			'French Guiana',
			'French Polynesia',
			'French Southern Territories',
			'Gabon',
			'Gambia',
			'Georgia',
			'Germany',
			'Ghana',
			'Gibraltar',
			'Greece',
			'Greenland',
			'Grenada',
			'Guadeloupe',
			'Guam',
			'Guatemala',
			'Guernsey',
			'Guinea',
			'Guinea-Bissau',
			'Guyana',
			'Haiti',
			'Heard Island and Mcdonald Islands',
			'Honduras',
			'Hong Kong',
			'Hungary',
			'Iceland',
			'India',
			'Indonesia',
			'Iran',
			'Iraq',
			'Ireland',
			'Isle of Man',
			'Israel',
			'Italy',
			'Jamaica',
			'Japan',
			'Jersey',
			'Jordan',
			'Kazakhstan',
			'Kenya',
			'Kiribati',
			'Korea',
			'Korea',
			'Kuwait',
			'Kyrgyzstan',
			'Laos',
			'Latvia',
			'Lebanon',
			'Lesotho',
			'Liberia',
			'Libya',
			'Liechtenstein',
			'Lithuania',
			'Luxembourg',
			'Macao',
			'Macedonia',
			'Madagascar',
			'Malawi',
			'Malaysia',
			'Maldives',
			'Mali',
			'Malta',
			'Marshall Islands',
			'Martinique',
			'Mauritania',
			'Mauritius',
			'Mayotte',
			'Mexico',
			'Micronesia',
			'Moldova',
			'Monaco',
			'Mongolia',
			'Montenegro',
			'Montserrat',
			'Morocco',
			'Mozambique',
			'Myanmar',
			'Namibia',
			'Nauru',
			'Nepal',
			'Netherlands',
			'Netherlands Antilles',
			'New Caledonia',
			'New Zealand',
			'Nicaragua',
			'Niger',
			'Nigeria',
			'Niue',
			'Norfolk Island',
			'Northern Mariana Islands',
			'Norway',
			'Oman',
			'Pakistan',
			'Palau',
			'Palestinian Territory',
			'Panama',
			'Papua New Guinea',
			'Paraguay',
			'Peru',
			'Philippines',
			'Pitcairn',
			'Poland',
			'Portugal',
			'Puerto Rico',
			'Qatar',
			'Reunion',
			'Romania',
			'Russian Federation',
			'Rwanda',
			'Saint Barth Lemy',
			'Saint Helena',
			'Saint Kitts and Nevis',
			'Saint Lucia',
			'Saint Martin',
			'Saint Pierre and Miquelon',
			'Saint Vincent and the Grenadines',
			'Samoa',
			'San Marino',
			'Sao Tome and Principe',
			'Saudi Arabia',
			'Senegal',
			'Serbia',
			'Seychelles',
			'Sierra Leone',
			'Singapore',
			'Slovakia',
			'Slovenia',
			'Solomon Islands',
			'Somalia',
			'South Africa',
			'South Georgia and the South Sandwich Islands',
			'South Sudan',
			'Spain',
			'Sri Lanka',
			'Sudan',
			'Suriname',
			'Svalbard and Jan Mayen',
			'Swaziland',
			'Sweden',
			'Switzerland',
			'Syrian Arab Republic',
			'Taiwan',
			'Tajikistan',
			'Tanzania',
			'Thailand',
			'Timor-Leste',
			'Togo',
			'Tokelau',
			'Tonga',
			'Trinidad and Tobago',
			'Tunisia',
			'Turkey',
			'Turkmenistan',
			'Turks and Caicos Islands',
			'Tuvalu',
			'Uganda',
			'Ukraine',
			'United Arab Emirates',
			'United Kingdom',
			'United States',
			'United States Minor Outlying Islands',
			'Uruguay',
			'Uzbekistan',
			'Vatican City',
			'Vanuatu',
			'Venezuela',
			'Viet Nam',
			'U.S. Virgin Islands',
			'Wallis and Futuna',
			'Western Sahara',
			'Yemen',
			'Zambia',
			'Zimbabwe'
		);
		
		return $countries;
	}
}
?>