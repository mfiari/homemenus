<ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="#">Commande du jour</a></li>
  <li role="presentation"><a href="?controler=commande&action=history">Historique des commandes</a></li>
</ul>
<h2>Commandes du jour</h2>
<div id="commandes">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Numéro de commande</th>
				<th>Restaurant</th>
				<th>Client</th>
				<th>Status</th>
				<th>Date</th>
				<th>Heure souhaité</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($request->commandes as $commande) : ?>
				<tr class="commande">
					<td>#<?php echo $commande->id; ?></td>
					<td><?php echo $commande->restaurant->nom; ?> (<?php echo $commande->restaurant->ville; ?>)</td>
					<td><?php echo $commande->client->prenom; ?> (<?php echo $commande->ville; ?>)</td>
					<td><?php echo $commande->getStatus(); ?></td>
					<td><?php echo $commande->date_commande; ?></td>
					<?php if ($commande->heure_souhaite == -1) : ?>
						<td>Au plus tôt</td>
					<?php else : ?>
						<td><?php echo $commande->heure_souhaite; ?>:<?php echo $commande->minute_souhaite; ?></td>
					<?php endif; ?>
					<td>
						<?php if ($commande->is_premium) : ?>
							<span style="color : #FFFF00" class="glyphicon glyphicon-star" aria-hidden="true"></span>
						<?php endif; ?>
					</td>
					<td>
						<a href="?controler=commande&action=detail&id=<?php echo $commande->id;?>"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>