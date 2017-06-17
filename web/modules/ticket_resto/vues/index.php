<div class="col-md-12">
	<h2>Titres restaurant</h2>
	<div>
		<form method="GET" enctype="x-www-form-urlencoded" action="">
			<span>Début : </span><input class="datepicker" type="text" name="date_debut" value="<?php echo $request->date_debut ? $request->date_debut : ''; ?>">
			<span>Fin : </span><input class="datepicker" type="text" name="date_fin" value="<?php echo $request->date_fin ? $request->date_fin : ''; ?>">
			<button class="btn btn-primary" type="submit">Valider</button>
		</form>
	</div>
	<div class="row">
		<p><b>Nombre de ticket restaurant : </b><?php echo $request->result["quantite"] == '' ? '0' : $request->result["quantite"]; ?></p>
		<p><b>Montant total : </b><?php echo $request->result["montant"] == '' ? '0,00 €' : $request->result["montant"]; ?></p>
	</div>
	<div class="row">
		<div class="col-md-10  col-md-offset-1">
			<div class="row">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Mois</th>
							<th>Restaurant</th>
							<th>Quantite</th>
							<th>Montant</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php $quantiteTotal = 0; $prixTotal = 0; ?>
						<?php foreach ($request->restaurants as $restaurant) : ?>
							<tr>
								<td><?php
									if ($restaurant['month'] == '' || $restaurant['year'] == '') {
										echo getMonthByIndex(date('m')).' '.date('Y');
									} else {
										echo getMonthByIndex($restaurant['month']).' '.$restaurant['year']; 
									}
								?></td>
								<td><?php echo utf8_encode($restaurant['nom']); ?> (<?php echo $restaurant['siret']; ?>)</td>
								<td><?php echo $restaurant['quantite_total']; ?></td>
								<td><?php echo number_format($restaurant['prix_total'], 2, ',', ' ').' €'; ?></td>
								<td><a href="?action=exportContrat&id_restaurant=<?php echo $restaurant['id']; ?>">
										<span data-toggle="tooltip" title="Télécharger le contrat" class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>
									</a>
								</td>
							</tr>
							<?php $quantiteTotal += $restaurant['quantite_total']; ?>
							<?php $prixTotal += $restaurant['prix_total']; ?>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="2">Total</th>
							<th><?php echo $quantiteTotal; ?></th>
							<th><?php echo number_format($prixTotal, 2, ',', ' ').' €'; ?></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
	<div class="row">
		<a class="validate-button" href="?action=exportExcel&date_debut=<?php echo $request->date_debut; ?>&date_fin=<?php echo $request->date_fin; ?>">Export Excel</a>
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