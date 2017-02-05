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
	<a style="float : left;" class="" href="?controler=restaurant&action=recherche">
		<span style="margin-right: 10px;" class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	</a>
	<h1><?php echo utf8_encode($restaurant->nom); ?></h1>
</div>
<div class="row">
	<div class="col-md-6 col-sm-6" style="text-align : left;">
		<?php if (!$restaurant->has_livreur_dispo) : ?>
			<span style="color : #FF0000;">Pas de livreur disponible</span>
		<?php elseif ($horaire->heure_debut > $current_heure || ($horaire->heure_debut == $current_heure && $horaire->minute_debut > $current_minute)) : ?>
			<span style="color : #FF0000;">Restaurant fermé</span>
		<?php else : ?>
			<span style="color : #00FF00;">Restaurant ouvert</span>
		<?php endif; ?>
	</div>
	<div class="col-md-6 col-sm-6" style="text-align : right;">
		<?php if ($restaurant->commentaire == 0) : ?>
			<?php echo $restaurant->note; ?> / 5 (<?php echo $restaurant->nb_note; ?> vote(s)) - 0 commentaire
		<?php else : ?>
			<a onclick="openCommentairesRestaurant(<?php echo $restaurant->id; ?>)">
				<?php echo $restaurant->note; ?> / 5 (<?php echo $restaurant->nb_note; ?> vote(s)) - <?php echo $restaurant->commentaire; ?> commentaire(s)
			</a>
		<?php endif; ?>
	</div>
</div>
<div id="restaurant-block" class="row" style="background-color : #F4F4F4;">
	<div class="col-md-4 col-sm-4">
		<img style="width : 100%" src="<?php echo getLogoRestaurant($restaurant->id); ?>" alt="HoMe Menus - Dalla Famiglia" Title="HoMe Menus - Dalla Famiglia">
	</div>
	<div class="col-md-8 col-sm-8" style="font-size : 12px;">
		<span><b style="font-size : 14px;"><?php echo utf8_encode($restaurant->rue); ?>, <?php echo $restaurant->code_postal; ?> <?php echo $restaurant->ville; ?></b></span><br /><br />
		<span><?php echo utf8_encode($restaurant->short_desc); ?></span><br /><br />
		<p><i><?php echo utf8_encode($restaurant->long_desc); ?></i></p>
		<div class="row">
			<div class="col-md-7 col-sm-7">
				<hr />
				<div class="row">
					<div class="col-md-5 col-sm-5">
						<span><b>Horaires d'ouverture</b></span>
					</div>
					<div class="col-md-7 col-sm-7">
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
					<div class="col-md-5 col-sm-5">
						<span><b>Distance</b></span>
					</div>
					<div class="col-md-7 col-sm-7">
						<span><?php echo $restaurant->distance; ?> KM</span>
					</div>
				</div>
				<hr />
				<div class="row">
					<div class="col-md-5 col-sm-5">
						<span><b>Prix de livraison</b></span>
					</div>
					<div class="col-md-7 col-sm-7">
						<span><?php echo formatPrix($request->prix_livraison); ?></span>
					</div>
				</div>
				<hr />
			</div>
			<div class="col-md-5 col-sm-5">
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
	<div class="col-md-2 col-sm-2">
		<div class="categories" style="margin-top: 50px; height: 400px; overflow-y: auto; overflow-x: hidden; font-size: 12px;">
			<?php foreach ($restaurant->categories as $categorie) : ?>
				<?php if (count($categorie->contenus) > 0) : ?>
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<a href="#categorie-<?php echo $categorie->id; ?>">
								<?php 
									if ($categorie->parent_categorie != '') {
										echo utf8_encode($categorie->parent_categorie->nom).' : ';
									}
									echo utf8_encode($categorie->nom);
								?>
							</a>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php if (count($restaurant->menus) > 0) : ?>
				<div class="row">
					<a href="#categorie-menu">Menus</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="col-md-6 col-sm-6">
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
						<div class="col-md-12 col-sm-12">
							<div class="row">
								<div class="col-md-8 col-sm-8">
									<p class="carte-item-title"><?php echo utf8_encode($contenu->nom); ?></p>
									<span><i><?php echo utf8_encode($contenu->commentaire); ?></i></span>
								</div>
								<div class="col-md-2 col-sm-2">
									<b><?php echo formatPrix($contenu->prix); ?></b>
								</div>
								<div class="col-md-2 col-sm-2">
									<span class="add-button" style="display : none;">+</span>
								</div>
							</div>
						</div>
					</div>
					<?php if ($contenu->supplement->note != '') : ?>
							<div class="row">
								<div class="col-md-12 col-sm-12" style="text-align : center;">
									<?php if ($contenu->supplement->commentaire == 0) : ?>
										<?php echo $contenu->supplement->note; ?> / 5 (<?php echo $contenu->supplement->vote; ?> vote(s)) - 0 commentaire
									<?php else : ?>
										<a onclick="openCommentaires(<?php echo $contenu->id; ?>)">
											<?php echo $contenu->supplement->note; ?> / 5 (<?php echo $contenu->supplement->vote; ?> vote(s))
											- <?php echo $contenu->supplement->commentaire; ?> commentaire(s)
										</a>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php if (count($restaurant->menus) > 0) : ?>
			<div class="row">
				<h3 id="categorie-menu">Menus</h3>
				<?php foreach ($restaurant->menus as $menu) : ?>
					<hr />
					<div data-id="<?php echo $menu->id; ?>" class="row menu-item">
						<div class="col-md-12 col-sm-12">
							<p class="carte-item-title"><?php echo utf8_encode($menu->nom); ?></p>
							<div class="row">
								<div class="col-md-4 col-sm-4">
									<div class="row">
										<div class="vignette"><img src="<?php echo $menu->logo; ?>"></div>
									</div>
									<div class="row">
										<span>Prix : <?php echo formatPrix($menu->prix); ?></span>
									</div>
								</div>
								<div class="col-md-6 col-sm-6">
									<?php echo utf8_encode($menu->commentaire); ?>
								</div>
								<div class="col-md-2 col-sm-2">
									<span class="add-button" style="display : none;">+</span>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	<div id="panier" class="col-md-4 col-sm-4"><div class="panier" style="margin-top: 50px; height: 400px; overflow-y: auto; overflow-x: hidden; font-size: 12px;">
		<h3>Panier</h3>
		<div id="panier-content">
			<?php if ($request->panier) : ?>
				<form method="post" enctype="x-www-form-urlencoded" id="panierForm" action="">
					<input type="hidden" id="id_panier" name="id_panier" value="<?php echo $request->panier->id; ?>" />
					<div>
						<?php if ($horaire->heure_debut > $current_heure || ($horaire->heure_debut == $current_heure && $horaire->minute_debut > $current_minute)) : ?>
							<input type="hidden" name="type_commande" value="pre_commande">
							<div class="alert alert-error" role="alert">
								<span class="sr-only">Error:</span>
								Le restaurant est actuellement fermé. Il ouvrira à partir de <?php echo formatHeureMinute($horaire->heure_debut, $horaire->minute_debut); ?>.
								Mais vous pouvez dès a présent faire une précommande en indiquant l'heure à laquelle vous souhaitez être livré.
							</div><br />
							<span><b>Heure de livraison souhaitée : </b></span><br />
							<div id="heure_livraison">
								<select id="heure_commande" name="heure_commande"></select>h<select id="minute_commande" name="minute_commande"></select>
							</div>
						<?php else : ?>
							<input type="radio" name="type_commande" value="now" checked>Au plus tôt
							<input type="radio" name="type_commande" value="pre_commande">Précommander
							<div id="heure_livraison">
								<span>heure de commande : </span>
								<select id="heure_commande" name="heure_commande"></select>h<select id="minute_commande" name="minute_commande"></select>
							</div>
						<?php endif; ?>
					</div><br />
					<div class="panel panel-default panel-primary">
						<div class="panel-heading">
							<b>Restaurant <?php echo utf8_encode($request->panier->restaurant->nom); ?></b>
						</div>
						<div>
							<?php $totalQte = 0; ?>
							<?php $totalPrix = 0; ?>
							<table class="table">
								<thead>
									<tr>
										<th>Nom</th>
										<th>Qté</th>
										<th>prix</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($request->panier->carteList as $carte) : ?>
										<tr id="tr_carte_<?php echo $carte->id; ?>">
											<td><?php echo utf8_encode($carte->nom); ?></td>
											<td><?php echo $carte->quantite; ?></td>
											<td><?php echo formatPrix($carte->prix); ?></td>
											<td><a class="carte-item-delete" data-id="<?php echo $carte->id; ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
										</tr>
										<?php $totalQte += $carte->quantite; ?>
										<?php $totalPrix += $carte->prix; ?>
									<?php endforeach; ?>
									<?php foreach ($request->panier->menuList as $menu) : ?>
										<tr>
											<td><?php echo utf8_encode($menu->nom); ?></td>
											<td><?php echo $menu->quantite; ?></td>
											<td><?php echo formatPrix($menu->prix); ?></td>
											<td><a class="menu-item-delete" data-id="<?php echo $menu->id; ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
										</tr>
										<?php $totalQte += $menu->quantite; ?>
										<?php $totalPrix += $menu->prix; ?>
									<?php endforeach; ?>
									<?php
										$prix_livraison = $request->panier->prix_livraison;
										if ($request->_auth && $request->_auth->is_premium) {
											$prix_livraison -= $request->panier->reduction_premium;
										}
									?>
									<tr>
										<td>prix de livraison</td>
										<td></td>
										<td>
											<?php 
												if ($request->panier->code_promo->surPrixLivraison()) {
													if ($request->panier->code_promo->estGratuit()) {
														echo "OFFERT";
														$prix_livraison = 0;
													} else {
														$prix_livraison -= $request->panier->code_promo->valeur_prix_livraison;
														$totalPrix += $prix_livraison;
														echo formatPrix($prix_livraison);
													}
												} else {
													echo formatPrix($prix_livraison);
													$totalPrix += $prix_livraison;
												}
											?>
										</td>
										<td></td>
									</tr>
								</tbody>
								<tfoot>
									<?php if ($request->panier->code_promo->description != '') : ?>
										<tr>
											<th>Promo :</th>
											<td colspan="3"><?php echo utf8_encode($request->panier->code_promo->description); ?></td>
										</tr>
									<?php endif; ?>
									<tr>
										<th>Total :</th>
										<th><?php echo $totalQte; ?></th>
										<th>
											<?php 
												if ($request->panier->code_promo->surPrixTotal()) {
													if ($request->panier->code_promo->estGratuit()) {
														echo "OFFERT";
													} else {
														$prixReduc = $totalPrix;
														if ($request->panier->code_promo->valeur_prix_total != -1) {
															$prixReduc -= $request->panier->code_promo->valeur_prix_total;
														}
														if ($request->panier->code_promo->pourcentage_prix_total != -1) {
															$prixReduc -= ($prixReduc * $request->panier->code_promo->pourcentage_prix_total) / 100;
														}
														echo formatPrix($prixReduc);
													}
												} else {
													echo formatPrix($totalPrix);
												}
											?>
										</th>
										<td></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					<?php if ($request->panier->prix_minimum > ($totalPrix - $prix_livraison)) : ?>
						<div class="alert alert-danger" role="alert">
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							<span class="sr-only">Error:</span>
							Le montant minimum pour commander est de <?php echo $request->panier->prix_minimum; ?> € (hors prix de livraison)
						</div>
					<?php else : ?>
						<?php if ($horaire->heure_debut > $current_heure || ($horaire->heure_debut == $current_heure && $horaire->minute_debut > $current_minute)) : ?>
							<button id="command" class="validate-button" type="submit">Précommander</button>
						<?php else : ?>
							<button id="command" class="validate-button" type="submit">Commander</button>
						<?php endif; ?>
					<?php endif; ?>
				</form>
			<?php else : ?>
				<span>(vide)</span>
			<?php endif; ?>
		</div>
	</div></div>
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
				url: '?controler=restaurant&action=carte&id='+id_restaurant+'&id_carte='+id_carte,
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
				url: "?controler=panier&action=removeCarte",
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
				url: "?controler=panier&action=removeMenu",
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
				url: "?controler=panier&action=addCodePromo",
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
			url: '?controler=restaurant&action=panier',
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
			url: '?controler=notes&action=viewRestaurant&id_restaurant='+id_resto,
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
			url: '?controler=notes&action=viewCarte&id_carte='+id_carte,
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
	
	#panierForm .panel-heading {
		background-color : #F4F4F4;
		border-color : #F4F4F4;
		color : #000000;
	}
	
</style>