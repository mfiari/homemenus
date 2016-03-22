
$(function() {
	var id = getParameterByName("id");
	$.ajax({
		type: "GET",
		url: "webservice/livreur.php",
		dataType: "html",
		data: "id="+id+"&ext=json"
	}).done(function( msg ) {
		var livreur = $.parseJSON(msg);
		if (livreur.length == 0) {
			return;
		}
		$("#livreur").append($('<p>'+livreur.nom +'</p>'));
	}).fail(function (jqXHR, textStatus, errorThrown) {
		
	});
});