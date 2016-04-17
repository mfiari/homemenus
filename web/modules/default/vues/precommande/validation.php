<form method="POST" action="?controler=compte&action=payment">
	<input type="hidden" name="id_commande" value="<?php echo $request->commande->id; ?>">
	<h2>Confirmer vos informations</h2>
	<div id="commande">
		<div class="row">
			<h3>Vos informations</h3>
			<span>Adresse de livraison : <?php echo $request->commande->rue; ?>, <?php echo $request->commande->code_postal; ?> <?php echo $request->commande->ville; ?>
			<span>Date de commande : <?php echo $request->commande->date_commande; ?>
			<span>Heure souhaité : <?php echo $request->commande->heure_souhaite; ?>h<?php echo $request->commande->minute_souhaite; ?>
		</div>
		<div class="row">
			<h3>Vous souhaitez régler : </h3>
			<div class="col-md-6">
				<input type="radio" name="payment" value="solde">
				<span>Avec votre solde</span>
				<span>Solde : </span>
			</div>
			<div class="col-md-6">
				<input type="radio" name="payment" value="paypal">
				<span>Par carte bancaire ou paypal</span>
			</div>
		</div>
	</div>
	<a class="btn btn-primary" href="?controler=compte&action=annulationCommande&commande=<?php echo $request->commande->id; ?>">Annuler la commande</a>
	<button class="btn btn-primary" type="submit">Valider</button>
</form>