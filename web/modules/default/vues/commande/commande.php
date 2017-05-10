<h2>Commande #<?php echo $request->commande->id; ?></h2>
<a class="btn btn-primary" href="?controler=commande&action=index">
	<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
</a>
<div id="restaurant">
	<?php $restaurant = $request->commande->restaurant; ?>
	<h3><?php echo utf8_encode($restaurant->nom); ?></h3>
	<p><?php echo utf8_encode($restaurant->rue); ?>, <?php echo $restaurant->code_postal; ?> <?php echo $restaurant->ville; ?></p>
</div>
<div id="livreur">
	<?php if ($request->commande->livreur->id != '') : ?>
		<h3>Livreur</h3>
		<div class="col-md-6">
			<span><?php echo $request->commande->livreur->prenom; ?></span>
		</div>
		<div class="col-md-6">
			<div id="googleMap" class="col-md-10 col-md-offset-1" style="height:200px; margin-top : 10px;"></div>
		</div>
		<script type="text/javascript">
			$(function() {
				initialize();
				var list = [];
				
				var homePoint = {};
				homePoint.type = "HOME";
				homePoint.adresse = "<?php echo $request->commande->rue.', '.$request->commande->ville; ?>";
				
				list.push(homePoint);
				
				var restoPoint = {};
				restoPoint.type = "ADRESSE";
				restoPoint.adresse = "<?php echo $request->commande->restaurant->rue.', '.$request->commande->restaurant->ville; ?>";
				restoPoint.content = "<?php echo utf8_encode($request->commande->restaurant->nom); ?>";
				
				list.push(restoPoint);
				
				var livreurPoint = {};
				livreurPoint.type = "ADRESSE";
				livreurPoint.latitude = <?php echo $request->commande->livreur->latitude; ?>;
				livreurPoint.longitude = <?php echo $request->commande->livreur->longitude; ?>;
				livreurPoint.content = "<?php echo utf8_encode($request->commande->livreur->prenom); ?>";
				
				if (livreurPoint.latitude != 0 && livreurPoint.longitude != 0) {
					list.push(livreurPoint);
				}
				
				boundToPoints(list);
			});
		</script>
	<?php endif; ?>
</div>
<div id="commande">
	<h3>Commande</h3>
	<span>status : <?php echo $request->commande->getStatus(); ?></span>
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
						<?php 
							$prix = $carte->prix;
							foreach ($carte->supplements as $supplement) {
								$prix += $supplement->prix * $carte->quantite;
							}
							echo formatPrix($prix);
							$totalPrix += $prix;
						?>
					</div>
				</div>
			</div>
		</div>
		<hr />
	<?php endforeach; ?>
	<?php
		$prix_livraison = $request->commande->prix_livraison;
		if ($request->_auth && $request->_auth->is_premium) {
			$prix_livraison -= $request->commande->reduction_premium;
		}
	?>
	<div class="row">
		<div class="col-md-8">
			<span>Prix de livraison : </span>
		</div>
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-6"></div>
				<div class="col-md-6">
					<?php 
						if ($request->commande->codePromo->surPrixLivraison()) {
							if ($request->commande->codePromo->estGratuit()) {
								echo "OFFERT";
								$prix_livraison = 0;
							} else {
								$prix_livraison -= $request->commande->codePromo->valeur_prix_livraison;
								echo formatPrix($prix_livraison);
							}
						} else {
							echo formatPrix($prix_livraison);
						}
					?>
				</div>
			</div>
		</div>
		<?php $totalPrix += $prix_livraison; ?>
	</div>
	<hr />
	<?php if ($request->commande->codePromo->description != '') : ?>
		<div class="row">
			<div class="col-md-6">
				<span>Promo : </span>
			</div>
			<div class="col-md-6">
				<span><?php echo utf8_encode($request->commande->codePromo->description); ?></span>
			</div>
		</div>
		<hr />
	<?php endif; ?>
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
					<?php 
						if ($request->commande->codePromo->surPrixTotal()) {
							if ($request->commande->codePromo->estGratuit()) {
								echo "OFFERT";
							} else {
								$prixReduc = $totalPrix;
								if ($request->commande->codePromo->valeur_prix_total != -1) {
									$prixReduc -= $request->commande->codePromo->valeur_prix_total;
								}
								if ($request->commande->codePromo->pourcentage_prix_total != -1) {
									$prixReduc -= ($prixReduc * $request->commande->codePromo->pourcentage_prix_total) / 100;
								}
								echo formatPrix($prixReduc);
							}
						} else {
							echo formatPrix($totalPrix);
						}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<a class="btn btn-primary" href="?controler=commande&action=facture&commande=<?php echo $request->commande->id; ?>">Générer la facture</a>
<a class="btn btn-primary" href="?controler=commande&action=index">
	<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
</a>