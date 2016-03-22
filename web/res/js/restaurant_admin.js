
var formules;
var fieldsCount = {};

$(function() {
	desactivateAll ();
	var id = getParameterByName("id");
	$.ajax({
		type: "GET",
		url: "webservice/index.php",
		dataType: "html",
		data: "module=restaurant&action=one&id="+id+"&ext=json"
	}).done(function( msg ) {
		var restaurant = $.parseJSON(msg);
		if (restaurant.length == 0) {
			return;
		}
		$("#restaurant").append($('<p>'+restaurant.nom +'</p>'));
	}).fail(function (jqXHR, textStatus, errorThrown) {
		
	});
	$.ajax({
		type: "GET",
		url: "webservice/menu.php",
		dataType: "html",
		data: "id_restaurant="+id+"&ext=json"
	}).done(function( msg ) {
		var menus = $.parseJSON(msg);
		if (menus.length == 0) {
			return;
		}
		$.each(menus, function(indice, menu) {
			$("#menus").append($('<a href="admin_menu.php?id='+menu.id+'">'+menu.nom +'  '+menu.prix+'€  '+menu.preparation+' min</a><br />'));
		});
	}).fail(function (jqXHR, textStatus, errorThrown) {
		
	});
	$.ajax({
		type: "GET",
		url: "webservice/menu.php",
		dataType: "html",
		data: "ext=json"
	}).done(function( msg ) {
		formules = $.parseJSON(msg);
		if (formules.length == 0) {
			return;
		}
		$("#formule").on('change', function() {
			changeFormule (this.value);
		});
		$.each(formules, function(code, values) {
			$("#formule").append('<option value="'+code+'">'+values.nom+'</option>');
		});
		changeFormule ($("#formule").val());
	}).fail(function (jqXHR, textStatus, errorThrown) {
		
	});
	$("#ajout_user_restaurant").submit(function (evt) {
		evt.preventDefault();
		$.ajax({
			type: "POST",
			url: "webservice/index.php?module=user&action=inscription",
			dataType: "html",
			data: $("#ajout_user_restaurant").serialize()
		}).done(function( msg ) {
			location.reload();
		});
	});
});

function changeFormule (formule) {
	desactivateAll ();
	var categories = formules[formule].categorie;
	for (var i = 0 ; i < categories.length ; i++) {
		acivateCategorie (categories[i]);
	}
}

function desactivateAll () {
	$("#menu_aucun").hide();
	$("#menu_aucun .fields").empty();
	fieldsCount["aucun"] = 0;
	$("#menu_entree").hide();
	$("#menu_entree .fields").empty();
	fieldsCount["entree"] = 0;
	$("#menu_plat").hide();
	$("#menu_plat .fields").empty();
	fieldsCount["plat"] = 0;
	$("#menu_dessert").hide();
	$("#menu_dessert .fields").empty();
	fieldsCount["dessert"] = 0;
	$("#menu_boisson").hide();
	$("#menu_boisson .fields").empty();
	fieldsCount["boisson"] = 0;
}

function acivateCategorie (categorie) {
	switch (categorie.id) {
		case "1":
			activateComplet ();
			break;
		case "2":
			activateEntree ();
			break;
		case "3":
			activatePlat ();
			break;
		case "4":
			activateDessert ();
			break;
		case "5":
			activateBoisson ();
			break;
	}
}

function activateComplet () {
	$("#menu_aucun").show();
}

function activateEntree () {
	$("#menu_entree").show();
}

function activatePlat () {
	$("#menu_plat").show();
}

function activateDessert () {
	$("#menu_dessert").show();
}

function activateBoisson () {
	$("#menu_boisson").show();
}

function addField (divId, type) {
	var html = "<div>";
	var name = type+'_'+fieldsCount[type];
	html += '<p><b>'+type+' '+fieldsCount[type]+'</b></p>';
	html += '<label for="'+name+'">Nom : </label>';
	html += '<input class="form-control" name="'+name+'" type="text" />';
	html += '<input type="checkbox" name="check_'+name+'"> obligatoire <br />';
	html += '<label for="commentaire_'+name+'">Commentaire : </label>';
	html += '<textarea rows="3" name="commentaire_'+name+'"></textarea>';
	html += "</div>";
	$(divId).append(html);
	fieldsCount[type]++;
}