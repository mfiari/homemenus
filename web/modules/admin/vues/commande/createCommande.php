<div class="row">
	<div class="col-md-12">
		<h2>Création d'une commande</h2>
		<?php if ($request->errorMessage) : ?>
			<?php foreach ($request->errorMessage as $key => $value) : ?>
				<div class="alert alert-danger" role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					<?php echo $value; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<div id="commandes">
			<form method="post" enctype="x-www-form-urlencoded" action="?controler=commande&action=create">
				<fieldset>
					<div class="form-group">
						<label for="nom">Client : </label>
						<select name="client">
							<option value=""></option>
							<?php foreach ($request->clients as $client) : ?>
								<option value="<?php echo $client->id; ?>"><?php echo $client->nom; ?> <?php echo $client->prenom; ?> (<?php echo $client->login; ?>)</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label for="nom">Restaurant : </label>
						<select name="restaurant">
							<option value=""></option>
							<?php foreach ($request->restaurants as $restaurant) : ?>
								<option value="<?php echo $restaurant->id; ?>"><?php echo $restaurant->nom; ?> (<?php echo $restaurant->ville; ?>)</option>
							<?php endforeach; ?>
						</select>
					</div>
					<button class="btn btn-primary" type="submit">Valider</button>
				</fieldset>
			</form>
		</div>
	</div>
</div>