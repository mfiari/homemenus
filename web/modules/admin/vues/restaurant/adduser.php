<?php $edit = $request->hasProperty("user"); ?>
<div class="row">
	<div class="col-md-12">
		<form method="post" id="adduser" class="col-md-offset-1 col-md-10" action="" enctype="multipart/form-data">
			<fieldset>
				<?php if ($edit) : ?>
					<legend>Modification</legend>
				<?php else : ?>
					<legend>Ajouter un utilisateur</legend>
				<?php endif; ?>
				<input type="text" name="restaurant" hidden="hidden" value="<?php echo $request->restaurant->id; ?>">
				<input type="text" name="user" hidden="hidden" value="<?php echo $edit ? $request->user->id : 0; ?>">
				<div class="form-group">
					<label for="status">Status<span class="required">*</span> : </label>
					<select name="status">
						<option value="RESTAURANT">Utilisateur</option>
						<option value="ADMIN_RESTAURANT">Administrateur</option>
					</select>
				</div>
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
				<button id="adduser_button" class="btn btn-primary" type="submit">Valider</button>
			</fieldset>
		</form>
	</div>
</div>