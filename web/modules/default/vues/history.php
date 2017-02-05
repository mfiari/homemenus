<ul class="nav nav-tabs">
  <li role="presentation"><a href="?controler=commande&action=index">Commande en cours</a></li>
  <li role="presentation"><a href="?controler=commande&action=finish">Commande réalisée(s)</a></li>
  <li role="presentation" class="active"><a href="#">Historique des commandes</a></li>
</ul>
<h2>Historique des commandes</h2>
<div id="commandes">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Numéro de commande</th>
				<th>Restaurant</th>
				<th>Status</th>
				<th>Date</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($request->commandes as $commande) : ?>
				<tr class="commande">
					<td>#<?php echo $commande->id; ?></td>
					<td><?php echo $commande->restaurant->nom; ?></td>
					<td><?php echo $commande->getStatus(); ?></td>
					<td><?php echo $commande->date_commande; ?></td>
					<td>
						<a href="?controler=commande&id=<?php echo $commande->id;?>"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
						<a onclick="enableRating(<?php echo $commande->id;?>)"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>