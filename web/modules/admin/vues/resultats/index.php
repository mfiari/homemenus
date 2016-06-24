<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h2>Résultats du jour</h2>
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="?action=stats">Résultats du jour</a></li>
			<li role="presentation"><a href="?action=stats_history">Historique</a></li>
		</ul>
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
				</table>
			</div>
		</div>
	</div>
</div>