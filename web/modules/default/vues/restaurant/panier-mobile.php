<?php include(WEBSITE_PATH.'layouts/panier_info.php'); ?>
<div id="panier-content">
	<div>
		<?php if ($request->panier) : ?>
			<form method="post" enctype="x-www-form-urlencoded" id="panierForm" action="">
				<input type="hidden" id="id_panier" name="id_panier" value="<?php echo $request->panier->id; ?>" />
				<?php
					$current_heure = date('G')+1;
					$current_minute = date('i');
					$horaire = $request->panier->restaurant->horaire;
				?>
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
						<?php 
							if ($horaire->heure_debut < $current_heure) {
								$beginHour = $current_heure;
							} else {
								$beginHour = $horaire->heure_debut;
							}
						?>
						<select name="heure_commande">
							<?php for ($i = $beginHour ; $i <= $horaire->heure_fin ; $i++) : ?>
								<option><?php echo $i; ?></option>
							<?php endfor; ?>
						</select>h
						<select name="minute_commande">
							<?php for ($i = 0 ; $i <= 60 ; $i++) : ?>
								<option <?php echo $i == $horaire->minute_debut ? 'selected' : ''; ?>><?php echo $i; ?></option>
							<?php endfor; ?>
						</select>
					<?php else : ?>
						<input type="radio" name="type_commande" value="now" checked>Au plus tôt
						<input type="radio" name="type_commande" value="pre_commande">Précommander
						<span>heure de commande</span>
						<?php 
							if ($horaire->heure_debut < $current_heure) {
								$beginHour = $current_heure;
							} else {
								$beginHour = $horaire->heure_debut;
							}
						?>
						<select name="heure_commande">
							<?php for ($i = $beginHour ; $i <= $horaire->heure_fin ; $i++) : ?>
								<option><?php echo $i; ?></option>
							<?php endfor; ?>
						</select>
						<select name="minute_commande">
							<?php for ($i = 0 ; $i <= 60 ; $i++) : ?>
								<option <?php echo $i == $horaire->minute_debut ? 'selected' : ''; ?>><?php echo $i; ?></option>
							<?php endfor; ?>
						</select>
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
				<div id="codePromoBlock" style="margin-bottom : 20px;">
					<span>Code promo</span><br />
					<input id="code_promo" name="code_promo" type="text" maxlength="10">
					<button id="codePromoButton" class="btn btn-primary" type="button">Valider</button>
					<div style="display : none;" class="alert alert-success" role="alert">
						<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
						Votre code promo a été validé.
					</div>
					<div style="display : none;" class="alert alert-danger" role="alert">
						<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						<span class="sr-only">Error:</span>
						<span class="message"></span>
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
						<button id="command" class="btn btn-primary" type="submit">Précommander</button>
					<?php else : ?>
						<button id="command" class="btn btn-primary" type="submit">Commander</button>
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
		
	});
	
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
			url: '?controler=restaurant&action=panier&type=ajax',
			dataType: "html"
		}).done(function( msg ) {
			$("#panier-content").html(msg);
			initDeleteCarteItem ();
			initDeleteMenuItem ();
			initPanierCommande();
			initCodePromo ();
		});
	}
</script>
<style>
	
	#command {
		font-size : 30px;
	}
	
</style>