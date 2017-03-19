<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h1>Résultats du jour</h1>
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="?action=stats">Résultats du jour</a></li>
			<li role="presentation"><a href="?action=stats_history">Historique</a></li>
		</ul>
		<h2>Générale</h2>
		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-tabs">
					<li role="presentation" class="active"><a href="?action=stats">Général</a></li>
					<li role="presentation"><a href="?action=restaurant">Par restaurant</a></li>
					<li role="presentation"><a href="?action=livreur">Par livreur</a></li>
					<li role="presentation"><a href="?action=client">Par client</a></li>
					<li role="presentation"><a href="?action=ville">Par ville</a></li>
				</ul>
				<div class="col-md-10  col-md-offset-1">
					<div class="row">
						<h3>Livreurs disponible</h3>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Nom</th>
									<th>Horaire</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($request->livreursDispo as $livreur) : ?>
									<tr>
										<td><?php echo utf8_encode($livreur->nom); ?> <?php echo utf8_encode($livreur->prenom); ?> (<?php echo $livreur->login; ?>)</td>
										<td>De <?php echo $livreur->dispos->heure_debut; ?>h<?php echo $livreur->dispos->minute_debut; ?> à 
											<?php echo $livreur->dispos->heure_fin; ?>h<?php echo $livreur->dispos->minute_fin; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-10  col-md-offset-1">
					<div class="row">
						<h3>Restaurant sans livreur</h3>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Nom</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($request->restaurants as $restaurant) : ?>
									<tr>
										<td><span style="color : red"><?php echo utf8_encode($restaurant->nom); ?></span></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
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
						<h3>Nouveau client</h3>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Nouveau client</th>
									<th>Nouveau client prenium</th>
									<th>Total client</th>
									<th>Total client prenium</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo count($request->nouveauClients); ?></td>
									<td>0</td>
									<td><?php echo $request->nbClients; ?></td>
									<td>0</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>