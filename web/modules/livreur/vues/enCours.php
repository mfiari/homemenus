<?php foreach ($request->commandes as $commande) : ?>
	<tr class="commande <?php echo $commande->is_modif ? 'new' : ''; ?>">
		<td>#<?php echo $commande->id; ?></td>
		<td><?php echo $commande->restaurant->nom; ?></td>
		<td><?php echo $commande->getStatus(); ?></td>
		<td><?php echo $commande->date_commande; ?></td>
		<td>
			<?php if ($commande->etape == 2) : ?>
				<a href="?module=livreur&controler=commande&action=recuperation&id=<?php echo $commande->id;?>"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>
			<?php elseif ($commande->etape == 3) : ?>
				<a href="?module=livreur&controler=commande&action=livraison&id=<?php echo $commande->id;?>"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>
			<?php endif; ?>
			<a href="?controler=commande&action=detail&id=<?php echo $commande->id;?>"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
		</td>
	</tr>
<?php endforeach; ?>