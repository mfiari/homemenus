<?php $edit = $request->hasProperty("restaurant"); ?>
<div class="row">
	<div class="col-md-12">
		<form method="post" id="ajout_restaurant" class="col-md-offset-1 col-md-10" action="?controler=restaurant&action=edit" enctype="multipart/form-data">
			<fieldset>
				<?php if ($edit) : ?>
					<legend>Modification</legend>
				<?php else : ?>
					<legend>Ajouter un restaurant</legend>
				<?php endif; ?>
				<input type="text" hidden="hidden" value="<?php echo $edit ? $request->restaurant->id : 0; ?>">
				<div class="form-group">
					<label for="nom">Nom : </label>
					<input class="form-control" name="nom" type="text" value="<?php echo $edit ? $request->restaurant->nom : ""; ?>" />
				</div>
				<div class="form-group">
					<label for="login">Logo : </label>
					<input type="file" name="logo" />
				</div>
				<div class="form-group">
					<label for="adresse">Adresse : </label>
					<input id="full_address" class="form-control" name="adresse" type="text" value="<?php echo $edit ? $request->restaurant->rue.', '.$request->restaurant->code_postal.' '.$request->restaurant->ville : ""; ?>" placeholder="Entrez votre adresse">
					<input id="rue" name="rue" type="text" value="<?php echo $edit ? $request->restaurant->rue : ""; ?>" hidden="hidden">
					<input id="ville" name="ville" type="text" value="<?php echo $edit ? $request->restaurant->ville : ""; ?>" hidden="hidden">
					<input id="code_postal" name="code_postal" type="text" value="<?php echo $edit ? $request->restaurant->code_postal : ""; ?>" hidden="hidden">
					<input id="latitude" name="latitude" type="text" value="<?php echo $edit ? $request->restaurant->latitude : ""; ?>" hidden="hidden">
					<input id="longitude" name="longitude" type="text" value="<?php echo $edit ? $request->restaurant->longitude : ""; ?>" hidden="hidden">
				</div>
				<div class="form-group">
					<label for="telephone">Téléphone : </label>
					<input class="form-control" name="telephone" type="text" value="<?php echo $edit ? $request->restaurant->telephone : ""; ?>" />
				</div>
				<div class="form-group">
					<label for="short_desc">Description courte : </label>
					<input class="form-control" name="short_desc" type="text" value="<?php echo $edit ? $request->restaurant->short_desc : ""; ?>" />
				</div>
				<div class="form-group">
					<label for="long_desc">Description : </label>
					<textarea class="form-control" name="long_desc" ><?php echo $edit ? $request->restaurant->long_desc : ""; ?></textarea>
				</div>
				<div id="horraires">
					<span>Horaires</span>
					<?php if ($edit) : ?>
						<?php foreach ($request->restaurant->horaires as $horaire) : ?>
							<div class="row">
								<div class="col-md-2">
									<label><?php echo $horaire->name; ?> : </label>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>De : </label>
										<input class="form-control" name="de_<?php echo $horaire->id; ?>_heure" type="text" value="<?php echo $horaire->heure_debut; ?>" size="2" />
										<label>h</label>
										<input class="form-control" name="de_<?php echo $horaire->id; ?>_minute" type="text" value="<?php echo $horaire->minute_debut; ?>" size="2" />
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>à : </label>
										<input class="form-control" name="a_<?php echo $horaire->id; ?>_heure" type="text" value="<?php echo $horaire->heure_fin; ?>" size="2" />
										<label>h</label>
										<input class="form-control" name="a_<?php echo $horaire->id; ?>_minute" type="text" value="<?php echo $horaire->minute_fin; ?>" size="2" />
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<input type="checkbox" name="ferme_<?php echo $horaire->id; ?>" value="1"> Fermé
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php else : ?>
						<?php 
							$jours = array(1 => "Lundi", 2 => "Mardi", 3 => "Mercredi", 4 => "Jeudi", 5 => "Vendredi", 6 => "Samedi", 7 => "Dimanche");
						?>
						<?php foreach ($jours as $jkey => $jour) : ?>
							<div class="row">
								<div class="col-md-2">
									<label><?php echo $jour; ?> : </label>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>De : </label>
										<input class="form-control" name="de_<?php echo $jkey; ?>_heure" type="text" value="" size="2" />
										<label>h</label>
										<input class="form-control" name="de_<?php echo $jkey; ?>_minute" type="text" value="00" size="2" />
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>à : </label>
										<input class="form-control" name="a_<?php echo $jkey; ?>_heure" type="text" value="" size="2" />
										<label>h</label>
										<input class="form-control" name="a_<?php echo $jkey; ?>_minute" type="text" value="00" size="2" />
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<input type="checkbox" name="ferme_<?php echo $jkey; ?>"> Fermé
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<button id="add_restaurant_button" class="btn btn-primary" type="button">Valider</button>
			</fieldset>
		</form>
	</div>
</div>
<script type="text/javascript">
	//enableAutocomplete ('full_address');
	$("#add_restaurant_button").click(function(event) {
		/*var addressComponents = getAdresseElements();
		var rue = addressComponents.street_number + ' ' + addressComponents.route;
		var ville = addressComponents.locality;
		var code_postal = addressComponents.postal_code;
		var lat = addressComponents.lat;
		var lon = addressComponents.lon;*/
		var rue = "22 rue du commerce";
		var ville = "Juziers";
		var code_postal = "78820";
		var lat = 0;
		var lon = 0;
		$("#rue").val(rue);
		$("#ville").val(ville);
		$("#code_postal").val(code_postal);
		$("#latitude").val(latitude);
		$("#longitude").val(lon);
		$("#ajout_restaurant").submit();
	});
</script>