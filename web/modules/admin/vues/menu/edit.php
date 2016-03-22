<?php $edit = $request->hasProperty("restaurant"); ?>
<div class="row">
	<div class="col-md-12">
		<form method="post" id="ajout_restaurant" class="col-md-offset-1 col-md-10" action="?module=admin&controler=restaurant&action=edit" enctype="multipart/form-data">
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
					<input type="file" name="logo" id="logo" />
				</div>
				<div class="form-group">
					<label for="rue">Rue : </label>
					<input class="form-control" name="rue" placeholder="ex : 5 avenue basilique" type="text" value="<?php echo $edit ? $request->restaurant->rue : ""; ?>" />
				</div>
				<div class="form-group">
					<label for="ville">Ville : </label>
					<input class="form-control" name="ville" placeholder="ex : Mantes-la-jolie" type="text" value="<?php echo $edit ? $request->restaurant->ville : ""; ?>" />
				</div>
				<div class="form-group">
					<label for="code_postal">Code postal : </label>
					<input class="form-control" name="code_postal" placeholder="ex : 78820" type="text" value="<?php echo $edit ? $request->restaurant->cp : ""; ?>" />
				</div>
				<div class="form-group">
					<label for="telephone">Téléphone : </label>
					<input class="form-control" name="telephone" type="text" value="<?php echo $edit ? $request->restaurant->telephone : ""; ?>" />
				</div>
				<div id="horraires">
					<span>Horaires</span>
					<?php 
						$jours = array("lundi" => "Lundi", "mardi" => "Mardi", "mercredi" => "Mercredi", "jeudi" => "Jeudi", "vendredi" => "Vendredi", "samedi" => "Samedi", "dimanche" => "Dimanche");
						$times = array("matin" => "matin", "apres_midi" => "après-midi");
					?>
					<?php foreach ($jours as $jkey => $jour) : ?>
						<?php foreach ($times as $tkey => $time) : ?>
							<div class="row">
								<div class="col-md-2">
									<label><?php echo $jour.' '.$time; ?> : </label>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>De : </label>
										<input class="form-control" name="de_<?php echo $jkey; ?>_<?php echo $tkey; ?>_heure" type="text" value="" size="2" />
										<label>h</label>
										<input class="form-control" name="de_<?php echo $jkey; ?>_<?php echo $tkey; ?>_minute" type="text" value="00" size="2" />
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>à : </label>
										<input class="form-control" name="a_<?php echo $jkey; ?>_<?php echo $tkey; ?>_heure" type="text" value="" size="2" />
										<label>h</label>
										<input class="form-control" name="a_<?php echo $jkey; ?>_<?php echo $tkey; ?>_minute" type="text" value="00" size="2" />
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<input type="checkbox" name="ferme_<?php echo $jkey; ?>_<?php echo $tkey; ?>" value="1"> Fermé
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</div>
				<button class="btn btn-primary" type="submit">Valider</button>
			</fieldset>
		</form>
	</div>
</div>