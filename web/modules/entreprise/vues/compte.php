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
		<div class="col-md-12">
			<form method="post" enctype="x-www-form-urlencoded" id="subscribeForm" action="?action=compte">
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
						<label for="login">email : </label>
						<input class="form-control" name="login" type="email" value="<?php echo $request->user->login; ?>" maxlength="32" required>
					</div>
					<div class="form-group">
						<label for="telephone">Téléphone : </label>
						<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" 
						title="Le numéro de téléphone est falcutatif, il nous sert à vous contacter en cas de problème sur votre commande. 
						En aucun cas il ne sera communiqué à des tiers"></span>
						<input class="form-control" name="telephone" type="text" value="<?php echo $request->user->telephone; ?>" maxlength="10" >
					</div>
					<button id="subscribe-button" class="btn btn-primary" type="button">Modifier</button>
				</fieldset>
			</form>
			<form method="post" enctype="x-www-form-urlencoded" id="subscribeForm" action="?action=compte">
				<h3>Modifier mon mot de passe</h3>
				<fieldset>
					<div class="form-group">
						<label for="password">Ancien Mot de passe : </label>
						<input class="form-control" id="password" name="password" type="password" maxlength="32" required>
					</div>
					<div class="form-group">
						<label for="password">Nouveau Mot de passe : </label>
						<input class="form-control" id="password" name="password" type="password" maxlength="32" required>
					</div>
					<div class="form-group">
						<label for="confirm_password">Confirmer Mot de passe : </label>
						<input class="form-control" name="confirm_password" type="password" maxlength="32" required>
					</div>
					<button id="subscribe-button" class="btn btn-primary" type="button">Modifier</button>
				</fieldset>
			</form>
		</div>
	</div>
</div>