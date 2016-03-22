
function accepteCommande (user, commande) {
	$.ajax({
		type: "POST",
		url: "webservice/index.php?module=commande&action=validationLivreur",
		dataType: "html",
		data: "id_user="+user+"&id_commande="+commande
	}).done(function( msg ) {
		location.reload();
	});
}

function valideMenu (user, menu) {
	$.ajax({
		type: "POST",
		url: "webservice/index.php?module=commande&action=validationMenuRestaurant",
		dataType: "html",
		data: "id_user="+user+"&id_menu="+menu
	}).done(function( msg ) {
		location.reload();
	});
}

function valideCommande (user, commande) {
	$.ajax({
		type: "POST",
		url: "webservice/index.php?module=commande&action=validationCommandeRestaurant",
		dataType: "html",
		data: "id_user="+user+"&id_commande="+commande
	}).done(function( msg ) {
		location.reload();
	});
}