<div class="row">
	<div class="col-md-12">
		<h1><?php echo utf8_encode($request->restaurant->nom); ?> : <?php echo utf8_encode($request->categorie->nom); ?></h1>
		<a class="btn btn-primary" href="?controler=restaurant&action=view&id_restaurant=<?php echo $request->restaurant->id; ?>">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
		<div class="row">
			<h2>Contenu</h2>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>logo</th>
						<th>Nom</th>
						<th>En stock</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($request->categorie->contenus as $contenu) : ?>
						<tr>
							<td><img style="width : 50px;" src="<?php echo $contenu->logo; ?>"></td>
							<td><a href="?controler=restaurant&action=editContenu&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $request->categorie->id; ?>&id_contenu=<?php echo $contenu->id; ?>">
								<?php echo utf8_encode($contenu->nom); ?>
							</a></td>
							<td><?php echo $contenu->stock ? '<span style="color : green; ">Oui</span>' : '<span style="color : red; ">Non</span>'; ?></td>
							<td>
								<a href="?controler=restaurant&action=editContenu&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $request->categorie->id; ?>&id_contenu=<?php echo $contenu->id; ?>">
									<span  data-toggle="tooltip" title="Modifier" class="glyphicon glyphicon-edit" aria-hidden="true"></span>
								</a>
								<?php if ($contenu->stock) : ?>
									<a href="?controler=restaurant&action=removeStock&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $request->categorie->id; ?>&id_contenu=<?php echo $contenu->id; ?>">
										<span  data-toggle="tooltip" title="Retirer du stock" class="glyphicon glyphicon-remove" aria-hidden="true"></span>
									</a>
								<?php else : ?>
									<a href="?controler=restaurant&action=addStock&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $request->categorie->id; ?>&id_contenu=<?php echo $contenu->id; ?>">
										<span  data-toggle="tooltip" title="Ajouter au stock" class="glyphicon glyphicon-ok" aria-hidden="true"></span>
									</a>
								<?php endif; ?>
								<a href="?controler=restaurant&action=deleteContenu&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $request->categorie->id; ?>&id_contenu=<?php echo $contenu->id; ?>">
									<span  data-toggle="tooltip" title="Supprimer" class="glyphicon glyphicon-trash" aria-hidden="true"></span>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<a href="?controler=restaurant&action=editContenu&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $request->categorie->id; ?>" class="btn btn-primary">Ajouter un contenu</a>
		</div>
		<a class="btn btn-primary" href="?controler=restaurant&action=view&id_restaurant=<?php echo $request->restaurant->id; ?>">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
	</div>
</div>