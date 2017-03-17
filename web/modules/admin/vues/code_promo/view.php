<div id="informations">
	<h2>Code promo <?php echo $request->codePromo->code; ?></h2>
	<a class="btn btn-primary" href="?controler=codePromo">
		<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
	</a>
	<div class="row">
		<p><?php echo utf8_encode($request->codePromo->description); ?></p>
	</div>
	<div class="row">
		<p>Du <?php echo $request->codePromo->date_debut; ?> au <?php echo $request->codePromo->date_fin; ?></p>
	</div>
	<?php if (!$request->codePromo->publique) : ?>
		<h2>Liste des clients</h2>
		<div id="clients">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Prénom</th>
						<th>login</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($request->codePromo->users as $user) : ?>
						<tr>
							<td><?php echo utf8_encode($user->nom); ?></td>
							<td><?php echo utf8_encode($user->prenom); ?></td>
							<td><?php echo $user->login; ?></td>
							<td>
								<a href="?controler=user&action=client&id_user=<?php echo $user->id; ?>">
									<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<div>
			<form method="post" enctype="x-www-form-urlencoded" action="?controler=codePromo&action=addUser">
				<input name="id_code_promo" type="text" value="<?php echo $request->codePromo->id; ?>" hidden="hidden">
				<span>Ajouter un utilisateur</span>
				<fieldset>
					<select name="id_user">
						<option value=""></option>
						<?php foreach ($request->clients as $client) : ?>
							<option value="<?php echo $client->id; ?>"><?php echo utf8_encode($client->nom); ?> <?php echo utf8_encode($client->prenom); ?> (<?php echo $client->login; ?>)</option>
						<?php endforeach; ?>
					</select>
					<button class="btn btn-primary" type="submit">Ajouter</button>
				</fieldset>
			</form>
		</div>
	<?php endif; ?>
	<?php if ($request->codePromo->sur_restaurant) : ?>
		<h2>Liste des restaurants</h2>
		<div id="restaurants">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Nom</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($request->codePromo->restaurants as $restaurant) : ?>
						<tr>
							<td><?php echo utf8_encode($restaurant->nom); ?></td>
							<td>
								<a href="?controler=restaurant&action=view&id_restaurant=<?php echo $restaurant->id; ?>">
									<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<div>
			<form method="post" enctype="x-www-form-urlencoded" action="?controler=codePromo&action=addUser">
				<input name="id_code_promo" type="text" value="<?php echo $request->codePromo->id; ?>" hidden="hidden">
				<span>Ajouter un restaurant</span>
				<fieldset>
					<select name="user">
						<option value=""></option>
						<?php foreach ($request->restaurants as $restaurant) : ?>
							<option value="<?php echo $restaurants->id; ?>"><?php echo utf8_encode($restaurants->nom); ?></option>
						<?php endforeach; ?>
					</select>
					<button class="btn btn-primary" type="submit">Ajouter</button>
				</fieldset>
			</form>
		</div>
	<?php endif; ?>
</div>

<a class="btn btn-primary" href="?controler=codePromo">
	<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
</a>