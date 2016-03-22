<?php $edit = $request->hasProperty("contenu"); ?>
<div class="row">
	<div class="col-md-12">
		<form method="post" class="col-md-offset-1 col-md-10" action="?controler=restaurant&action=editContenuMenu" enctype="multipart/form-data">
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
				<div class="form-group">
					<label for="contenu">Contenu<span class="required">*</span> : </label>
					<?php if ($edit) : ?>
						<input class="form-control" name="nom" type="text" value="<?php echo utf8_encode($request->contenu->nom); ?>" />
					<?php else : ?>
						<select name="contenu">
							<?php foreach ($request->restaurant->categories AS $categorie) : ?>
								<?php foreach ($categorie->contenus AS $contenu) : ?>
									<option value="<?php echo $contenu->id; ?>"><?php echo utf8_encode($contenu->nom); ?></option>
								<?php endforeach ; ?>
							<?php endforeach ; ?>
						</select>
					<?php endif; ?>
				</div>
				<div class="form-group">
					<label for="obligatoire">Obligatoire : </label>
					<input name="obligatoire" type="checkbox" <?php echo $edit && $request->contenu->is_visible ? 'checked' : ""; ?> />
				</div>
				<div class="form-group">
					<label for="ville">Commentaire : </label>
					<textarea class="form-control" name="commentaire" ><?php echo $edit ? $request->contenu->commentaire : ""; ?></textarea>
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
				</div>
				<button class="btn btn-primary" type="submit">Valider</button>
				<a class="btn btn-primary" href="?controler=restaurant&action=viewCategorie&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $request->categorie->id; ?>">
					<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
				</a>
			</fieldset>
		</form>
	</div>
</div>