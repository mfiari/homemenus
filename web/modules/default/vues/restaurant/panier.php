<div>
	<?php if ($request->panier) : ?>
		<form method="post" enctype="x-www-form-urlencoded" id="panierForm" action="">
			<input type="hidden" id="id_panier" name="id_panier" value="<?php echo $request->panier->id; ?>" />
			<?php
				$current_heure = date('G')+GTM_INTERVAL;
				$current_minute = date('i');
				$horaire = $request->panier->restaurant->horaire;
			?>
			<div>
				<?php if (!$horaire->id) : ?>
					<span style="color : red;">Le <?php echo utf8_encode($request->panier->restaurant->nom); ?> est actuellement fermé.</span>
				<?php elseif ($horaire->heure_debut > $current_heure || ($horaire->heure_debut == $current_heure && $horaire->minute_debut > $current_minute)) : ?>
					<input type="hidden" name="type_commande" value="pre_commande">
					<span style="color : red;">
						Le restaurant <?php echo utf8_encode($request->panier->restaurant->nom); ?> est actuellement fermé. Ouverture
							de <?php echo formatHeureMinute($horaire->heure_debut, $horaire->minute_debut); ?> 
							à <?php echo formatHeureMinute($horaire->heure_fin, $horaire->minute_fin); ?><br />
						Précommande possible dès maintenant.
					</span><br /><br />
					<span><b>Heure de livraison souhaitée : </b></span><br />
					<div id="heure_livraison">
						<select id="heure_commande" name="heure_commande"></select>h<select id="minute_commande" name="minute_commande"></select>
					</div><br />
					<div>
						<span>Temps de livraison estimé : <?php echo $request->panier->getTempsLivraison(); ?> min</span>
					</div>
				<?php else : ?>
					<input type="radio" name="type_commande" value="now" checked>Au plus tôt
					<input type="radio" name="type_commande" value="pre_commande">Précommander
					<div id="heure_livraison">
						<span>heure de commande : </span>
						<select id="heure_commande" name="heure_commande"></select>h<select id="minute_commande" name="minute_commande"></select>
					</div><br />
					<div>
						<span>Temps de livraison estimé : <?php echo $request->panier->getTempsLivraison(); ?> min</span>
					</div>
				<?php endif; ?>
			</div><br />
			<div class="panel panel-default panel-primary">
				<div class="panel-heading">
					Restaurant <?php echo utf8_encode($request->panier->restaurant->nom); ?>
				</div>
				<div>
					<?php $totalQte = 0; ?>
					<?php $totalPrix = 0; ?>
					<table class="table">
						<thead>
							<tr>
								<th>Nom</th>
								<th>Quantité</th>
								<th>prix</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($request->panier->carteList as $carte) : ?>
								<tr id="tr_carte_<?php echo $carte->id; ?>">
									<td><?php echo utf8_encode($carte->nom); ?></td>
									<td><?php echo $carte->quantite; ?></td>
									<td><?php echo formatPrix($carte->prix); ?></td>
									<td><a class="carte-item-delete" data-id="<?php echo $carte->id; ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
								</tr>
								<?php $totalQte += $carte->quantite; ?>
								<?php $totalPrix += $carte->prix; ?>
							<?php endforeach; ?>
							<?php foreach ($request->panier->menuList as $menu) : ?>
								<tr>
									<td><?php echo utf8_encode($menu->nom); ?></td>
									<td><?php echo $menu->quantite; ?></td>
									<td><?php echo formatPrix($menu->prix); ?></td>
									<td><a class="menu-item-delete" data-id="<?php echo $menu->id; ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
								</tr>
								<?php $totalQte += $menu->quantite; ?>
								<?php $totalPrix += $menu->prix; ?>
							<?php endforeach; ?>
							<?php
								$prix_livraison = $request->panier->prix_livraison;
								if ($request->_auth && $request->_auth->is_premium) {
									$prix_livraison -= $request->panier->reduction_premium;
								}
							?>
							<tr>
								<td>prix de livraison</td>
								<td></td>
								<td>
									<?php 
										if ($request->panier->code_promo->surPrixLivraison()) {
											if ($request->panier->code_promo->estGratuit()) {
												echo "OFFERT";
												$prix_livraison = 0;
											} else {
												$prix_livraison -= $request->panier->code_promo->valeur_prix_livraison;
												$totalPrix += $prix_livraison;
												echo formatPrix($prix_livraison);
											}
										} else {
											echo formatPrix($prix_livraison);
											$totalPrix += $prix_livraison;
										}
									?>
								</td>
								<td></td>
							</tr>
						</tbody>
						<tfoot>
							<?php if ($request->panier->code_promo->description != '') : ?>
								<tr>
									<th>Promo :</th>
									<td colspan="3"><?php echo utf8_encode($request->panier->code_promo->description); ?></td>
								</tr>
							<?php endif; ?>
							<tr>
								<th>Total :</th>
								<th><?php echo $totalQte; ?></th>
								<th>
									<?php 
										if ($request->panier->code_promo->surPrixTotal()) {
											if ($request->panier->code_promo->estGratuit()) {
												echo "OFFERT";
											} else {
												$prixReduc = $totalPrix;
												if ($request->panier->code_promo->valeur_prix_total != -1) {
													$prixReduc -= $request->panier->code_promo->valeur_prix_total;
												}
												if ($request->panier->code_promo->pourcentage_prix_total != -1) {
													$prixReduc -= ($prixReduc * $request->panier->code_promo->pourcentage_prix_total) / 100;
												}
												echo formatPrix($prixReduc);
											}
										} else {
											echo formatPrix($totalPrix);
										}
									?>
								</th>
								<td></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<?php if (!$horaire->id) : ?>
				<div class="alert alert-danger" role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					Commande impossible car restaurant fermé. Merci de réessayer un autre jour
				</div>
			<?php elseif ($request->panier->prix_minimum > ($totalPrix - $prix_livraison)) : ?>
				<div class="alert alert-danger" role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					Le montant minimum pour commander est de <?php echo $request->panier->prix_minimum; ?> € (hors prix de livraison)
				</div>
			<?php else : ?>
				<?php if ($horaire->heure_debut > $current_heure || ($horaire->heure_debut == $current_heure && $horaire->minute_debut > $current_minute)) : ?>
					<button id="command" class="validate-button" type="submit">Précommander</button>
				<?php else : ?>
					<button id="command" class="validate-button" type="submit">Commander</button>
				<?php endif; ?>
			<?php endif; ?>
		</form>
	<?php else : ?>
		<span>(vide)</span>
	<?php endif; ?>
</div>