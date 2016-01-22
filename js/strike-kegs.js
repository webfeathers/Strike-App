$(document).ready(function($) {
	"use strict";
	var latlong,
		getlocation = function() {
			if(!navigator.geolocation) {
				$('.nogeo').html("browser not capable");
			} else {
				navigator.geolocation.getCurrentPosition(foundGeoLocation, failedGeoLocation, {enableHighAccuracy:true,maximumAge:30000, timeout:27000});
			}
		},
		foundGeoLocation = function(position) {
			latlong = position.coords.latitude + ',' + position.coords.longitude;
			checkLatLong();
			$.ajax({
				url: '/includes/locate.php',
				type : "POST",
				dataType : "json",
				data : {
					sensor: true,
					latlng : latlong
				},
				success: function(data) {
					
							console.log($('#physical_address_number').val());
					if(data.status === "OK"){
						var addr_number = data.results[0].address_components[0].short_name,
							addr_street = data.results[0].address_components[1].long_name,
							addr_city = data.results[1].address_components[1].short_name,
							addr_state = data.results[1].address_components[3].short_name,
							addr_zip = parseInt(data.results[1].address_components[0].long_name);
						//sometimes we get different data for zip code
						//console.log(isNaN(addr_zip));
						if(isNaN(addr_zip)){
							addr_zip = parseInt(data.results[0].address_components[7].long_name);
						}
						if($('#physical_address_number').val() === ''){
							$('#physical_address_number').val(addr_number);
						}
						if($('#physical_address_street').val() === ''){
							$('#physical_address_street').val(addr_street);
						}
						if($('#physical_address_city').val() === ''){
							$('#physical_address_city').val(addr_city);
						}
						if($('#physical_address_latlong').val() === ''){
							$('#physical_address_latlong').val(latlong);
						}
						if($('#physical_address_state').val() === ''){
							$('#physical_address_state').val(addr_state);
						}
						if($('#physical_address_zip').val() === ''){
							$('#physical_address_zip').val(addr_zip);
						}
					} // end case where result is "OK"
				}
			});
		},
		failedGeoLocation = function(){
			$('.nogeo').html("Can't get a bead on you");
		},
		checkLatLong = function(){
			$('.selectAndGoTrigger option').each(function(){
				if($(this).data("latlong") === latlong){
					$('.selectAndGoTrigger').val($(this).val());
					$('#preselect .customer').html($(this).text());
					$('#preselect').show();
				}
			});
		};
		
		$('.selectAndGoTrigger').on('change',function(){
			$('#selectAndGoTarget').submit();
		});
		getlocation();
});