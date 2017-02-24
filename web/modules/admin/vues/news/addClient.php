<div class="row">
	<div class="col-md-12">
		<form method="post" class="col-md-offset-1 col-md-10" action="" enctype="multipart/form-data">
			<fieldset>
				<legend>Ajouter un client</legend>
				<?php if ($request->errorMessage) : ?>
					<?php foreach ($request->errorMessage as $key => $value) : ?>
						<div class="alert alert-danger" role="alert">
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							<span class="sr-only">Error:</span>
							<?php echo $value; ?>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
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
					<label for="rue">Adresse : </label>
					<div class="search-block">
						<input id="full_address" class="form-control" name="adresse" type="text" value="<?php echo $request->fieldAdresse !== false ? $request->fieldAdresse : ''; ?>" placeholder="Entrez votre adresse">
						<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
					</div>
					<input id="rue" name="rue" type="text" value="<?php echo $request->fieldRue !== false ? $request->fieldRue : ''; ?>" hidden="hidden">
					<input id="ville" name="ville" type="text" value="<?php echo $request->fieldVille !== false ? $request->fieldVille : ''; ?>" hidden="hidden">
					<input id="code_postal" name="code_postal" type="text" value="<?php echo $request->fieldCP !== false ? $request->fieldCP : ''; ?>" hidden="hidden">
				</div>
				<div class="form-group">
					<label for="telephone">Téléphone : </label>
					<input class="form-control" name="telephone" type="text" value="<?php echo $request->fieldTel !== false ? $request->fieldTel : ''; ?>" maxlength="10" >
				</div>
				<button class="btn btn-primary" type="submit">Valider</button>
			</fieldset>
		</form>
	</div>
</div>