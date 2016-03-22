<h2>Commandes</h2>
<div>
	<form method="post" enctype="x-www-form-urlencoded" action="">
		<span>Début : </span><input class="datepicker" type="text" name="date_debut" value="<?php echo $request->date_debut ? $request->date_debut : ''; ?>">
		<span>Fin : </span><input class="datepicker" type="text" name="date_fin" value="<?php echo $request->date_fin ? $request->date_fin : ''; ?>">
		<button class="btn btn-primary" type="submit">Valider</button>
	</form>
</div>
<div id="commandes">
	<?php $total = 0; ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Numéro de commande</th>
				<th>Date de commande</th>
				<th>Temps de préparation</th>
				<th>Prix</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($request->commandes as $commande) : ?>
				<tr class="commande">
					<td>#<?php echo $commande->id; ?></td>
					<td><?php echo $commande->date_commande; ?></td>
					<td></td>
					<td><?php echo $commande->prix; ?> €</td>
					<td>
						<a href="?controler=commande&action=detail&id=<?php echo $commande->id;?>"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
					</td>
				</tr>
				<?php $total += $commande->prix; ?>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="3">Total : </th>
				<th><?php echo $total; ?> €</th>
				<th></th>
			</tr>
		</tfoot>
	</table>
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