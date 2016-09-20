<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h2>Historique</h2>
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="?action=stats">Résultats du jour</a></li>
			<li role="presentation" class="active"><a href="?action=stats_history">Historique</a></li>
		</ul>
		<div>
			<form method="post" enctype="x-www-form-urlencoded" action="">
				<span>Début : </span><input class="datepicker" type="text" name="date_debut" value="<?php echo $request->date_debut ? $request->date_debut : ''; ?>">
				<span>Fin : </span><input class="datepicker" type="text" name="date_fin" value="<?php echo $request->date_fin ? $request->date_fin : ''; ?>">
				<button class="btn btn-primary" type="submit">Valider</button>
			</form>
		</div>
		<div class="col-md-10  col-md-offset-1">
			<div class="row">
				<h2>Total</h2>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Nb de commande</th>
							<th>Total restaurant</th>
							<th>Total Livreur</th>
							<th>Total HoMe Menus</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $request->resultats['total_commande']; ?></td>
							<td><?php echo $request->resultats['part_restaurant']; ?></td>
							<td><?php echo $request->resultats['part_livreur']; ?></td>
							<td><?php echo $request->resultats['total_prix'] - $request->resultats['part_restaurant'] - $request->resultats['part_livreur']; ?></td>
							<td><?php echo $request->resultats['total_prix']; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-md-10  col-md-offset-1">
			<div class="row">
				<h2>Total par jour et par restaurants</h2>
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
						<?php $weekdayArray = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'); ?>
						<?php foreach ($request->days as $day) : ?>
							<tr>
								<td><?php echo $weekdayArray[$day['weekday']]; ?></td>
								<td><?php echo $day['hour']; ?>h - <?php echo $day['hour']+1; ?>h</td>
								<td><?php echo utf8_encode($day['nom_restaurant']); ?></td>
								<td><?php echo $day['total_commande']; ?></td>
								<td><?php echo $day['part_restaurant']; ?></td>
								<td><?php echo $day['part_livreur']; ?></td>
								<td><?php echo $day['total_prix'] - $day['part_restaurant'] - $day['part_livreur']; ?></td>
								<td><?php echo $day['total_prix']; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="3">Total</th>
							<th><?php echo $request->resultats['total_commande']; ?></th>
							<th><?php echo $request->resultats['part_restaurant']; ?></th>
							<th><?php echo $request->resultats['part_livreur']; ?></th>
							<th><?php echo $request->resultats['total_prix'] - $request->resultats['part_restaurant'] - $request->resultats['part_livreur']; ?></td>
							<th><?php echo $request->resultats['total_prix']; ?></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="col-md-10  col-md-offset-1">
			<div class="row">
				<h2>Total par mois</h2>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Mois</th>
							<th>Nb de commande</th>
							<th>Total restaurant</th>
							<th>Total Livreur</th>
							<th>Total HoMe Menus</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($request->months as $month) : ?>
							<tr>
								<td><?php echo $month['month']; ?></td>
								<td><?php echo $month['total_commande']; ?></td>
								<td><?php echo $month['part_restaurant']; ?></td>
								<td><?php echo $month['part_livreur']; ?></td>
								<td><?php echo $month['total_prix'] - $month['part_restaurant'] - $month['part_livreur']; ?></td>
								<td><?php echo $month['total_prix']; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th>Total</th>
							<th><?php echo $request->resultats['total_commande']; ?></th>
							<th><?php echo $request->resultats['part_restaurant']; ?></th>
							<th><?php echo $request->resultats['part_livreur']; ?></th>
							<th><?php echo $request->resultats['total_prix'] - $request->resultats['part_restaurant'] - $request->resultats['part_livreur']; ?></td>
							<th><?php echo $request->resultats['total_prix']; ?></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="col-md-10  col-md-offset-1">
			<div class="row">
				<h2>Par livreur</h2>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Livreur</th>
							<th>Nb de commande</th>
							<th>Total restaurant</th>
							<th>Total Livreur</th>
							<th>Total HoMe Menus</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($request->livreurs as $livreur) : ?>
							<tr>
								<td><?php echo utf8_encode($livreur['nom']); ?></td>
								<td><?php echo $livreur['total_commande']; ?></td>
								<td><?php echo $livreur['part_restaurant']; ?></td>
								<td><?php echo $livreur['part_livreur']; ?></td>
								<td><?php echo $livreur['total_prix'] - $livreur['part_restaurant'] - $livreur['part_livreur']; ?></td>
								<td><?php echo $livreur['total_prix']; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th>Total</th>
							<th><?php echo $request->resultats['total_commande']; ?></th>
							<th><?php echo $request->resultats['part_restaurant']; ?></th>
							<th><?php echo $request->resultats['part_livreur']; ?></th>
							<th><?php echo $request->resultats['total_prix'] - $request->resultats['part_restaurant'] - $request->resultats['part_livreur']; ?></td>
							<th><?php echo $request->resultats['total_prix']; ?></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="col-md-10  col-md-offset-1">
			<div class="row">
				<h2>Par restaurant</h2>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Restaurant</th>
							<th>Nb de commande</th>
							<th>Total restaurant</th>
							<th>Total Livreur</th>
							<th>Total HoMe Menus</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($request->restaurants as $restaurant) : ?>
							<tr>
								<td><?php echo utf8_encode($restaurant['nom']); ?></td>
								<td><?php echo $restaurant['total_commande']; ?></td>
								<td><?php echo $restaurant['part_restaurant']; ?></td>
								<td><?php echo $restaurant['part_livreur']; ?></td>
								<td><?php echo $restaurant['total_prix'] - $restaurant['part_restaurant'] - $restaurant['part_livreur']; ?></td>
								<td><?php echo $restaurant['total_prix']; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th>Total</th>
							<th><?php echo $request->resultats['total_commande']; ?></th>
							<th><?php echo $request->resultats['part_restaurant']; ?></th>
							<th><?php echo $request->resultats['part_livreur']; ?></th>
							<th><?php echo $request->resultats['total_prix'] - $request->resultats['part_restaurant'] - $request->resultats['part_livreur']; ?></td>
							<th><?php echo $request->resultats['total_prix']; ?></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="col-md-10  col-md-offset-1">
			<div class="row">
				<h2>Par client</h2>
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
						<?php foreach ($request->clients as $client) : ?>
							<tr>
								<td><?php echo $client['nom']; ?> <?php echo $client['prenom']; ?></td>
								<td><?php echo $client['total_commande']; ?></td>
								<td><?php echo $client['part_restaurant']; ?></td>
								<td><?php echo $client['part_livreur']; ?></td>
								<td><?php echo $client['total_prix'] - $client['part_restaurant'] - $client['part_livreur']; ?></td>
								<td><?php echo $client['total_prix']; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th>Total</th>
							<th><?php echo $request->resultats['total_commande']; ?></th>
							<th><?php echo $request->resultats['part_restaurant']; ?></th>
							<th><?php echo $request->resultats['part_livreur']; ?></th>
							<th><?php echo $request->resultats['total_prix'] - $request->resultats['part_restaurant'] - $request->resultats['part_livreur']; ?></td>
							<th><?php echo $request->resultats['total_prix']; ?></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="col-md-10  col-md-offset-1">
			<div class="row">
				<h2>Par ville</h2>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Ville</th>
							<th>Nb de commande</th>
							<th>Total restaurant</th>
							<th>Total Livreur</th>
							<th>Total HoMe Menus</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($request->villes as $ville) : ?>
							<tr>
								<td><?php echo $ville['nom']; ?> (<?php echo $ville['cp']; ?>)</td>
								<td><?php echo $ville['total_commande']; ?></td>
								<td><?php echo $ville['part_restaurant']; ?></td>
								<td><?php echo $ville['part_livreur']; ?></td>
								<td><?php echo $ville['total_prix'] - $ville['part_restaurant'] - $ville['part_livreur']; ?></td>
								<td><?php echo $ville['total_prix']; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th>Total</th>
							<th><?php echo $request->resultats['total_commande']; ?></th>
							<th><?php echo $request->resultats['part_restaurant']; ?></th>
							<th><?php echo $request->resultats['part_livreur']; ?></th>
							<th><?php echo $request->resultats['total_prix'] - $request->resultats['part_restaurant'] - $request->resultats['part_livreur']; ?></td>
							<th><?php echo $request->resultats['total_prix']; ?></th>
						</tr>
					</tfoot>
				</table>
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