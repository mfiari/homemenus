<div class="row">
	<div class="col-md-12">
		<h2>Code promo</h2>
		<div id="code_promos">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Code</th>
						<th>description</th>
						<th>Début</th>
						<th>Fin</th>
						<th>Publique</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($request->promos as $promo) : ?>
						<tr>
							<td>
								<a href="?controler=codePromo&action=view&id=<?php echo $promo->id; ?>">
									<?php echo $promo->code; ?>
								</a>
							</td>
							<td><?php echo utf8_encode($promo->description); ?></td>
							<td><?php echo $promo->date_debut; ?></td>
							<td><?php echo $promo->date_fin; ?></td>
							<td><?php echo $promo->publique ? '<span style="color : green; ">Oui</span>' : '<span style="color : red; ">Non</span>'; ?></td>
							<td>
								<a href="?controler=codePromo&action=view&id=<?php echo $promo->id; ?>">
									<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-12">
		<a href="?controler=codePromo&action=edit" class="btn btn-primary">Ajouter un code promo</a>
	</div>
</div>