<h2>Commande #<?php echo $request->commande->id; ?></h2>
<a class="btn btn-primary" href="?controler=commande&action=index">
	<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
</a>
<div id="restaurant">
	<h3>Restaurant : <?php echo utf8_encode($request->commande->restaurant->nom); ?></h3>
	<p>Adresse : <?php echo utf8_encode($request->commande->restaurant->rue); ?>, <?php echo $request->commande->restaurant->code_postal; ?> <?php echo utf8_encode($request->commande->restaurant->ville); ?></p>
	<p>Téléphone : <?php echo $request->commande->restaurant->telephone; ?></p>
</div>
<div id="client">
	<h3>Client</h3>
	<span><?php echo utf8_encode($request->commande->client->nom); ?> <?php echo utf8_encode($request->commande->client->prenom); ?></span>
	<p>Adresse : <?php echo utf8_encode($request->commande->rue); ?>, <?php echo $request->commande->code_postal; ?> <?php echo utf8_encode($request->commande->ville); ?></p>
	<p>Complément : <?php echo utf8_encode($request->commande->complement); ?></p>
	<p>Téléphone : <?php echo $request->commande->telephone; ?></p>
</div>
<div id="livreur">
	<h3>Livreur</h3>
	<?php if ($request->commande->livreur->prenom != '') : ?>
		<div class="col-md-12">
			<span><?php echo $request->commande->livreur->prenom; ?></span>
		</div>
	<?php endif; ?>
	<form method="post" enctype="x-www-form-urlencoded" action="?controler=commande&action=updateLivreur">
		<input name="id_commande" type="text" value="<?php echo $request->commande->id; ?>" hidden="hidden">
		modifier le livreur
		<fieldset>
			<select name="livreur">
				<option value=""></option>
				<?php foreach ($request->livreurs as $livreur) : ?>
					<option value="<?php echo $livreur->id; ?>"><?php echo $livreur->login; ?></option>
				<?php endforeach; ?>
			</select>
			<button class="btn btn-primary" type="submit">Modifier</button>
		</fieldset>
	</form>
</div>
<div id="commande">
	<h3>Commande</h3>
	<span>status : <?php echo $request->commande->getStatus(); ?></span><br /><br />
	<span>Date de commande : <?php echo $request->commande->date_commande; ?></span><br /><br />
	<?php if ($request->commande->heure_souhaite == -1) : ?>
		<span>Heure de livraison souhaitée : Au plus tôt</span>
	<?php else : ?>
		<span>Heure de livraison souhaitée : <?php echo utf8_encode($request->commande->heure_souhaite); ?>h<?php echo utf8_encode($request->commande->minute_souhaite); ?></span>
	<?php endif; ?>
	<br /><br />
	<span>Temps de preparation estimé : <?php echo $request->commande->preparation_restaurant; ?> min</span><br /><br />
	<span>Temps de livraison estimé : <?php echo $request->commande->temps_livraison; ?> min</span><br /><br />
	<?php if ($request->commande->heure_souhaite == -1) : ?>
		<span>Heure de livraison estimé : <?php echo $request->commande->getHeureLivraison(); ?></span><br /><br />
	<?php endif; ?>
	<span>Methode de paiement : <?php echo $request->commande->paiement_method; ?></span><br /><br />
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
					<div class="row">
						<div class="col-md-offset-1 col-md-11">
							<span><?php echo utf8_encode($formule->nom); ?></span>
							<?php foreach ($formule->categories as $categorie) : ?>
								<div class="row">
									<div class="col-md-offset-1 col-md-11">
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
										<?php foreach ($option->values as $optionValue) : ?>
											<span><?php echo utf8_encode($option->nom); ?> : <?php echo utf8_encode($optionValue->nom); ?></span>
										<?php endforeach; ?>
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
	<?php
		$prix_livraison = $request->commande->prix_livraison;
		if ($request->_auth && $request->_auth->is_premium) {
			$prix_livraison -= $request->commande->reduction_premium;
		}
	?>
	<div class="row">
		<div class="col-md-8 col-sm-8">
			<span>Prix de livraison : </span>
		</div>
		<div class="col-md-4 col-sm-4">
			<div class="row">
				<div class="col-md-6 col-sm-6"></div>
				<div class="col-md-6 col-sm-6">
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
<?php if ($request->commande->annulation_commentaire != '') : ?>
	<div class="row">
		<h3>Commentaire annulation</h3>
		<p><?php echo utf8_encode($request->commande->annulation_commentaire); ?></p>
	</div>
<?php endif; ?>
<?php if ($request->commande->annomalie_commentaire != '') : ?>
	<div class="row">
		<h3>Anomalie</h3>
		<div class="row">
			<p><b>Montant du remboursement : </b><?php echo formatPrix($request->commande->annomalie_montant); ?></p>
		</div>
		<div class="row">
			<p><b>Commentaire : </b><?php echo utf8_encode($request->commande->annomalie_commentaire); ?></p>
		</div>
	</div>
<?php endif; ?>
<?php if ($request->commande->etape == 0) : ?>
	<a class="btn btn-primary" href="?controler=commande&action=validationRestaurant&id_commande=<?php echo $request->commande->id; ?>">Préparaion de la commande</a>
<?php elseif ($request->commande->etape == 1) : ?>
	<a class="btn btn-primary" href="?controler=commande&action=preparationRestaurant&id_commande=<?php echo $request->commande->id; ?>">Valider la préparaion de la commande</a>
<?php elseif ($request->commande->etape == 2) : ?>
	<a class="btn btn-primary" href="?controler=commande&action=recuperationLivreur&id_commande=<?php echo $request->commande->id; ?>">Livraison de la commande</a>
<?php elseif ($request->commande->etape == 3) : ?>
	<a class="btn btn-primary" href="?controler=commande&action=livraisonCommande&id_commande=<?php echo $request->commande->id; ?>">Valider la livraison de la commande</a>
<?php endif; ?>
<?php if ($request->commande->etape != -1) : ?>
	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#annulation-modal">Anuler et rembourser</button>
	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#annomalie-modal">Signaler une anomalie</button>
<?php endif; ?>
<a class="btn btn-primary" href="?controler=commande&action=remove&id_commande=<?php echo $request->commande->id; ?>">Supprimer</a>
<a class="btn btn-primary" href="?controler=commande&action=facture&commande=<?php echo $request->commande->id; ?>">Générer la facture</a>
<a class="btn btn-primary" href="?controler=commande&action=index">
	<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
</a>
<div id="annulation-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Annuler et rembourser de la commande</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<form method="post" enctype="x-www-form-urlencoded" action="?controler=commande&action=annule">
					<input name="id_commande" value="<?php echo $request->commande->id; ?>" hidden="hidden" />
					<div class="form-group">
						<label for="rue">Commentaire : </label>
						<textarea id="annulation_commentaire" class="form-control" name="commentaire" rows="8" cols="45"></textarea>
					</div>
					<div class="row">
						<div class="col-md-6 center">
							<button class="validate-button" type="submit">Valider</button>
						</div>
						<div class="col-md-6 center">
							<button type="button" class="close-button" data-dismiss="modal">Fermer</button>
						</div>
					</div>
				</form>
				
			</div>
		</div>
	</div>
</div>
<div id="annomalie-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Signaler une anomalie</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<form method="post" enctype="x-www-form-urlencoded" action="?controler=commande&action=annomalie">
					<input name="id_commande" value="<?php echo $request->commande->id; ?>" hidden="hidden" />
					<div class="form-group">
						<label for="rue">Montant : </label>
						<input class="form-control" name="montant" type="text" value="0">
					</div>
					<div class="form-group">
						<label for="rue">Commentaire : </label>
						<textarea id="annomalie_commentaire" class="form-control" name="commentaire" rows="8" cols="45"></textarea>
					</div>
					<div class="row">
						<div class="col-md-6 center">
							<button class="validate-button" type="submit">Valider</button>
						</div>
						<div class="col-md-6 center">
							<button type="button" class="close-button" data-dismiss="modal">Fermer</button>
						</div>
					</div>
				</form>
				
			</div>
		</div>
	</div>
</div>