<div class="row">
	<div class="col-md-12">
		<h1><?php echo utf8_encode($request->restaurant->nom); ?> : <?php echo utf8_encode($request->option->nom); ?></h1>
		<a class="btn btn-primary" href="?controler=restaurant&action=view&id_restaurant=<?php echo $request->restaurant->id; ?>">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
		<div class="row">
			<h2>Value</h2>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Nom</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($request->option->values as $value) : ?>
						<tr>
							<td><?php echo utf8_encode($value->nom); ?></td>
							<td>
								<a href="">
									<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
								</a>
								<a href="?controler=restaurant&action=deleteOptionValue&id=<?php echo $value->id; ?>&id_restaurant=<?php echo $request->restaurant->id; ?>&id_option=<?php echo $request->option->id; ?>">
									<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<form method="post" enctype="x-www-form-urlencoded" action="?controler=restaurant&action=addOptionValue">
				<fieldset>
					<input name="id_restaurant" type="text" value="<?php echo $request->restaurant->id; ?>" hidden="hidden">
					<input name="id_option" type="text" value="<?php echo $request->option->id; ?>" hidden="hidden">
					<div class="form-group">
						<label for="nom">Nom<span class="required">*</span> : </label>
						<input class="form-control" name="nom" type="text" maxlength="100" required>
					</div>
					<button class="btn btn-primary" type="submit">Ajouter</button>
				</fieldset>
			</form>
		</div>
		<a class="btn btn-primary" href="?controler=restaurant&action=view&id_restaurant=<?php echo $request->restaurant->id; ?>">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
	</div>
</div>