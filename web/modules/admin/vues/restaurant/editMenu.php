<?php $edit = $request->hasProperty("menu"); ?>
<div class="row">
	<div class="col-md-12">
		<form method="post" class="col-md-offset-1 col-md-10" action="?controler=restaurant&action=editMenu" enctype="multipart/form-data">
			<input name="id_restaurant" type="text" value="<?php echo $request->restaurant->id; ?>" hidden="hidden">
			<input name="id_menu" type="text" value="<?php echo $edit ? $request->menu->id : -1; ?>" hidden="hidden">
			<fieldset>
				<?php if ($edit) : ?>
					<legend>Modification</legend>
				<?php else : ?>
					<legend>Ajouter un menu</legend>
				<?php endif; ?>
				<a class="btn btn-primary" href="?controler=restaurant&action=view&id_restaurant=<?php echo $request->restaurant->id; ?>">
					<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
				</a>
				<input type="text" hidden="hidden" value="<?php echo $edit ? $request->contenu->id : 0; ?>">
				<div class="form-group">
					<label for="nom">Nom : </label>
					<input class="form-control" name="nom" type="text" value="<?php echo $edit ? $request->contenu->nom : ""; ?>" />
				</div>
				<div class="form-group">
					<label for="login">Logo : </label>
					<input type="file" name="logo" />
				</div>
				<div class="form-group">
					<label for="commentaire">Commentaire : </label>
					<textarea class="form-control" name="commentaire" ><?php echo $edit ? $request->contenu->commentaire : ""; ?></textarea>
				</div>
				<div id="formats">
					<span>Formats</span>
					<?php foreach ($request->restaurant->formats as $format) : ?>
						<div class="row">
							<div class="col-md-4">
								<input type="checkbox" name="format_<?php echo $format->id; ?>">
								<?php 
									if ($format->nom == '') {
										echo 'Pas de format';
									} else {
										echo utf8_encode($format->nom);
									}
								?>
							</div>
							<div class="col-md-4">
								<label for="format_<?php echo $format->id; ?>_prix">Prix : </label>
								<input type="text" name="format_<?php echo $format->id; ?>_prix" value="0"> €
							</div>
							<div class="col-md-4">
								<label for="format_<?php echo $format->id; ?>_temps">Temps de préparation : </label>
								<input type="text" name="format_<?php echo $format->id; ?>_temps" value="0"> minutes
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div id="formules">
					<span>Formules</span>
					<?php foreach ($request->restaurant->formules as $formule) : ?>
						<div class="row">
							<div class="col-md-12">
								<input type="checkbox" name="formule_<?php echo $formule->id; ?>"><?php echo utf8_encode($formule->nom); ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div id="disponibilite">
					<span>Disponibilité</span>
					<?php foreach ($request->restaurant->horaires as $horaire) : ?>
						<div class="row">
							<div class="col-md-12">
								<input type="checkbox" name="disponibilite_<?php echo $horaire->id; ?>" checked>
								<label><?php echo $horaire->name; ?> : </label>
								<label>De : <?php echo $horaire->heure_debut; ?>h<?php echo $horaire->minute_debut; ?></label>
								<label>à : <?php echo $horaire->heure_fin; ?>h<?php echo $horaire->minute_fin; ?></label>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<button class="btn btn-primary" type="submit">Valider</button>				
				<a class="btn btn-primary" href="?controler=restaurant&action=view&id_restaurant=<?php echo $request->restaurant->id; ?>">
					<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
				</a>
			</fieldset>
		</form>
	</div>
</div>