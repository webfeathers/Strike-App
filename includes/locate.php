<?php
	//This will take the parameters from the front end, and make a get request to the Google places api
	$latLong = $_REQUEST['latlng'];
	$url = "http://maps.googleapis.com/maps/api/geocode/json?&sensor=true&latlng=" . $latLong ;
	$json = file_get_contents($url);
	echo($json);
?>