<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h2>Mon solde</h2>
		<div class="row">
			<div class="col-md-9">
				<span>Votre solde actuelle est de </span>
				<form style="text-align : center;" id="payCard" action="?controler=compte&action=paiementSolde" method="POST">
					<div class="row">
						<div class="col-md-4">
							<span><input id="radio_cb" name="paiement_method" type="radio" value="cb">Recharger par carte bancaire</span>
							<span><i>(effectuer un paiement par carte bancaire)</i></span>
						</div>
						<div class="col-md-4">
							<span><input id="radio_espece" name="paiement_method" type="radio" value="espece">Recharger par chèque ou espèce</span>
							<span><i>(effectuer un paiment par carte bancaire)</i></span>
						</div>
						<div class="col-md-4">
							<span><input id="radio_ticket" name="paiement_method" type="radio" value="ticket">Recharger par ticket restaurant</span>
							<span><i>(effectuer un paiment par carte bancaire)</i></span>
						</div>
					</div>
					<div id="solde-form" class="row">
						<fieldset>
							<div id="solde-form-montant" class="form-group">
								<div class="row">
									<div class="col-md-4">
										<label for="montant">Saisir le montant total : </label>
									</div>
									<div class="col-md-8">
										<input class="form-control" id="montant" name="montant" type="text" value="0" maxlength="32" required>
									</div>
								</div>
							</div>
							<div id="solde-form-quantite" class="form-group">
								<div class="row">
									<div class="col-md-4">
										<label for="quantite">Nombre de ticket : </label>
									</div>
									<div class="col-md-8">
										<input class="form-control" id="quantite" name="quantite" type="text" value="0" maxlength="32" required>
									</div>
								</div>
							</div>
							<div id="solde-form-envoi" class="form-group">
								<div class="row">
									<div class="col-md-4">
										<label for="envoi">Type d'envoi : <span class="required">*</span> : </label>
									</div>
									<div class="col-md-8">
										<select name="envoi">
											<option value="COURSIER">Par coursier</option>
											<option value="POSTAL">Postal</option>
										</select>
									</div>
								</div>
							</div>
							<div id="solde-form-submit" class="form-group">
								<button class="validate-button" type="submit">Valider</button>
							</div>
							<div id="solde-form-stripe" class="form-group">
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
							</div>
						</fieldset>
					</div>
				</form>
			</div>
			<div class="col-md-3">
				<ul>
					<li><a href="index.html?controler=commande">Mes commandes</a></li>
					<li><a href="index.html?controler=notes">Mes notes</a></li>
					<li><a href="index.html?controler=compte&action=solde">Mon solde</a></li>
					<li><a href="index.html?controler=compte&action=calendrier">Mon calendrier</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		$("#radio_cb").click(function () {
			$("#solde-form").show();
			$("#solde-form-montant label").html('Saisir le montant total : ');
			$("#solde-form-quantite").hide();
			$("#solde-form-envoi").hide();
			$("#solde-form-submit").hide();
			$("#solde-form-stripe").show();
		});
		$("#radio_espece").click(function () {
			$("#solde-form").show();
			$("#solde-form-montant label").html('Saisir le montant total : ');
			$("#solde-form-quantite").hide();
			$("#solde-form-envoi").show();
			$("#solde-form-submit").show();
			$("#solde-form-stripe").hide();
		});
		$("#radio_ticket").click(function () {
			$("#solde-form").show();
			$("#solde-form-montant label").html('Saisir le montant du ticket : ');
			$("#solde-form-quantite").show();
			$("#solde-form-envoi").show();
			$("#solde-form-submit").show();
			$("#solde-form-stripe").hide();
		});
	});
</script>
<style>
	#solde-form {
		display : none;
		margin-top : 50px;
	}
</style>