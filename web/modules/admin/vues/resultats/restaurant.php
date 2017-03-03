<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h1>Résultats du jour</h1>
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="?action=stats">Résultats du jour</a></li>
			<li role="presentation"><a href="?action=stats_history">Historique</a></li>
		</ul>
		<h2>Par restaurant</h2>
		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-tabs">
					<li role="presentation"><a href="?action=stats">Général</a></li>
					<li role="presentation" class="active"><a href="?action=restaurant">Par restaurant</a></li>
					<li role="presentation"><a href="?action=livreur">Par livreur</a></li>
					<li role="presentation"><a href="?action=client">Par client</a></li>
					<li role="presentation"><a href="?action=ville">Par ville</a></li>
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
									<th>Total HoMe Menus</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<?php $totalCommande = $partRestaurant = $partLivreur = $totalPrix = 0; ?>
								<?php foreach ($request->restaurants as $restaurant) : ?>
									<tr>
										<td><?php echo utf8_encode($restaurant['nom']); ?></td>
										<td><?php echo $restaurant['total_commande']; ?></td>
										<td><?php echo number_format($restaurant['part_restaurant'], 2, ',', ' '); ?></td>
										<td><?php echo number_format($restaurant['part_livreur'], 2, ',', ' '); ?></td>
										<td><?php echo number_format($restaurant['total_prix'] - $restaurant['part_restaurant'] - $restaurant['part_livreur'], 2, ',', ' '); ?></td>
										<td><?php echo number_format($restaurant['total_prix'], 2, ',', ' '); ?></td>
									</tr>
									<?php 
										$totalCommande += $restaurant['total_commande'];
										$partRestaurant += $restaurant['part_restaurant'];
										$partLivreur += $restaurant['part_livreur'];
										$totalPrix += $restaurant['total_prix'];
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