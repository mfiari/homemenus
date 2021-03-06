<div class="row">
	<div class="col-md-12">
		<h2>Liste des restaurants</h2>
		<div id="restaurants">
			<table class="table table-striped">
				<thead>
					<tr>
						<th></th>
						<th>Nom</th>
						<th>Adresse</th>
						<th>Actif</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($request->restaurants as $restaurant) : ?>
						<tr>
							<td><img src="<?php echo getLogoRestaurant ($restaurant->id); ?>" alt="logo" height="50" width="50"></td>
							<td><a href="?controler=restaurant&action=view&id_restaurant=<?php echo $restaurant->id; ?>"><?php echo utf8_encode($restaurant->nom); ?></a></td>
							<td><?php echo utf8_encode($restaurant->rue); ?>, <?php echo $restaurant->code_postal; ?> <?php echo utf8_encode($restaurant->ville); ?></td>
							<td><?php echo $restaurant->is_enable ? '<span style="color : green; ">Oui</span>' : '<span style="color : red; ">Non</span>'; ?></td>
							<td>
								<a href="?controler=restaurant&action=view&id_restaurant=<?php echo $restaurant->id; ?>">
									<span data-toggle="tooltip" title="Voir" class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
								</a>
								<a href="?controler=restaurant&action=edit&id_restaurant=<?php echo $restaurant->id; ?>">
									<span data-toggle="tooltip" title="Modifier" class="glyphicon glyphicon-edit" aria-hidden="true"></span>
								</a>
								<?php if ($restaurant->is_enable) : ?>
									<a href="?controler=restaurant&action=disable&id_restaurant=<?php echo $restaurant->id; ?>">
										<span data-toggle="tooltip" title="Désactiver" class="glyphicon glyphicon-remove" aria-hidden="true" style="color : #FF0000;"></span>
									</a>
								<?php else : ?>
									<a href="?controler=restaurant&action=enable&id_restaurant=<?php echo $restaurant->id; ?>">
										<span data-toggle="tooltip" title="Activer" class="glyphicon glyphicon-ok" aria-hidden="true" style="color : #00FF00;"></span>
									</a>
									<a href="?controler=restaurant&action=deleted&id_restaurant=<?php echo $restaurant->id; ?>">
										<span data-toggle="tooltip" title="Supprimer" class="glyphicon glyphicon-remove" aria-hidden="true" style="color : #FF0000;"></span>
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
		<a href="?controler=restaurant&action=edit" class="btn btn-primary">Ajouter un restaurant</a>
	</div>
</div>