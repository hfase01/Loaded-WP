<?php

class GeoTagSettings {
	
	function settingsPage() {
?>		

	<script type="text/javascript">
		var $j = jQuery.noConflict();

		$j(document).ready(function() {
		
			$j('#use_google_api_key').click(function() {
				if( $j('#use_google_api_key').attr('checked') ) {
					$j('#google_api_key').removeAttr('readonly');
				} else {
					$j('#google_api_key').attr('readonly', true);
				}
			});

			$j('#toggleGeoJsonExample').click(function() {
				if($j('#toggleGeoJsonExample').hasClass('hiding')) {
					$j('#toggleGeoJsonExample').text('Hide geoJson example');
					$j('#geoJsonExample').show();
					$j('#toggleGeoJsonExample')
						.removeClass('hiding')
						.addClass('showing');
				} else {
					$j('#toggleGeoJsonExample').text('Show geoJson example');
					$j('#geoJsonExample').hide();
					$j('#toggleGeoJsonExample')
						.removeClass('showing')
						.addClass('hiding');
				}
			});
		});			
	</script>

	<div class="column1">
		<form name="latitude_settings" id="latitudeSettings" method="post">
		
			<fieldset>
				<legend>Google Map Options</legend>
			
				<div class="divRow">
					<div class="firstCell">
						<label for="google_api_key">&nbsp;&nbsp;&nbsp;Google API Key:</label>
					</div>
					<div class="secondCell">
						<input type="text" name="google_api_key" id="google_api_key" class="regular-text" value="<?php echo get_option('geotag_google_api_key'); ?>" <?php if(get_option('geotag_use_google_api_key') != 'true') { echo 'readonly="readonly"'; } ?>/>		
					</div>
				</div>
				
				<div class="divRow">
					<div class="firstCell">
						<label for="use_google_api_key">&nbsp;&nbsp;&nbsp;Use Google API Key:</label>
					</div>
					<div class="secondCell">
						<div style="float:left;"><input type="checkbox" name="use_google_api_key" id="use_google_api_key" value="true" <?php if(get_option('geotag_use_google_api_key') == 'true') { echo 'checked="checked"'; } ?> /></div>
						<div style="float:right;"><a href="https://code.google.com/apis/console" target="_blank">Google API Console</a></div>				
					</div>
				</div>
				
				<div class="divRow">
					<div class="firstCell">
						<label for="google_api_ssl">&nbsp;&nbsp;&nbsp;Google Maps over SSL:</label>
					</div>
					<div class="secondCell">
						<input type="checkbox" name="google_api_ssl" id="google_api_ssl" value="true" <?php if(get_option('geotag_google_api_ssl') == 'true') { echo 'checked="checked"'; } ?> />
					</div>
				</div>
			</fieldset>
			
			<p>
				<input type="submit" name="submit" value="Update Options" />
			</p>
			
			<br/>
			<p class="bold">ShortCode Options for [geotag /]</p>
			<ul style="list-style-type:circle;padding-left:20px;">
				<li>height = height of the map in pixels</li>
				<li>width = width of the map in pixels</li>
				<li>order = order of the posts by ascending or descending (ASC, DESC)</li>			
				<li>limit = number of post (default 10)</li>
				<li>currentpostmap = show map of current post (true or false)</li>			
				<li>postsmap = show map of multiple posts (true or false)</li>
				<li>country = show map of posts from the country</li>
				<li>markercolor = color of the map markers ( HTML color codes like <span style="color: #0000FF">#0000FF</span> )</li>
				<li>maptype = Google map type ( HYBRID, ROADMAP, SATELLITE, TERRAIN )</li>
				<li>zoom = map zoom level</li>			
			</ul>
			<p>The shortcode without any options will inject a <a href="http://en.wikipedia.org/wiki/GeoJSON" target="_blank">geoJson</a> representation of your geo tagged posts into the page. If currentpostmap or postsmap option is true, a map representing the geo tagged posts will be created</p>
			
			<p><a href="#" id="toggleGeoJsonExample" class="hiding" onclick="return false;">Show geoJson example</a></p>
			<pre id="geoJsonExample">
var geoTagPosts[0] =			
{
   "type":"FeatureCollection",
   "features":[
      {
         "type":"Feature",
         "geometry":{
            "type":"Point",
            "coordinates":[
               "51.508129",
               "-0.128005"
            ]
         },
         "properties":{
            "id":"489",
            "title":"test",
            "date":"June 8, 2012",
            "url":"http:\/\/example.com\/2012\/06\/test\/",
            "region":"London",
            "country":"United Kingdom",
            "location":"London, United Kingdom"
         }
      },
      {
         "type":"Feature",
         "geometry":{
            "type":"Point",
            "coordinates":[
               "20.219235",
               "-103.138427"
            ]
         },
         "properties":{
            "id":"460",
            "title":"lorem ispum",
            "date":"June 3, 2012",
            "url":"http:\/\/example.com\/2012\/06\/lorem-ispum\/",
            "region":"Jalisco",
            "country":"Mexico",
            "location":"Jalisco, Mexico"
         }
      }
   ]
}
			</pre>
		</form>
	</div>
	<div class="column2">
		<p>
			Send your questions or comments to <a href="mailto:peter@worldtravelblog.com?Subject=GeoTag Plugin">peter@worldtravelblog.com</a>
		</p>
		
		
		
		<p>
			<a href="http://wordpress.org/extend/plugins/geo-tag/" target="_blank">Please rate my plugin :)</a>
		</p>
	</div>
<?php 		
	}
	
	
}