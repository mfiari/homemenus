<div class="row">
	<div class="col-md-12">
		<h1><?php echo utf8_encode($request->restaurant->nom); ?></h1>
		<a class="btn btn-primary" href="?controler=restaurant">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
		<div class="row">
			<div class="panel panel-default panel-primary">
				<div class="panel-heading">
					<h2>Utilisateurs</h2>
				</div>
				<div class="row">
					<div class="col-md-12" style="padding : 30px;">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Login</th>
									<th>Role</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($request->users as $user) : ?>
									<tr>
										<td><?php echo utf8_encode($user->nom); ?> <?php echo utf8_encode($user->prenom); ?></td>
										<td><?php echo $user->status; ?></td>
										<td></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<a href="?controler=restaurant&action=adduser&id_restaurant=<?php echo $request->restaurant->id; ?>" class="btn btn-primary">Ajouter une nouvelle utilisateur</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-default panel-primary">
				<div class="panel-heading">
					<h2>Catégories</h2>
				</div>
				<div class="row">
					<div class="col-md-12" style="padding : 30px;">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Nom</th>
									<th></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($request->restaurant->categories as $categorie) : ?>
									<?php $childrens = $categorie->getChildren(); ?>
									<?php if (count($childrens) > 0) : ?>
										<tr>
											<td><?php echo utf8_encode($categorie->nom); ?></td>
											<td></td>
											<td></td>
										</tr>
										<?php foreach ($childrens as $children) : ?>
											<tr>
												<td></td>
												<td><a href="?controler=restaurant&action=viewCategorie&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $children->id; ?>"><?php echo utf8_encode($children->nom); ?></a></td>
												<td>
													<a href="?controler=restaurant&action=viewCategorie&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $children->id; ?>">
														<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
													</a>
													<a href="">
														<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
													</a>
													<a href="?controler=restaurant&action=deleteCategorie&id_categorie=<?php echo $children->id; ?>">
														<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
													</a>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr>
											<td><a href="?controler=restaurant&action=viewCategorie&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $categorie->id; ?>"><?php echo utf8_encode($categorie->nom); ?></a></td>
											<td></td>
											<td>
												<a href="?controler=restaurant&action=viewCategorie&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $categorie->id; ?>">
													<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
												</a>
												<a href="">
													<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
												</a>
												<a href="?controler=restaurant&action=deleteCategorie&id_categorie=<?php echo $categorie->id; ?>">
													<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
												</a>
											</td>
										</tr>
									<?php endif; ?>
								<?php endforeach; ?>
							</tbody>
						</table>
						<form method="post" enctype="x-www-form-urlencoded" action="?controler=restaurant&action=addCategorie">
							<fieldset>
								<input name="id_restaurant" type="text" value="<?php echo $request->restaurant->id; ?>" hidden="hidden">
								<div class="form-group">
									<label for="nom">Nom<span class="required">*</span> : </label>
									<input class="form-control" name="nom" type="text" maxlength="32" required>
								</div>
								<div class="form-group">
									<label for="parent">Parent<span class="required">*</span> : </label>
									<select name="parent">
										<option value="0"></option>
										<?php foreach ($request->restaurant->categories as $categorie) : ?>
											<option value="<?php echo $categorie->id; ?>"><?php echo utf8_encode($categorie->nom); ?></option>
										<?php endforeach ?>
									</select>
								</div>
								<button class="btn btn-primary" type="submit">Ajouter une nouvelle catégorie</button>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-default panel-primary">
				<div class="panel-heading">
					<h2>Menus</h2>
				</div>
				<div class="row">
					<div class="col-md-12" style="padding : 30px;">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Nom</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($request->restaurant->menus as $menu) : ?>
									<tr>
										<td><a href="?controler=restaurant&action=viewMenu&id_restaurant=<?php echo $request->restaurant->id; ?>&id_menu=<?php echo $menu->id; ?>"><?php echo utf8_encode($menu->nom); ?></a></td>
										<td>
											<a href="?controler=restaurant&action=viewMenu&id_restaurant=<?php echo $request->restaurant->id; ?>&id_menu=<?php echo $menu->id; ?>">
												<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
											</a>
											<a href="">
												<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
											</a>
											<a href="?controler=restaurant&action=deleteMenu&id_menu=<?php echo $menu->id; ?>">
												<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
											</a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<a href="?controler=restaurant&action=editMenu&id_restaurant=<?php echo $request->restaurant->id; ?>" class="btn btn-primary">Ajouter un menu</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-default panel-primary">
				<div class="panel-heading">
					<h2>Tags</h2>
				</div>
				<div class="row">
					<div class="col-md-12" style="padding : 30px;">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Nom</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($request->restaurant->tags as $tag) : ?>
									<tr>
										<td><?php echo utf8_encode($tag->nom); ?></td>
										<td>
											<a><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
											<a href="?controler=restaurant&action=deleteTag&id_restaurant=<?php echo $request->restaurant->id; ?>&id_tag=<?php echo $tag->id; ?>">
												<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
											</a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<form method="post" enctype="x-www-form-urlencoded" action="?controler=restaurant&action=addTag">
							<fieldset>
								<input name="id_restaurant" type="text" value="<?php echo $request->restaurant->id; ?>" hidden="hidden">
								<div class="form-group">
									<label for="nom">Nom<span class="required">*</span> : </label>
									<input class="form-control" name="nom" type="text" maxlength="32" required>
								</div>
								<button class="btn btn-primary" type="submit">Ajouter un tag</button>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-default panel-primary">
				<div class="panel-heading">
					<h2>Formats</h2>
				</div>
				<div class="row">
					<div class="col-md-12" style="padding : 30px;">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Nom</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($request->restaurant->formats as $format) : ?>
									<tr>
										<td><?php echo $format->nom != '' ? utf8_encode($format->nom) : 'Pas de format'; ?></td>
										<td>
											<?php if ($format->nom != '') : ?>
												<a><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
												<a href="?controler=restaurant&action=deleteFormat&id_restaurant=<?php echo $request->restaurant->id; ?>&id_format=<?php echo $format->id; ?>">
													<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
												</a>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<form method="post" enctype="x-www-form-urlencoded" action="?controler=restaurant&action=addFormat">
							<fieldset>
								<input name="id_restaurant" type="text" value="<?php echo $request->restaurant->id; ?>" hidden="hidden">
								<div class="form-group">
									<label for="nom">Nom<span class="required">*</span> : </label>
									<input class="form-control" name="nom" type="text" maxlength="32" required>
								</div>
								<button class="btn btn-primary" type="submit">Ajouter un format</button>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-default panel-primary">
				<div class="panel-heading">
					<h2>Formules</h2>
				</div>
				<div class="row">
					<div class="col-md-12" style="padding : 30px;">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Nom</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($request->restaurant->formules as $formule) : ?>
									<tr>
										<td><?php echo utf8_encode($formule->nom); ?></td>
										<td>
											<a><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
											<a href="?controler=restaurant&action=deleteFormule&id_restaurant=<?php echo $request->restaurant->id; ?>&id_formule=<?php echo $formule->id; ?>">
												<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
											</a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<form method="post" enctype="x-www-form-urlencoded" action="?controler=restaurant&action=addFormule">
							<fieldset>
								<input name="id_restaurant" type="text" value="<?php echo $request->restaurant->id; ?>" hidden="hidden">
								<div class="form-group">
									<label for="nom">Nom<span class="required">*</span> : </label>
									<input class="form-control" name="nom" type="text" maxlength="100" required>
								</div>
								<button class="btn btn-primary" type="submit">Ajouter une formule</button>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-default panel-primary">
				<div class="panel-heading">
					<h2>Supplements</h2>
				</div>
				<div class="row">
					<div class="col-md-12" style="padding : 30px;">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Nom</th>
									<th>Prix</th>
									<th>Commentaire</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($request->restaurant->supplements as $supplement) : ?>
									<tr>
										<td><?php echo utf8_encode($supplement->nom); ?></td>
										<td><?php echo $supplement->prix; ?></td>
										<td><?php echo $supplement->commentaire; ?></td>
										<td>
											<a><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
											<a href="?controler=restaurant&action=deleteSupplement&id_supplement=<?php echo $supplement->id; ?>">
												<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
											</a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<form method="post" enctype="x-www-form-urlencoded" action="?controler=restaurant&action=addSupplement">
							<fieldset>
								<input name="id_restaurant" type="text" value="<?php echo $request->restaurant->id; ?>" hidden="hidden">
								<div class="form-group">
									<label for="nom">Nom<span class="required">*</span> : </label>
									<input class="form-control" name="nom" type="text" maxlength="32" required>
								</div>
								<div class="form-group">
									<label for="nom">Prix<span class="required">*</span> : </label>
									<input class="form-control" name="prix" type="text" maxlength="32" value="0" required>
								</div>
								<div class="form-group">
									<label for="commentaire">Commentaire : </label>
									<textarea class="form-control" name="commentaire" ></textarea>
								</div>
								<button class="btn btn-primary" type="submit">Ajouter un supplement</button>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-default panel-primary">
				<div class="panel-heading">
					<h2>Options</h2>
				</div>
				<div class="row">
					<div class="col-md-12" style="padding : 30px;">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Nom</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($request->restaurant->options as $option) : ?>
									<tr>
										<td><a href="?controler=restaurant&action=viewOption&id_restaurant=<?php echo $request->restaurant->id; ?>&id_option=<?php echo $option->id; ?>"><?php echo utf8_encode($option->nom); ?></a></td>
										<td>
											<a><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
											<a href="?controler=restaurant&action=deleteOption&id=<?php echo $option->id; ?>">
												<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
											</a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<form method="post" enctype="x-www-form-urlencoded" action="?controler=restaurant&action=addOption">
							<fieldset>
								<input name="id_restaurant" type="text" value="<?php echo $request->restaurant->id; ?>" hidden="hidden">
								<div class="form-group">
									<label for="nom">Nom<span class="required">*</span> : </label>
									<input class="form-control" name="nom" type="text" maxlength="100" required>
								</div>
								<button class="btn btn-primary" type="submit">Ajouter une option</button>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
		<a class="btn btn-primary" href="?controler=restaurant">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
	</div>
</div>