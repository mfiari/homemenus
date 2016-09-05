<ul class="nav nav-tabs">
  <li role="presentation"><a href="?controler=commande">Commande du jour</a></li>
  <li role="presentation" class="active"><a href="#">Historique des commandes</a></li>
</ul>
<h2>Historique des commandes</h2>
<div id="commandes">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Numéro de commande</th>
				<th>Restaurant</th>
				<th>Client</th>
				<th>Date</th>
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
					<td><?php echo $commande->date_commande; ?></td>
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