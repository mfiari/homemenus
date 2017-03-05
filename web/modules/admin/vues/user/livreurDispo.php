<div class="row">
	<div class="col-md-12">
		<h2>Disponibilité du jour</h2>
		<div id="livreurs">
			<ul class="nav nav-tabs">
				<li role="presentation"><a href="?controler=user&action=livreurs">Liste des livreurs</a></li>
				<li role="presentation" class="active"><a href="?controler=user&action=livreurDispo">Disponibilité du jour</a></li>
				<li role="presentation"><a href="?controler=user&action=livreurPlaning">Disponibilité de la semaine</a></li>
			</ul>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Horaire</th>
						<th>Restaurant</th>
						<th>Périmetre</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($request->livreurs as $livreur) : ?>
						<tr>
							<td><?php echo utf8_encode($livreur->nom); ?> <?php echo utf8_encode($livreur->prenom); ?> (<?php echo $livreur->login; ?>)</td>
							<td>De <?php echo $livreur->dispos->heure_debut; ?>h<?php echo $livreur->dispos->minute_debut; ?> à 
								<?php echo $livreur->dispos->heure_fin; ?>h<?php echo $livreur->dispos->minute_fin; ?>
							</td>
							<td><?php echo utf8_encode($livreur->dispos->restaurants->nom); ?></td>
							<td><?php echo $livreur->dispos->perimetre; ?> KM</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-12">
		<a href="?controler=user&action=edit" class="btn btn-primary">Ajouter un livreur</a>
	</div>
</div>