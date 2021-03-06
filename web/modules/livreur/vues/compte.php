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
		<?php if ($request->modifyPasswordSuccess) : ?>
			<div class="alert alert-success" role="alert">
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
				Votre mot de passe a été modifié avec succès.
			</div>
		<?php endif; ?>
		<div class="col-md-12">
			<div class="row">
				<h3>Mes informations</h3>
				<form method="post" enctype="x-www-form-urlencoded" id="userForm" action="">
					<fieldset>
						<div class="form-group">
							<label for="nom">Nom : </label>
							<input class="form-control" name="nom" type="text" value="<?php echo utf8_encode($request->user->nom); ?>" maxlength="32" required>
						</div>
						<div class="form-group">
							<label for="prenom">Prénom : </label>
							<input class="form-control" name="prenom" type="text" value="<?php echo utf8_encode($request->user->prenom); ?>" maxlength="32" required>
						</div>
						<div class="form-group">
							<label for="login">Identifiant : </label>
							<input class="form-control" name="login" type="text" value="<?php echo $request->user->login; ?>" maxlength="32" required>
						</div>
						<div class="form-group">
							<label for="email">email : </label>
							<input class="form-control" name="email" type="email" value="<?php echo $request->user->email; ?>" maxlength="32" required>
						</div>
						<div class="form-group">
							<label for="telephone">Téléphone : </label>
							<input class="form-control" name="telephone" type="text" value="<?php echo $request->user->telephone; ?>" maxlength="10" >
						</div>
						<button class="btn btn-primary" type="submit" id="userFormValidationButton" >Modifier</button>
					</fieldset>
				</form>
			</div>
			<div class="row">
				<h3>Mes horaires</h3>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Adresse de départ</th>
							<th>Périmètre</th>
							<th>Véhicule</th>
							<th>Horaires</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($request->user->dispos as $dispo) : ?>
							<tr>
								<th colspan="4" style="background-color : #337ab7; text-align : center;"><?php echo $dispo->jour; ?></th>
							</tr>
							<tr>
								<td><?php echo utf8_encode($dispo->rue); ?>, <?php echo $dispo->code_postal; ?> <?php echo utf8_encode($dispo->ville); ?></td>
								<td><?php echo $dispo->perimetre; ?> KM</td>
								<td><?php echo $dispo->vehicule; ?></td>
								<td>De <?php echo $dispo->heure_debut; ?>h<?php echo $dispo->minute_debut; ?> à <?php echo $dispo->heure_fin; ?>h<?php echo $dispo->minute_fin; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<form method="post" enctype="x-www-form-urlencoded" action="?controler=compte&action=modifyPassword">
				<h3>Modifier mon mot de passe</h3>
				<fieldset>
					<div class="form-group">
						<label for="password">Ancien Mot de passe : </label>
						<input class="form-control" id="old_password" name="old_password" type="password" maxlength="32" required>
					</div>
					<div class="form-group">
						<label for="password">Nouveau Mot de passe : </label>
						<input class="form-control" id="new_password" name="new_password" type="password" maxlength="32" required>
					</div>
					<div class="form-group">
						<label for="confirm_password">Confirmer Mot de passe : </label>
						<input class="form-control" name="confirm_password" type="password" maxlength="32" required>
					</div>
					<button class="btn btn-primary" type="submit">Modifier</button>
				</fieldset>
			</form>
		</div>
	</div>
</div>