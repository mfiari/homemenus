<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h1>Historique</h1>
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="?action=stats">Résultats du jour</a></li>
			<li role="presentation" class="active"><a href="?action=stats_history">Historique</a></li>
		</ul>
		<h2>Par restaurant</h2>
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
					<li role="presentation" class="active"><a href="?action=restaurant_history">Par restaurant</a></li>
					<li role="presentation"><a href="?action=livreur_history">Par livreur</a></li>
					<li role="presentation"><a href="?action=client_history">Par client</a></li>
					<li role="presentation"><a href="?action=ville_history">Par ville</a></li>
					<li role="presentation"><a href="?action=jour_history">Par jour</a></li>
				</ul>
				<div class="col-md-10  col-md-offset-1">
					<div class="row">
						<h3>Total</h3>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Restaurant</th>
									<th>Nb de commande</th>
									<th>Total restaurant</th>
									<th>Total Livreur</th>
									<th>Total Anomalies</th>
									<th>Total HoMe Menus</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<?php $totalCommande = $partRestaurant = $partLivreur = $anomalie = $totalPrix = 0; ?>
								<?php foreach ($request->restaurants as $restaurant) : ?>
									<tr>
										<td><?php echo utf8_encode($restaurant['nom']); ?></td>
										<td><?php echo $restaurant['total_commande']; ?></td>
										<td><?php echo number_format($restaurant['part_restaurant'] - $restaurant['anomalie'], 2, ',', ' '); ?></td>
										<td><?php echo number_format($restaurant['part_livreur'], 2, ',', ' '); ?></td>
										<td><?php echo number_format($restaurant['anomalie'], 2, ',', ' '); ?></td>
										<td><?php echo number_format($restaurant['total_prix'] - $restaurant['part_restaurant'] - $restaurant['part_livreur'], 2, ',', ' '); ?></td>
										<td><?php echo number_format($restaurant['total_prix'], 2, ',', ' '); ?></td>
									</tr>
									<?php 
										$totalCommande += $restaurant['total_commande'];
										$partRestaurant += $restaurant['part_restaurant'];
										$partLivreur += $restaurant['part_livreur'];
										$anomalie += $restaurant['anomalie'];
										$totalPrix += $restaurant['total_prix'];
									?>
								<?php endforeach; ?>
							</tbody>
							<tfoot>
								<tr>
									<th>Total</th>
									<th><?php echo $totalCommande; ?></th>
									<th><?php echo number_format($partRestaurant - $anomalie, 2, ',', ' '); ?></th>
									<th><?php echo number_format($partLivreur, 2, ',', ' '); ?></th>
									<th><?php echo number_format($anomalie, 2, ',', ' '); ?></th>
									<th><?php echo number_format($totalPrix - $partRestaurant - $partLivreur, 2, ',', ' '); ?></th>
									<th><?php echo number_format($totalPrix, 2, ',', ' '); ?></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<div class="col-md-10  col-md-offset-1">
					<div class="row">
						<h3>Temps moyen de préparation</h3>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Restaurant</th>
									<th>Temps de préparation</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($request->timeRestaurant as $time) : ?>
									<tr>
										<td><?php echo utf8_encode($time['nom']); ?></td>
										<td><?php echo number_format($time['diff'], 2, ',', ' '); ?> min</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-10  col-md-offset-1">
					<div class="row">
						<h3>Total par jour</h3>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Jour</th>
									<th>Heure</th>
									<th>Restaurant</th>
									<th>Nb de commande</th>
									<th>Total restaurant</th>
									<th>Total Livreur</th>
									<th>Total HoMe Menus</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<?php $totalCommande = $partRestaurant = $partLivreur = $totalPrix = 0; ?>
								<?php $weekdayArray = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'); ?>
								<?php foreach ($request->days as $day) : ?>
									<tr>
										<td><?php echo $weekdayArray[$day['weekday']]; ?></td>
										<td><?php echo $day['hour']; ?>h - <?php echo $day['hour']+1; ?>h</td>
										<td><?php echo utf8_encode($day['nom_restaurant']); ?></td>
										<td><?php echo $day['total_commande']; ?></td>
										<td><?php echo number_format($day['part_restaurant'], 2, ',', ' '); ?></td>
										<td><?php echo number_format($day['part_livreur'], 2, ',', ' '); ?></td>
										<td><?php echo number_format($day['total_prix'] - $day['part_restaurant'] - $day['part_livreur'], 2, ',', ' '); ?></td>
										<td><?php echo number_format($day['total_prix'], 2, ',', ' '); ?></td>
									</tr>
									<?php 
										$totalCommande += $day['total_commande'];
										$partRestaurant += $day['part_restaurant'];
										$partLivreur += $day['part_livreur'];
										$totalPrix += $day['total_prix'];
									?>
								<?php endforeach; ?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="3">Total</th>
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