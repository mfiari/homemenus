<ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="#">Commandes</a></li>
  <li role="presentation"><a href="?controler=commentaire&action=restaurants">Restaurants</a></li>
  <li role="presentation"><a href="?controler=commentaire&action=plats">Plats</a></li>
</ul>
<h2>Commentaire Commandes</h2>
<div id="commandes">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Numéro de commande</th>
				<th>Restaurant</th>
				<th>Livreur</th>
				<th>Client</th>
				<th>Date de commande</th>
				<th>Notes</th>
				<th>Commentaire</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($request->commandes as $commande) : ?>
				<tr class="commande">
					<td>#<?php echo $commande->id; ?></td>
					<td><?php echo utf8_encode($commande->restaurant->nom); ?></td>
					<td><?php echo utf8_encode($commande->livreur->login); ?></td>
					<td><?php echo utf8_encode($commande->client->nom); ?> <?php echo utf8_encode($commande->client->prenom); ?></td>
					<td><?php echo $commande->date_commande; ?></td>
					<td><?php echo $commande->note; ?> / 5</td>
					<td><?php echo utf8_encode($commande->commentaire); ?></td>
					<td>
						<?php if ($commande->validation_commentaire) : ?>
							<a href="?controler=commentaire&action=annule&id_commande=<?php echo $commande->id; ?>">
								<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							</a>
						<?php else : ?>
							<a href="?controler=commentaire&action=enable&id_commande=<?php echo $commande->id; ?>">
								<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>