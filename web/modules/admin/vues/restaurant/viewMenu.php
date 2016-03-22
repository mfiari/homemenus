<div class="row">
	<div class="col-md-12">
		<h1><?php echo utf8_encode($request->restaurant->nom); ?> : <?php echo utf8_encode($request->menu->nom); ?></h1>
		<a class="btn btn-primary" href="?controler=restaurant&action=view&id_restaurant=<?php echo $request->restaurant->id; ?>">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
		<div class="row">
			<h2>Formats</h2>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>nom</th>
						<th>prix</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($request->menu->formats as $format) : ?>
						<tr>
							<td><?php echo utf8_encode($format->nom); ?></td>
							<td><?php echo $format->prix; ?> €</td>
							<td>
								<a href="?controler=restaurant&action=deleteContenu&id_contenu=<?php echo $format->id; ?>">
									<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<div class="row">
			<h2>Formules</h2>
			<?php foreach ($request->menu->formules as $formule) : ?>
				<h3><?php echo utf8_encode($formule->nom); ?></h3>
				
				<?php foreach ($formule->categories as $categorie) : ?>
					<h4><?php echo utf8_encode($categorie->nom); ?></h4>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Nom</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($categorie->contenus as $contenu) : ?>
								<tr>
									<td><?php echo utf8_encode($contenu->carte->nom); ?></td>
									<td>
										<a href="?controler=restaurant&action=deleteContenu&id_contenu=<?php echo $contenu->id; ?>">
											<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
										</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<form method="post" enctype="x-www-form-urlencoded" action="?controler=restaurant&action=addContenuToMenu">
						<fieldset>
							<input name="id_restaurant" type="text" value="<?php echo $request->restaurant->id; ?>" hidden="hidden">
							<input name="id_menu" type="text" value="<?php echo $request->menu->id; ?>" hidden="hidden">
							<input name="id_categorie" type="text" value="<?php echo $categorie->id; ?>" hidden="hidden">
							<div class="form-group">
								<label for="contenu">Contenu<span class="required">*</span> : </label>
								<select name="contenu">
									<?php foreach ($request->restaurant->categories AS $categorie) : ?>
										<?php foreach ($categorie->contenus AS $contenu) : ?>
											<option value="<?php echo $contenu->id; ?>"><?php echo utf8_encode($contenu->nom); ?></option>
										<?php endforeach ; ?>
									<?php endforeach ; ?>
								</select>
							</div>
							<button class="btn btn-primary" type="submit">Ajouter un nouveau contenu</button>
						</fieldset>
					</form>
				<?php endforeach; ?>
				
				<form method="post" enctype="x-www-form-urlencoded" action="?controler=restaurant&action=addCategorieToMenu">
					<fieldset>
						<input name="id_restaurant" type="text" value="<?php echo $request->restaurant->id; ?>" hidden="hidden">
						<input name="id_menu" type="text" value="<?php echo $request->menu->id; ?>" hidden="hidden">
						<input name="id_formule" type="text" value="<?php echo $formule->id; ?>" hidden="hidden">
						<div class="form-group">
							<label for="categorie">Categorie<span class="required">*</span> : </label>
							<input class="form-control" name="categorie" type="text" value="" required>
						</div>
						<div class="form-group">
							<label for="quantite">Quantité<span class="required">*</span> : </label>
							<input class="form-control" name="quantite" type="text" value="1" required>
						</div>
						<button class="btn btn-primary" type="submit">Ajouter une nouvelle catégorie</button>
					</fieldset>
				</form>
				
			<?php endforeach; ?>
		</div>
		<a class="btn btn-primary" href="?controler=restaurant&action=view&id_restaurant=<?php echo $request->restaurant->id; ?>">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
	</div>
</div>