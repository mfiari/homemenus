<ul class="nav nav-tabs">
  <li role="presentation"><a href="?controler=commande&action=index">Commande en cours</a></li>
  <li role="presentation" class="active"><a href="#">Commande réalisée(s)</a></li>
  <li role="presentation"><a href="?controler=commande&action=history">Historique des commandes</a></li>
</ul>
<h2>Commandes réalisée(s)</h2>
<div id="commandes">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Numéro de commande</th>
				<th>Restaurant</th>
				<th>Livreur</th>
				<th>Prix</th>
				<th>Date de commande</th>
				<th>Heure souhaité</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($request->commandes as $commande) : ?>
				<tr class="commande">
					<td>#<?php echo $commande->id; ?></td>
					<td><?php echo utf8_encode($commande->restaurant->nom); ?></td>
					<td><?php echo $commande->livreur->prenom == '' ? "NA" : utf8_encode($commande->livreur->prenom); ?></td>
					<td><?php echo formatPrix($commande->prix); ?></td>
					<td><?php echo $commande->date_commande; ?></td>
					<?php if ($commande->heure_souhaite == -1) : ?>
						<td>Au plus tôt</td>
					<?php else : ?>
						<td><?php echo formatHeureMinute($commande->heure_souhaite, $commande->minute_souhaite); ?></td>
					<?php endif; ?>
					<td>
						<a href="?controler=commande&id=<?php echo $commande->id;?>" title="Détail de la commande"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
						<a onclick="enableRating(<?php echo $commande->id;?>)"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>