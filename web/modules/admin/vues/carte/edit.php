<?php $edit = $request->hasProperty("carte"); ?>
<div class="row">
	<div class="col-md-12">
		<form method="post" id="ajout_produit_carte" class="col-md-offset-1 col-md-10" action="?module=admin&controler=carte&action=edit" enctype="multipart/form-data">
			<fieldset>
				<?php if ($edit) : ?>
					<legend>Modification</legend>
				<?php else : ?>
					<legend>Ajouter un produit à la carte</legend>
				<?php endif; ?>
				<input type="text" name="id_carte" hidden="hidden" value="<?php echo $edit ? $request->carte->id : 0; ?>">
				<input type="text" name="id_restaurant" hidden="hidden" value="<?php echo $edit ? $request->carte->id_restaurant : $request->id_restaurant; ?>">
				<div class="form-group">
					<label for="nom">Nom : </label>
					<input class="form-control" name="nom" type="text" value="<?php echo $edit ? $request->carte->nom : ""; ?>" />
				</div>
				<div class="form-group">
					<label for="image">Image : </label>
					<input type="file" name="image" />
				</div>
				<div class="form-group">
					<label for="prix">prix : </label>
					<input class="form-control" name="prix" type="text" value="<?php echo $edit ? $request->carte->prix : ""; ?>" />
				</div>
				<div class="form-group">
					<label for="temps_preparation">Temps de préparation : </label>
					<input class="form-control" name="temps_preparation" type="text" value="<?php echo $edit ? $request->carte->temps_preparation : ""; ?>" />
				</div>
				<div class="form-group">
					<label for="commentaire">Commentaire : </label>
					<input class="form-control" name="commentaire" type="text" value="<?php echo $edit ? $request->carte->commentaire : ""; ?>" />
				</div>
				<button class="btn btn-primary" type="submit">Valider</button>
			</fieldset>
		</form>
	</div>
</div>