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
			<h3>Information du restaurant</h3>
			<form method="post" enctype="x-www-form-urlencoded" id="adminForm" action="?action=updateInformation">
				<fieldset>
					<div class="form-group">
						<label for="nom">Nom du restaurant : </label>
						<input class="form-control" name="nom" type="text" value="<?php echo $request->restaurant->nom; ?>" maxlength="32" required>
						<?php if ($request->restaurant->nom_modification != '') : ?>
							<span>Le nom a été modifié, sa nouvelle valeur est <?php echo $request->restaurant->nom_modification; ?> et sera prise en compte ce soir. <a href="?action=cancelModification&id=<?php echo $request->restaurant->nom_modification_id; ?>">annuler la modification</a></span>
						<?php endif; ?>
					</div>
					<div class="form-group">
						<label for="rue">Adresse : </label>
						<input id="full_address" class="form-control" name="adresse" type="text" value="<?php echo $request->restaurant->rue.', '.$request->restaurant->code_postal.' '.$request->restaurant->ville; ?>" placeholder="Entrez votre adresse">
						<input id="rue" name="rue" type="text" value="<?php echo $request->restaurant->rue; ?>" hidden="hidden">
						<input id="ville" name="ville" type="text" value="<?php echo $request->restaurant->ville; ?>" hidden="hidden">
						<input id="code_postal" name="code_postal" type="text" value="<?php echo $request->restaurant->code_postal; ?>" hidden="hidden">
					</div>
					<div class="form-group">
						<label for="telephone">Téléphone : </label>
						<input class="form-control" name="telephone" type="text" value="<?php echo $request->restaurant->telephone; ?>" maxlength="10" >
					</div>
					<div class="form-group">
						<label>Courte description : </label>
						<span><?php echo $request->restaurant->short_desc; ?></span>
					</div>
					<div class="form-group">
						<label>Description : </label>
						<span><?php echo $request->restaurant->long_desc; ?></span>
					</div>
					<div class="form-group">
						<label>Pourcentage : </label>
						<span><?php echo $request->restaurant->pourcentage; ?> %</span>
					</div>
					<div class="form-group">
						<label>Virement : </label>
						<span><?php echo $request->restaurant->virement; ?></span>
					</div>
				</fieldset>
			</form>
		</div>
		<div class="row">
			<h3>horaire</h3>
			<form method="post" enctype="x-www-form-urlencoded" id="adminForm" action="?action=inscription">
				<fieldset>
					<?php $jour = -1; ?>
					<?php foreach ($request->restaurant->horaires as $horaire) : ?>
						<?php if ($jour != $horaire->id_jour) : ?>
							<span><?php echo $horaire->name; ?></span>
							<?php $jour = $horaire->id_jour; ?>
						<?php endif; ?>
						<div class="row">
							<div class="col-md-6">
								<label>De</label><input name="nom" type="text" value="<?php echo $horaire->heure_debut; ?>">:<input name="nom" type="text" value="<?php echo $horaire->minute_debut; ?>">
							</div>
							<div class="col-md-6">
								<label>à</label><input name="nom" type="text" value="<?php echo $horaire->heure_fin; ?>">:<input name="nom" type="text" value="<?php echo $horaire->minute_fin; ?>">
							</div>
						</div>
					<?php endforeach; ?>
					<button id="subscribe-button" class="btn btn-primary" type="button">Valider</button>
				</fieldset>
			</form>
		</div>
		<div class="row">
			<h3>Information du compte</h3>
			<form method="post" enctype="x-www-form-urlencoded" id="adminForm" action="?action=inscription">
				<fieldset>
					<div class="form-group">
						<label for="nom">Nom<span class="required">*</span> : </label>
						<input class="form-control" name="nom" type="text" value="<?php echo $request->restaurant->user->nom; ?>" maxlength="32" required>
					</div>
					<div class="form-group">
						<label for="prenom">Prénom<span class="required">*</span> : </label>
						<input class="form-control" name="prenom" type="text" value="<?php echo $request->restaurant->user->prenom; ?>" maxlength="32" required>
					</div>
					<div class="form-group">
						<label for="login">email<span class="required">*</span> : </label>
						<input class="form-control" name="login" type="email" value="<?php echo $request->restaurant->user->login; ?>" maxlength="32" required>
					</div>
					<div class="form-group">
						<label for="password">Mot de passe<span class="required">*</span> : </label>
						<input class="form-control" id="password" name="password" type="password" maxlength="32" required>
					</div>
					<div class="form-group">
						<label for="confirm_password">Confirmer Mot de passe<span class="required">*</span> : </label>
						<input class="form-control" name="confirm_password" type="password" maxlength="32" required>
					</div>
					<button id="subscribe-button" class="btn btn-primary" type="button">Inscription</button>
				</fieldset>
			</form>
		</div>
	</div>
</div>