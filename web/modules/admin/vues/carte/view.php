<div class="row">
	<div class="col-md-12">
		<h1><?php echo $request->restaurant->nom; ?></h1>
		<div class="row">
			<table class="table table-striped">
				<tbody>
					<?php foreach ($request->restaurant->horaires as $horaire) : ?>
						<tr>
							<td><?php echo $horaire->name; ?></td>
							<td><?php echo $horaire->heure_debut; ?>:<?php echo $horaire->minute_debut; ?></td>
							<td><?php echo $horaire->heure_fin; ?>:<?php echo $horaire->minute_fin; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<div class="row">
			<h2>Cartes</h2>
			<a href="?module=admin&controler=carte&action=edit" class="btn btn-primary">Ajouter un produit à la carte</a>
		</div>
		<div class="row">
			<h2>Menus</h2>
			<a href="?module=admin&controler=menu&action=edit" class="btn btn-primary">Ajouter un menu</a>
		</div>
	</div>
</div>