
var geocoder;
var map;
var latitude;
var longitude;
var autocomplete;

$(function() {
	$("#logout").click(function (evt) {
		$.ajax({
			type: "POST",
			url: "logout.php",
			dataType: "html"
		}).done(function( msg ) {
			location.reload();
		});
	});
	$("#cardMenu").click(function () {
		openCard ();
	});
	$("#ratingButton").click(function () {
		var note = $("#stars-default").attr('data-rating');
		var commentaire = $("#ratingComment").val();
		var id_commande = $("#rating_id_commande").val();
		$.ajax({
			type: "POST",
			url: '?controler=commande&action=noter',
			dataType: "html",
			data: {note : note, commentaire : commentaire, id_commande : id_commande}
		}).done(function( msg ) {
			$("#notation-modal").modal('hide');
		});
	});
});

function openCard () {
	$("#loading-modal").modal();
	$.ajax({
		type: "GET",
		url: '?controler=panier&action=view',
		dataType: "html"
	}).done(function( msg ) {
		$("#loading-modal").modal('hide');
		$("#panier-modal .modal-content").html(msg);
		$("#panier-modal").modal();
	});
}

function initStepper (stepperId) {
	var minValue = $("#"+stepperId).attr('data-min-value');
	var maxValue = $("#"+stepperId).attr('data-max-value');
	$("#"+stepperId+" .stepper-value").val(minValue);
	$("#"+stepperId+" .stepper-less").click(function () {
		var value = parseInt($("#"+stepperId+" .stepper-value").val());
		if (value > minValue) {
			$("#"+stepperId+" .stepper-value").val(value -1);
		}
	});
	$("#"+stepperId+" .stepper-more").click(function () {
		var value = parseInt($("#"+stepperId+" .stepper-value").val());
		if (value < maxValue) {
			$("#"+stepperId+" .stepper-value").val(value +1);
		}
	});
}

function enableAutocomplete (inputId) {
	var options_auto = {
		types: ['address'],
		componentRestrictions: {country: "fr"}
	};
	var input = document.getElementById(inputId);
	if (typeof(google) == "undefined") {
		return;
	}
	autocomplete = new google.maps.places.Autocomplete(input,options_auto);
	console.log(autocomplete);
	$("#"+inputId).keypress(function () {
		var searchVal = $(this).val();
		$(".search-block .glyphicon-refresh-animate").css('display', 'inline-block');
		var service = new google.maps.places.AutocompleteService();
		service.getQueryPredictions({ input: input }, function(predictions, status) {
			$(".search-block .glyphicon-refresh-animate").css('display', 'none');
			/*console.log(predictions);
			console.log(status);
			if (status != google.maps.places.PlacesServiceStatus.OK) {
				return;
			}*/
		});
	});
}

function getAdresseElements () {
	if (autocomplete) {
		var place = autocomplete.getPlace();
		console.log(place);
		
		if (typeof(place) == "undefined") {
			return false;
		}
		
		var addressComponents = {};
		
		for (var i = 0; i < place.address_components.length; i++) {
			for (var j = 0; j < place.address_components[i].types.length; j++) {
				var addressType = place.address_components[i].types[j];
				if (addressType == "street_number") {
					addressComponents.street_number = place.address_components[i].long_name;
				} else if (addressType == "route") {
					addressComponents.route = place.address_components[i].long_name;
				} else if (addressType == "locality") {
					addressComponents.locality = place.address_components[i].long_name;
				} else if (addressType == "country") {
					addressComponents.country = place.address_components[i].long_name;
					addressComponents.country_code = place.address_components[i].short_name;
				} else if (addressType == "postal_code") {
					addressComponents.postal_code = place.address_components[i].long_name;
				}
			}
		}
		var location = place.geometry.location;
		addressComponents.lat = location.lat();
		addressComponents.lon = location.lng();
		console.log(addressComponents);
		
		return addressComponents;
	}
	return false;
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function initialize() {
	latitude = 48.99329;
	longitude = 1.8455;
	geocoder = new google.maps.Geocoder();
	var mapProp = {
		center:new google.maps.LatLng(latitude,longitude),
		zoom:13,
		mapTypeId:google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
}

function initializeGeolocalisation () {
	if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(getUserPosition);
    } else {
        alert("Geolocation is not supported by this browser.");
    }
	if (!map) {
		initialize();
	}
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

function codeAddress(address, content, bounds, displayWindow, icon) {
	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			var position = results[0].geometry.location;
			var options = {
				map: map,
				position: position
			}
			if (typeof(icon) != "undefined") {
				options.icon = icon;
			}
			var marker = new google.maps.Marker(options);
			if (typeof(displayWindow) != "undefined") {
				var infowindow = new google.maps.InfoWindow({
					content:content
				});
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.open(map,marker);
				});
			}
			bounds.extend(position);
			map.fitBounds(bounds);
		}
	});
}

function boundToPoints (list) {
	var bounds = new google.maps.LatLngBounds();
	for (var i = 0; i < list.length; i++) {
		var point = list[i];
		if (point.type == "HOME") {
			if (point.adresse) {
				codeAddress(point.adresse, "", bounds, false, 'res/img/home_marker_bleu.png');
			} else {
				var position = new google.maps.LatLng(point.latitude,point.longitude);
				var marker = new google.maps.Marker({
					map: map,
					position: position,
					icon: 'res/img/home_marker_bleu.png'
				});
				bounds = bounds.extend(position);
				map.fitBounds(bounds);
			}
		} else if (point.type == "ADRESSE") {
			codeAddress(point.adresse, point.content, bounds);
		}
	}
}

function showLoading (divid, hide) {
	if (hide) {
		$("#"+divid).css('display', 'none');
	}
	$("#"+divid).after('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
}

function hideLoading (divid, show) {
	if (show) {
		$("#"+divid).css('display', 'block');
	}
	console.log($("#"+divid).next());
	$("#"+divid).next(".glyphicon-refresh").remove();
}

function playNotificationSong () {
	var audio = new Audio('res/media/sound.mp3');
	audio.play();
}

function playMessageSong () {
	var audio = new Audio('res/media/message.mp3');
	audio.play();
}

function enableRating (id_commande) {
	$("#rating_span_id_commande").html(id_commande);
	$("#rating_id_commande").val(id_commande);
	$("#notation-modal").modal();
	if ($("#stars-default").children().length == 0) {
		$("#stars-default").rating();
	}
}

function openChatBox (id_commande) {
	$.ajax({
		type: "POST",
		url: "?controler=commande&action=getChat&id_commande="+id_commande,
		dataType: "html"
	}).done(function(msg) {
		$("#live-chat").show();
		$("#live-chat").html(msg);
		$('#live-chat header').on('click', function() {
			$('.chat').slideToggle(300, 'swing');
			$('.chat-message-counter').fadeToggle(300, 'swing');
		});
		$('.chat-close').on('click', function(e) {
			e.preventDefault();
			$('#live-chat').fadeOut(300);
		});
	});
}