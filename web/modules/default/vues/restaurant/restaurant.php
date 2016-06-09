<?php $restaurant = $request->restaurant; ?>
<?php $horaire = $restaurant->horaire; ?>
<input id="id_restaurant" value="<?php echo $restaurant->id; ?>" hidden="hidden" />
<div id="restaurant">
	<h2><?php echo utf8_encode($request->restaurant->nom); ?></h2>
	<a style="margin-bottom: 10px;" class="btn btn-primary" href="?controler=restaurant&action=recherche">
		<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour au resultat de la recherche
	</a>
	<div class="row">
		<div class="panel panel-default panel-primary">
			<div class="panel-heading">
				Informations générales
			</div>
			<div class="row">
				<div class="col-md-6">
					<div style="margin-left : 10px; margin-top : 10px;">
						<p><span>Adresse : </span><?php echo utf8_encode($restaurant->rue); ?>, <?php echo $restaurant->code_postal; ?> <?php echo $restaurant->ville; ?></p>
						<p><?php echo utf8_encode($restaurant->short_desc); ?></p>
						<p><i><?php echo utf8_encode($restaurant->long_desc); ?></i></p>
						<p>
							<?php if ($horaire === false || $horaire->id_jour == '') : ?>
								<span>Fermé</span>
							<?php else : ?>
								<span>Ouvert de <?php echo formatHeureMinute($horaire->heure_debut,$horaire->minute_debut); ?> à <?php echo formatHeureMinute($horaire->heure_fin,$horaire->minute_fin); ?></span>
							<?php endif; ?>
						</p>
						<p>Distance : <?php echo $restaurant->distance; ?> km</p>
						<p>Prix de livraison : <?php echo formatPrix($request->prix_livraison); ?></p>
					</div>
				</div>
				<div class="col-md-6">
					<div id="googleMap" class="col-md-10 col-md-offset-1" style="height:200px; margin-top : 10px;"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<?php foreach ($restaurant->categories as $categorie) : ?>
				<div class="row">
					<a href="#categorie-<?php echo $categorie->id; ?>"><?php echo utf8_encode($categorie->nom); ?></a>
				</div>
			<?php endforeach; ?>
			<?php if (count($restaurant->menus) > 0) : ?>
				<div class="row">
					<a href="#categorie-menu">Menus</a>
				</div>
			<?php endif; ?>
		</div>
		<div class="col-md-6">
			<?php foreach ($restaurant->categories as $categorie) : ?>
				<div class="row">
					<h3 id="categorie-<?php echo $categorie->id; ?>"><?php echo utf8_encode($categorie->nom); ?></h3>
					<?php foreach ($categorie->contenus as $contenu) : ?>
						<hr />
						<div data-id="<?php echo $contenu->id; ?>" class="row carte-item">
							<div class="col-md-12">
								<p class="carte-item-title"><?php echo utf8_encode($contenu->nom); ?></p>
								<div class="row">
									<div class="col-md-4">
										<div class="row">
											<div class="vignette"><img src="<?php echo $contenu->logo; ?>"></div>
										</div>
										<div class="row">
											<span>Prix : <?php echo formatPrix($contenu->prix); ?></span>
										</div>
									</div>
									<div class="col-md-6">
										<?php echo utf8_encode($contenu->commentaire); ?>
									</div>
									<div class="col-md-2">
										<span class="add-button" style="display : none;">+</span>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
			<?php if (count($restaurant->menus) > 0) : ?>
				<div class="row">
					<h3 id="categorie-menu">Menus</h3>
					<?php foreach ($restaurant->menus as $menu) : ?>
						<hr />
						<div data-id="<?php echo $menu->id; ?>" class="row menu-item">
							<div class="col-md-12">
								<p class="carte-item-title"><?php echo utf8_encode($menu->nom); ?></p>
								<div class="row">
									<div class="col-md-4">
										<div class="row">
											<div class="vignette"><img src="<?php echo $menu->logo; ?>"></div>
										</div>
										<div class="row">
											<span>Prix : <?php echo formatPrix($menu->prix); ?></span>
										</div>
									</div>
									<div class="col-md-6">
										<?php echo utf8_encode($menu->commentaire); ?>
									</div>
									<div class="col-md-2">
										<span class="add-button" style="display : none;">+</span>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<div id="panier" class="col-md-3">
			<h3>Panier</h3>
			<div>
				<?php if ($request->panier) : ?>
					<form method="post" enctype="x-www-form-urlencoded" id="panierForm" action="">
						<input type="hidden" id="id_panier" name="id_panier" value="<?php echo $request->panier->id; ?>" />
						<input id="rue" name="rue" type="text" value="<?php echo $request->rue; ?>" hidden="hidden">
						<input id="ville" name="ville" type="text" value="<?php echo $request->ville; ?>" hidden="hidden">
						<input id="code_postal" name="code_postal" type="text" value="<?php echo $request->codePostal; ?>" hidden="hidden">
						<input id="telephone" name="telephone" type="text" value="<?php echo $request->_auth->telephone; ?>" hidden="hidden">
						<?php
							$current_heure = date('G')+1;
							$current_minute = date('i');
							$horaire = $request->panier->restaurant->horaire;
						?>
						<div>
							<?php if ($horaire->heure_debut > $current_heure || ($horaire->heure_debut == $current_heure && $horaire->minute_debut > $current_minute)) : ?>
								<input type="hidden" name="type_commande" value="pre_commande">
								<span style="color : red;">
									Le <?php echo utf8_encode($request->panier->restaurant->nom); ?> est actuellement fermé. Ouverture
										de <?php echo formatHeureMinute($horaire->heure_debut, $horaire->minute_debut); ?> 
										à <?php echo formatHeureMinute($horaire->heure_fin, $horaire->minute_fin); ?></span><br /><br />
								<span><b>heure de livraison souhaité : </b></span><br />
								<?php 
									if ($horaire->heure_debut < $current_heure) {
										$beginHour = $current_heure;
									} else {
										$beginHour = $horaire->heure_debut;
									}
								?>
								<select name="heure_commande">
									<?php for ($i = $beginHour ; $i <= $horaire->heure_fin ; $i++) : ?>
										<option><?php echo $i; ?></option>
									<?php endfor; ?>
								</select>h
								<select name="minute_commande">
									<?php for ($i = 0 ; $i <= 60 ; $i++) : ?>
										<option <?php echo $i == $horaire->minute_debut ? 'selected' : ''; ?>><?php echo $i; ?></option>
									<?php endfor; ?>
								</select>
							<?php else : ?>
								<input type="radio" name="type_commande" value="now" checked>Au plus tôt
								<input type="radio" name="type_commande" value="pre_commande">Pré-commande
								<span>heure de commande</span>
								<?php 
									if ($horaire->heure_debut < $current_heure) {
										$beginHour = $current_heure;
									} else {
										$beginHour = $horaire->heure_debut;
									}
								?>
								<select name="heure_commande">
									<?php for ($i = $beginHour ; $i <= $horaire->heure_fin ; $i++) : ?>
										<option><?php echo $i; ?></option>
									<?php endfor; ?>
								</select>
								<select name="minute_commande">
									<?php for ($i = 0 ; $i <= 60 ; $i++) : ?>
										<option <?php echo $i == $horaire->minute_debut ? 'selected' : ''; ?>><?php echo $i; ?></option>
									<?php endfor; ?>
								</select>
							<?php endif; ?>
						</div><br />
						<div class="panel panel-default panel-primary">
							<div class="panel-heading">
								Restaurant <?php echo utf8_encode($request->panier->restaurant->nom); ?>
							</div>
							<div>
								<?php $totalQte = 0; ?>
								<?php $totalPrix = 0; ?>
								<table class="table table-striped">
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
											if ($request->_auth->is_premium) {
												$prix_livraison -= $request->panier->reduction_premium;
											}
										?>
										<tr>
											<td>prix de livraison</td>
											<td></td>
											<td><?php echo formatPrix($prix_livraison); ?></td>
											<td></td>
										</tr>
										<?php $totalPrix += $prix_livraison; ?>
									</tbody>
									<tfoot>
										<tr>
											<th>Total :</th>
											<th><?php echo $totalQte; ?></th>
											<th><?php echo formatPrix($totalPrix); ?></th>
											<td></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<?php if ($request->panier->prix_minimum > ($totalPrix - $prix_livraison)) : ?>
							<div class="alert alert-danger" role="alert">
								<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
								<span class="sr-only">Error:</span>
								Le montant minimum pour commander est de <?php echo $request->panier->prix_minimum; ?> €
							</div>
						<?php else : ?>
							<?php if ($horaire->heure_debut > $current_heure || ($horaire->heure_debut == $current_heure && $horaire->minute_debut > $current_minute)) : ?>
								<button id="command" class="btn btn-primary" type="submit">Pré-Commande</button>
							<?php else : ?>
								<button id="command" class="btn btn-primary" type="submit">Commander</button>
							<?php endif; ?>
						<?php endif; ?>
					</form>
				<?php else : ?>
					<span>(vide)</span>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<a style="margin-top: 10px;" class="btn btn-primary" href="?controler=restaurant&action=recherche">
		<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour au resultat de la recherche
	</a>
</div>
<div id="carte-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
		</div>
	</div>
</div>
<div id="menu-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		initialize();
		var list = [];
		
		var homePoint = {};
		homePoint.type = "HOME";
		homePoint.adresse = "<?php echo $request->search_adresse; ?>";
		
		var restoPoint = {};
		restoPoint.type = "ADRESSE";
		restoPoint.adresse = "<?php echo $restaurant->rue.', '.$restaurant->ville; ?>";
		restoPoint.content = "<?php echo utf8_encode($restaurant->nom); ?>";
		
		list.push(homePoint);
		list.push(restoPoint);
		
		boundToPoints(list);
		
		$(".carte-item").click(function () {
			$("#loading-modal").modal();
			var id_carte = $(this).attr('data-id');
			var id_restaurant = $("#id_restaurant").val();
			$.ajax({
				type: "GET",
				url: '?controler=restaurant&action=carte&id='+id_restaurant+'&id_carte='+id_carte,
				dataType: "html"
			}).done(function( msg ) {
				$("#loading-modal").modal('hide');
				$("#carte-modal").modal();
				$("#carte-modal .modal-content").html(msg);
			});
		});
		
		$(".menu-item").click(function () {
			$("#loading-modal").modal();
			var id_menu = $(this).attr('data-id');
			var id_restaurant = $("#id_restaurant").val();
			$.ajax({
				type: "GET",
				url: '<?php echo WS_URL; ?>index.php?module=restaurant&action=menu&id='+id_restaurant+'&id_menu='+id_menu+'&ext=json',
				dataType: "html"
			}).done(function( msg ) {
				var data = $.parseJSON(msg);
				initMenu (data);
			});
		});
		$(".carte-item-delete").click(function(event) {
			$("#loading-modal").modal();
			var id_panier = $("#id_panier").val();
			var id = $(this).attr('data-id');
			$.ajax({
				type: "POST",
				url: "?controler=panier&action=removeCarte",
				dataType: "html",
				data: {id_panier : id_panier, id_panier_carte : id}
			}).done(function( msg ) {
				$("#tr_carte_"+id).remove();
				$("#loading-modal").modal('hide');
				//location.reload();
			}).error(function(msg) {
				alert("error");
				$("#loading-modal").modal('hide');
			});
		});
		$(".menu-item-delete").click(function(event) {
			$("#loading-modal").modal();
			var id_panier = $("#id_panier").val();
			var id = $(this).attr('data-id');
			$.ajax({
				type: "POST",
				url: "?controler=panier&action=removeMenu",
				dataType: "html",
				data: {id_panier : id_panier, id_panier_menu : id}
			}).done(function( msg ) {
				$("#tr_menu_"+id).remove();
				$("#loading-modal").modal('hide');
				//location.reload();
			}).error(function(msg) {
				alert("error");
				$("#loading-modal").modal('hide');
			});
		});
		$("#panier #command").click(function(event) {
			event.preventDefault();
			var type_commande = $('#panierForm input[name=type_commande]').val();
			var heure_commande = $('#panierForm select[name=heure_commande]').val();
			var minute_commande = $('#panierForm select[name=minute_commande]').val();
			
			$("#panier-info-modal #type_commande").val(type_commande);
			$("#panier-info-modal #heure_commande").val(heure_commande);
			$("#panier-info-modal #minute_commande").val(minute_commande);
			$("#panier-info-modal").modal();
		});
	});
</script>
<style>
	#restaurant .vignette img{
		height: 80px;
		width: 80px;
	}
	
	#restaurant .carte-item:hover {
		background-color : #CCCCCC;
		cursor : pointer;
	}
	
	#restaurant .carte-item:hover .add-button {
		display : block !important;
		color: green;
		font-size: 30px;
	}
	
	#restaurant .carte-item-title {
		font-size : 16px;
		text-align : center;
	}
	
	#restaurant .menu-item:hover {
		background-color : #CCCCCC;
		cursor : pointer;
	}
	
	#restaurant .menu-item:hover .add-button {
		display : block !important;
		color: green;
		font-size: 30px;
	}
	
	#restaurant .menu-item-title {
		font-size : 16px;
		text-align : center;
	}
	
</style>