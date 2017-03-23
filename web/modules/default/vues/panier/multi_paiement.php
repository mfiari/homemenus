<?php if (isset($_GET['payment']) && $_GET['payment'] == 'refused') : ?>
	<div class="alert alert-danger" role="alert">
		<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
		<span class="sr-only">Error:</span>
		Votre paiement a été refusée
	</div>
<?php endif; ?>
<div id="commande">
	<h3>Vos paiements</h3>
	<div class="panel panel-default panel-primary">
		<div class="panel-heading">
			Détail
		</div>
		<div>
			<table class="table table-striped">
				<thead>
					<tr>
						<th></th>
						<th>Montant</th>
						<th>Méthode de paiement</th>
						<th>Paiement validé</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php $totalPayer = 0; ?>
					<?php foreach ($request->panier->paiements as $paiement) : ?>
						<tr id="paiement_<?php echo $paiement->id; ?>">
							<td></td>
							<td><?php echo formatPrix($paiement->montant); ?></td>
							<td><?php echo $paiement->method; ?></td>
							<td><?php echo $paiement->error_code == '' ? '<span style="color : green; ">Oui</span>' : '<span style="color : red; ">Non</span>'; ?></td>
							<td><a href="?controler=panier&action=annuleCarteMultiPaiement&id_paiement=<?php echo $paiement->id; ?>">
								<span data-toggle="tooltip" title="Annuler le paiement" class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							</a></td>
						</tr>
						<?php $totalPayer += $paiement->montant; ?>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<th>Montant payer :</th>
						<td colspan="3"><?php echo formatPrix($totalPayer); ?></td>
					</tr>
					<tr>
						<th>Total panier :</th>
						<td colspan="3">
							<?php 
								
								$totalPrix = 0;
								foreach ($request->panier->carteList as $carte) {
									$totalPrix += $carte->prix;
								}
								foreach ($request->panier->menuList as $menu) {
									$totalPrix += $menu->prix;
								}
								$prix_livraison = $request->panier->prix_livraison;
								if ($request->_auth->is_premium) {
									$prix_livraison -= $request->panier->reduction_premium;
								}
								if ($request->panier->code_promo && $request->panier->code_promo->surPrixLivraison()) {
									if ($request->panier->code_promo->estGratuit()) {
										$prix_livraison = 0;
									} else {
										$prix_livraison -= $request->panier->code_promo->valeur_prix_livraison;
										$totalPrix += $prix_livraison;
									}
								} else {
									$totalPrix += $prix_livraison;
								}
								if ($request->panier->code_promo && $request->panier->code_promo->surPrixTotal()) {
									if ($request->panier->code_promo->estGratuit()) {
										$totalPrix = 0;
									} else {
										if ($request->panier->code_promo->valeur_prix_total != -1) {
											$totalPrix -= $request->panier->code_promo->valeur_prix_total;
										}
										if ($request->panier->code_promo->pourcentage_prix_total != -1) {
											$totalPrix -= ($totalPrix * $request->panier->code_promo->pourcentage_prix_total) / 100;
										}
									}
								}
								echo formatPrix($totalPrix);
							?>
						</td>
					</tr>
					<tr>
						<th>Reste à payer :</th>
						<td colspan="3"><?php echo formatPrix($totalPrix - $totalPayer); ?></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<div style="margin-top : 20px;">
	<div id="paiementsForm" class="row">
		<!--<div class="col-md-6">
			<form style="text-align : center;" id="payPaypal" action="?controler=paypal" method="POST">
				<input id="command" class="validate-button" type="submit" value="Payer avec paypal">
			</form>
			<div class="col-md-offset-2 col-md-10">
				<img style="width : 80%; margin-top : 20px;" src="res/img/paiement-paypal.jpg" title="HoMe Menus - paiement paypal secure" alt="HoMe Menus - paiement paypal secure">
			</div>
		</div>-->
		<div class="col-md-12">
			
		</div>
		<div class="col-md-12">
			<form style="text-align : center;" id="payCard" action="?controler=panier&action=valideCarteMultiPaiement" method="POST">
				<span>Saisir le montant à payer : </span><input style="border : 1px solid #000000; margin-left : 10px;" id="montant" name="montant" type="text" value="<?php echo $totalPrix - $totalPayer; ?>"><br /><br />
				<script
					src="https://checkout.stripe.com/checkout.js" class="stripe-button"
					data-email="<?php echo $request->_auth->login; ?>"
					data-allow-remember-me="false"
					data-label="Payer par carte"
					data-key="<?php echo STRIPE_PUBLIC_KEY; ?>"
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
	</div>
</div>
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
<script type="text/javascript">
	$("#payCard .stripe-button-el span").css("min-height", "0");
	$("#payCard .stripe-button-el").removeClass("stripe-button-el").addClass("validate-button");
	
	$("#payCard .validate-button").click(function () {
		$("#payCard script.stripe-button").attr('data-amount', $("#montant").val());
	});
</script>