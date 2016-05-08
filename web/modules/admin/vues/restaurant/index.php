<div class="row">
	<div class="col-md-12">
		<h2>Liste des restaurants</h2>
		<div id="restaurants">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Adresse</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($request->restaurants as $restaurant) : ?>
						<tr>
							<td><a href="?controler=restaurant&action=view&id_restaurant=<?php echo $restaurant->id; ?>"><?php echo utf8_encode($restaurant->nom); ?></a></td>
							<td><?php echo utf8_encode($restaurant->rue); ?>, <?php echo $restaurant->code_postal; ?> <?php echo utf8_encode($restaurant->ville); ?></td>
							<td>
								<a href="?controler=restaurant&action=view&id_restaurant=<?php echo $restaurant->id; ?>">
									<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
								</a>
								<a href="?controler=restaurant&action=edit&id_restaurant=<?php echo $restaurant->id; ?>">
									<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
								</a>
								<a href="?controler=restaurant&action=delete&id_restaurant=<?php echo $restaurant->id; ?>">
									<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
								</a>
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