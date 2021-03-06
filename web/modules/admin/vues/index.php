<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h2>Administration</h2>
		<?php if ($request->errorMessage) : ?>
			<?php foreach ($request->errorMessage as $key => $value) : ?>
				<div class="alert alert-danger" role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					<?php echo $value; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<div class="row">
			<h3>Information du compte</h3>
			<form method="post" enctype="x-www-form-urlencoded" id="adminForm" action="?action=inscription">
				<fieldset>
					<div class="form-group">
						<label for="nom">Nom<span class="required">*</span> : </label>
						<input class="form-control" name="nom" type="text" value="" maxlength="32" required>
					</div>
					<div class="form-group">
						<label for="prenom">Prénom<span class="required">*</span> : </label>
						<input class="form-control" name="prenom" type="text" value="" maxlength="32" required>
					</div>
					<div class="form-group">
						<label for="login">email<span class="required">*</span> : </label>
						<input class="form-control" name="login" type="email" value="" maxlength="32" required>
					</div>
					<div class="form-group">
						<label for="password">Mot de passe<span class="required">*</span> : </label>
						<input class="form-control" id="password" name="password" type="password" maxlength="32" required>
					</div>
					<div class="form-group">
						<label for="confirm_password">Confirmer Mot de passe<span class="required">*</span> : </label>
						<input class="form-control" name="confirm_password" type="password" maxlength="32" required>
					</div>
					<button id="subscribe-button" class="btn btn-primary" type="button">Valider</button>
				</fieldset>
			</form>
		</div>
	</div>
</div>