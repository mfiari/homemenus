<?php 
	$restaurant = $request->restaurant;
	$livreurs = $request->livreurs;
	$horaire = $restaurant->horaire;
	$horaires = $restaurant->horaires;
	$current_heure = date('G')+GTM_INTERVAL;
	$current_minute = date('i');
	
	$horaires_dispo_livreur = array();
	
	for ($i = 0 ; $i < 24 ; $i++) {
		$livreurDispo = false;
		$minute_debut = 59;
		$minute_fin = 0;
		foreach ($livreurs as $livreur) {
			foreach ($livreur->dispos as $dispo) {
				if ($i >= $dispo->heure_debut && $i <= $dispo->heure_fin) {
					$livreurDispo = true;
					if ($i == $dispo->heure_debut) {
						if ($minute_debut > $dispo->minute_debut) {
							$minute_debut = $dispo->minute_debut;
							$minute_fin = 59;
						}
					} else if ($i == $dispo->heure_fin) {
						if ($minute_fin < $dispo->minute_fin) {
							$minute_fin = $dispo->minute_fin;
							$minute_debut = 0;
						}
					} else {
						$minute_debut = 0;
						$minute_fin = 59;
					}
				}
			}
		}
		if ($livreurDispo) {
			$horaires_dispo_livreur[] = array(
				"livreur" => $livreurDispo,
				"heure" => $i,
				"minute_debut" => $minute_debut,
				"minute_fin" => $minute_fin,
			);
		}
		$livreurDispo = false;
	}
	
	$horaires_final = array();
	foreach ($horaires_dispo_livreur as $horaire_dispo_livreur) {
		foreach ($horaires as $h) {
			if ($horaire_dispo_livreur['heure'] >= $h->heure_debut && $horaire_dispo_livreur['heure'] <= $h->heure_fin && $horaire_dispo_livreur['heure'] >= $current_heure) {
				if ($horaire_dispo_livreur['heure'] == $h->heure_debut) {
					if ($horaire_dispo_livreur['minute_debut'] < $h->minute_debut) {
						$horaire_dispo_livreur['minute_debut'] = $h->minute_debut;
					}
				}
				if ($horaire_dispo_livreur['heure'] == $h->heure_fin) {
					if ($horaire_dispo_livreur['minute_fin'] > $h->minute_fin) {
						$horaire_dispo_livreur['minute_fin'] = $h->minute_fin;
					}
				}
				if ($horaire_dispo_livreur['heure'] == $current_heure) {
					if ($horaire_dispo_livreur['minute_debut'] < $current_minute) {
						$horaire_dispo_livreur['minute_debut'] = $current_minute;
					} else if ($horaire_dispo_livreur['minute_fin'] > $current_minute) {
						$horaire_dispo_livreur['minute_fin'] = $current_minute;
					}
				}
				$horaires_final[] = $horaire_dispo_livreur;
			}
		}
	}
	
?>
<script type="text/javascript">
	var horaires_final = new Array();
	<?php foreach ($horaires_final as $horaire_final) : ?>
		var ligne = {};
		ligne.heure = <?php echo $horaire_final['heure']; ?>;
		ligne.minute_debut = <?php echo $horaire_final['minute_debut']; ?>;
		ligne.minute_fin = <?php echo $horaire_final['minute_fin']; ?>;
		horaires_final.push(ligne);
	<?php endforeach; ?>
</script>
<input id="id_restaurant" value="<?php echo $restaurant->id; ?>" hidden="hidden" />

<div class="row">
	<a style="float : left;" class="" href="restaurants.html">
		<span style="margin-right: 10px;" class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	</a>
	<h1><?php echo utf8_encode($restaurant->nom); ?></h1>
</div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12" style="text-align : left;">
		<?php if (!$restaurant->has_livreur_dispo) : ?>
			<span style="color : #FF0000;">Pas de livreur disponible</span>
		<?php elseif (($horaire->heure_debut > $current_heure) || 
				($horaire->heure_debut == $current_heure && $horaire->minute_debut > $current_minute) ||
				($horaire->heure_debut < $current_heure && $horaire->heure_fin < $current_heure) ||
				($horaire->heure_fin == $current_heure && $horaire->minute_fin < $current_minute)
		) : ?>
			<span style="color : #FF0000;">Restaurant fermé</span>
		<?php elseif (!$horaires) : ?>
			<span style="color : #FF0000;">Restaurant fermé</span>
		<?php else : ?>
			<span style="color : #00FF00;">Restaurant ouvert</span>
		<?php endif; ?>
	</div>
</div>
<div id="restaurant-block" class="row" style="background-color : #F4F4F4;">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<img style="width : 100%" src="<?php echo getLogoRestaurant($restaurant->id); ?>" alt="HoMe Menus - <?php echo utf8_encode($restaurant->nom); ?>" Title="HoMe Menus - <?php echo utf8_encode($restaurant->nom); ?>">
	</div>
	<div class="col-md-12 col-sm-12 col-xs-12" style="font-size : 12px;">
		<span><b><?php echo utf8_encode($restaurant->rue); ?>, <?php echo $restaurant->code_postal; ?> <?php echo $restaurant->ville; ?></b></span><br /><br />
		<span><?php echo utf8_encode($restaurant->short_desc); ?></span><br /><br />
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<hr />
				<div class="row">
					<div class="col-md-5 col-sm-5 col-xs-5">
						<span><b>Horaires d'ouverture</b></span>
					</div>
					<div class="col-md-7 col-sm-7 col-xs-7">
						<span>
							<?php 
								$link = '';
								foreach ($horaires as $h) {
									echo $link.' '.formatHeureMinute($h->heure_debut,$h->minute_debut).' - '.formatHeureMinute($h->heure_fin,$h->minute_fin);
									$link = ' et de ';
								}
							?>
						</span>
					</div>
				</div>
				<hr />
				<div class="row">
					<div class="col-md-5 col-sm-5 col-xs-5">
						<span><b>Distance</b></span>
					</div>
					<div class="col-md-7 col-sm-7 col-xs-7">
						<span><?php echo $restaurant->distance; ?> KM</span>
					</div>
				</div>
				<hr />
				<div class="row">
					<div class="col-md-5 col-sm-5 col-xs-5">
						<span><b>Prix de livraison</b></span>
					</div>
					<div class="col-md-7 col-sm-7 col-xs-7">
						<span><?php echo formatPrix($request->prix_livraison); ?></span>
					</div>
				</div>
				<hr />
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div>
					<?php foreach ($restaurant->certificats as $certificat) : ?>
						<a href="<?php echo $certificat->url; ?>" target="_blank" data-toggle="tooltip" title="<?php echo utf8_encode($certificat->description); ?>">
							<img  style="width : 70px" src="res/img/<?php echo $certificat->logo; ?>">
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="restaurant" class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<?php foreach ($restaurant->categories as $categorie) : ?>
			<?php if (count($categorie->contenus) > 0) : ?>
				<h3 id="categorie-<?php echo $categorie->id; ?>"><b>
					<?php 
						if ($categorie->parent_categorie != '') {
							echo utf8_encode($categorie->parent_categorie->nom).' : ';
						}
						echo utf8_encode($categorie->nom);
					?>
				</b></h3>
				<?php foreach ($categorie->contenus as $contenu) : ?>
					<hr />
					<div data-id="<?php echo $contenu->id; ?>" class="row carte-item">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-md-7 col-sm-7 col-xs-7">
									<p class="carte-item-title"><?php echo utf8_encode($contenu->nom); ?></p>
									<span><i><?php echo utf8_encode($contenu->commentaire); ?></i></span>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-3">
									<b><?php echo formatPrix($contenu->prix); ?></b>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-2">
									<span class="add-button" style="display : none;">+</span>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php if (count($restaurant->menus) > 0) : ?>
			<div class="row">
				<h3 id="categorie-menu">Menus</h3>
				<?php foreach ($restaurant->menus as $menu) : ?>
					<hr />
					<div data-id="<?php echo $menu->id; ?>" class="row menu-item">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<p class="carte-item-title"><?php echo utf8_encode($menu->nom); ?></p>
							<div class="row">
								<div class="col-md-4 col-sm-4 col-xs-4">
									<div class="row">
										<div class="vignette"><img src="<?php echo $menu->logo; ?>"></div>
									</div>
									<div class="row">
										<span>Prix : <?php echo formatPrix($menu->prix); ?></span>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<?php echo utf8_encode($menu->commentaire); ?>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-2">
									<span class="add-button" style="display : none;">+</span>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<div id="panier">
	<a href="index.php?controler=restaurant&action=panier">Voir le panier</a>
</div>
<div id="carte-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
		</div>
	</div>
</div>
<div id="menu-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
		</div>
	</div>
</div>
<div id="commentaire-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		$(".carte-item").click(function () {
			$("#loading-modal").modal();
			var id_carte = $(this).attr('data-id');
			var id_restaurant = $("#id_restaurant").val();
			$.ajax({
				type: "GET",
				url: 'index.php?controler=restaurant&action=carte&id='+id_restaurant+'&id_carte='+id_carte,
				dataType: "html"
			}).done(function( msg ) {
				$("#loading-modal").modal('hide');
				$("#carte-modal").modal();
				$("#carte-modal .modal-content").html(msg);
			});
		});
		
		$(".menu-item").click(function () {
			$("#loading-modal").modal();
			var id_menu = $(this).attr('data-id');
			var id_restaurant = $("#id_restaurant").val();
			$.ajax({
				type: "GET",
				url: '<?php echo WS_URL; ?>index.php?module=restaurant&action=menu&id='+id_restaurant+'&id_menu='+id_menu+'&ext=json',
				dataType: "html"
			}).done(function( msg ) {
				var data = $.parseJSON(msg);
				initMenu (data);
			});
		});
		
		initDeleteCarteItem ();
		initDeleteMenuItem ();
		initPanierCommande();
		initCodePromo ();
		initHoraireCommande ();
		
		$(".stars-default").each(function () {
			$(this).rating('create', {readonly : true});
		});
		
	});
	
	function initHoraireCommande () {
		for (var i = 0 ; i < horaires_final.length ; i++) {
			$("select#heure_commande").append($('<option />').html(horaires_final[i].heure));
		}
		for (var j = horaires_final[0].minute_debut ; j <= horaires_final[0].minute_fin ; j++) {
			$("select#minute_commande").append($('<option />').html(j));
		}
		$("select#heure_commande").change(function() {
			var heure = $(this).val();
			$("select#minute_commande").html('');
			for (var i = 0 ; i < horaires_final.length ; i++) {
				if (horaires_final[i].heure == heure) {
					for (var j = horaires_final[i].minute_debut ; j <= horaires_final[i].minute_fin ; j++) {
						$("select#minute_commande").append($('<option />').html(j));
					}
				}
			}
		});
	}
	
	function initDeleteCarteItem () {
		$(".carte-item-delete").click(function(event) {
			$("#loading-modal").modal();
			var id_panier = $("#id_panier").val();
			var id = $(this).attr('data-id');
			$.ajax({
				type: "POST",
				url: "index.php?controler=panier&action=removeCarte",
				dataType: "html",
				data: {id_panier : id_panier, id_panier_carte : id}
			}).done(function( msg ) {
				reloadPanier ();
				$("#loading-modal").modal('hide');
			}).error(function(msg) {
				alert("error");
				$("#loading-modal").modal('hide');
			});
		});
	}
	
	function initDeleteMenuItem () {
		$(".menu-item-delete").click(function(event) {
			$("#loading-modal").modal();
			var id_panier = $("#id_panier").val();
			var id = $(this).attr('data-id');
			$.ajax({
				type: "POST",
				url: "index.php?controler=panier&action=removeMenu",
				dataType: "html",
				data: {id_panier : id_panier, id_panier_menu : id}
			}).done(function( msg ) {
				reloadPanier ();
				$("#loading-modal").modal('hide');
			}).error(function(msg) {
				alert("error");
				$("#loading-modal").modal('hide');
			});
		});
	}
	
	function initCodePromo () {
		$("#codePromoButton").click(function () {
			$("#loading-modal").modal();
			var codePromo = $("#code_promo").val();
			$.ajax({
				type: "POST",
				url: "index.php?controler=panier&action=addCodePromo",
				dataType: "html",
				data: {code_promo : codePromo}
			}).done(function( msg ) {
				reloadPanier ();
				$("#loading-modal").modal('hide');
			}).error(function(jqXHR, textStatus, errorThrown) {
				switch (jqXHR.status) {
					case 400 :
						$("#codePromoBlock div.alert-danger span.message").html("Le code promo n'est pas applicable sur ce restaurant.");
						break;
					case 401 :
						$("#codePromoBlock div.alert-danger span.message").html("Vous n'êtes pas autorisé à utiliser ce code promo.");
						break;
					case 403 :
						$("#codePromoBlock div.alert-danger span.message").html("Veuillez vous connecter pour utiliser ce code promo.");
						break;
					case 404 :
						$("#codePromoBlock div.alert-danger span.message").html("Ce code promo n'existe pas.");
						break;
					case 410 :
						$("#codePromoBlock div.alert-danger span.message").html("Vous avez déjà utilisé ce code promo.");
						break;
					default :
						$("#codePromoBlock div.alert-danger span.message").html("Une erreur est survenu, veuillez réessayé.");
						break;
				}
				$("#codePromoBlock div.alert-danger").css('display', 'block');
				$("#loading-modal").modal('hide');
			});
		});
	}
	
	function initPanierCommande () {
		$("#panier #command").click(function(event) {
			event.preventDefault();
			var type_commande = $('#panierForm input[name=type_commande]').val();
			var heure_commande = $('#panierForm select[name=heure_commande]').val();
			var minute_commande = $('#panierForm select[name=minute_commande]').val();
			
			$("#panier-info-modal #type_commande").val(type_commande);
			$("#panier-info-modal #heure_commande").val(heure_commande);
			$("#panier-info-modal #minute_commande").val(minute_commande);
			$("#panier-info-modal").modal();
		});
	}
	
	function reloadPanier () {
		$.ajax({
			type: "GET",
			url: 'index.php?controler=restaurant&action=panier',
			dataType: "html"
		}).done(function( msg ) {
			$("#panier-content").html(msg);
			initDeleteCarteItem ();
			initDeleteMenuItem ();
			initPanierCommande();
			initCodePromo ();
			initHoraireCommande ();
		});
	}
	
	function openCommentairesRestaurant (id_resto) {
		$("#loading-modal").modal();
		$.ajax({
			type: "GET",
			url: 'index.php?controler=notes&action=viewRestaurant&id_restaurant='+id_resto,
			dataType: "html"
		}).done(function( msg ) {
			$("#loading-modal").modal('hide');
			$("#commentaire-modal .modal-body").html(msg);
			$("#commentaire-modal").modal();
		});
	}
	
	function openCommentaires (id_carte) {
		$("#loading-modal").modal();
		$.ajax({
			type: "GET",
			url: 'index.php?controler=notes&action=viewCarte&id_carte='+id_carte,
			dataType: "html"
		}).done(function( msg ) {
			$("#loading-modal").modal('hide');
			$("#commentaire-modal .modal-body").html(msg);
			$("#commentaire-modal").modal();
		});
	}
	
	$(window).scroll(function(){
		if ($(this).scrollTop() > 450) {
			$('#restaurant .categories').addClass('fixed');
			$('#restaurant .panier').addClass('fixed');
		} else {
			$('#restaurant .categories').removeClass('fixed');
			$('#restaurant .panier').removeClass('fixed');
		}
	});
</script>
<style>
	#restaurant .vignette img{
		height: 80px;
		width: 80px;
	}
	
	#restaurant .carte-item:hover {
		background-color : #F4F4F4;
		cursor : pointer;
	}
	
	#restaurant .carte-item .add-button {
		display : block !important;
		background-color: #FF2A00;
		color: #FFFFFF;
		font-size: 28px;
		border-radius : 20px;
		text-align : center;
		width : 40px;
	}
	
	#restaurant .carte-item-title {
		font-size : 16px;
		text-align : center;
	}
	
	#restaurant .menu-item:hover {
		background-color : #F4F4F4;
		cursor : pointer;
	}
	
	#restaurant .menu-item .add-button {
		display : block !important;
		background-color: #FF2A00;
		color: #FFFFFF;
		font-size: 28px;
		border-radius : 20px;
		text-align : center;
		width : 40px;
	}
	
	#restaurant .menu-item-title {
		font-size : 16px;
		text-align : center;
	}
	
	#restaurant-block hr {
		margin-top : 5px;
		margin-bottom : 5px;
	}
	
	#restaurant .fixed {
		position: fixed; 
		top : 0;
	}
	
	#panier {
		bottom : 20px;
		color : #FFFFFF;
		position : fixed;
		text-align : center;
		width : 100%;
		z-index : 200;
	}
	
	#panier a {
		background-color : #CCCCCC;
		-moz-border-radius:16px;
		-webkit-border-radius:16px;
		border-radius:16px;
		border:1px solid #CCCCCC;
		font-size : 20px;
	}
	
	
</style>