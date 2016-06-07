
var formContent = {};

var menuContainer;

function initMenu (data) {
	var restaurant = data.restaurant;
	var menu = restaurant.menu;
	
	formContent.id_menu = menu.id;
	formContent.id_restaurant = restaurant.id;
	
	$("#menu-modal .modal-content").html('');
	
	/* Setting header */
	var modalHeader = $('<div />').addClass('modal-header').append('<button type="button" class="close" data-dismiss="modal">&times;</button>');
	var modalHeaderTitle = $('<h2 />').addClass('modal-title').html(menu.nom);
	modalHeader.append(modalHeaderTitle);
	if (menu.commentaire != "") {
		var menuCommentaire = $('<span />').html(menu.commentaire);
		modalHeader.append(menuCommentaire);
	}
	$("#menu-modal .modal-content").append(modalHeader);
				
	/* Setting body content */
				
	$("#menu-modal .modal-content").append(
		$('<div />').addClass('modal-body').append(
			$('<div />').addClass('row').append(
				$('<div />').attr('id', 'menu-resume').addClass('col-md-4').append(
					$('<h3>Votre séléction</h3>')
				)
			).append(
				$('<div />').attr('id', 'menu-content').addClass('col-md-8')
			)
		)
	);
	
	$("#loading-modal").modal('hide');
	$("#menu-modal").modal();
	
	initFormat (menu);
	
}

function initFormat (menu) {
	if (menu.formats.length > 1) {
		var formatDiv = $('<div />').attr('id', 'step').append('<h3>Choisissez votre format</h3>');
		for (var i = 0 ; i < menu.formats.length ; i++) {
			formatDiv.append(
				$('<div />').append(
					$('<div />').append(
						$('<input />').attr('name', 'id_format').attr('type', 'radio').val(menu.formats[i].id).click(
							{format : menu.formats[i]},
							function (event) {
								chooseFormat (event.data.format);
							}
						)
					).append(menu.formats[i].nom)
				).append(
					$('<div />').html('Prix : ' + menu.formats[i].prix + ' €')
				)
			);
		}
		formatDiv.append(
			$('<button />').addClass('btn btn-primary').html('Suivant').click(function () {
				if (!formContent.format) {
					alert('format non choisit');
				} else {
					validateFormat (menu);
				}
			})
		)
		$('#menu-content').append(formatDiv);
	} else {
		chooseFormat (menu.formats[0]);
		initFormule (menu);
	}
}

function chooseFormat (format) {
	formContent.format = {};
	formContent.format.id = format.id;
	formContent.format.nom = format.nom;
	formContent.format.prix = format.prix;
	formContent.chooseFormat = true;
	console.log(formContent.format);
}

function validateFormat (menu) {
	$('#menu-resume').append(
		$('<hr />')
	).append(
		$('<span />').html(formContent.format.nom + ' (' + formContent.format.prix + '€)')
	);
	initFormule (menu);
}

function initFormule (menu) {
	if (menu.formules.length > 1) {
		var formuleDiv = $('<div />').attr('id', 'step-').append('<h3>Choisissez votre formule</h3>');
		for (var i = 0 ; i < menu.formules.length ; i++) {
			formuleDiv.append(
				$('<div />').append(
					$('<div />').append(
						$('<input />').attr('name', 'id_format').attr('type', 'radio').val(menu.formules[i].id)
					).append(menu.formules[i].nom)
				)
			);
		}
		formuleDiv.append('<button class="btn btn-primary">Précédent</button>');
		formuleDiv.append('<button class="btn btn-primary">Suivant</button>');
		$('#menu-content').append(formuleDiv);
	} else {
		chooseFormule (menu.formules[0]);
		initCategorie (menu.formules[0], 0);
	}
}

function chooseFormule (formule) {
	formContent.formule = {};
	formContent.formule.id = formule.id;
	formContent.formule.nom = formule.nom;
	formContent.chooseFormule = true;
	console.log(formContent.formule);
}

function validateFormule (formule) {
	$('#menu-resume').append(
		$('<hr />')
	).append(
		$('<span />').html(formContent.formule.nom)
	);
	initCategorie (formule, 0);
}

function initCategorie (formule, index) {
	if (formule.categories.length > index) {
		var categorie = formule.categories[index];
		var categorieDiv = $('<div />').css('max-height', '500px').css('overflow-y', 'auto').attr('id', 'step-').append('<h3>Choisissez votre ' + categorie.nom + '</h3>');
		for (var j = 0 ; j < categorie.contenus.length ; j++) {
			categorieDiv.append(
				$('<div />').addClass('col-md-12').append(
					$('<div />').append(
						$('<input />').attr('name', 'contenu_'+categorie.id).attr('type', 'radio').val(categorie.contenus[j].id).click(
							{categorie : categorie, contenus : categorie.contenus[j], index : index},
							function (event) {
								chooseCategorie (event.data.categorie, event.data.contenus, event.data.index);
							}
						)
					).append(
						$('<span />').html(categorie.contenus[j].carte.nom).css('margin-left', '10px')
					).append(
						$('<img />').attr('src', categorie.contenus[j].carte.logo).css('width', '80px')
					)
				)
			);
		}
		categorieDiv.append(
			$('<button />').addClass('btn btn-primary').html('Précédent').click(
				{formule : formule, index : index},
				function (event) {
					if (index == 0) {
						
					} else {
						initCategorie (event.data.formule, event.data.index -1);
					}
				}
			)
		);
		categorieDiv.append(
			$('<button />').addClass('btn btn-primary').html('Suivant').click(
				{formule : formule, index : index},
				function (event) {
					if (!formContent.categorie[event.data.index]) {
						alert('contenu non choisit');
					} else {
						validateCategorie (event.data.formule, event.data.index);
					}
				}
			)
		);
		$('#menu-content').html('');
		$('#menu-content').append(categorieDiv);
	} else {
		resume();
	}
}

function chooseCategorie (categorie, contenu, index) {
	if (!formContent.categorie) {
		formContent.categorie = [];
	}
	formContent.categorie[index] = {};
	formContent.categorie[index].id = categorie.id;
	formContent.categorie[index].nom = categorie.nom;
	formContent.categorie[index].contenu = {};
	formContent.categorie[index].contenu.id = contenu.id;
	formContent.categorie[index].contenu.id_carte = contenu.carte.id;
	formContent.categorie[index].contenu.nom = contenu.carte.nom;
	console.log(formContent.categorie[index]);
}

function validateCategorie (formule, index) {
	$('#menu-resume').append(
		$('<hr />')
	).append(
		$('<h4 />').html(formContent.categorie[index].nom)
	).append(
		$('<span />').html(formContent.categorie[index].contenu.nom)
	);
	initCategorie (formule, index+1);
}

function resume () {
	
	var stepper = $('<div />').attr('id', 'stepper').addClass('stepper').attr('data-min-value', '1').attr('data-max-value', '5').append(
		'<label>Quantite</label>' + 
		'<a class="stepper-less stepper-button">-</a>' + 
		'<input type="text" name="quantite" value="0" class="stepper-value">' + 
		'<a class="stepper-more stepper-button">+</a>'
	);
	
	$('#menu-content').hide();
	$('#menu-resume').removeClass('col-md-4').addClass('col-md-12');
	
	$('#menu-resume').after(
		$('<div />').addClass('col-md-12').append(
			$('<button />').addClass('btn btn-primary').html('Précédent').click(function () {
			})
		).append(
			$('<button />').addClass('btn btn-primary').html('Valider').click(function () {
				
				var sendData = {};
				sendData.id_menu = formContent.id_menu;
				sendData.id_restaurant = formContent.id_restaurant;
				sendData.quantite = 1;
				sendData.id_format = formContent.format.id;
				sendData.id_formule = formContent.formule.id;
				for (var i = 0 ; i < formContent.categorie.length ; i++) {
					sendData['contenu_'+formContent.categorie[i].id] = formContent.categorie[i].contenu.id;
				}
				
				$.ajax({
					type: "POST",
					url: "?controler=panier&action=addMenu",
					dataType: "html",
					data: sendData
				}).done(function( msg ) {
					$("#menu-modal .modal-footer .glyphicon-refresh-animate").css('display', 'none');
					$("#menu-modal .modal-footer div.alert-success").css('display', 'inline-block');
					setTimeout(function(){ 
						$("#menu-modal").modal('hide');
						location.reload();
					}, 2000);
				}).error(function(msg) {
					$("#menu-modal .modal-footer .glyphicon-refresh-animate").css('display', 'none');
					$("#menu-modal .modal-footer div.alert-danger").css('display', 'inline-block');
				});
			})
		)
	).before(stepper);
	
	initStepper ("stepper");
}