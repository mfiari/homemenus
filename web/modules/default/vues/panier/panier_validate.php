<?php if (isset($_GET['payment']) && $_GET['payment'] == 'refused') : ?>
	<div class="alert alert-danger" role="alert">
		<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
		<span class="sr-only">Error:</span>
		Votre paiement a été refusée
	</div>
<?php endif; ?>
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
	<?php if ($request->panier->complement != '') : ?>
		<div class="row">
			<div class="col-md-3">
				<span>Complément : </span>
			</div>
			<div class="col-md-9">
				<input class="form-control" name="nom" type="text" value="<?php echo utf8_encode($request->panier->complement); ?>" disabled>
			</div>
		</div>
	<?php endif; ?>
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
			<span>Temps de livraison estimé : <?php echo $request->panier->getTempsLivraison(); ?> min</span>
		<?php else : ?>
			<span>Heure de livraison souhaitée : <?php echo utf8_encode($request->panier->heure_souhaite); ?>h<?php echo utf8_encode($request->panier->minute_souhaite); ?></span>
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
							<td>
								<?php 
									$prix = $carte->prix;
									foreach ($carte->supplements as $supplement) {
										$prix += $supplement->prix * $carte->quantite;
									}
									echo formatPrix($prix);
								?>
							</td>
						</tr>
						<?php $totalQte += $carte->quantite; ?>
						<?php $totalPrix += $prix; ?>
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
	<div class="row">
		<div class="col-md-6">
			<div id="codePromoPanierBlock" style="margin-bottom : 20px;">
				<span>Code promo : </span>
				<input id="code_promo" name="code_promo" type="text" maxlength="10">
				<button id="codePromoPanierButton" class="validate-button" type="button">Valider</button>
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
		</div>
		<div class="col-md-6" style="text-align : right;">
			<a href="<?php echo restaurantToLink($request->panier->restaurant, $request->panier->restaurant->ville); ?>" class="btn btn-default" >Retour à la carte</a>
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
		<!--<div class="col-md-6">
			<form style="text-align : center;" id="payPaypal" action="?controler=paypal" method="POST">
				<input id="command" class="validate-button" type="submit" value="Payer avec paypal">
			</form>
			<div class="col-md-offset-2 col-md-10">
				<img style="width : 80%; margin-top : 20px;" src="res/img/paiement-paypal.jpg" title="HoMe Menus - paiement paypal secure" alt="HoMe Menus - paiement paypal secure">
			</div>
		</div>-->
		<div class="col-md-12">
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
				<img style="width : 60%; margin-top : 20px;" src="res/img/stripe-secure.png" title="HoMe Menus - paiement stripe secure" alt="HoMe Menus - paiement stripe secure">
			</div>
		</div>
		<!--<div class="col-md-4">
			<form style="text-align : center;" id="" action="?controler=panier&action=multi_paiement" method="POST">
				<input style="width : 200px;" class="validate-button" type="submit" value="Payer avec plusieurs carte">
			</form>
			<div class="col-md-offset-2 col-md-10">
				<p><i>Régler votre panier en utilisant plusieurs carte de paiement</i></p>
				<p><i>Idéal pour les repas à plusieurs</i></p>
				<p><i>Le paiement se fait toujours de manière sécurisé</i></p>
			</div>
		</div>
		<div class="col-md-4">
			<div class="col-md-offset-2 col-md-10">
				<p>Le paiement se fait uniquement en carte bleu.</p>
			</div>
		</div>-->
	</div>
</div>
<script type="text/javascript">
	$("#accept_cgv").click(function () {
		if ($("#accept_cgv").is(":checked")) {
			$("#accept_cgv_error_message").hide();
			$("#paiementsForm").show();
			$("#payCard .stripe-button-el span").css("min-height", "0");
			$("#payCard .stripe-button-el").removeClass("stripe-button-el").addClass("validate-button");
		} else {
			$("#accept_cgv_error_message").show();
			$("#paiementsForm").hide();
		}
	});
	
	$("#codePromoPanierButton").click(function () {
		$("#loading-modal").modal();
		var codePromo = $("#code_promo").val();
		$.ajax({
			type: "POST",
			url: "index.php?controler=panier&action=addCodePromo",
			dataType: "html",
			data: {code_promo : codePromo}
		}).done(function( msg ) {
			$("#loading-modal").modal('hide');
			location.reload ();
		}).error(function(jqXHR, textStatus, errorThrown) {
			switch (jqXHR.status) {
				case 400 :
					$("#codePromoPanierBlock div.alert-danger span.message").html("Le code promo n'est pas applicable sur ce restaurant.");
					break;
				case 401 :
					$("#codePromoPanierBlock div.alert-danger span.message").html("Vous n'êtes pas autorisé à utiliser ce code promo.");
					break;
				case 403 :
					$("#codePromoPanierBlock div.alert-danger span.message").html("Veuillez vous connecter pour utiliser ce code promo.");
					break;
				case 404 :
					$("#codePromoPanierBlock div.alert-danger span.message").html("Ce code promo n'existe pas.");
					break;
				case 410 :
					$("#codePromoPanierBlock div.alert-danger span.message").html("Vous avez déjà utilisé ce code promo.");
					break;
				default :
					$("#codePromoPanierBlock div.alert-danger span.message").html("Une erreur est survenu, veuillez réessayé.");
					break;
			}
			$("#codePromoPanierBlock div.alert-danger").css('display', 'block');
			$("#loading-modal").modal('hide');
		});
	});
</script>
<style>
	#commande .panel-heading {
		background-color : #F4F4F4;
		border-color : #F4F4F4;
		color : #000000;
	}
	
	#code_promo {
		border : 1px solid #000000;
	}
</style>