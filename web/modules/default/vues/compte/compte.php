<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h2>Mon compte</h2>
		<?php if ($request->errorMessage) : ?>
			<?php foreach ($request->errorMessage as $key => $value) : ?>
				<div class="alert alert-danger" role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					<?php echo $value; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if ($request->successMessage) : ?>
			<div class="alert alert-success" role="alert">
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
				<?php echo $request->successMessage; ?>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col-md-9">
				<form method="post" enctype="x-www-form-urlencoded" id="userForm" action="">
					<fieldset>
						<div class="form-group">
							<label for="nom">Nom : </label>
							<input class="form-control" name="nom" type="text" value="<?php echo $request->user->nom; ?>" maxlength="32" required>
						</div>
						<div class="form-group">
							<label for="prenom">Prénom : </label>
							<input class="form-control" name="prenom" type="text" value="<?php echo $request->user->prenom; ?>" maxlength="32" required>
						</div>
						<div class="form-group">
							<label for="login">Identifiant : </label>
							<input class="form-control" name="login" type="email" value="<?php echo $request->user->login; ?>" maxlength="32" required>
						</div>
						<div class="form-group">
							<label for="email">email : </label>
							<input class="form-control" name="email" type="email" value="<?php echo $request->user->email; ?>" maxlength="32" required>
						</div>
						<div class="form-group">
							<label for="rue">Adresse : </label>
							<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" 
							title="L'adresse est facultative. Elle est utilisé pour facilité votre recherche."></span>
							<div class="search-block">
								<input id="full_address" class="form-control" name="adresse" type="text" value="<?php echo utf8_encode($request->user->rue).', '.$request->user->code_postal.' '.utf8_encode($request->user->ville); ?>" placeholder="Entrez votre adresse">
								<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
							</div>
							<input id="rue" name="rue" type="text" value="<?php $request->user->rue; ?>" hidden="hidden">
							<input id="ville" name="ville" type="text" value="<?php echo $request->user->ville; ?>" hidden="hidden">
							<input id="code_postal" name="code_postal" type="text" value="<?php echo $request->user->code_postal; ?>" hidden="hidden">
						</div>
						<div class="form-group">
							<label for="telephone">Téléphone : </label>
							<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" 
							title="Le numéro de téléphone est falcutatif, il nous sert à vous contacter en cas de problème sur votre commande. 
							En aucun cas il ne sera communiqué à des tiers"></span>
							<input class="form-control" name="telephone" type="text" value="<?php echo $request->user->telephone; ?>" maxlength="10" >
						</div>
						<button class="validate-button" type="button" id="userFormValidationButton" >Modifier</button>
					</fieldset>
				</form>
				<form method="post" enctype="x-www-form-urlencoded" id="subscribeForm" action="?controler=compte&action=modify_password">
					<h3>Modifier mon mot de passe</h3>
					<fieldset>
						<div class="form-group">
							<label for="old_password">Ancien Mot de passe : </label>
							<input class="form-control" id="old_password" name="old_password" type="password" maxlength="32" required>
						</div>
						<div class="form-group">
							<label for="new_password">Nouveau Mot de passe : </label>
							<input class="form-control" id="new_password" name="new_password" type="password" maxlength="32" required>
						</div>
						<div class="form-group">
							<label for="confirm_password">Confirmer Mot de passe : </label>
							<input class="form-control" name="confirm_password" type="password" maxlength="32" required>
						</div>
						<button id="subscribe-button" class="validate-button" type="submit">Modifier</button>
					</fieldset>
				</form>
				<form method="post" enctype="x-www-form-urlencoded" action="?controler=compte&action=parametrage">
					<h3>Paramétrage</h3>
					<fieldset>
						<div class="form-group">
							<input type="checkbox" name="default_adress" <?php echo $request->user->parametre->default_adresse_search ? "checked" : ""; ?>/>
							Utiliser votre adresse comme valeur par défaut pour la recherche.
						</div>
						<div class="form-group">
							<input type="checkbox" name="mail_commande" <?php echo $request->user->parametre->send_mail_commande ? "checked" : ""; ?>/>
							Reçevoir un mail lors de la validation de ma commande
						</div>
						<div class="form-group">
							<input type="checkbox" name="sms_commande" <?php echo $request->user->parametre->send_sms_commande ? "checked" : ""; ?>/>
							Reçevoir le suivi de ma commande par SMS.
						</div>
						<button class="validate-button" type="submit">Modifier</button>
					</fieldset>
				</form>
				<form method="post" enctype="x-www-form-urlencoded" id="subscribeForm" action="?controler=paypal&action=premium_subscribe">
					<h3>Souscrire au compte premium</h3>
					<fieldset>
						<button class="validate-button" type="submit">Souscrire</button>
					</fieldset>
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
		enableAutocomplete("full_address");
		$("#userFormValidationButton").click(function(event) {
			var addressComponents = getAdresseElements();
			if (addressComponents !== false) {
				var rue = addressComponents.street_number + ' ' + addressComponents.route;
				var ville = addressComponents.locality;
				var code_postal = addressComponents.postal_code;
				
				$('#rue').val(rue);
				$('#ville').val(ville);
				$('#code_postal').val(code_postal);
			}
			$("#userForm").submit();
		});
	});
</script>