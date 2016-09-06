<?php $commande = $request->commande; ?>
<h2>Commande #<?php echo $request->commande->id; ?></h2>
<a class="btn btn-primary" href="?controler=commande&action=history">
	<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
</a>
<div id="restaurant">
	<h3>Restaurant</h3>
	<?php $restaurant = $request->commande->restaurant; ?>
	<span><?php echo utf8_encode($restaurant->nom); ?></span><br />
	<?php $adresse = $restaurant->rue.', '.$restaurant->code_postal.' '.$restaurant->ville; ?>
	<span>Adresse : <?php echo utf8_encode($adresse); ?></span><br />
	<span>Téléphone : <?php echo $restaurant->telephone; ?></span><br />
</div>
<div id="client">
	<h3>Client</h3>
	<?php $client = $request->commande->client; ?>
	<span><?php echo utf8_encode($client->nom); ?> <?php echo utf8_encode($client->prenom); ?></span><br />
	<?php $adresse = $commande->rue.', '.$commande->code_postal.' '.$commande->ville; ?>
	<span>Adresse : <?php echo utf8_encode($adresse); ?></span><br />
	<span>Téléphone : <?php echo $commande->telephone; ?></span><br />
</div>
<div id="commande">
	<h3>Commande</h3>
	<?php $totalPrix = 0; ?>
	<?php $totalQte = 0; ?>
	<?php foreach ($request->commande->menus as $menu) : ?>
		<div class="row">
			<div class="col-md-8 col-sm-8">
				<?php echo utf8_encode($menu->nom); ?>
				<?php if (count($menu->formats) == 1 && $menu->formats[0]->nom != "") : ?>
					(<?php echo utf8_encode($menu->formats[0]->nom); ?>)
				<?php endif; ?>
				<?php foreach ($menu->formules as $formule) : ?>
					<div class="row">
						<div class="col-md-offset-1 col-md-11 col-sm-11">
							<span><?php echo utf8_encode($formule->nom); ?></span>
							<?php foreach ($formule->categories as $categorie) : ?>
								<div class="row">
									<div class="col-md-offset-1 col-md-11 col-sm-11">
										<span><?php echo utf8_encode($categorie->nom); ?></span>
										<?php foreach ($categorie->contenus as $contenu) : ?>
											<span><?php echo utf8_encode($contenu->nom); ?></span>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="col-md-4 col-sm-4">
				<div class="row">
					<div class="col-md-6 col-sm-6">
						X <?php echo $menu->quantite; ?>
						<?php $totalQte += $menu->quantite; ?>
					</div>
					<div class="col-md-6 col-sm-6">
						<?php echo $menu->prix; ?> €
						<?php $totalPrix += $menu->prix; ?>
					</div>
				</div>
			</div>
		</div>
		<hr />
	<?php endforeach; ?>
	<?php foreach ($request->commande->cartes as $carte) : ?>
		<div class="row">
			<div class="col-md-8 col-sm-8">
				<?php echo utf8_encode($carte->nom); ?>
				<?php if (count($carte->formats) == 1 && $carte->formats[0]->nom != "") : ?>
					(<?php echo utf8_encode($carte->formats[0]->nom); ?>)
				<?php endif; ?>
				<?php if (count($carte->supplements) > 0) : ?>
					<div class="row">
						<div class="col-md-offset-1 col-md-11 col-sm-11">
							<span>Suppléments : </span>
							<div class="row">
								<div class="col-md-offset-1 col-md-11 col-sm-11">
									<?php foreach ($carte->supplements as $supplement) : ?>
										<span><?php echo utf8_encode($supplement->nom); ?></span>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<div class="col-md-4 col-sm-4">
				<div class="row">
					<div class="col-md-6 col-sm-6">
						X <?php echo $carte->quantite; ?>
						<?php $totalQte += $carte->quantite; ?>
					</div>
					<div class="col-md-6 col-sm-6">
						<?php echo $carte->prix; ?> €
						<?php $totalPrix += $carte->prix; ?>
					</div>
				</div>
			</div>
		</div>
		<hr />
	<?php endforeach; ?>
	<div class="row">
		<div class="col-md-8 col-sm-8">
			<span>Prix de livraison : </span>
		</div>
		<div class="col-md-4 col-sm-4">
			<div class="row">
				<div class="col-md-6 col-sm-6"></div>
				<div class="col-md-6 col-sm-6">
					<?php echo $request->commande->prix_livraison; ?> €
				</div>
			</div>
		</div>
		<?php $totalPrix += $request->commande->prix_livraison; ?>
	</div>
	<hr />
	<div class="row">
		<div class="col-md-8 col-sm-8">
			<span>Total : </span>
		</div>
		<div class="col-md-4 col-sm-4">
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<?php echo $totalQte; ?>
				</div>
				<div class="col-md-6 col-sm-6">
					<?php echo $totalPrix; ?> €
				</div>
			</div>
		</div>
	</div>
</div>
<a class="btn btn-primary" href="?controler=commande&action=history">
	<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
</a>