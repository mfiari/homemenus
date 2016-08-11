<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h2>Administration</h2>
		<?php if ($request->errorMessage) : ?>
			<?php foreach ($request->errorMessage as $key => $value) : ?>
				<div class="alert alert-danger" role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					<?php echo $value; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<div class="row">
			<h3>Information du restaurant</h3>
			<form method="post" enctype="x-www-form-urlencoded" id="adminForm" action="?action=updateInformation">
				<fieldset>
					<div class="form-group">
						<label>Nom du restaurant : </label>
						<span><?php echo utf8_encode($request->restaurant->nom); ?></span>
					</div>
					<div class="form-group">
						<label for="rue">Adresse : </label>
						<span><?php echo utf8_encode($request->restaurant->rue.', '.$request->restaurant->code_postal.' '.$request->restaurant->ville); ?></span>
					</div>
					<div class="form-group">
						<label for="telephone">Téléphone : </label>
						<span><?php echo $request->restaurant->telephone; ?></span>
					</div>
					<div class="form-group">
						<label>Courte description : </label>
						<span><?php echo utf8_encode($request->restaurant->short_desc); ?></span>
					</div>
					<div class="form-group">
						<label>Description : </label>
						<span><?php echo utf8_encode($request->restaurant->long_desc); ?></span>
					</div>
					<div class="form-group">
						<label>Pourcentage : </label>
						<span><?php echo $request->restaurant->pourcentage; ?> %</span>
					</div>
					<div class="form-group">
						<label>Virement : </label>
						<span><?php echo $request->restaurant->virement; ?></span>
					</div>
				</fieldset>
			</form>
		</div>
		<div class="row">
			<h3>Horaire</h3>
			<form method="post" enctype="x-www-form-urlencoded" id="adminForm" action="?action=inscription">
				<fieldset>
					<?php $jour = -1; ?>
					<?php foreach ($request->restaurant->horaires as $horaire) : ?>
						<?php if ($jour != $horaire->id_jour) : ?>
							<span><?php echo $horaire->name; ?></span>
							<?php $jour = $horaire->id_jour; ?>
						<?php endif; ?>
						<div class="row">
							<div class="col-md-6">
								<label>De <?php echo $horaire->heure_debut; ?>h<?php echo $horaire->minute_debut; ?></label>
							</div>
							<div class="col-md-6">
								<label>à <?php echo $horaire->heure_fin; ?>h<?php echo $horaire->minute_fin; ?></label>
							</div>
						</div>
					<?php endforeach; ?>
				</fieldset>
			</form>
		</div>
		<div class="row">
			<h3>Vos comptes utilisateurs</h3>
			<div class="row">
				<div class="col-md-12" style="padding : 30px;">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Nom</th>
								<th>Login</th>
								<th>Rôle</th>
								<th>Actif</th>
								<th>Connecté</th>
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
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="row">
			<h3>Votre périmètre de livraison</h3>
			<div class="row">
				<div class="col-md-12" style="padding : 30px;">
					<div id="googleMap" class="col-md-10 col-md-offset-1" style="height:500px; margin-top : 10px;"></div>
				</div>
			</div>
		</div>
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
		circle.radius = 15000;
		circle.latitude = <?php echo $request->restaurant->latitude; ?>;
		circle.longitude = <?php echo $request->restaurant->longitude; ?>;
		
		list.push(restoPoint);
		list.push(circle);
		
		boundToPoints(list);
		
	});
</script>