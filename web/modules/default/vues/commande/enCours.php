<?php foreach ($request->commandes as $commande) : ?>
	<tr class="commande">
		<td>#<?php echo $commande->id; ?></td>
		<td><?php echo utf8_encode($commande->restaurant->nom); ?></td>
		<td><?php echo $commande->livreur->prenom == '' ? "NA" : utf8_encode($commande->livreur->prenom); ?></td>
		<td><?php echo $commande->getStatus(); ?></td>
		<td><?php echo formatPrix($commande->prix); ?></td>
		<td><?php echo $commande->date_commande; ?></td>
		<?php if ($commande->heure_souhaite == -1) : ?>
			<td>Au plus tôt</td>
		<?php else : ?>
			<td><?php echo formatHeureMinute($commande->heure_souhaite, $commande->minute_souhaite); ?></td>
		<?php endif; ?>
		<td>
			<a href="?controler=commande&id=<?php echo $commande->id;?>" title="Détail de la commande"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
			<?php if ($commande->livreur->id != '') : ?>
				<a onclick="openChatBox(<?php echo $commande->id;?>)" title="Contacter le livreur"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
			<?php endif; ?>
		</td>
	</tr>
<?php endforeach; ?>