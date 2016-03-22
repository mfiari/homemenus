
var geocoder;
var map;
var latitude;
var longitude;

$(function() {
	/*initialize();*/
	$.ajax({
		type: "GET",
		url: "webservice/index.php",
		dataType: "html",
		data: "module=restaurant&action=all&ext=json"
	}).done(function( msg ) {
		var result = $.parseJSON(msg);
		if (result.length == 0) {
			return;
		}
		$.each(result, function(indice, restaurant) {
			$("#restaurants").append($('<a href="admin_restaurant.php?id='+restaurant.id+'">'+restaurant.nom +' ('+restaurant.ville+')</a><br />'));
			/*var adresse = restaurant.rue + ', ' + restaurant.ville;
			codeAddress(adresse, restaurant.nom);*/
		});
	}).fail(function (jqXHR, textStatus, errorThrown) {
		
	});
});

function initialize() {
	latitude = 48.99329;
	longitude = 1.8455;
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(getUserPosition);
    } else {
        alert("Geolocation is not supported by this browser.");
    }
	geocoder = new google.maps.Geocoder();
	var mapProp = {
		center:new google.maps.LatLng(latitude,longitude),
		zoom:11,
		mapTypeId:google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
}

function getUserPosition(position) {
	latitude = position.coords.latitude;
	longitude = position.coords.longitude;
	var marker = new google.maps.Marker({
		map: map,
		position: new google.maps.LatLng(latitude,longitude),
		icon: 'res/img/home_marker_bleu.png'
	});
	map.setCenter({lat: latitude, lng: longitude});
}

function codeAddress(address, content) {
	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
			var marker = new google.maps.Marker({
				map: map,
				position: results[0].geometry.location
			});
			var infowindow = new google.maps.InfoWindow({
				content:content
			});
			google.maps.event.addListener(marker, 'click', function() {
				infowindow.open(map,marker);
			});
		} else {
			alert("Geocode was not successful for the following reason: " + status);
		}
	});
}
