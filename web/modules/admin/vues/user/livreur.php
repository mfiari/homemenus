<div class="row">
	<div class="col-md-12">
		<a class="btn btn-primary" href="?controler=user&action=livreurs">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
		<div class="row">
			<h2>Coordonnées</h2>
			<p>Nom : <?php echo utf8_encode($request->livreur->nom); ?> <?php echo utf8_encode($request->livreur->prenom); ?></p>
			<p>Email : <?php echo $request->livreur->email; ?></p>
			<p>Telephone : <?php echo $request->livreur->telephone; ?></p>
		</div>
		<div class="row">
			<h2>Périmètre</h2>
			<?php foreach ($request->livreur->perimetres as $perimetre) : ?>
				<p><?php echo $perimetre->ville; ?> <?php echo $perimetre->code_postal; ?></p>
			<?php endforeach; ?>
		</div>
		<div class="row">
			<h2>Horaires</h2>
			<?php foreach ($request->livreur->horaires as $horaire) : ?>
				<p><?php echo $horaire->name; ?> : de <?php echo $horaire->heure_debut; ?>h<?php echo $horaire->minute_debut; ?> à <?php echo $horaire->heure_fin; ?>h<?php echo $horaire->minute_fin; ?></p>
			<?php endforeach; ?>
		</div>
		<div class="row">
			<h2>Trajet</h2>
		</div>
		<div class="row">
			<h2>Commande du jour</h2>
			<div id="commandes">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Numéro</th>
						<th>Client</th>
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
							<td><a href="?controler=user&action=view&id_user=<?php echo $commande->client->id; ?>"><?php echo utf8_encode($commande->client->nom); ?> <?php echo utf8_encode($commande->client->prenom); ?></a>
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
							<th>Ville</th>
							<th>Date de commande</th>
							<th>Prix</th>
							<th>Note</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php $total = 0; ?>
					<?php foreach ($request->commandesHistory as $commande) : ?>
						<tr>
							<td><a href="?controler=commande&action=view&id_commande=<?php echo $commande->id; ?>"><?php echo $commande->id; ?></a></td>
							<td><a href="?controler=user&action=view&id_user=<?php echo $commande->client->id; ?>"><?php echo utf8_encode($commande->client->nom); ?> <?php echo utf8_encode($commande->client->prenom); ?></a>
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
		<a class="btn btn-primary" href="?controler=user&action=livreurs">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
	</div>
</div>