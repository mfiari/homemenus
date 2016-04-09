<div class="row">
	<div class="col-md-12">
		<h2>Liste des clients</h2>
		<div id="clients">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Prénom</th>
						<th>login</th>
						<th>Ville</th>
						<th>Actif</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($request->clients as $client) : ?>
						<tr>
							<td><?php echo utf8_encode($client->nom); ?></td>
							<td><?php echo utf8_encode($client->prenom); ?></td>
							<td><?php echo $client->login; ?></td>
							<td><?php echo utf8_encode($client->ville); ?> (<?php echo $client->code_postal; ?>)</td>
							<td><?php echo $client->is_enable ? '<span style="color : green; ">Oui</span>' : '<span style="color : red; ">Non</span>'; ?></td>
							<td>
								<a href="?controler=user&action=client&id_user=<?php echo $client->id; ?>">
									<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
								</a>
								<?php if ($client->is_enable) : ?>
									<a href="?controler=user&action=disable&id_user=<?php echo $client->id; ?>&type=client">
										<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
									</a>
								<?php else : ?>
									<a href="?controler=user&action=enable&id_user=<?php echo $client->id; ?>&type=client">
										<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
									</a>
									<a href="?controler=user&action=delete&id_user=<?php echo $client->id; ?>&type=client">
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
</div>