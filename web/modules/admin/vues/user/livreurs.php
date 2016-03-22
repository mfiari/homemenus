<div class="row">
	<div class="col-md-12">
		<h2>Liste des livreurs</h2>
		<div id="livreurs">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Prénom</th>
						<th>Login</th>
						<th>Connecté</th>
						<th>Actif</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($request->livreurs as $livreur) : ?>
						<tr>
							<td><?php echo utf8_encode($livreur->nom); ?></td>
							<td><?php echo utf8_encode($livreur->prenom); ?></td>
							<td><?php echo utf8_encode($livreur->login); ?></td>
							<td><?php echo $livreur->is_ready ? '<span style="color : green; ">Oui</span>' : '<span style="color : red; ">Non</span>'; ?></td>
							<td><?php echo $livreur->is_enable ? '<span style="color : green; ">Oui</span>' : '<span style="color : red; ">Non</span>'; ?></td>
							<td>
								<a href="?controler=user&action=livreur&id_user=<?php echo $livreur->id; ?>">
									<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
								</a>
								<a href="?controler=user&action=edit&id_user=<?php echo $livreur->id; ?>">
									<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
								</a>
								<?php if ($livreur->is_enable) : ?>
									<a href="?controler=user&action=disable&id_user=<?php echo $livreur->id; ?>">
										<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
									</a>
								<?php else : ?>
									<a href="?controler=user&action=enable&id_user=<?php echo $livreur->id; ?>">
										<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
									</a>
									<a href="?controler=user&action=delete&id_user=<?php echo $livreur->id; ?>">
										<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
									</a>
								<?php endif; ?>
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