<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h1>Historique</h1>
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="?action=stats">Résultats du jour</a></li>
			<li role="presentation" class="active"><a href="?action=stats_history">Historique</a></li>
		</ul>
		<h2>Par client</h2>
		<div>
			<form method="post" enctype="x-www-form-urlencoded" action="">
				<span>Début : </span><input class="datepicker" type="text" name="date_debut" value="<?php echo $request->date_debut ? $request->date_debut : ''; ?>">
				<span>Fin : </span><input class="datepicker" type="text" name="date_fin" value="<?php echo $request->date_fin ? $request->date_fin : ''; ?>">
				<button class="btn btn-primary" type="submit">Valider</button>
			</form>
		</div>
		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-tabs">
					<li role="presentation"><a href="?action=stats_history">Général</a></li>
					<li role="presentation"><a href="?action=restaurant_history">Par restaurant</a></li>
					<li role="presentation"><a href="?action=livreur_history">Par livreur</a></li>
					<li role="presentation" class="active"><a href="?action=client_history">Par client</a></li>
					<li role="presentation"><a href="?action=ville_history">Par ville</a></li>
					<li role="presentation"><a href="?action=jour_history">Par jour</a></li>
				</ul>
				<div class="col-md-10  col-md-offset-1">
					<div class="row">
						<h3>Total</h3>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Client</th>
									<th>Nb de commande</th>
									<th>Total restaurant</th>
									<th>Total Livreur</th>
									<th>Total HoMe Menus</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<?php $totalCommande = $partRestaurant = $partLivreur = $totalPrix = 0; ?>
								<?php foreach ($request->clients as $client) : ?>
									<tr>
										<td><?php echo $client['nom']; ?> <?php echo $client['prenom']; ?></td>
										<td><?php echo $client['total_commande']; ?></td>
										<td><?php echo number_format($client['part_restaurant'], 2, ',', ' '); ?></td>
										<td><?php echo number_format($client['part_livreur'], 2, ',', ' '); ?></td>
										<td><?php echo number_format($client['total_prix'] - $client['part_restaurant'] - $client['part_livreur'], 2, ',', ' '); ?></td>
										<td><?php echo number_format($client['total_prix'], 2, ',', ' '); ?></td>
									</tr>
									<?php 
										$totalCommande += $client['total_commande'];
										$partRestaurant += $client['part_restaurant'];
										$partLivreur += $client['part_livreur'];
										$totalPrix += $client['total_prix'];
									?>
								<?php endforeach; ?>
							</tbody>
							<tfoot>
								<tr>
									<th>Total</th>
									<th><?php echo $totalCommande; ?></th>
									<th><?php echo number_format($partRestaurant, 2, ',', ' '); ?></th>
									<th><?php echo number_format($partLivreur, 2, ',', ' '); ?></th>
									<th><?php echo number_format($totalPrix - $partRestaurant - $partLivreur, 2, ',', ' '); ?></th>
									<th><?php echo number_format($totalPrix, 2, ',', ' '); ?></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
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