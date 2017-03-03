<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h1>Résultats du jour</h1>
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="?action=stats">Résultats du jour</a></li>
			<li role="presentation"><a href="?action=stats_history">Historique</a></li>
		</ul>
		<h2>Par client</h2>
		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-tabs">
					<li role="presentation"><a href="?action=stats">Général</a></li>
					<li role="presentation"><a href="?action=restaurant">Par restaurant</a></li>
					<li role="presentation"><a href="?action=livreur">Par livreur</a></li>
					<li role="presentation" class="active"><a href="?action=client">Par client</a></li>
					<li role="presentation"><a href="?action=ville">Par ville</a></li>
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
										<td><?php echo utf8_encode($client['nom']); ?> <?php echo utf8_encode($client['prenom']); ?></td>
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