<div class="row">
	<div class="col-md-12">
		<h2>Liste des news</h2>
		<div id="news">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Titre</th>
						<th>Date début</th>
						<th>Date fin</th>
						<th>Actif</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($request->news as $news) : ?>
						<tr>
							<td><?php echo utf8_encode($news->titre); ?></td>
							<td><?php echo $news->date_debut; ?></td>
							<td><?php echo $news->date_fin; ?></td>
							<td><?php echo $news->actif ? '<span style="color : green; ">Oui</span>' : '<span style="color : red; ">Non</span>'; ?></td>
							<td>
								<a href="?controler=news&action=view&id_news=<?php echo $news->id; ?>">
									<span data-toggle="tooltip" title="Voir" class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
								</a>
								<?php if ($news->actif) : ?>
									<a href="?controler=news&action=disable&id_news=<?php echo $news->id; ?>">
										<span data-toggle="tooltip" title="Désactiver" class="glyphicon glyphicon-remove" aria-hidden="true"></span>
									</a>
								<?php else : ?>
									<a href="?controler=news&action=enable&id_news=<?php echo $news->id; ?>">
										<span data-toggle="tooltip" title="Activer" class="glyphicon glyphicon-ok" aria-hidden="true"></span>
									</a>
									<a href="?controler=news&action=deleted&id_news=<?php echo $news->id; ?>">
										<span data-toggle="tooltip" title="Supprimer" class="glyphicon glyphicon-remove" aria-hidden="true"></span>
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
		<a href="?controler=news&action=add" class="btn btn-primary">Ajouter une news</a>
	</div>
</div>