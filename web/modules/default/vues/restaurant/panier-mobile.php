<?php 
	include(WEBSITE_PATH.'layouts/panier_info.php');
	$restaurant = $request->restaurant;
	$livreurs = $request->livreurs ? $request->livreurs : array();
	$horaire = $request->restaurant ? $restaurant->horaire : false;
	$horaires = $request->restaurant ? $restaurant->horaires : array();
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
<div id="panier-content">
	<?php if ($request->panier) : ?>
		<a style="margin-top: 10px;" class="close-button" href="?controler=restaurant&action=index&id=<?php echo $request->panier->restaurant->id; ?>">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour à la carte du restaurant
		</a>
	<?php else : ?>
		<a style="margin-top: 10px;" class="close-button" href="javascript:history.back()">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
	<?php endif; ?>
	<div>
		<?php if ($request->panier) : ?>
			<form method="post" enctype="x-www-form-urlencoded" id="panierForm" action="">
				<input type="hidden" id="id_panier" name="id_panier" value="<?php echo $request->panier->id; ?>" />
				<div>
					<?php if ($horaire->heure_debut > $current_heure || ($horaire->heure_debut == $current_heure && $horaire->minute_debut > $current_minute)) : ?>
						<input type="hidden" name="type_commande" value="pre_commande">
						<span style="color : red;">
							Le <?php echo utf8_encode($request->panier->restaurant->nom); ?> est actuellement fermé. Ouverture
								de <?php echo formatHeureMinute($horaire->heure_debut, $horaire->minute_debut); ?> 
								à <?php echo formatHeureMinute($horaire->heure_fin, $horaire->minute_fin); ?><br />
							Précommande possible dès maintenant.
						</span><br /><br />
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
						Restaurant <?php echo utf8_encode($request->panier->restaurant->nom); ?>
					</div>
					<div>
						<?php $totalQte = 0; ?>
						<?php $totalPrix = 0; ?>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Nom</th>
									<th>Quantité</th>
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
											if ($request->panier->code_promo && $request->panier->code_promo->surPrixLivraison()) {
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
								<?php if ($request->panier->code_promo && $request->panier->code_promo->description != '') : ?>
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
											if ($request->panier->code_promo && $request->panier->code_promo->surPrixTotal()) {
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
</div>

<script type="text/javascript">
	$(function() {
		
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
		$("#command").click(function(event) {
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
			url: 'index.php?controler=restaurant&action=panier&type=ajax',
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
</script>
<style>

	#panier-content a.close-button {
		width : 250px;
	}
	
	#command {
		font-size : 20px;
	}
	
	#panierForm .panel-heading {
		background-color : #F4F4F4;
		border-color : #F4F4F4;
		color : #000000;
	}
	
</style>