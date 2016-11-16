<div class="row">
	<div class="col-md-12">
		<a class="btn btn-primary" href="?controler=user&action=clients">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
		<div class="row">
			<h2>Coordonnées</h2>
			<p>Nom : <?php echo utf8_encode($request->client->nom); ?> <?php echo utf8_encode($request->client->prenom); ?></p>
			<p>Email : <?php echo $request->client->email; ?></p>
			<p>Adresse : <?php echo utf8_encode($request->client->rue); ?>, <?php echo $request->client->code_postal; ?> <?php echo utf8_encode($request->client->ville); ?></p>
			<p>Telephone : <?php echo $request->client->telephone; ?></p>
		</div>
		<div class="row">
			<h2>Commande du jour</h2>
			<div id="commandes">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Numéro</th>
						<th>Livreur</th>
						<th>Restaurant</th>
						<th>Ville</th>
						<th>Date de commande</th>
						<th>Prix</th>
						<th>Note</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php $total = 0; ?>
					<?php foreach ($request->commandes as $commande) : ?>
						<tr>
							<td><a href="?controler=commande&action=view&id_commande=<?php echo $commande->id; ?>"><?php echo $commande->id; ?></a></td>
							<td>
								<?php if ($commande->livreur) : ?>
									<a href="?controler=user&action=view&id_user=<?php echo $commande->livreur->id; ?>"><?php echo utf8_encode($commande->livreur->login); ?></a>
								<?php endif; ?>
							</td>
							<td><a href="?controler=restaurant&action=view&id_restaurant=<?php echo $commande->restaurant->id; ?>"><?php echo utf8_encode($commande->restaurant->nom); ?></a></td>
							<td><?php echo utf8_encode($commande->restaurant->ville); ?> (<?php echo $commande->restaurant->code_postal; ?>)</td>
							<td><?php echo $commande->date_commande; ?></td>
							<td><?php echo $commande->prix; ?> €</td>
							<td><?php echo $commande->note; ?> / 5</td>
							<td>
								<a href="?controler=commande&action=view&id_commande=<?php echo $commande->id; ?>">
									<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
								</a>
								<a href="?controler=user&action=delete&id_user=<?php echo $commande->id; ?>">
									<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
								</a>
							</td>
						</tr>
						<?php $total += $commande->prix; ?>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="5">Total : </th>
						<th><?php echo $total; ?> €</th>
						<th></th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		</div>
		</div>
		<div class="row">
			<h2>Historique des commandes</h2>
			<div id="commandes">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Numéro</th>
							<th>Livreur</th>
							<th>Restaurant</th>
							<th>Adresse de commande</th>
							<th>Date de commande</th>
							<th>Prix</th>
							<th>Prix de livraison</th>
							<th>Note</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php $total = 0; ?>
						<?php foreach ($request->commandesHistory as $commande) : ?>
							<tr>
								<td><a href="?controler=commande&action=viewHistory&id_commande=<?php echo $commande->id; ?>">#<?php echo $commande->id; ?></a></td>
								<td><a href="?controler=user&action=view&id_user=<?php echo $commande->livreur->id; ?>">
									<?php echo utf8_encode($commande->livreur->nom); ?> <?php echo utf8_encode($commande->livreur->prenom); ?> (<?php echo utf8_encode($commande->livreur->login); ?>)
								</a></td>
								<td><a href="?controler=restaurant&action=view&id_restaurant=<?php echo $commande->restaurant->id; ?>">
									<?php echo utf8_encode($commande->restaurant->nom); ?> (<?php echo utf8_encode($commande->restaurant->ville); ?>)
								</a></td>
								<td><?php echo utf8_encode($commande->rue); ?>, <?php echo $commande->code_postal; ?> <?php echo utf8_encode($commande->ville); ?></td>
								<td><?php echo $commande->date_commande; ?></td>
								<td><?php echo $commande->prix; ?> €</td>
								<td><?php echo $commande->prix_livraison; ?> €</td>
								<td><?php echo $commande->note; ?> / 5</td>
								<td>
									<a href="?controler=commande&action=viewHistory&id_commande=<?php echo $commande->id; ?>">
										<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
									</a>
								</td>
							</tr>
							<?php $total += $commande->prix; ?>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="5">Total : </th>
							<th><?php echo $total; ?> €</th>
							<th></th>
							<th></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<a class="btn btn-primary" href="?controler=user&action=clients">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
	</div>
</div>