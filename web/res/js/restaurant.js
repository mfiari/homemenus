
$(function() {
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
		$("#restaurant").append($('<h1>'+restaurant.nom +'</h1>'));
	}).fail(function (jqXHR, textStatus, errorThrown) {
		
	});
	$.ajax({
		type: "GET",
		url: "webservice/index.php",
		dataType: "html",
		data: "module=menu&action=getByRestaurant&id_restaurant="+id+"&ext=json"
	}).done(function( msg ) {
		var menus = $.parseJSON(msg);
		if (menus.length == 0) {
			return;
		}
		/* ON créer un tableau*/
		var table = '<table class="table table-striped"><thead><tr><th>Nom</th><th>Prix (en €)</th><th>Temps de préparation (en min)</th></tr></thead><tbody>';
		$.each(menus, function(indice, menu) {
			/*$("#menus").append($('<a href="menu.php?id='+menu.id+'">'+menu.nom +'  '+menu.prix+'€  '+menu.preparation+' min</a><br />'));*/
			table += '<tr><td><a href="menu.php?id='+menu.id+'">'+menu.nom+'</a></td><td>'+menu.prix+'€  </td><td>'+menu.preparation+' min </td></tr>';
		});
		table += "</tbody></table>";
		$("#menus").html(table);
	}).fail(function (jqXHR, textStatus, errorThrown) {
		
	});
});