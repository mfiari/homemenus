
$(function() {
	$.ajax({
		type: "GET",
		url: "webservice/livreur.php",
		dataType: "html",
		data: "ext=json"
	}).done(function( msg ) {
		var livreurs = $.parseJSON(msg);
		if (livreurs.length == 0) {
			return;
		}
		$.each(livreurs, function(indice, livreur) {
			$("#livreurs").append($('<a href="admin_livreur.php?id='+livreur.id+'">'+livreur.nom +'</a>'));
		});
	}).fail(function (jqXHR, textStatus, errorThrown) {
		
	});
	$("#ajout_livreur").submit(function (evt) {
		evt.preventDefault();
		$.ajax({
			type: "POST",
			url: "webservice/index.php?module=user&action=inscription",
			dataType: "html",
			data: $("#ajout_livreur").serialize()
		}).done(function( msg ) {
			location.reload();
		});
	});
});