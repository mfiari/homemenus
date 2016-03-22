
$(function() {
	var id = getParameterByName("id");
	$.ajax({
		type: "GET",
		url: "webservice/index.php",
		dataType: "html",
		data: "module=menu&action=one&id="+id+"&ext=json"
	}).done(function( msg ) {
		var menu = $.parseJSON(msg);
		if (menu.length == 0) {
			return;
		}
		var html = '<h2>'+menu.nom+'</h2>';
		html += '<p>Prix : '+menu.prix+' €</p>';
		html += '<p>Temps de préparation : '+menu.preparation+' min</p>';
		html += '<p>'+menu.commentaire+'</p>';
		var formulaire = "";
		$.each(menu.categories, function(id, categorie) {
			if (id != 1) {
				formulaire += '<h3>'+categorie.nom+'</h3>';
				var operation;
				if (categorie.quantite == 1) {
					operation = function (id_categorie, categorie, contenu) {
						return '<input type="radio" name="'+id_categorie+'" value="'+contenu.id+'">'+contenu.nom+'<br />';
					};
				} else {
					operation = function (id_categorie, categorie, contenu) {
						var select = '<select name="select'+id_categorie+'_'+contenu.id+'">';
						for (var i = 1 ; i <= categorie.quantite ; i++) {
							select += '<option value="'+i+'">'+i+'</option>';
						}
						select += '</select>';
						return '<input type="checkbox" name="'+id_categorie+'_'+contenu.id+'" value="'+contenu.id+'">'+contenu.nom+select+'<br />';
					};
				}
				$.each(categorie.contenus, function(indice, contenu) {
					if (contenu.obligatoire == "1") {
						formulaire += '<input type="checkbox" disabled="disabled" name="'+id+'_'+contenu.id+'" value="'+contenu.id+'" checked>'+contenu.nom+'<br />';
						formulaire += '<input type="text" hidden="hidden" name="'+id+'_'+contenu.id+'" value="'+contenu.id+'" />';
					} else {
						formulaire += operation(id, categorie, contenu);
					}
					if (contenu.commentaire != "") {
						formulaire += '<p>('+contenu.commentaire+')</p>';
					}
				});
			} else {
				formulaire += '<h3>Menu</h3>';
				$.each(categorie.contenus, function(indice, contenu) {
					formulaire += '<p>'+contenu.nom+'<p/>';
					if (contenu.commentaire != "") {
						formulaire += '<p>('+contenu.commentaire+')</p>';
					}
				});
			}
		});
		$("#menu").append(html);
		$("#ajout_panier button").before(formulaire);
	}).fail(function (jqXHR, textStatus, errorThrown) {
		
	});
	$("#ajout_panier").submit(function (evt) {
		evt.preventDefault();
		var id_user = $("#id_user_field").val();
		if (id_user == "") {
			alert("Vous devez être connecter pour commander");
		} else {
			$.ajax({
				type: "POST",
				url: "webservice/index.php?module=panier&action=ajouter",
				dataType: "html",
				data: $("#ajout_panier").serialize()
			}).done(function( msg ) {
				panier();
			});
		}
	});
});