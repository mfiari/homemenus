<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h2>Modifier mot de passe</h2>
		<?php if ($request->errorMessage) : ?>
			<?php foreach ($request->errorMessage as $key => $value) : ?>
				<div class="alert alert-danger" role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					<?php echo $value; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<form method="post" enctype="x-www-form-urlencoded" action="">
			<input name="uid" value="<?php echo $_GET['uid']; ?>" hidden="hidden" />
			<input name="token" value="<?php echo $_GET['token']; ?>" hidden="hidden" />
			<fieldset>
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
				<button id="subscribe-button" class="btn btn-primary" type="submit">Valider</button>
			</fieldset>
		</form>
	</div>
</div>