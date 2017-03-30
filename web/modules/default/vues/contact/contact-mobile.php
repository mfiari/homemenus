<h2>Contact</h2>
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
	<div class="col-md-11 col-md-offset-1">
		<h2>Une question ? Contactez-nous</h2>
		<form method="post" enctype="x-www-form-urlencoded" id="contactForm" action="">
			<fieldset>
				<div class="form-group">
					<label for="sujet">Sujet<span class="required">*</span> : </label>
					<select name="sujet">
					<?php foreach ($request->sujets as $key => $sujet) : ?>
						<option value="<?php echo $key; ?>" <?php echo $request->sujet !== false && $request->sujet == $key ? 'selected' : ''; ?>><?php echo $sujet; ?></option>
					<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="email">Votre email<span class="required">*</span> : </label>
					<input class="form-control" name="email" type="email" value="<?php echo $request->fieldEmail !== false ? $request->fieldEmail : ''; ?>" required>
				</div>
				<div class="form-group">
					<label for="message">Message<span class="required">*</span> : </label>
					<textarea class="form-control" name="message" rows="8" cols="45" required><?php echo $request->fieldMessage !== false ? $request->fieldMessage : ''; ?></textarea>
				</div>
				<div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
				<input name="status" type="text" hidden="hidden" value="user"/>
				<button class="send-button" type="submit">Envoyer <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></button>
			</fieldset>
		</form>
	</div>
</div>