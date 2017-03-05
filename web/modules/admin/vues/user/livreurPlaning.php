<div class="row">
	<div class="col-md-12">
		<h2>Disponibilité de la semaine</h2>
		<div id="livreurs">
			<ul class="nav nav-tabs">
				<li role="presentation"><a href="?controler=user&action=livreurs">Liste des livreurs</a></li>
				<li role="presentation"><a href="?controler=user&action=livreurDispo">Disponibilité du jour</a></li>
				<li role="presentation" class="active"><a href="?controler=user&action=livreurPlaning">Disponibilité de la semaine</a></li>
			</ul>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Horaire</th>
					</tr>
				</thead>
				<tbody>
					<?php $id_jour = 0; ?>
					<?php foreach ($request->livreurs as $livreur) : ?>
						<?php if ($id_jour != $livreur->dispos->id_jour) : ?>
							<?php $id_jour = $livreur->dispos->id_jour; ?>
							<tr>
								<th colspan="2" style="background-color : #337ab7; text-align : center;"><?php echo $livreur->dispos->jour; ?></th>
							</tr>
						<?php endif; ?>
						<tr>
							<td><?php echo utf8_encode($livreur->nom); ?> <?php echo utf8_encode($livreur->prenom); ?> (<?php echo $livreur->login; ?>)</td>
							<td>De <?php echo $livreur->dispos->heure_debut; ?>h<?php echo $livreur->dispos->minute_debut; ?> à 
								<?php echo $livreur->dispos->heure_fin; ?>h<?php echo $livreur->dispos->minute_fin; ?>
							</td>
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