<?php $edit = $request->hasProperty("news"); ?>
<div class="row">
	<div class="col-md-12">
		<form method="post" class="col-md-offset-1 col-md-10" action="" enctype="multipart/form-data">
			<fieldset>
				<?php if ($edit) : ?>
					<legend>Modification</legend>
				<?php else : ?>
					<legend>Ajouter une news</legend>
				<?php endif; ?>
				<input type="text" name="id" hidden="hidden" value="<?php echo $edit ? $request->news->id : -1; ?>">
				<div class="form-group">
					<label for="titre">Titre<span class="required">*</span> : </label>
					<input class="form-control" name="titre" type="text" value="<?php echo $request->fieldTitre !== false ? $request->fieldTitre : ''; ?>" maxlength="100" required>
				</div>
				<div class="form-group">
					<label for="texte">Texte<span class="required">*</span> : </label>
					<textarea class="form-control" name="texte" rows="8" cols="45" required><?php echo $request->fieldTexte !== false ? $request->fieldTexte : ''; ?></textarea>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<img style="width : 50px" src="">
						</div>
						<div class="col-md-6">
							<label for="image">Logo : </label>
							<input type="file" name="image" />
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="link_text">Texte du lien<span class="required">*</span> : </label>
					<input class="form-control" name="link_text" type="text" value="<?php echo $request->fieldLinkText !== false ? $request->fieldLinkText : ''; ?>" maxlength="100" required>
				</div>
				<div class="form-group">
					<label for="link_url">Url du lien<span class="required">*</span> : </label>
					<input class="form-control" name="link_url" type="text" value="<?php echo $request->fieldLinkUrl !== false ? $request->fieldLinkUrl : ''; ?>" maxlength="100" required>
				</div>
				<div class="form-group">
					<label for="date_debut">Date début : </label>
					<input class="form-control datepicker" type="text" name="date_debut" value="<?php echo $edit ? $request->news->date_debut : ""; ?>">
				</div>
				<div class="form-group">
					<label for="date_fin">Date fin : </label>
					<input class="form-control datepicker" type="text" name="date_fin" value="<?php echo $edit ? $request->news->date_fin : ""; ?>">
				</div>
				<button class="btn btn-primary" type="submit">Valider</button>
			</fieldset>
		</form>
	</div>
</div>