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
		<div class="col-md-8">
			<span>Total : </span>
		</div>
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-6">
					<?php echo $totalQte; ?>
				</div>
				<div class="col-md-6">
					<?php echo $totalPrix; ?> €
				</div>
			</div>
		</div>
	</div>
</div>
<?php if ($request->commande->etape == 0) : ?>
	<a class="btn btn-primary" href="?controler=commande&action=validationRestaurant&id_commande=<?php echo $request->commande->id; ?>">Préparaion de la commande</a>
<?php elseif ($request->commande->etape == 1) : ?>
	<a class="btn btn-primary" href="?controler=commande&action=preparationRestaurant&id_commande=<?php echo $request->commande->id; ?>">Valider la préparaion de la commande</a>
<?php elseif ($request->commande->etape == 2) : ?>
	<a class="btn btn-primary" href="?controler=commande&action=recuperationLivreur&id_commande=<?php echo $request->commande->id; ?>">Livraison de la commande</a>
<?php elseif ($request->commande->etape == 3) : ?>
	<a class="btn btn-primary" href="?controler=commande&action=livraisonCommande&id_commande=<?php echo $request->commande->id; ?>">Valider la livraison de la commande</a>
<?php endif; ?>
<a class="btn btn-primary" href="?controler=commande&action=annule&id_commande=<?php echo $request->commande->id; ?>">Anuler et rembourser</a>
<a class="btn btn-primary" href="?controler=commande&action=facture&commande=<?php echo $request->commande->id; ?>">Générer la facture</a>
<a class="btn btn-primary" href="?controler=commande&action=index">
	<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
</a>