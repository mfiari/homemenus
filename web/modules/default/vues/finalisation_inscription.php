<div class="row">
	<div class="col-md-6">
		<h2>Créer votre compte</h2>
		<p style="text-align : center;">Afin de finaliser votre achat, veuillez renseigner les informations ci-dessous afin de créer votre compte.</p>
		<?php if ($request->errorMessageSubscribe) : ?>
			<?php foreach ($request->errorMessageSubscribe as $key => $value) : ?>
				<div class="alert alert-danger" role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					<?php echo $value; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<form method="post" enctype="x-www-form-urlencoded" id="subscribeForm" action="">
			<fieldset>
				<div class="form-group">
					<label for="nom">Nom<span class="required">*</span> : </label>
					<input class="form-control" name="nom" type="text" value="<?php echo $request->fieldNom !== false ? $request->fieldNom : ''; ?>" maxlength="32" required>
				</div>
				<div class="form-group">
					<label for="prenom">Prénom<span class="required">*</span> : </label>
					<input class="form-control" name="prenom" type="text" value="<?php echo $request->fieldPrenom !== false ? $request->fieldPrenom : ''; ?>" maxlength="32" required>
				</div>
				<div class="form-group">
					<label for="login">email<span class="required">*</span> : </label>
					<input class="form-control" name="login" type="email" value="<?php echo $request->fieldEmail !== false ? $request->fieldEmail : ''; ?>" maxlength="32" required>
				</div>
				<div class="form-group">
					<label for="password">Mot de passe<span class="required">*</span> : </label>
					<input class="form-control" id="password" name="password" type="password" maxlength="32" required>
				</div>
				<div class="form-group">
					<label for="confirm_password">Confirmer Mot de passe<span class="required">*</span> : </label>
					<input class="form-control" name="confirm_password" type="password" maxlength="32" required>
				</div>
				<div>
					<span class="required">* Obligatoire</span>
				</div>
				<button id="subscribe-button" name="subscribeButton" class="btn btn-primary" type="submit">Créer mon compte</button>
			</fieldset>
		</form>
	</div>
	<div class="col-md-6">
		<h2>Vous avez déjà un compte?</h2>
		<p style="text-align : center;">Connectez-vous afin de finaliser votre achat.</p>
		<?php if ($request->errorMessageLogin) : ?>
			<?php foreach ($request->errorMessageLogin as $key => $value) : ?>
				<div class="alert alert-danger" role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					<?php echo $value; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<form method="post" enctype="x-www-form-urlencoded" id="loginForm" action="">
			<fieldset>
				<div class="form-group">
					<label for="login">Identifiant<span class="required">*</span> : </label>
					<input class="form-control" name="login" type="email" maxlength="32" required>
				</div>
				<div class="form-group">
					<label for="password">Mot de passe<span class="required">*</span> : </label>
					<input class="form-control" id="password" name="password" type="password" maxlength="32" required>
				</div>
				<button id="login-button" name="loginButton" class="btn btn-primary" type="submit">Connexion</button>
			</fieldset>
		</form>
	</div>
</div>