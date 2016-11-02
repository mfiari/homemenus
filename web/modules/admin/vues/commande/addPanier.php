<?php 
	$restaurant = $request->restaurant;
	$horaire = $restaurant->horaire;
	$current_heure = date('G')+1;
	$current_minute = date('i');
?>
<input id="id_restaurant" value="<?php echo $restaurant->id; ?>" hidden="hidden" />
<input id="id_user" value="<?php echo $request->id_user; ?>" hidden="hidden" />
<div id="restaurant">
	<h2><?php echo utf8_encode($request->restaurant->nom); ?></h2>
	<div class="row">
		<div class="panel panel-default panel-primary">
			<div class="panel-heading">
				Informations générales
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-6">
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
				<div class="col-md-6  col-sm-6">
					<div id="googleMap" class="col-md-10 col-md-offset-1" style="height:200px; margin-top : 10px;"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-8 col-sm-12">
					<?php foreach ($restaurant->certificats as $certificat) : ?>
						<a href="<?php echo $certificat->url; ?>" target="_blank" data-toggle="tooltip" title="<?php echo utf8_encode($certificat->description); ?>">
							<img src="res/img/<?php echo $certificat->logo; ?>">
							<span><?php echo utf8_encode($certificat->nom); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
				<div class="col-md-4 col-sm-12" style="text-align : center;">
					<?php if ($restaurant->commentaire == 0) : ?>
						<?php echo $restaurant->note; ?> / 5 (<?php echo $restaurant->nb_note; ?> vote(s)) - 0 commentaire
					<?php else : ?>
						<a onclick="openCommentairesRestaurant(<?php echo $restaurant->id; ?>)">
							<?php echo $restaurant->note; ?> / 5 (<?php echo $restaurant->nb_note; ?> vote(s)) - <?php echo $restaurant->commentaire; ?> commentaire(s)
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3 col-sm-12">
			<?php foreach ($restaurant->categories as $categorie) : ?>
				<?php if (count($categorie->contenus) > 0) : ?>
					<div class="row">
						<a href="#categorie-<?php echo $categorie->id; ?>">
							<?php 
								if ($categorie->parent_categorie != '') {
									echo utf8_encode($categorie->parent_categorie->nom).' : ';
								}
								echo utf8_encode($categorie->nom);
							?>
						</a>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php if (count($restaurant->menus) > 0) : ?>
				<div class="row">
					<a href="#categorie-menu">Menus</a>
				</div>
			<?php endif; ?>
		</div>
		<div class="col-md-6 col-sm-7">
			<?php foreach ($restaurant->categories as $categorie) : ?>
				<?php if (count($categorie->contenus) > 0) : ?>
					<div class="row">
						<h3 id="categorie-<?php echo $categorie->id; ?>">
							<?php 
								if ($categorie->parent_categorie != '') {
									echo utf8_encode($categorie->parent_categorie->nom).' : ';
								}
								echo utf8_encode($categorie->nom);
							?>
						</h3>
						<?php foreach ($categorie->contenus as $contenu) : ?>
							<hr />
							<div data-id="<?php echo $contenu->id; ?>" class="row carte-item">
								<div class="col-md-12">
									<p class="carte-item-title"><?php echo utf8_encode($contenu->nom); ?></p>
									<div class="row">
										<div class="col-md-4 col-sm-4">
											<div class="row">
												<div class="vignette"><img src="<?php echo $contenu->logo; ?>"></div>
											</div>
											<div class="row">
												<span>Prix : <?php echo formatPrix($contenu->prix); ?></span>
											</div>
										</div>
										<div class="col-md-6 col-sm-6">
											<?php echo utf8_encode($contenu->commentaire); ?>
										</div>
										<div class="col-md-2 col-sm-2">
											<span class="add-button" style="display : none;">+</span>
										</div>
									</div>
								</div>
							</div>
							<?php if ($contenu->supplement->note != '') : ?>
								<div class="row">
									<div class="col-md-12" style="text-align : center;">
										<?php if ($contenu->supplement->commentaire == 0) : ?>
											<?php echo $contenu->supplement->note; ?> / 5 (<?php echo $contenu->supplement->vote; ?> vote(s)) - 0 commentaire
										<?php else : ?>
											<a onclick="openCommentaires(<?php echo $contenu->id; ?>)">
												<?php echo $contenu->supplement->note; ?> / 5 (<?php echo $contenu->supplement->vote; ?> vote(s))
												- <?php echo $contenu->supplement->commentaire; ?> commentaire(s)
											</a>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
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
									<div class="col-md-4 col-sm-4">
										<div class="row">
											<div class="vignette"><img src="<?php echo $menu->logo; ?>"></div>
										</div>
										<div class="row">
											<span>Prix : <?php echo formatPrix($menu->prix); ?></span>
										</div>
									</div>
									<div class="col-md-6 col-sm-6">
										<?php echo utf8_encode($menu->commentaire); ?>
									</div>
									<div class="col-md-2 col-sm-2">
										<span class="add-button" style="display : none;">+</span>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<div id="panier" class="col-md-3 col-sm-5">
			<h3>Panier</h3>
			<div id="panier-content">
				<?php if ($request->panier) : ?>
					<form method="post" enctype="x-www-form-urlencoded" id="panierForm" action="">
						<input type="hidden" id="id_panier" name="id_panier" value="<?php echo $request->panier->id; ?>" />
						<div>
							<?php if ($horaire->heure_debut > $current_heure || ($horaire->heure_debut == $current_heure && $horaire->minute_debut > $current_minute)) : ?>
								<input type="hidden" name="type_commande" value="pre_commande">
								<span style="color : red;">
									Le <?php echo utf8_encode($request->panier->restaurant->nom); ?> est actuellement fermé. Ouverture
										de <?php echo formatHeureMinute($horaire->heure_debut, $horaire->minute_debut); ?> 
										à <?php echo formatHeureMinute($horaire->heure_fin, $horaire->minute_fin); ?><br />
									Précommande possible dès maintenant.
								</span><br /><br />
								<span><b>Heure de livraison souhaitée : </b></span><br />
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
								<input type="radio" name="type_commande" value="pre_commande">Précommander
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
						<div id="codePromoBlock" style="margin-bottom : 20px;">
							<span>Code promo</span><br />
							<input id="code_promo" name="code_promo" type="text" maxlength="10">
							<button id="codePromoButton" class="btn btn-primary" type="button">Valider</button>
							<div style="display : none;" class="alert alert-success" role="alert">
								<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
								Votre code promo a été validé.
							</div>
							<div style="display : none;" class="alert alert-danger" role="alert">
								<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
								<span class="sr-only">Error:</span>
								<span class="message"></span>
							</div>
						</div>
						<?php if ($request->panier->prix_minimum > ($totalPrix - $prix_livraison)) : ?>
							<div class="alert alert-danger" role="alert">
								<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
								<span class="sr-only">Error:</span>
								Le montant minimum pour commander est de <?php echo $request->panier->prix_minimum; ?> € (hors prix de livraison)
							</div>
						<?php else : ?>
							<?php if ($horaire->heure_debut > $current_heure || ($horaire->heure_debut == $current_heure && $horaire->minute_debut > $current_minute)) : ?>
								<button id="command" class="btn btn-primary" type="submit">Précommander</button>
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
<div id="commentaire-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
<div id="panier-info-admin-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Veuillez renseigner vos informations de livraison</h4>
			</div>
			<div class="modal-body">
				<form method="post" enctype="x-www-form-urlencoded" id="panierInfoAdminForm">
					<input id="type_commande_admin" name="type" value="" hidden="hidden" />
					<input id="heure_commande_admin" name="heure_commande" value="-1" hidden="hidden" />
					<input id="minute_commande_admin" name="minute_commande" value="0" hidden="hidden" />
					<input name="id_user" value="<?php echo $request->id_user; ?>" hidden="hidden" />
					<div class="form-group">
						<label for="login">Rue<span class="required">*</span> : </label>
						<input id="rue_field_admin" class="form-control" name="rue" type="text" value="<?php echo isset($_SESSION['search_rue']) ? $_SESSION['search_rue'] : ''; ?>" required>
					</div>
					<div class="form-group">
						<label for="login">Code postal<span class="required">*</span> : </label>
						<input id="cp_field_admin" class="form-control" name="code_postal" type="text" value="<?php echo isset($_SESSION['search_cp']) ? $_SESSION['search_cp'] : ''; ?>" required>
					</div>
					<div class="form-group">
						<label for="login">Ville<span class="required">*</span> : </label>
						<input id="ville_field_admin" class="form-control" name="ville" type="text" value="<?php echo isset($_SESSION['search_ville']) ? $_SESSION['search_ville'] : ''; ?>" required>
					</div>
					<div class="form-group">
						<label for="login">Téléphone<span class="required">*</span> : </label>
						<input id="phone_field_admin" class="form-control" name="telephone" type="text" required>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button id="validationButtonAdmin" class="btn btn-primary" type="submit">Valider</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				<div style="display : none; text-align: center;" class="alert alert-danger" role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					<span class="message"></span>
				</div>
			</div>
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
		
		var circle = {};
		circle.type = "CIRCLE";
		circle.radius = 10000;
		circle.latitude = <?php echo $restaurant->latitude; ?>;
		circle.longitude = <?php echo $restaurant->longitude; ?>;
		
		list.push(homePoint);
		list.push(restoPoint);
		list.push(circle);
		
		boundToPoints(list);
		
		$(".carte-item").click(function () {
			$("#loading-modal").modal();
			var id_carte = $(this).attr('data-id');
			var id_restaurant = $("#id_restaurant").val();
			var id_user = $("#id_user").val();
			$.ajax({
				type: "GET",
				url: '?controler=commande&action=carte&id='+id_restaurant+'&id_user='+id_user+'&id_carte='+id_carte,
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
			var id_user = $("#id_user").val();
			$.ajax({
				type: "GET",
				url: '<?php echo WS_URL; ?>index.php?module=restaurant&action=menu&id='+id_restaurant+'&id_user='+id_user+'&id_menu='+id_menu+'&ext=json',
				dataType: "html"
			}).done(function( msg ) {
				var data = $.parseJSON(msg);
				initMenu (data, 'commande', 'addMenu', 'commande', 'panier');
			});
		});
		
		initDeleteCarteItem ();
		initDeleteMenuItem ();
		initPanierCommande();
		initCodePromo ();
		
		$("#validationButtonAdmin").click(function(event) {
			var rue = $("#rue_field_admin").val();
			var cp = $("#cp_field_admin").val();
			var ville = $("#ville_field_admin").val();
			var phone = $("#phone_field_admin").val();
			if (rue.trim() == '' || cp.trim() == '' || ville.trim() == '' || phone.trim() == '') {
				alert("veuillez remplir tous les champs");
			} else {
				showLoading('validationButtonAdmin', true);
				$.ajax({
					type: "POST",
					url: "?controler=commande&action=validate",
					dataType: "html",
					data: $("#panierInfoAdminForm").serialize()
				}).done(function( msg ) {
					var data = $.parseJSON(msg);
					console.log(data);
					var id_user = $("#id_user").val();
					document.location.href = "?controler=commande&action=finalisation&id_user="+id_user;
				}).error(function(jqXHR, textStatus, errorThrown) {
					switch (jqXHR.status) {
						case 404 :
							$("#panier-info-modal .modal-footer div.alert-danger span.message").html("L'adresse saisie est invalide");
							break;
						default :
							$("#panier-info-modal .modal-footer div.alert-danger span.message").html("Une erreur est survenu, veuillez réessayé.");
							break;
					}
					$("#panier-info-modal .modal-footer div.alert-danger").css('display', 'block');
					hideLoading('validationButtonAdmin', true);
				});
				
			}
		});
		
	});
	
	function initDeleteCarteItem () {
		$(".carte-item-delete").click(function(event) {
			$("#loading-modal").modal();
			var id_panier = $("#id_panier").val();
			var id_user = $("#id_user").val();
			var id = $(this).attr('data-id');
			$.ajax({
				type: "POST",
				url: "?controler=commande&action=removeCarte",
				dataType: "html",
				data: {id_panier : id_panier, id_user : id_user, id_panier_carte : id}
			}).done(function( msg ) {
				reloadPanier ();
				$("#loading-modal").modal('hide');
			}).error(function(msg) {
				alert("error");
				$("#loading-modal").modal('hide');
			});
		});
	}
	
	function initDeleteMenuItem () {
		$(".menu-item-delete").click(function(event) {
			$("#loading-modal").modal();
			var id_panier = $("#id_panier").val();
			var id_user = $("#id_user").val();
			var id = $(this).attr('data-id');
			$.ajax({
				type: "POST",
				url: "?controler=commande&action=removeMenu",
				dataType: "html",
				data: {id_panier : id_panier, id_user : id_user, id_panier_menu : id}
			}).done(function( msg ) {
				reloadPanier ();
				$("#loading-modal").modal('hide');
			}).error(function(msg) {
				alert("error");
				$("#loading-modal").modal('hide');
			});
		});
	}
	
	function initCodePromo () {
		$("#codePromoButton").click(function () {
			$("#loading-modal").modal();
			var codePromo = $("#code_promo").val();
			var id_user = $("#id_user").val();
			$.ajax({
				type: "POST",
				url: "?controler=commande&action=addCodePromo",
				dataType: "html",
				data: {code_promo : codePromo, id_user : id_user}
			}).done(function( msg ) {
				reloadPanier ();
				$("#loading-modal").modal('hide');
			}).error(function(jqXHR, textStatus, errorThrown) {
				switch (jqXHR.status) {
					case 400 :
						$("#codePromoBlock div.alert-danger span.message").html("Le code promo n'est pas applicable sur ce restaurant.");
						break;
					case 401 :
						$("#codePromoBlock div.alert-danger span.message").html("Vous n'êtes pas autorisé à utiliser ce code promo.");
						break;
					case 403 :
						$("#codePromoBlock div.alert-danger span.message").html("Veuillez vous connecter pour utiliser ce code promo.");
						break;
					case 404 :
						$("#codePromoBlock div.alert-danger span.message").html("Ce code promo n'existe pas.");
						break;
					case 410 :
						$("#codePromoBlock div.alert-danger span.message").html("Vous avez déjà utilisé ce code promo.");
						break;
					default :
						$("#codePromoBlock div.alert-danger span.message").html("Une erreur est survenu, veuillez réessayé.");
						break;
				}
				$("#codePromoBlock div.alert-danger").css('display', 'block');
				$("#loading-modal").modal('hide');
			});
		});
	}
	
	function initPanierCommande () {
		$("#panier #command").click(function(event) {
			event.preventDefault();
			var type_commande = $('#panierForm input[name=type_commande]').val();
			var heure_commande = $('#panierForm select[name=heure_commande]').val();
			var minute_commande = $('#panierForm select[name=minute_commande]').val();
			
			$("#panier-info-admin-modal #type_commande").val(type_commande);
			$("#panier-info-admin-modal #heure_commande").val(heure_commande);
			$("#panier-info-admin-modal #minute_commande").val(minute_commande);
			$("#panier-info-admin-modal").modal();
		});
	}
	
	function reloadPanier () {
		var id_user = $("#id_user").val();
		$.ajax({
			type: "GET",
			url: '?controler=commande&action=panier&id_user='+id_user,
			dataType: "html"
		}).done(function( msg ) {
			$("#panier-content").html(msg);
			initDeleteCarteItem ();
			initDeleteMenuItem ();
			initPanierCommande();
			initCodePromo ();
		});
	}
	
	function openCommentairesRestaurant (id_resto) {
		$("#loading-modal").modal();
		$.ajax({
			type: "GET",
			url: '?controler=notes&action=viewRestaurant&id_restaurant='+id_resto,
			dataType: "html"
		}).done(function( msg ) {
			$("#loading-modal").modal('hide');
			$("#commentaire-modal .modal-body").html(msg);
			$("#commentaire-modal").modal();
		});
	}
	
	function openCommentaires (id_carte) {
		$("#loading-modal").modal();
		$.ajax({
			type: "GET",
			url: '?controler=notes&action=viewCarte&id_carte='+id_carte,
			dataType: "html"
		}).done(function( msg ) {
			$("#loading-modal").modal('hide');
			$("#commentaire-modal .modal-body").html(msg);
			$("#commentaire-modal").modal();
		});
	}
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
</style>