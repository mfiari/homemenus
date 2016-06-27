<?php $edit = $request->hasProperty("code_promo"); ?>
<div class="row">
	<div class="col-md-12">
		<form method="post" id="addCodePromo" class="col-md-offset-1 col-md-10" action="" enctype="multipart/form-data">
			<fieldset>
				<?php if ($edit) : ?>
					<legend>Modification</legend>
				<?php else : ?>
					<legend>Ajouter un code promo</legend>
				<?php endif; ?>
				<input type="text" name="id_code_promo" hidden="hidden" value="<?php echo $edit ? $request->code_promo->id : -1; ?>">
				<div class="form-group">
					<label for="code">Code : </label>
					<input class="form-control" name="code" type="text" value="<?php echo $edit ? utf8_encode($request->code_promo->code) : ""; ?>" />
				</div>
				<div class="form-group">
					<label for="description">Description : </label>
					<textarea class="form-control" name="description" ><?php echo $edit ? utf8_encode($request->code_promo->description) : ""; ?></textarea>
				</div>
				<div class="form-group">
					<label for="date_debut">Date début : </label>
					<input class="datepicker" type="text" name="date_debut" value="<?php echo $edit ? $request->code_promo->date_debut : ""; ?>">
				</div>
				<div class="form-group">
					<label for="date_fin">Date fin : </label>
					<input class="datepicker" type="text" name="date_fin" value="<?php echo $edit ? $request->code_promo->date_fin : ""; ?>">
				</div>
				<div class="form-group">
					<label for="publique">Publique ? : </label>
					<input type="checkbox" name="publique" <?php echo $edit && $request->code_promo->publique ? 'checked' : ""; ?>>
				</div>
				<div class="form-group">
					<label for="sur_restaurant">Sur restaurant ? : </label>
					<input type="checkbox" name="sur_restaurant" <?php echo $edit && $request->code_promo->sur_restaurant ? 'checked' : ""; ?>>
				</div>
				<div class="form-group">
					<label for="type_reduction">Type reduction : </label>
					<select name="type_reduction">
						<option value="REDUCTION" <?php echo $edit && $request->code_promo->type_reduc == 'REDUCTION' ? 'selected' : ""; ?>>Réduction</option>
						<option value="GRATUIT" <?php echo $edit && $request->code_promo->type_reduc == 'GRATUIT' ? 'selected' : ""; ?>>Gratuit</option>
					</select>
				</div>
				<div class="form-group">
					<label for="sur_prix_livraison">Sur prix de livraison ? : </label>
					<input type="checkbox" name="sur_prix_livraison" <?php echo $edit && $request->code_promo->sur_prix_livraison ? 'checked' : ""; ?>>
				</div>
				<div class="form-group">
					<label for="valeur_prix_livraison">Valeur : </label>
					<input class="form-control" name="valeur_prix_livraison" type="text" value="<?php echo $edit ? $request->code_promo->valeur_prix_livraison : ""; ?>" />
				</div>
				<div class="form-group">
					<label for="sur_prix_total">Sur prix total ? : </label>
					<input type="checkbox" name="sur_prix_total" <?php echo $edit && $request->code_promo->sur_prix_total ? 'checked' : ""; ?>>
				</div>
				<div class="form-group">
					<label for="valeur_prix_total">Valeur : </label>
					<input class="form-control" name="valeur_prix_total" type="text" value="<?php echo $edit ? $request->code_promo->valeur_prix_total : ""; ?>" />
				</div>
				<div class="form-group">
					<label for="pourcentage_prix_total">Pourcentage : </label>
					<input class="form-control" name="pourcentage_prix_total" type="text" value="<?php echo $edit ? $request->code_promo->pourcentage_prix_total : ""; ?>" />
				</div>
				<button class="btn btn-primary" type="submit">Valider</button>
			</fieldset>
		</form>
	</div>
</div>
<script>
	$(function() {
		$(".datepicker").datepicker({
			closeText: 'Fermer',
			prevText: 'Précédent',
			nextText: 'Suivant',
			currentText: 'Aujourd\'hui',
			monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
			monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
			dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
			dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
			dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
			weekHeader: 'Sem.',
			dateFormat: 'dd/mm/yy'
		});
	});
</script>