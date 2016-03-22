
$(function() {
	var id = getParameterByName("id");
	$.ajax({
		type: "GET",
		url: "webservice/menu.php",
		dataType: "html",
		data: "id="+id+"&ext=json"
	}).done(function( msg ) {
		var menu = $.parseJSON(msg);
		if (menu.length == 0) {
			return;
		}
		var html = '<h2>'+menu.nom+'</h2>';
		html += '<p>Prix : '+menu.prix+' €</p>';
		html += '<p>Temps de préparation : '+menu.preparation+' min</p>';
		html += '<p>'+menu.commentaire+'</p>';
		$.each(menu.categories, function(id, categorie) {
			if (id != 1) {
				html += '<h3>'+categorie.nom+'</h3>';
			} else {
				html += '<h3>Menu</h3>';
			}
			$.each(categorie.contenus, function(indice, contenu) {
				html += '<p>'+contenu.nom+'</p>';
				if (contenu.commentaire != "") {
					html += '<p>('+contenu.commentaire+')</p>';
				}
			});
		});
		$("#menu").append(html);
	}).fail(function (jqXHR, textStatus, errorThrown) {
		
	});
});