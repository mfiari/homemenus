<?php foreach ($request->commandes as $commande) : ?>
	<tr class="commande <?php echo $commande->is_modif ? 'new' : ''; ?>">
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
			<?php if ($commande->etape == 2) : ?>
				<a href="?module=livreur&controler=commande&action=recuperation&id=<?php echo $commande->id;?>"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>
			<?php elseif ($commande->etape == 3) : ?>
				<a href="?module=livreur&controler=commande&action=livraison&id=<?php echo $commande->id;?>"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>
			<?php endif; ?>
			<a href="?controler=commande&action=detail&id=<?php echo $commande->id;?>"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
			<a onclick="openChatBox(<?php echo $commande->id;?>)"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
		</td>
	</tr>
<?php endforeach; ?>