jQuery(document).ready(function($) {
    
    //  ADD MARKER TO GOOGLE MAPS
    function addMarker(location, label) {
        var marker = new google.maps.Marker({
            position: location,
            label: label.toString(),
            map:wp_realtymole_map
        });
        var loc = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
        wp_realtymole_map_bounds.extend(loc);
    }
    
    //  WHEN FORM IS SUBMITTED
    $('#wp-realtymole-form form').submit(function(e) {
        
        e.preventDefault();
        
		$('#wp-realtymole-loading').show();
		$('#wp-realtymole-search').attr('disabled', true);
		
        var formdata = $(this).serialize();
        
        //  AJAX CALL TO API
        $.ajax({ 
            data: { action: 'wp_realtymole_ajax', formdata: formdata },
            dataType: 'json',
            type: 'post',
            url: wp_realtymole_object.ajaxUrl,
            success: function(data) {
                
                $('#wp-realtymole-form, #wp-realtymole-quota-exceeded').hide();
				if( $('#wp-realtymole-google-map').length != 0 ) wp_realtymole_initMap();
				
                $('#wp-realtymole-listings ol, #wp-realtymole-searched-address, #wp-realtymole-estimated-rent, #wp-realtymole-rent-range-low, #wp-realtymole-rent-range-high').empty();
				$('#wp-realtymole-searched-address').append($('input[name="wp-realtymole-address"]').val() + ', ' + $('input[name="wp-realtymole-city"]').val() + ', ' + $('input[name="wp-realtymole-state"]').val());
				$('#wp-realtymole-estimated-rent').append(data.rent);
				$('#wp-realtymole-rent-range-low').append(data.rentRangeLow);
				$('#wp-realtymole-rent-range-high').append(data.rentRangeHigh);
				
                $.each(data.listings, function(i, listing) {
                    var listingPhoto;
                    if( listing.photo ) {
                        listingPhoto = listing.photo;
                    } else if( listing.latitude && listing.longitude && wp_realtymole_object.googleMapsApi ) {
                        listingPhoto = 'https://maps.googleapis.com/maps/api/streetview?location=' + listing.latitude + ',' + listing.longitude + '&size=480x360&key=' + wp_realtymole_object.googleMapsApi;
                    } else {
                        listingPhoto = wp_realtymole_object.pluginUrl + 'img/no-photo.png';
                    }
                    
                    $('#wp-realtymole-listings ol').append('<li style="background-image:url(' + listingPhoto + ');"><strong>' + listing.address + ', ' + listing.city + ', ' + listing.state + '</strong><br>' + wp_realtymole_object.rent +
                                                        ' $' + listing.price + '<br>' + wp_realtymole_object.propertyType + listing.propertyType +
                                                        '<br>' + wp_realtymole_object.squareFootage + listing.squareFootage +
                                                        '<br><small>' + listing.bedrooms + ' ' + wp_realtymole_object.bedrooms +
														' - ' + listing.bathrooms + ' ' + wp_realtymole_object.bathrooms +
														' - ' + listing.distance + ' ' + wp_realtymole_object.miles + '</small></li>');
                    if( $('#wp-realtymole-google-map').length != 0 ) addMarker({lat: listing.latitude, lng: listing.longitude}, i + 1);
                });
                
                $('#wp-realtymole-results').show();
				$('#wp-realtymole-loading').hide();
				$('#wp-realtymole-search').removeAttr('disabled');
				
				if( $('#wp-realtymole-google-map').length != 0 ) { 
				    addMarker({lat: data.latitude, lng: data.longitude}, '');
                    wp_realtymole_map.fitBounds(wp_realtymole_map_bounds);
                    wp_realtymole_map.panToBounds(wp_realtymole_map_bounds);
				}
				
            },
            error: function(data) {
                
                if( data.responseText == 'Quota exceeded.' ) {
                    $('#wp-realtymole-form').hide();
                    $('#wp-realtymole-quota-exceeded').show();
                }
                
            }
        });
        
    });
	
	//	WHEN THE 'SEARCH AGAIN' BUTTON IS PRESSED
	$('#wp-realtymole-search-again').on('click', function() {
		$('#wp-realtymole-form').show();
		$('#wp-realtymole-quota-exceeded, #wp-realtymole-results').hide();
	});

});
