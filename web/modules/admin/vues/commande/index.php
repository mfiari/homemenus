<div class="row">
	<div class="col-md-12">
		<h2>Liste des commandes</h2>
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a>Commande du jour</a></li>
			<li role="presentation"><a href="?controler=commande&action=history">Historique des commandes</a></li>
		</ul>
		<div id="commandes">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Numéro</th>
						<th>Livreur</th>
						<th>Restaurant</th>
						<th>client</th>
						<th>Ville</th>
						<th>Date de commande</th>
						<th>Prix</th>
						<th>Vue par livreur</th>
						<th>Statut</th>
						<th>Note</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php $total = 0; ?>
					<?php foreach ($request->commandes as $commande) : ?>
						<tr>
							<td><a href="?controler=commande&action=view&id_commande=<?php echo $commande->id; ?>">#<?php echo $commande->id; ?></a></td>
							<td><a href="?controler=user&action=view&id_user=<?php echo $commande->livreur->id; ?>"><?php echo utf8_encode($commande->livreur->login); ?></a></td>
							<td><a href="?controler=restaurant&action=view&id_restaurant=<?php echo $commande->restaurant->id; ?>"><?php echo utf8_encode($commande->restaurant->nom); ?></a></td>
							<td><a href="?controler=user&action=client&id_user=<?php echo $commande->client->id; ?>"><?php echo $commande->client->id; ?></a></td>
							<td><?php echo utf8_encode($commande->restaurant->ville); ?> (<?php echo $commande->restaurant->code_postal; ?>)</td>
							<td><?php echo $commande->date_commande; ?></td>
							<td><?php echo $commande->prix; ?> €</td>
							<td><?php echo $commande->date_validation_livreur != '0000-00-00 00:00:00' ? $commande->date_validation_livreur : '<span style="color : red; ">Non</span>'; ?></td>
							<td><?php echo $commande->getStatus(); ?></td>
							<td><?php echo $commande->note; ?> / 5</td>
							<td>
								<a href="?controler=commande&action=view&id_commande=<?php echo $commande->id; ?>">
									<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
								</a>
								<a href="?controler=commande&action=annule&id_commande=<?php echo $commande->id; ?>">
									<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
								</a>
								<a href="?controler=commande&action=renew&id_commande=<?php echo $commande->id; ?>">
									<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
								</a>
								<a href="?controler=commande&action=remove&id_commande=<?php echo $commande->id; ?>">
									<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
								</a>
							</td>
						</tr>
						<?php $total += $commande->prix; ?>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="6">Total : </th>
						<th><?php echo $total; ?> €</th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>