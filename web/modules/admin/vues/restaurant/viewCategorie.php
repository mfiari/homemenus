<div class="row">
	<div class="col-md-12">
		<h1><?php echo $request->restaurant->nom; ?> : <?php echo $request->categorie->nom; ?></h1>
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
					</tr>
				</thead>
				<tbody>
					<?php foreach ($request->categorie->contenus as $contenu) : ?>
						<tr>
							<td><img style="width : 50px;" src="<?php echo $contenu->logo; ?>"></td>
							<td><a href="?controler=restaurant&action=editContenu&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $request->categorie->id; ?>&id_contenu=<?php echo $contenu->id; ?>"><?php echo utf8_encode($contenu->nom); ?></a></td>
							<td>
								<a href="">
									<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
								</a>
								<a href="?controler=restaurant&action=deleteContenu&id_contenu=<?php echo $contenu->id; ?>">
									<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
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