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
			<div class="col-md-12">
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
							<input class="form-control" name="login" type="text" value="<?php echo $request->user->login; ?>" maxlength="32" required>
						</div>
						<div class="form-group">
							<label for="email">email : </label>
							<input class="form-control" name="email" type="email" value="<?php echo $request->user->email; ?>" maxlength="32" required>
						</div>
						<button class="validate-button" type="submit" id="userFormValidationButton" >Modifier</button>
					</fieldset>
				</form>
				<form method="post" enctype="x-www-form-urlencoded" id="subscribeForm" action="?action=modify_password">
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
			</div>
		</div>
	</div>
</div>