
var geoTagInfowindows = new Array();

var spiderfiers = new Array();

jQuery(document).ready(function() {
	jQuery('.geoTagMap').each(function(){
		var index = jQuery(this).attr('id').split('_')[1];
		createGeoTagMap(index);
	});
});

function createGeoTagMap(index) {
	
	var attrs = geoTagAttributes[index];

	var geoTagOptions = {
		zoom: (attrs.zoom) ? parseInt(attrs.zoom) : 1,
		center: new google.maps.LatLng(0,0),
		mapTypeId: google.maps.MapTypeId[attrs.maptype],
		mapTypeControlOptions: {
			style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
		},
		zoomControlOptions: {
			style: google.maps.ZoomControlStyle.SMALL
		},
		panControl: false,
		streetViewControl: false
	};
		
	var geoTagMap = new google.maps.Map(document.getElementById('geoTagMap_'+index), geoTagOptions);
	
	var oms = new OverlappingMarkerSpiderfier(geoTagMap, { 
		markersWontMove: true, 
		markersWontHide: true, 
		keepSpiderfied: true 
	});
	spiderfiers.push(oms);
	
	var bounds = new google.maps.LatLngBounds();
	
	for(var i in geoTagPosts[index].features) {
		
		var marker = geoTagCreateMarker(geoTagPosts[index].features[i], attrs, geoTagMap, index, oms);
	
		oms.addMarker(marker); 
		oms.addListener('spiderfy', function(markers) {
			geoTagInfowindows[index].close();
		});
		bounds.extend(marker.getPosition());
	}
	
	if(!bounds.isEmpty() && parseInt(attrs.zoom) <= 0) {
		geoTagMap.fitBounds(bounds);
	}
	if(!bounds.isEmpty()) {
		geoTagMap.setCenter(bounds.getCenter());
	}
}

function geoTagCreateMarker(post, attrs, map, index, oms) {

	var latlng = new google.maps.LatLng(post.geometry.coordinates[0], post.geometry.coordinates[1]);
	var marker = new StyledMarker({
		styleIcon: new StyledIcon(
			StyledIconTypes.MARKER, { color: attrs.markercolor }
		), 
		position: latlng,
		title: post.properties.title,
		flat: true,
		map: map,
		infowindowContent: '<p style="padding-right:15px;"><a href="'+post.properties.url+'">'+post.properties.title+'</a><br/>'+post.properties.date+'<br/>'+post.properties.location+'</p>'
	});
	
	geoTagInfowindows[index] = new google.maps.InfoWindow({
		content: 'holding...'
	});
	
	oms.addListener('click', function(marker) {
		geoTagInfowindows[index].setContent( marker.infowindowContent );
		geoTagInfowindows[index].open(map, marker);
	});

	return marker;
}