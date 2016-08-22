<ul class="nav nav-tabs">
  <li role="presentation"><a href="?controler=commentaire">Commandes</a></li>
  <li role="presentation"><a href="?controler=commentaire&action=restaurants">Restaurants</a></li>
  <li role="presentation" class="active"><a href="#">Plats</a></li>
</ul>
<h2>Plats</h2>
<div id="plats">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Restaurant</th>
				<th>Client</th>
				<th>Nom</th>
				<th>Notes</th>
				<th>Commentaire</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($request->restaurants as $restaurant) : ?>
				<?php foreach ($restaurant->categories as $categorie) : ?>
					<?php foreach ($categorie->contenus as $contenu) : ?>
						<tr class="carte">
							<td><a href=""><?php echo utf8_encode($restaurant->nom); ?></a></td>
							<td><?php echo utf8_encode($restaurant->user->nom); ?> <?php echo utf8_encode($restaurant->user->prenom); ?></td>
							<td><?php echo utf8_encode($contenu->nom); ?></td>
							<td><?php echo $contenu->commentaire->note; ?> / 5</td>
							<td><?php echo utf8_encode($contenu->commentaire->commentaire); ?></td>
							<td>
								<?php if ($contenu->commentaire->validation) : ?>
									<a href="?controler=commentaire&action=annuleCarte&id_commentaire=<?php echo $contenu->commentaire->id; ?>">
										<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
									</a>
								<?php else : ?>
									<a href="?controler=commentaire&action=enableCarte&id_commentaire=<?php echo $contenu->commentaire->id; ?>">
										<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
									</a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endforeach; ?>
				<?php foreach ($restaurant->menus as $menu) : ?>
					<tr class="menu">
						<td><a href=""><?php echo utf8_encode($restaurant->nom); ?></a></td>
						<td><?php echo utf8_encode($restaurant->user->nom); ?> <?php echo utf8_encode($restaurant->user->prenom); ?></td>
						<td><?php echo utf8_encode($menu->nom); ?></td>
						<td><?php echo $menu->commentaire->note; ?> / 5</td>
						<td><?php echo utf8_encode($menu->commentaire->commentaire); ?></td>
						<td>
							<?php if ($menu->commentaire->validation) : ?>
								<a href="?controler=commentaire&action=annuleMenu&id_commentaire=<?php echo $menu->commentaire->id; ?>">
									<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
								</a>
							<?php else : ?>
								<a href="?controler=commentaire&action=enableMenu&id_commentaire=<?php echo $menu->commentaire->id; ?>">
									<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
								</a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>