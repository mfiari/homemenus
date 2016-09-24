<div id="informations">
	<h3>Vos informations</h3>
	<div class="row">
		<div class="col-md-3">
			<span>Nom : </span>
		</div>
		<div class="col-md-9">
			<input class="form-control" name="nom" type="text" value="<?php echo $request->_auth->nom; ?>" disabled>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<span>Prénom : </span>
		</div>
		<div class="col-md-9">
			<input class="form-control" name="nom" type="text" value="<?php echo $request->_auth->prenom; ?>" disabled>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<span>Mail : </span>
		</div>
		<div class="col-md-9">
			<input class="form-control" name="nom" type="text" value="<?php echo $request->_auth->login; ?>" disabled>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<span>adresse de livraison : </span>
		</div>
		<div class="col-md-9">
			<input class="form-control" name="nom" type="text" value="<?php echo utf8_encode($request->panier->rue); ?>, <?php echo utf8_encode($request->panier->code_postal); ?> <?php echo utf8_encode($request->panier->ville); ?>" disabled>
		</div>
	</div>
	<!--<div class="row">
		<div class="col-md-3">
			<span>Complémént d'adresse : </span>
		</div>
		<div class="col-md-9">
			<input class="form-control" name="nom" type="text" value="" >
		</div>
	</div>-->
	<div class="row">
		<div class="col-md-3">
			<span>Téléphone : </span>
		</div>
		<div class="col-md-9">
			<input class="form-control" name="nom" type="text" value="<?php echo $request->panier->telephone; ?>" disabled>
		</div>
	</div>
</div>
<div id="restaurant">
	<h3>Le restaurant</h3>
	<?php $restaurant = $request->panier->restaurant; ?>
	<div class="row">
		<span>Nom : <?php echo utf8_encode($restaurant->nom); ?></span>
	</div>
	<div class="row">
		<span>Adresse : <?php echo utf8_encode($restaurant->rue); ?>, <?php echo $restaurant->code_postal; ?> <?php echo utf8_encode($restaurant->ville); ?></span>
	</div>
	<div class="row">
		<span>Distance : <?php echo $request->panier->distance; ?> km</span>
	</div>
</div>
<div id="commande">
	<h3>Votre commande</h3>
	<div class="row">
		<?php if ($request->panier->heure_souhaite == -1) : ?>
			<span>Heure souhaitée : Au plus tôt</span>
		<?php else : ?>
			<span>Heure souhaitée : <?php echo utf8_encode($request->panier->heure_souhaite); ?>h<?php echo utf8_encode($request->panier->minute_souhaite); ?></span>
		<?php endif; ?>
	</div>
	<div class="panel panel-default panel-primary">
		<div class="panel-heading">
			Détail
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
					</tr>
				</thead>
				<tbody>
					<?php foreach ($request->panier->carteList as $carte) : ?>
						<tr id="tr_carte_<?php echo $carte->id; ?>">
							<td>
								<div class="row">
									<div class="col-md-8">
										<?php echo utf8_encode($carte->nom); ?>
										<?php if (count($carte->formats) == 1 && $carte->formats[0]->nom != "") : ?>
											(<?php echo utf8_encode($carte->formats[0]->nom); ?>)
										<?php endif; ?>
										<?php if (count($carte->supplements) > 0) : ?>
											<div class="row">
												<div class="col-md-offset-1 col-md-11">
													<span>Suppléments : </span>
													<div class="row">
														<div class="col-md-offset-1 col-md-11">
															<?php foreach ($carte->supplements as $supplement) : ?>
																<span><?php echo utf8_encode($supplement->nom); ?></span>
															<?php endforeach; ?>
														</div>
													</div>
												</div>
											</div>
										<?php endif; ?>
										<?php if (count($carte->options) > 0) : ?>
											<div class="row">
												<div class="col-md-offset-1 col-md-11">
													<?php foreach ($carte->options as $option) : ?>
														<?php foreach ($option->values as $value) : ?>
															<div class="row">
																<span><?php echo utf8_encode($option->nom); ?> : <?php echo utf8_encode($value->nom); ?></span>
															</div>
														<?php endforeach; ?>
													<?php endforeach; ?>
												</div>
											</div>
										<?php endif; ?>
										<?php if (count($carte->accompagnements) > 0) : ?>
											<div class="row">
												<div class="col-md-offset-1 col-md-11">
													<span>accompagnements : </span>
													<div class="row">
														<div class="col-md-offset-1 col-md-11">
															<?php foreach ($carte->accompagnements as $accompagnement) : ?>
																<?php foreach ($accompagnement->cartes as $carteAccompagnement) : ?>
																	<span><?php echo utf8_encode($carteAccompagnement->nom); ?></span>
																<?php endforeach; ?>
															<?php endforeach; ?>
														</div>
													</div>
												</div>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</td>
							<td><?php echo $carte->quantite; ?></td>
							<td><?php echo formatPrix($carte->prix); ?></td>
						</tr>
						<?php $totalQte += $carte->quantite; ?>
						<?php $totalPrix += $carte->prix; ?>
					<?php endforeach; ?>
					<?php foreach ($request->panier->menuList as $menu) : ?>
						<tr>
							<td>
								<div class="row">
									<div class="col-md-8">
										<?php echo utf8_encode($menu->nom); ?>
										<?php if (count($menu->formats) == 1 && $menu->formats[0]->nom != "") : ?>
											(<?php echo utf8_encode($menu->formats[0]->nom); ?>)
										<?php endif; ?>
										<?php foreach ($menu->formules as $formule) : ?>
											<?php foreach ($formule->categories as $categorie) : ?>
												<div class="row">
													<div class="col-md-offset-1 col-md-11">
														<span><?php echo utf8_encode($categorie->nom); ?> : </span>
														<?php foreach ($categorie->contenus as $contenu) : ?>
															<span><?php echo utf8_encode($contenu->nom); ?></span>
														<?php endforeach; ?>
													</div>
												</div>
											<?php endforeach; ?>
										<?php endforeach; ?>
									</div>
								</div>
							<td><?php echo $menu->quantite; ?></td>
							<td><?php echo formatPrix($menu->prix); ?></td>
						</tr>
						<?php $totalQte += $menu->quantite; ?>
						<?php $totalPrix += $menu->prix; ?>
					<?php endforeach; ?>
					<?php
						$prix_livraison = $request->panier->prix_livraison;
						if ($request->_auth->is_premium) {
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
										$totalPrix = 0;
									} else {
										if ($request->panier->code_promo->valeur_prix_total != -1) {
											$totalPrix -= $request->panier->code_promo->valeur_prix_total;
										}
										if ($request->panier->code_promo->pourcentage_prix_total != -1) {
											$totalPrix -= ($totalPrix * $request->panier->code_promo->pourcentage_prix_total) / 100;
										}
										echo formatPrix($totalPrix);
									}
								} else {
									echo formatPrix($totalPrix);
								}
							?>
						</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<div>
	<input id="accept_cgv" type="checkbox" /> Avant de continuer, vous devez accepter les <a href="?action=cgv" target="_blank">conditions générales de vente</a>.
</div>
<div id="accept_cgv_error_message" class="alert alert-danger" role="alert">
	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
	<span class="sr-only">Error:</span>
	Vous devez accepter les conditions générales de vente pour pouvoir continuer
</div>
<div style="margin-top : 20px;">
	<div id="paiementsForm" class="row" style="display : none;">
		<div class="col-md-6">
			<form style="text-align : center;" id="payPaypal" action="?controler=paypal" method="POST">
				<input id="command" class="btn btn-primary" type="submit" value="Payer avec paypal">
			</form>
			<div class="col-md-offset-2 col-md-10">
				<img style="width : 80%; margin-top : 20px;" src="res/img/paiement-paypal.jpg" title="HoMe Menus - paiement paypal secure" alt="HoMe Menus - paiement paypal secure">
			</div>
		</div>
		<div class="col-md-6">
			<form style="text-align : center;" id="payCard" action="?controler=panier&action=valideCarte" method="POST">
			  <script
				src="https://checkout.stripe.com/checkout.js" class="stripe-button"
				data-email="<?php echo $request->_auth->login; ?>"
				data-allow-remember-me="false"
				data-label="Payer par carte"
				data-key="<?php echo STRIPE_PUBLIC_KEY; ?>"
				data-amount="<?php echo ($totalPrix * 100); ?>"
				data-name="HoMe Menus"
				data-description="Paiement de la commande"
				data-image="/web/res/img/logo_mail.png"
				data-locale="auto"
				data-zip-code="true"
				data-currency="eur">
			  </script>
			</form>
			<div class="col-md-offset-2 col-md-10">
				<img style="width : 80%; margin-top : 20px;" src="res/img/stripe-secure.png" title="HoMe Menus - paiement stripe secure" alt="HoMe Menus - paiement stripe secure">
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#accept_cgv").click(function () {
		if ($("#accept_cgv").is(":checked")) {
			$("#accept_cgv_error_message").hide();
			$("#paiementsForm").show();
			$("#payCard .stripe-button-el span").css("min-height", "0");
			$("#payCard .stripe-button-el").removeClass("stripe-button-el").addClass("btn btn-primary");
		} else {
			$("#accept_cgv_error_message").show();
			$("#paiementsForm").hide();
		}
	});
	/*$("#payPaypal").submit(function(event) {
		event.preventDefault();
		if ($("#accept_cgv").is(":checked")) {
			$.ajax({
				type: "POST",
				url: "?controler=panier&action=commande",
				dataType: "html",
				data: $("#panierForm").serialize()
			}).done(function( msg ) {
				$("#panier-modal").modal('hide');
				document.location.href="?controler=paypal"
			});
		} else {
			$("#accept_cgv_error_message").show();
		}
	});
	$("#payCard").submit(function(event) {
		if ($("#accept_cgv").is(":checked")) {
			return true;
			$.ajax({
				type: "POST",
				url: "?controler=panier&action=commande",
				dataType: "html",
				data: $("#panierForm").serialize()
			}).done(function( msg ) {
				$("#panier-modal").modal('hide');
				document.location.href="?controler=panier&action=valideCarte"
			});
		} else {
			$("#accept_cgv_error_message").show();
			event.preventDefault();
		}
	});*/
</script>