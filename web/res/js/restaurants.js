


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
			$("#restaurants").append($('<a href="restaurant.php?id='+restaurant.id+'">'+restaurant.nom +' ('+restaurant.ville+')</a><br />'));
			/*var adresse = restaurant.rue + ', ' + restaurant.ville;
			codeAddress(adresse, restaurant.nom);*/
		});
	}).fail(function (jqXHR, textStatus, errorThrown) {
		
	});
});
