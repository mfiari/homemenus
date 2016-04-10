<?php foreach ($request->commandes as $commande) : ?>
	<tr class="commande <?php echo $commande->etape == 0 ? "recu" : "cours"; ?>">
		<td>#<?php echo $commande->id; ?></td>
		<td><?php echo $commande->livreur->prenom == '' ? "NA" : utf8_encode($commande->livreur->prenom); ?></td>
		<?php if ($commande->heure_souhaite == -1) : ?>
			<td>Au plus tôt</td>
		<?php else : ?>
			<td><?php echo $commande->heure_souhaite; ?>:<?php echo $commande->minute_souhaite; ?></td>
		<?php endif; ?>
		<td><?php echo $commande->getStatus(); ?></td>
		<td>
			<?php if ($commande->is_premium) : ?>
				<span class="glyphicon glyphicon-star" aria-hidden="true"></span>
			<?php endif; ?>
		</td>
		<td>
			<?php if ($commande->etape == 0) : ?>
				<a href="?controler=commande&action=validation&id=<?php echo $commande->id;?>"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>
			<?php elseif ($commande->etape == 1) : ?>
				<a href="?controler=commande&action=preparation&id=<?php echo $commande->id;?>"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>
			<?php endif; ?>
			<a href="?controler=commande&action=detail&id=<?php echo $commande->id;?>"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
		</td>
	</tr>
<?php endforeach; ?>