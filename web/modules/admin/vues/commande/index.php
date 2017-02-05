<div class="row">
	<div class="col-md-12">
		<h2>Liste des commandes</h2>
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a>Commande du jour</a></li>
			<li role="presentation"><a href="?controler=commande&action=history">Historique des commandes</a></li>
		</ul>
		<div id="commandes">
			<form method="post" action="?controler=commande&action=create">
				<button class="btn btn-primary" type="submit">Créer une nouvelle commande</button>
			</form>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Numéro</th>
						<th>Livreur</th>
						<th>Restaurant</th>
						<th>client</th>
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
							<td><a href="?controler=commande&action=view&id_commande=<?php echo $commande->id; ?>">
								#<?php echo $commande->id; ?>
							</a></td>
							<td><a href="?controler=user&action=view&id_user=<?php echo $commande->livreur->id; ?>">
								<?php echo utf8_encode($commande->livreur->prenom); ?> (<?php echo utf8_encode($commande->livreur->login); ?>)
							</a></td>
							<td><a href="?controler=restaurant&action=view&id_restaurant=<?php echo $commande->restaurant->id; ?>">
								<?php echo utf8_encode($commande->restaurant->nom); ?> (<?php echo utf8_encode($commande->restaurant->ville); ?>)
							</a></td>
							<td><a href="?controler=user&action=client&id_user=<?php echo $commande->client->id; ?>">
								<?php echo utf8_encode($commande->client->nom); ?> <?php echo utf8_encode($commande->client->prenom); ?>
								(<?php echo utf8_encode($commande->ville); ?>)
							</a></td>
							<td><?php echo $commande->date_commande; ?></td>
							<td><?php echo $commande->prix; ?> €</td>
							<td><?php echo $commande->date_validation_livreur != '0000-00-00 00:00:00' ? $commande->date_validation_livreur : '<span style="color : red; ">Non</span>'; ?></td>
							<td><?php echo $commande->getStatus(); ?></td>
							<td><?php echo $commande->note; ?> / 5</td>
							<td>
								<a href="?controler=commande&action=view&id_commande=<?php echo $commande->id; ?>">
									<span data-toggle="tooltip" title="Détail de la commande" class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
								</a>
								<a href="?controler=commande&action=annule&id_commande=<?php echo $commande->id; ?>">
									<span data-toggle="tooltip" title="Annuler de la commande" class="glyphicon glyphicon-remove" aria-hidden="true"></span>
								</a>
								<a href="?controler=commande&action=renew&id_commande=<?php echo $commande->id; ?>">
									<span data-toggle="tooltip" title="Recréer de la commande" class="glyphicon glyphicon-edit" aria-hidden="true"></span>
								</a>
								<a href="?controler=commande&action=remove&id_commande=<?php echo $commande->id; ?>">
									<span data-toggle="tooltip" title="Supprimer de la commande" class="glyphicon glyphicon-trash" aria-hidden="true"></span>
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
		<div>
			<form method="post" action="?controler=commande&action=rapport_commandes">
				<fieldset>
					<div class="form-group">
						<label for="nom">Restaurant : </label>
						<select name="restaurant">
							<option value=""></option>
							<?php foreach ($request->restaurants as $restaurant) : ?>
								<option value="<?php echo $restaurant->id; ?>"><?php echo $restaurant->nom; ?> (<?php echo $restaurant->ville; ?>)</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<span>Début : </span><input class="datepicker" type="text" name="date_debut" value="">
						<span>Fin : </span><input class="datepicker" type="text" name="date_fin" value="">
					</div>
					<div class="form-group">
						<label for="eùail">Email : </label>
						<input type="text" name="email" value="admin@homemenus.fr">
					</div>
					<button class="btn btn-primary" type="submit">Generer rapport commandes</button>
				</fieldset>
			</form>
		</div>
	</div>
</div>