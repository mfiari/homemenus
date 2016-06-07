<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h2>Recherches</h2>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Adresse</th>
					<th>distance</th>
					<th>ville</th>
					<th>Nb restaurants trouvé</th>
					<th>Utilisateur</th>
					<th>Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($request->recherches as $recherche) : ?>
					<tr>
						<td><?php echo utf8_encode($recherche->recherche); ?></td>
						<td><?php echo $recherche->distance; ?></td>
						<td><?php echo $recherche->ville; ?></td>
						<td><?php echo $recherche->nbRestaurant; ?></td>
						<td></td>
						<td><?php echo $recherche->date_recherche; ?></td>
						<td>
							<a href="?controler=recherche&action=detail&id=<?php echo $recherche->id; ?>">
								<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>