<h2>Contact</h2>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<?php if ($request->hasProperty("mailSuccess")) : ?>
			<?php if ($request->mailSuccess === true) : ?>
				<div class="alert alert-success" role="alert">
					<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
					Votre mail a bien été transmis aux équipe de cservichezvous
				</div>
			<?php else : ?>
				<div class="alert alert-danger" role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					Une erreur est survenu lors de l'envoi du mail, veuillez réessayer
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<form method="post" enctype="x-www-form-urlencoded" id="contactForm" action="">
			<fieldset>
				<h3>Vos coordonnées</h3>
				<div class="form-group">
					<label for="nom">Nom<span class="required">*</span> : </label>
					<input class="form-control" name="nom" type="text" required>
				</div>
				<div class="form-group">
					<label for="prenom">Prenom<span class="required">*</span> : </label>
					<input class="form-control" name="prenom" type="text" required>
				</div>
				<div class="form-group">
					<label for="telephone">Téléphone<span class="required">*</span> : </label>
					<input class="form-control" name="telephone" type="text" required>
				</div>
				<div class="form-group">
					<label for="fonction">Moyens de transport : </label>
					<input class="form-control" name="aucun" type="checkbox">Aucun
					<input class="form-control" name="velo" type="checkbox">Vélo
					<input class="form-control" name="voiture" type="checkbox">Voiture
					<input class="form-control" name="autre" type="checkbox">autre<input class="form-control" name="transport" type="text">
				</div>
				<h3>Informations complémentaires</h3>
				<div class="form-group">
					<label for="message">Message : </label>
					<textarea class="form-control" name="message" rows="8" cols="45" required></textarea>
				</div>
				<div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
				<input name="status" type="text" hidden="hidden" value="user"/>
				<button class="btn btn-primary" type="submit">Envoyer</button>
			</fieldset>
		</form>
	</div>
</div>