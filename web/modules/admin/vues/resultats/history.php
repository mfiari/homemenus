<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h1>Historique</h1>
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="?action=stats">Résultats du jour</a></li>
			<li role="presentation" class="active"><a href="?action=stats_history">Historique</a></li>
		</ul>
		<h2>Générale</h2>
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
					<li role="presentation" class="active"><a href="?action=stats_history">Général</a></li>
					<li role="presentation"><a href="?action=restaurant_history">Par restaurant</a></li>
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
									<td><?php echo number_format($request->resultats['part_restaurant'], 2, ',', ' '); ?></td>
									<td><?php echo number_format($request->resultats['part_livreur'], 2, ',', ' '); ?></td>
									<td><?php echo number_format($request->resultats['total_prix'] - $request->resultats['part_restaurant'] - $request->resultats['part_livreur'], 2, ',', ' '); ?></td>
									<td><?php echo number_format($request->resultats['total_prix'], 2, ',', ' '); ?></td>
								</tr>
							</tbody>
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
										<td><?php echo number_format($month['part_restaurant'], 2, ',', ' '); ?></td>
										<td><?php echo number_format($month['part_livreur'], 2, ',', ' '); ?></td>
										<td><?php echo number_format($month['total_prix'] - $month['part_restaurant'] - $month['part_livreur'], 2, ',', ' '); ?></td>
										<td><?php echo number_format($month['total_prix'], 2, ',', ' '); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
							<tfoot>
								<tr>
									<th>Total</th>
									<th><?php echo $request->resultats['total_commande']; ?></th>
									<th><?php echo number_format($request->resultats['part_restaurant'], 2, ',', ' '); ?></th>
									<th><?php echo number_format($request->resultats['part_livreur'], 2, ',', ' '); ?></th>
									<th><?php echo number_format($request->resultats['total_prix'] - $request->resultats['part_restaurant'] - $request->resultats['part_livreur'], 2, ',', ' '); ?></td>
									<th><?php echo number_format($request->resultats['total_prix'], 2, ',', ' '); ?></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<div class="col-md-10  col-md-offset-1">
					<div class="row">
						<h3>Nouveau client par mois</h3>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Mois</th>
									<th>Nouveau client</th>
									<th>Nouveau client prenium</th>
									<th>Total client</th>
									<th>Total client prenium</th>
								</tr>
							</thead>
							<tbody>
								<?php $totalClient = $request->totalClients; ?>
								<?php foreach ($request->nouveauClients as $nouveauClient) : ?>
									<tr>
										<td><?php echo $nouveauClient['month']; ?></td>
										<td><?php echo $nouveauClient['total']; ?></td>
										<td>0</td>
										<td><?php echo $totalClient; ?></td>
										<td>0</td>
									</tr>
									<?php $totalClient += $nouveauClient['total']; ?>
								<?php endforeach; ?>
							</tbody>
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