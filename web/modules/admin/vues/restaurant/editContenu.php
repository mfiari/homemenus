<?php $edit = $request->hasProperty("contenu"); ?>
<div class="row">
	<div class="col-md-12">
		<form method="post" class="col-md-offset-1 col-md-10" action="?controler=restaurant&action=editContenu" enctype="multipart/form-data">
			<input name="id_restaurant" type="text" value="<?php echo $request->restaurant->id; ?>" hidden="hidden">
			<input name="id_categorie" type="text" value="<?php echo $request->categorie->id; ?>" hidden="hidden">
			<input name="id_contenu" type="text" value="<?php echo $edit ? $request->contenu->id : -1; ?>" hidden="hidden">
			<fieldset>
				<?php if ($edit) : ?>
					<legend>Modification</legend>
				<?php else : ?>
					<legend>Ajouter un contenu</legend>
				<?php endif; ?>
				<a class="btn btn-primary" href="?controler=restaurant&action=viewCategorie&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $request->categorie->id; ?>">
					<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
				</a>
				<input type="text" hidden="hidden" value="<?php echo $edit ? $request->contenu->id : 0; ?>">
				<div class="form-group">
					<label for="nom">Nom : </label>
					<input class="form-control" name="nom" type="text" value="<?php echo $edit ? utf8_encode($request->contenu->nom) : ""; ?>" />
				</div>
				<div class="form-group">
					<label for="login">Logo : </label>
					<input type="file" name="logo" />
				</div>
				<div class="form-group">
					<label for="is_visible">Produit affiché à la carte : </label>
					<input name="is_visible" type="checkbox" <?php echo $edit && $request->contenu->is_visible ? 'checked' : ""; ?> />
				</div>
				<div class="form-group">
					<label for="ville">Commentaire : </label>
					<textarea class="form-control" name="commentaire" ><?php echo $edit ? utf8_encode($request->contenu->commentaire) : ""; ?></textarea>
				</div>
				<div id="formats">
					<h3>Formats</h3>
					<?php foreach ($request->restaurant->formats as $format) : ?>
						<?php 
							if ($edit) {
								$carteFormat = null;
								foreach ($request->contenu->formats as $contenuFormat) {
									if ($contenuFormat->id == $format->id) {
										$carteFormat = $contenuFormat;
										break;
									}
								}
							}
						?>
						<div class="row">
							<div class="col-md-4">
								<input type="checkbox" name="format_<?php echo $format->id; ?>" <?php echo $edit && $carteFormat !== null ? 'checked' : ""; ?>>
								<?php 
									if ($format->nom == '') {
										echo 'Pas de format';
									} else {
										echo utf8_encode($format->nom);
									}
								?>
							</div>
							<div class="col-md-3">
								<label for="format_<?php echo $format->id; ?>_prix">Prix : </label>
								<input type="text" name="format_<?php echo $format->id; ?>_prix" value="<?php echo $edit && $carteFormat !== null ? $carteFormat->prix : ""; ?>"> €
							</div>
							<div class="col-md-5">
								<label for="format_<?php echo $format->id; ?>_temps">Temps de préparation : </label>
								<input type="text" name="format_<?php echo $format->id; ?>_temps" value="<?php echo $edit && $carteFormat !== null ? $carteFormat->temps_preparation : ""; ?>"> minutes
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div id="accompagnement">
					<h3>accompagnement</h3>
					<?php foreach ($request->restaurant->categories as $categorie) : ?>
						<?php 
							if ($edit) {
								$carteAccompagnement = null;
								foreach ($request->contenu->accompagnements as $accompagnement) {
									if ($accompagnement->id_categorie == $categorie->id) {
										$carteAccompagnement = $accompagnement;
										break;
									}
								}
							}
						?>
						<div class="row">
							<h4><?php echo utf8_encode($categorie->nom); ?></h4>
							<div class="form-group">
								<label for="limite_accompagnement">Limite accompagnement : </label>
								<input name="limite_accompagnement_<?php echo $categorie->id; ?>" type="text" value="<?php echo $edit && $carteAccompagnement !== null ? $carteAccompagnement->limite : 0; ?>" />
							</div>
							<?php foreach ($categorie->contenus as $contenu) : ?>
								<?php 
									if ($edit) {
										$carteAccompagnementCarte = null;
										if ($carteAccompagnement !== null) {
											foreach ($carteAccompagnement->cartes as $carte) {
												if ($carte->id == $contenu->id) {
													$carteAccompagnementCarte = $carte;
													break;
												}
											}
										}
									}
								?>
								<div class="col-md-12">
									<input type="checkbox" name="accompagnement_<?php echo $categorie->id; ?>_<?php echo $contenu->id; ?>" <?php echo $edit && $carteAccompagnementCarte !== null ? 'checked' : ""; ?>><?php echo utf8_encode($contenu->nom); ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>
				<div id="supplements">
					<h3>Supplements</h3>
					<div class="form-group">
						<label for="limite_supplement">Limite supplement : </label>
						<input name="limite_supplement" type="text" value="<?php echo $edit ? $request->contenu->limite_supplement : ""; ?>" />
					</div>
					<?php foreach ($request->restaurant->supplements as $supplement) : ?>
						<div class="row">
							<div class="col-md-12">
								<input type="checkbox" name="supplement_<?php echo $supplement->id; ?>"><?php echo utf8_encode($supplement->nom); ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div id="options">
					<h3>Options</h3>
					<?php foreach ($request->restaurant->options as $option) : ?>
						<?php 
							if ($edit) {
								$carteOption = null;
								foreach ($request->contenu->options as $contenuOption) {
									if ($contenuOption->id == $option->id) {
										$carteOption = $contenuOption;
										break;
									}
								}
							}
						?>
						<div class="row">
							<div class="col-md-12">
								<input type="checkbox" name="option_<?php echo $option->id; ?>" <?php echo $edit && $carteOption !== null ? 'checked' : ""; ?>><?php echo utf8_encode($option->nom); ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div id="disponibilite">
					<h3>Disponibilité</h3>
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
				<a class="btn btn-primary" href="?controler=restaurant&action=viewCategorie&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $request->categorie->id; ?>">
					<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
				</a>
			</fieldset>
		</form>
	</div>
</div>