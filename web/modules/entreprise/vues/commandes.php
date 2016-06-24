<h2>Commandes</h2>
<ul class="nav nav-tabs">
	<li role="presentation" class="active"><a href="?controler=commande">Commandes du jour</a></li>
	<li role="presentation"><a href="?controler=commande&action=history">Historique des commandes</a></li>
</ul>
<div id="commandes">
	<?php $total = 0; ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Numéro de commande</th>
				<th>Livreur</th>
				<th>Date de commande</th>
				<th>Heure souhaité</th>
				<th>Etat</th>
				<th>Prix</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($request->commandes as $commande) : ?>
				<tr class="commande">
					<td>#<?php echo $commande->id; ?></td>
					<td><?php echo $commande->livreur->prenom == '' ? "NA" : utf8_encode($commande->livreur->prenom); ?></td>
					<td><?php echo $commande->date_commande; ?></td>
					<?php if ($commande->heure_souhaite == -1) : ?>
						<td>Au plus tôt</td>
					<?php else : ?>
						<td><?php echo formatHeureMinute($commande->heure_souhaite,$commande->minute_souhaite); ?></td>
					<?php endif; ?>
					<td>
						<?php if ($commande->etape == 0) : ?>
							<span style="color : #FF0000;">
						<?php elseif ($commande->etape == 1) : ?>
							<span style="color : orange;">
						<?php else : ?>
							<span>
						<?php endif; ?>
						<?php echo $commande->getStatus(); ?></span>
					</td>
					<td><?php echo formatPrix($commande->prix); ?></td>
					<td>
						<?php if ($commande->is_premium) : ?>
							<span style="color : #FFFF00" class="glyphicon glyphicon-star" aria-hidden="true"></span>
						<?php endif; ?>
					</td>
					<td>
						<a href="?controler=commande&action=detail&id=<?php echo $commande->id;?>" title="Détail de la commande"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
					</td>
				</tr>
				<?php $total += $commande->prix; ?>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="5">Total : </th>
				<th><?php echo formatPrix($total); ?></th>
				<th></th>
			</tr>
		</tfoot>
	</table>
</div>