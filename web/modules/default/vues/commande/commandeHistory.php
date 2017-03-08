<h2>Commande #<?php echo $request->commande->id; ?></h2>
<a class="btn btn-primary" href="?controler=commande&action=history">
	<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
</a>
<div id="restaurant">
	<?php $restaurant = $request->commande->restaurant; ?>
	<h3><?php echo utf8_encode($restaurant->nom); ?></h3>
	<p><?php echo utf8_encode($restaurant->rue); ?>, <?php echo $restaurant->code_postal; ?> <?php echo $restaurant->ville; ?></p>
</div>
<div id="livreur">
	<h3>Livreur</h3>
	<div class="col-md-12">
		<span><?php echo $request->commande->livreur->prenom; ?></span>
	</div>
</div>
<div id="commande">
	<h3>Commande</h3>
	<?php $totalPrix = 0; ?>
	<?php $totalQte = 0; ?>
	<?php foreach ($request->commande->menus as $menu) : ?>
		<div class="row">
			<div class="col-md-8">
				<?php echo utf8_encode($menu->nom); ?>
				<?php if (count($menu->formats) == 1 && $menu->formats[0]->nom != "") : ?>
					(<?php echo utf8_encode($menu->formats[0]->nom); ?>)
				<?php endif; ?>
				<?php foreach ($menu->formules as $formule) : ?>
					<?php foreach ($formule->categories as $categorie) : ?>
						<div class="row">
							<div class="col-md-offset-1 col-md-11">
								<span><?php echo utf8_encode($categorie->nom); ?> : </span>
								<?php foreach ($categorie->contenus as $contenu) : ?>
									<span><?php echo utf8_encode($contenu->nom); ?></span>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</div>
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-6">
						X <?php echo $menu->quantite; ?>
						<?php $totalQte += $menu->quantite; ?>
					</div>
					<div class="col-md-6">
						<?php echo formatPrix($menu->prix); ?>
						<?php $totalPrix += $menu->prix; ?>
					</div>
				</div>
			</div>
		</div>
		<hr />
	<?php endforeach; ?>
	<?php foreach ($request->commande->cartes as $carte) : ?>
		<div class="row">
			<div class="col-md-8">
				<?php echo utf8_encode($carte->nom); ?>
				<?php if (count($carte->formats) == 1 && $carte->formats[0]->nom != "") : ?>
					(<?php echo utf8_encode($carte->formats[0]->nom); ?>)
				<?php endif; ?>
				<?php if (count($carte->supplements) > 0) : ?>
					<div class="row">
						<div class="col-md-offset-1 col-md-11">
							<span>Suppléments : </span>
							<div class="row">
								<div class="col-md-offset-1 col-md-11">
									<?php foreach ($carte->supplements as $supplement) : ?>
										<span><?php echo utf8_encode($supplement->nom); ?></span>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<?php if (count($carte->options) > 0) : ?>
					<div class="row">
						<div class="col-md-offset-1 col-md-11">
							<div class="row">
								<div class="col-md-offset-1 col-md-11">
									<?php foreach ($carte->options as $option) : ?>
										<span><?php echo utf8_encode($option->nom); ?></span>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<?php if (count($carte->accompagnements) > 0) : ?>
					<div class="row">
						<div class="col-md-offset-1 col-md-11">
							<span>accompagnements : </span>
							<div class="row">
								<div class="col-md-offset-1 col-md-11">
									<?php foreach ($carte->accompagnements as $accompagnement) : ?>
										<span><?php echo utf8_encode($accompagnement->nom); ?></span>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-6">
						X <?php echo $carte->quantite; ?>
						<?php $totalQte += $carte->quantite; ?>
					</div>
					<div class="col-md-6">
						<?php echo formatPrix($carte->prix); ?>
						<?php $totalPrix += $carte->prix; ?>
					</div>
				</div>
			</div>
		</div>
		<hr />
	<?php endforeach; ?>
	<div class="row">
		<div class="col-md-8">
			<span>Prix de livraison : </span>
		</div>
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-6"></div>
				<div class="col-md-6">
					<?php echo formatPrix($request->commande->prix_livraison); ?>
				</div>
			</div>
		</div>
		<?php $totalPrix += $request->commande->prix_livraison; ?>
	</div>
	<hr />
	<div class="row">
		<div class="col-md-8">
			<span>Total : </span>
		</div>
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-6">
					<?php echo $totalQte; ?>
				</div>
				<div class="col-md-6">
					<?php echo formatPrix($totalPrix); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<a class="btn btn-primary" href="?controler=commande&action=factureHistory&commande=<?php echo $request->commande->id; ?>">Générer la facture</a>
<a class="btn btn-primary" href="?controler=commande&action=history">
	<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
</a>