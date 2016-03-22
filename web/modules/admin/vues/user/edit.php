<?php $edit = $request->hasProperty("restaurant"); ?>
<div class="row">
	<div class="col-md-12">
		<form method="post" id="ajout_restaurant" class="col-md-offset-1 col-md-10" action="" enctype="multipart/form-data">
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
										<input type="checkbox" name="ferme_<?php echo $horaire->id; ?>" value="1"> Indisponible
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
										<input type="checkbox" name="ferme_<?php echo $jkey; ?>"> Indisponible
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<div id="perimetre">
					<span>Perimetre</span>
					<?php for ($i = 1; $i <= 5 ; $i++) : ?>
						<div class="row">
							<span>Code postal : </span><input type="text" name="per_cp_<?php echo $i; ?>">
							<span>Ville : </span><input type="text" name="per_ville_<?php echo $i; ?>">
						</div>
					<?php endfor; ?>
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