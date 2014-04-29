var $j = jQuery.noConflict();

$j(document).ready(function() {
	
	var map;
	var geocoder = new google.maps.Geocoder();
	var marker;
	var countries = new Array();
		
	createMap();
	
	createCountryAutoComplete();
	
	$j('#current_location').click(function() {
		if(navigator.geolocation) { 
			console.log('nav');
		    navigator.geolocation.getCurrentPosition(function(position) {
		    	var latLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
				setMarker(latLng);
				map.setZoom(12);
				decodeLatLng(latLng);
		    });
		} else {
			alert('position could not be determined');
		}
	});
	
	$j('#get_coords').click(function() { 	
    	var region = $j('#region').val();
    	var country = $j('#country').val();
    	
    		var location = region + ',' + country;
    		geocoder.geocode({'address': location, 'language': 'en'}, function(results, status) {
    			if (status == google.maps.GeocoderStatus.OK) {    				
    				var latLng = results[0].geometry.location;
    				setMarker(latLng);
    				map.setZoom(12);
    	            $j('#map_address').html("Map Address: " + results[0].formatted_address);
    			} else {
    				$j('#map_address').html('<span style="color:red">Map Address: no results</span>');	
    			}
    		});     	
	});
	
	function setMarker(latLng) {
		
		$j('#lat').val(roundCoordinate(latLng.lat()));
		$j('#lng').val(roundCoordinate(latLng.lng()));
		
		if(marker) {
			marker.setMap(null);
		}
		
		marker = new google.maps.Marker({
			position: latLng, 
            draggable: true, 
			map: map
		});	

        google.maps.event.addListener(marker, 'dragend', function(event) {
			setMarker(event.latLng);
			decodeLatLng(event.latLng);
		});
		
		map.setCenter(latLng);
	}
	
	function decodeLatLng(latLng) {
		geocoder.geocode({'latLng': latLng, 'language': 'en'}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
	        	$j('#map_address').text("Map Address: " + results[0].formatted_address);
				var address = new Array();
				var country;
	        	for(result in results) {
					for(type in results[result]['types']) {
						if(results[result]['types'][type] == 'locality') {
							address['locality'] = getFormattedRegion(results[result]['formatted_address']);
							country = getCountry(results[result]);
							break;
						} else if(results[result]['types'][type] == 'administrative_area_level_2') {
							address['administrative_area_level_2'] = getFormattedRegion(results[result]['formatted_address']);
							country = getCountry(results[result]);
							break;
						} else if(results[result]['types'][type] == 'administrative_area_level_1') {
							address['administrative_area_level_1'] = getFormattedRegion(results[result]['formatted_address']);
							country = getCountry(results[result]);
							break;
						}
					}
					// must preferred formatted address so let's stop
					if(address['locality']) {
						break;
					}
				}
				var locationName = "";
				if(address['locality']) {
					locationName = address['locality'];
				} else if(address['administrative_area_level_2']) {
					locationName = address['administrative_area_level_2'];
				} else if(address['administrative_area_level_1']) {
					locationName = address['administrative_area_level_1'];
				}
				$j('#region').val(locationName);
				$j('#country').val(country);
	        } else {
	        	$j('#map_address').html('<span style="color:red">Map Address: no results</span>');	
                $j('#region').val("");
                $j('#country').val("");
        	}
		});
	}
	
	function getFormattedRegion(address) {
		var addressComponents = address.split(",");
		addressComponents.pop(); //remove country
		return addressComponents.join(",");	
	}
	
	function getCountry(result) {
		var country;
		for(addressCount in result['address_components']) {
			var isCountry = false;
			for(addressType in result['address_components'][addressCount]['types']) {
				if('country' == result['address_components'][addressCount]['types'][addressType]) {
					isCountry = true;
				}
			}
			if(isCountry) {
				country = result['address_components'][addressCount]['long_name'];
			}
		}
		
		return country;
	}
	
	function createCountryAutoComplete() {
		$j('.countries').each(function() {
			countries.push( $j(this).val() );
		});
		$j('#country').autocomplete(countries);	
	}
	
	function createMap() {
		var myOptions = {
			zoom: 1,
			center: new google.maps.LatLng(0, 0),
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			streetViewControl: true,
			mapTypeControlOptions: {
				style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
			}
		};
			
		map = new google.maps.Map(document.getElementById("map"), myOptions);
			
		if( isNumeric($j('#lat').val()) && isNumeric($j('#lng').val()) ) {
			var latLng = new google.maps.LatLng($j('#lat').val(), $j('#lng').val());
			setMarker(latLng);
			map.setZoom(5);
			decodeLatLng(latLng);
		}

		geocoder = new google.maps.Geocoder();

		google.maps.event.addListener(map, 'click', function(event) {
			setMarker(event.latLng);
			decodeLatLng(event.latLng);
		});
	}
	
	function roundCoordinate(coord) {
		return Math.round(coord*Math.pow(10,6))/Math.pow(10,6);
	}

	function isNumeric(input) {
		return (input - 0) == input && input.length > 0;
	}
		
});