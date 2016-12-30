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
									<th>Nom</th>
									<th>Login</th>
									<th>Role</th>
									<th>Actif</th>
									<th>Connecté</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($request->users as $user) : ?>
									<tr>
										<td><?php echo utf8_encode($user->nom); ?> <?php echo utf8_encode($user->prenom); ?></td>
										<td><?php echo utf8_encode($user->login); ?></td>
										<td><?php echo $user->status; ?></td>
										<td><?php echo $user->is_enable ? '<span style="color : green; ">Oui</span>' : '<span style="color : red; ">Non</span>'; ?></td>
										<td><?php echo $user->is_login ? '<span style="color : green; ">Oui</span>' : '<span style="color : red; ">Non</span>'; ?></td>
										<td>
											<?php if ($user->is_enable) : ?>
												<a href="?controler=restaurant&action=disableUser&id_user=<?php echo $user->id; ?>&id_restaurant=<?php echo $request->restaurant->id; ?>">
													<span data-toggle="tooltip" title="Désactiver" class="glyphicon glyphicon-remove" aria-hidden="true"></span>
												</a>
											<?php else : ?>
												<a href="?controler=restaurant&action=enableUser&id_user=<?php echo $user->id; ?>&id_restaurant=<?php echo $request->restaurant->id; ?>">
													<span data-toggle="tooltip" title="Activer" class="glyphicon glyphicon-ok" aria-hidden="true"></span>
												</a>
												<a href="?controler=restaurant&action=deleteUser&id_user=<?php echo $user->id; ?>&id_restaurant=<?php echo $request->restaurant->id; ?>">
													<span data-toggle="tooltip" title="Supprimer" class="glyphicon glyphicon-trash" aria-hidden="true"></span>
												</a>
											<?php endif; ?>
										</td>
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
											<td>
												<a class="edit-categorie" data-id="<?php echo $categorie->id; ?>">
													<span  data-toggle="tooltip" title="Modifier" class="glyphicon glyphicon-edit" aria-hidden="true"></span>
												</a>
											</td>
										</tr>
										<?php foreach ($childrens as $children) : ?>
											<tr>
												<td></td>
												<td><a href="?controler=restaurant&action=viewCategorie&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $children->id; ?>"><?php echo utf8_encode($children->nom); ?></a></td>
												<td>
													<a href="?controler=restaurant&action=viewCategorie&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $children->id; ?>">
														<span  data-toggle="tooltip" title="Voir" class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
													</a>
													<a class="edit-categorie" data-id="<?php echo $categorie->id; ?>">
														<span  data-toggle="tooltip" title="Modifier" class="glyphicon glyphicon-edit" aria-hidden="true"></span>
													</a>
													<a href="?controler=restaurant&action=deleteCategorie&id_categorie=<?php echo $children->id; ?>">
														<span  data-toggle="tooltip" title="Supprimer" class="glyphicon glyphicon-trash" aria-hidden="true"></span>
													</a>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr class="tr-view-categorie" id="tr-view-categorie-<?php echo $categorie->id; ?>">
											<td><a href="?controler=restaurant&action=viewCategorie&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $categorie->id; ?>"><?php echo utf8_encode($categorie->nom); ?></a></td>
											<td></td>
											<td>
												<a href="?controler=restaurant&action=viewCategorie&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $categorie->id; ?>">
													<span  data-toggle="tooltip" title="Voir" class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
												</a>
												<a class="edit-categorie" data-id="<?php echo $categorie->id; ?>">
													<span  data-toggle="tooltip" title="Modifier" class="glyphicon glyphicon-edit" aria-hidden="true"></span>
												</a>
												<a href="?controler=restaurant&action=deleteCategorie&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $categorie->id; ?>">
													<span data-toggle="tooltip" title="Supprimer" class="glyphicon glyphicon-trash" aria-hidden="true"></span>
												</a>
											</td>
										</tr>
										<tr class="tr-edit-categorie" id="tr-edit-categorie-<?php echo $categorie->id; ?>" style="display : none;">
											<form method="post" action="?controler=restaurant&action=modifyCategorie">
												<input type="text" name="id_restaurant" value="<?php echo $request->restaurant->id; ?>" hidden>
												<input type="text" name="id_categorie" value="<?php echo $categorie->id; ?>" hidden>
												<td><input type="text" name="nom" value="<?php echo utf8_encode($categorie->nom); ?>"></td>
												<td></td>
												<td>
													<button class="btn btn-primary" type="submit">Modifier</button>
													<button class="btn btn-primary edit-cancel" type="button" data-id="<?php echo $categorie->id; ?>">Annuler</button>
												</td>
											</form>
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
									<th>Logo</th>
									<th>Nom</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($request->restaurant->menus as $menu) : ?>
									<tr class="tr-view-menu" id="tr-view-menu-<?php echo $menu->id; ?>">
										<td></td>
										<td><a href="?controler=restaurant&action=viewMenu&id_restaurant=<?php echo $request->restaurant->id; ?>&id_menu=<?php echo $menu->id; ?>"><?php echo utf8_encode($menu->nom); ?></a></td>
										<td>
											<a href="?controler=restaurant&action=viewMenu&id_restaurant=<?php echo $request->restaurant->id; ?>&id_menu=<?php echo $menu->id; ?>">
												<span data-toggle="tooltip" title="Voir" class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
											</a>
											<a class="edit-menu" data-id="<?php echo $menu->id; ?>">
												<span  data-toggle="tooltip" title="Modifier" class="glyphicon glyphicon-edit" aria-hidden="true"></span>
											</a>
											<a href="?controler=restaurant&action=deleteMenu&id_restaurant=<?php echo $request->restaurant->id; ?>&id_menu=<?php echo $menu->id; ?>">
												<span data-toggle="tooltip" title="Supprimer" class="glyphicon glyphicon-trash" aria-hidden="true"></span>
											</a>
										</td>
									</tr>
									<tr class="tr-edit-menu" id="tr-edit-menu-<?php echo $menu->id; ?>" style="display : none;">
										<form method="post" action="?controler=restaurant&action=modifyMenu">
											<input type="text" name="id_restaurant" value="<?php echo $request->restaurant->id; ?>" hidden>
											<input type="text" name="id_menu" value="<?php echo $menu->id; ?>" hidden>
											<td><input type="text" name="nom" value="<?php echo utf8_encode($menu->nom); ?>"></td>
											<td></td>
											<td>
												<button class="btn btn-primary" type="submit">Modifier</button>
												<button class="btn btn-primary edit-cancel" type="button" data-id="<?php echo $menu->id; ?>">Annuler</button>
											</td>
										</form>
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
									<tr class="tr-view-tag" id="tr-view-tag-<?php echo $tag->id; ?>">
										<td><?php echo utf8_encode($tag->nom); ?></td>
										<td>
											<a class="edit-tag" data-id="<?php echo $tag->id; ?>">
												<span data-toggle="tooltip" title="Modifier" class="glyphicon glyphicon-edit" aria-hidden="true"></span>
											</a>
											<a href="?controler=restaurant&action=deleteTag&id_restaurant=<?php echo $request->restaurant->id; ?>&id_tag=<?php echo $tag->id; ?>">
												<span data-toggle="tooltip" title="Supprimer" class="glyphicon glyphicon-trash" aria-hidden="true"></span>
											</a>
										</td>
									</tr>
									<tr class="tr-edit-tag" id="tr-edit-tag-<?php echo $tag->id; ?>" style="display : none;">
										<form method="post" action="?controler=restaurant&action=modifyTag">
											<input type="text" name="id_restaurant" value="<?php echo $request->restaurant->id; ?>" hidden>
											<input type="text" name="id_tag" value="<?php echo $tag->id; ?>" hidden>
											<td><input type="text" name="nom" value="<?php echo utf8_encode($tag->nom); ?>"></td>
											<td></td>
											<td>
												<button class="btn btn-primary" type="submit">Modifier</button>
												<button class="btn btn-primary edit-cancel" type="button" data-id="<?php echo $tag->id; ?>">Annuler</button>
											</td>
										</form>
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
									<tr class="tr-view-format" id="tr-view-format-<?php echo $format->id; ?>">
										<td><?php echo $format->nom != '' ? utf8_encode($format->nom) : 'Pas de format'; ?></td>
										<td>
											<?php if ($format->nom != '') : ?>
												<a class="edit-format" data-id="<?php echo $format->id; ?>">
													<span data-toggle="tooltip" title="Modifier" class="glyphicon glyphicon-edit" aria-hidden="true"></span>
												</a>
												<a href="?controler=restaurant&action=deleteFormat&id_restaurant=<?php echo $request->restaurant->id; ?>&id_format=<?php echo $format->id; ?>">
													<span data-toggle="tooltip" title="Supprimer" class="glyphicon glyphicon-trash" aria-hidden="true"></span>
												</a>
											<?php endif; ?>
										</td>
									</tr>
									<tr class="tr-edit-format" id="tr-edit-format-<?php echo $format->id; ?>" style="display : none;">
										<form method="post" action="?controler=restaurant&action=modifyFormat">
											<input type="text" name="id_restaurant" value="<?php echo $request->restaurant->id; ?>" hidden>
											<input type="text" name="id_format" value="<?php echo $format->id; ?>" hidden>
											<td><input type="text" name="nom" value="<?php echo utf8_encode($format->nom); ?>"></td>
											<td></td>
											<td>
												<button class="btn btn-primary" type="submit">Modifier</button>
												<button class="btn btn-primary edit-cancel" type="button" data-id="<?php echo $format->id; ?>">Annuler</button>
											</td>
										</form>
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
									<tr class="tr-view-formule" id="tr-view-formule-<?php echo $formule->id; ?>">
										<td><?php echo utf8_encode($formule->nom); ?></td>
										<td>
											<a class="edit-formule" data-id="<?php echo $formule->id; ?>">
												<span data-toggle="tooltip" title="Modifier" class="glyphicon glyphicon-edit" aria-hidden="true"></span>
											</a>
											<a href="?controler=restaurant&action=deleteFormule&id_restaurant=<?php echo $request->restaurant->id; ?>&id_formule=<?php echo $formule->id; ?>">
												<span data-toggle="tooltip" title="Supprimer" class="glyphicon glyphicon-trash" aria-hidden="true"></span>
											</a>
										</td>
									</tr>
									<tr class="tr-edit-formule" id="tr-edit-formule-<?php echo $formule->id; ?>" style="display : none;">
										<form method="post" action="?controler=restaurant&action=modifyFormule">
											<input type="text" name="id_restaurant" value="<?php echo $request->restaurant->id; ?>" hidden>
											<input type="text" name="id_formule" value="<?php echo $formule->id; ?>" hidden>
											<td><input type="text" name="nom" value="<?php echo utf8_encode($formule->nom); ?>"></td>
											<td></td>
											<td>
												<button class="btn btn-primary" type="submit">Modifier</button>
												<button class="btn btn-primary edit-cancel" type="button" data-id="<?php echo $formule->id; ?>">Annuler</button>
											</td>
										</form>
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
									<tr class="tr-view-supplement" id="tr-view-supplement-<?php echo $supplement->id; ?>">
										<td><?php echo utf8_encode($supplement->nom); ?></td>
										<td><?php echo $supplement->prix; ?></td>
										<td><?php echo $supplement->commentaire; ?></td>
										<td>
											<a class="edit-supplement" data-id="<?php echo $supplement->id; ?>">
												<span data-toggle="tooltip" title="Modifier" class="glyphicon glyphicon-edit" aria-hidden="true"></span>
											</a>
											<a href="?controler=restaurant&action=deleteSupplement&id_restaurant=<?php echo $request->restaurant->id; ?>&id_supplement=<?php echo $supplement->id; ?>">
												<span data-toggle="tooltip" title="Supprimer" class="glyphicon glyphicon-trash" aria-hidden="true"></span>
											</a>
										</td>
									</tr>
									<tr class="tr-edit-supplement" id="tr-edit-supplement-<?php echo $supplement->id; ?>" style="display : none;">
										<form method="post" action="?controler=restaurant&action=modifySupplement">
											<input type="text" name="id_restaurant" value="<?php echo $request->restaurant->id; ?>" hidden>
											<input type="text" name="id_supplement" value="<?php echo $supplement->id; ?>" hidden>
											<td><input type="text" name="nom" value="<?php echo utf8_encode($supplement->nom); ?>"></td>
											<td><input type="text" name="prix" value="<?php echo $supplement->prix; ?>"></td>
											<td><textarea class="form-control" name="commentaire"><?php echo utf8_encode($supplement->commentaire); ?></textarea></td>
											<td></td>
											<td>
												<button class="btn btn-primary" type="submit">Modifier</button>
												<button class="btn btn-primary edit-cancel" type="button" data-id="<?php echo $supplement->id; ?>">Annuler</button>
											</td>
										</form>
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
											<a><span data-toggle="tooltip" title="Modifier" class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
											<a href="?controler=restaurant&action=deleteOption&id=<?php echo $option->id; ?>">
												<span data-toggle="tooltip" title="Supprimer" class="glyphicon glyphicon-trash" aria-hidden="true"></span>
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
		<div class="row">
			<div class="panel panel-default panel-primary">
				<div class="panel-heading">
					<h2>Périmètre de livraison</h2>
				</div>
				<div class="row">
					<div class="col-md-12" style="padding : 30px;">
						<div id="googleMap" class="col-md-10 col-md-offset-1" style="height:500px; margin-top : 10px;"></div>
					</div>
				</div>
			</div>
		</div>
		<a class="btn btn-primary" href="?controler=restaurant">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		initialize();
		var list = [];
		
		var restoPoint = {};
		restoPoint.type = "ADRESSE";
		restoPoint.adresse = "<?php echo $request->restaurant->rue.', '.$request->restaurant->ville; ?>";
		restoPoint.content = "<?php echo utf8_encode($request->restaurant->nom); ?>";
		
		var circle = {};
		circle.type = "CIRCLE";
		circle.radius = 10000;
		circle.latitude = <?php echo $request->restaurant->latitude; ?>;
		circle.longitude = <?php echo $request->restaurant->longitude; ?>;
		
		list.push(restoPoint);
		list.push(circle);
		
		boundToPoints(list);
		
		$(".tr-view-categorie .edit-categorie").click(function () {
			var id = $(this).attr('data-id');
			$("#tr-view-categorie-"+id).hide();
			$("#tr-edit-categorie-"+id).show();
		});
		
		$(".tr-edit-categorie .edit-cancel").click(function () {
			var id = $(this).attr('data-id');
			$("#tr-edit-categorie-"+id).hide();
			$("#tr-view-categorie-"+id).show();
		});
		
		$(".tr-view-menu .edit-menu").click(function () {
			var id = $(this).attr('data-id');
			$("#tr-view-menu-"+id).hide();
			$("#tr-edit-menu-"+id).show();
		});
		
		$(".tr-edit-menu .edit-cancel").click(function () {
			var id = $(this).attr('data-id');
			$("#tr-edit-menu-"+id).hide();
			$("#tr-view-menu-"+id).show();
		});
		
		$(".tr-view-format .edit-format").click(function () {
			var id = $(this).attr('data-id');
			$("#tr-view-format-"+id).hide();
			$("#tr-edit-format-"+id).show();
		});
		
		$(".tr-edit-format .edit-cancel").click(function () {
			var id = $(this).attr('data-id');
			$("#tr-edit-format-"+id).hide();
			$("#tr-view-format-"+id).show();
		});
		
		$(".tr-view-formule .edit-formule").click(function () {
			var id = $(this).attr('data-id');
			$("#tr-view-formule-"+id).hide();
			$("#tr-edit-formule-"+id).show();
		});
		
		$(".tr-edit-formule .edit-cancel").click(function () {
			var id = $(this).attr('data-id');
			$("#tr-edit-formule-"+id).hide();
			$("#tr-view-formule-"+id).show();
		});
		
		$(".tr-view-supplement .edit-supplement").click(function () {
			var id = $(this).attr('data-id');
			$("#tr-view-supplement-"+id).hide();
			$("#tr-edit-supplement-"+id).show();
		});
		
		$(".tr-edit-supplement .edit-cancel").click(function () {
			var id = $(this).attr('data-id');
			$("#tr-edit-supplement-"+id).hide();
			$("#tr-view-supplement-"+id).show();
		});
	});
</script>