<ul class="nav nav-tabs">
  <li role="presentation"><a href="?controler=notes">Commandes</a></li>
  <li role="presentation" class="active"><a href="#">Restaurants</a></li>
  <li role="presentation"><a href="?controler=notes&action=plats">Plats</a></li>
</ul>
<h2>Commentaire Restaurants</h2>
<div id="restaurants">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Nom</th>
				<th>Client</th>
				<th>Notes</th>
				<th>Commentaire</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($request->restaurants as $restaurant) : ?>
				<tr class="restaurant">
					<td><a href=""><?php echo utf8_encode($restaurant->nom); ?></a></td>
					<td><?php echo utf8_encode($restaurant->user->nom); ?> <?php echo utf8_encode($restaurant->user->prenom); ?></td>
					<td><?php echo $restaurant->commentaire->note; ?> / 5</td>
					<td><?php echo utf8_encode($restaurant->commentaire->commentaire); ?></td>
					<td>
						<?php if ($restaurant->commentaire->validation) : ?>
							<a href="?controler=commentaire&action=annuleRestaurant&id_commentaire=<?php echo $restaurant->commentaire->id; ?>">
								<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							</a>
						<?php else : ?>
							<a href="?controler=commentaire&action=enableRestaurant&id_commentaire=<?php echo $restaurant->commentaire->id; ?>">
								<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>