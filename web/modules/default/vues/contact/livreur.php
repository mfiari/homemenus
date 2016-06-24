<h2>Devenir livreur</h2>
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
			Votre message a bien été transmis aux équipes d'HoMe Menus
		</div>
	<?php else : ?>
		<div class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<span class="sr-only">Error:</span>
			Une erreur est survenu lors de l'envoi du message, veuillez réessayer
		</div>
	<?php endif; ?>
<?php endif; ?>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<p style="text-align : center;">Vous êtes sportif, dynamique et avez envie de vous faire un extra, rejoignez l’aventure HoMe Menus. Envoyez-nous vos coordonnées et nous vous contacterons rapidement.</p>
		<form method="post" enctype="x-www-form-urlencoded" id="contactForm" action="">
			<fieldset>
				<h3>Vos coordonnées</h3>
				<div class="form-group">
					<label for="nom">Nom<span class="required">*</span> : </label>
					<input class="form-control" name="nom" type="text" value="<?php echo $request->fieldNom !== false ? $request->fieldNom : ''; ?>" required>
				</div>
				<div class="form-group">
					<label for="prenom">Prénom<span class="required">*</span> : </label>
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
				<div class="form-group">
					<label for="fonction">Moyens de transport : </label><br /><br />
					<input style="margin-right : 15px;" name="velo" type="checkbox">Vélo<br />
					<input style="margin-right : 15px;" name="voiture" type="checkbox">Voiture<br />
					<input style="margin-right : 15px;" name="scooter" type="checkbox">Scooter<br />
					<input style="margin-right : 15px;" name="autre" type="checkbox">Autre(s)
					<input class="form-control" name="transport" type="text">
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