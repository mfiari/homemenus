<h2>Pour les entreprises</h2>
<?php if ($request->errorMessage) : ?>
	<?php foreach ($request->errorMessage as $key => $value) : ?>
		<div class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<span class="sr-only">Error:</span>
			<?php echo $value; ?>
		</div>
	<?php endforeach; ?>
<?php endif; ?>
<?php if ($request->hasProperty("mailSuccess")) : ?>
	<?php if ($request->mailSuccess === true) : ?>
		<div class="alert alert-success" role="alert">
			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			Votre mail a bien été transmis aux équipe de homemenus
		</div>
	<?php else : ?>
		<div class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<span class="sr-only">Error:</span>
			Une erreur est survenu lors de l'envoi du mail, veuillez réessayer
		</div>
	<?php endif; ?>
<?php endif; ?>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<p style="text-align : center;">Envoyez-nous vos coordonnées et nous vous contacterons rapidement.</p>
		<form method="post" enctype="x-www-form-urlencoded" id="contactForm" action="">
			<fieldset>
				<div id="entreprise">
					<h3>Votre entreprise</h3>
					<div class="form-group">
						<label for="entreprise">Nom<span class="required">*</span> : </label>
						<input class="form-control" name="entreprise" type="text" value="<?php echo $request->fieldEntreprise !== false ? $request->fieldEntreprise : ''; ?>" required>
					</div>
					<div class="form-group">
						<label for="code_postal">Code postal<span class="required">*</span> : </label>
						<input class="form-control" name="code_postal" type="text" value="<?php echo $request->fieldCP !== false ? $request->fieldCP : ''; ?>" required>
					</div>
					<div class="form-group">
						<label for="ville">Ville<span class="required">*</span> : </label>
						<input class="form-control" name="ville" type="text" value="<?php echo $request->fieldVille !== false ? $request->fieldVille : ''; ?>" required>
					</div>
				</div>
				<h3>Vos coordonnées</h3>
				<div class="form-group">
					<label for="nom">Nom<span class="required">*</span> : </label>
					<input class="form-control" name="nom" type="text" value="<?php echo $request->fieldNom !== false ? $request->fieldNom : ''; ?>" required>
				</div>
				<div class="form-group">
					<label for="prenom">Prenom<span class="required">*</span> : </label>
					<input class="form-control" name="prenom" type="text" value="<?php echo $request->fieldPrenom !== false ? $request->fieldPrenom : ''; ?>" required>
				</div>
				<div class="form-group">
					<label for="telephone">Téléphone<span class="required">*</span> : </label>
					<input class="form-control" name="telephone" type="text" value="<?php echo $request->fieldTelephone !== false ? $request->fieldTelephone : ''; ?>" required>
				</div>
				<div class="form-group">
					<label for="email">Votre email<span class="required">*</span> : </label>
					<input class="form-control" name="email" type="email" value="<?php echo $request->fieldEmail !== false ? $request->fieldEmail : ''; ?>" required>
				</div>
				<div id="divFieldFonction" class="form-group">
					<label for="fonction">Fonction<span class="required">*</span> : </label>
					<input class="form-control" name="fonction" type="text" value="<?php echo $request->fieldFonction !== false ? $request->fieldFonction : ''; ?>" required>
				</div>
				<h3>Informations complémentaires</h3>
				<div class="form-group">
					<label for="message">Message : </label>
					<textarea class="form-control" name="message" rows="8" cols="45"><?php echo $request->fieldMessage !== false ? $request->fieldMessage : ''; ?></textarea>
				</div>
				<div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
				<input name="status" type="text" hidden="hidden" value="user"/>
				<button class="btn btn-primary" type="submit">Envoyer</button>
			</fieldset>
		</form>
	</div>
</div>