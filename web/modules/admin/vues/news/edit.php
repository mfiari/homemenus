<?php $edit = $request->hasProperty("restaurant"); ?>
<div class="row">
	<div class="col-md-12">
		<form method="post" id="add_livreur" class="col-md-offset-1 col-md-10" action="" enctype="multipart/form-data">
			<fieldset>
				<?php if ($edit) : ?>
					<legend>Modification</legend>
				<?php else : ?>
					<legend>Ajouter un livreur</legend>
				<?php endif; ?>
				<input type="text" hidden="hidden" value="<?php echo $edit ? $request->restaurant->id : 0; ?>">
				<div class="form-group">
					<label for="nom">Nom<span class="required">*</span> : </label>
					<input class="form-control" name="nom" type="text" value="<?php echo $request->fieldNom !== false ? $request->fieldNom : ''; ?>" maxlength="32" required>
				</div>
				<div class="form-group">
					<label for="prenom">Prénom<span class="required">*</span> : </label>
					<input class="form-control" name="prenom" type="text" value="<?php echo $request->fieldPrenom !== false ? $request->fieldPrenom : ''; ?>" maxlength="32" required>
				</div>
				<div class="form-group">
					<label for="login">Identifiant<span class="required">*</span> : </label>
					<input class="form-control" name="login" type="text" value="<?php echo $request->fieldPrenom !== false ? $request->fieldPrenom : ''; ?>" maxlength="32" required>
				</div>
				<div class="form-group">
					<label for="email">email<span class="required">*</span> : </label>
					<input class="form-control" name="email" type="email" value="<?php echo $request->fieldEmail !== false ? $request->fieldEmail : ''; ?>" maxlength="32" required>
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