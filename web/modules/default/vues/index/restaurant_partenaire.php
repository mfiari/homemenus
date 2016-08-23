<?php $restaurant = $request->restaurant; ?>
<div id="restaurant">
	<h1><?php echo utf8_encode($restaurant->nom); ?></h1>
	<a href="javascript:history.back()" style="margin-bottom: 10px;" class="btn btn-primary">
		<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
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
					</div>
				</div>
				<div class="col-md-6">
					<div id="googleMap" class="col-md-10 col-md-offset-1" style="height:200px; margin-top : 10px;"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-8">
					<?php foreach ($restaurant->certificats as $certificat) : ?>
						<a href="<?php echo $certificat->url; ?>" target="_blank" data-toggle="tooltip" title="<?php echo utf8_encode($certificat->description); ?>">
							<img src="res/img/<?php echo $certificat->logo; ?>">
							<span><?php echo utf8_encode($certificat->nom); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
				<div class="col-md-4">
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
		<div class="col-md-4">
			<?php foreach ($restaurant->categories as $categorie) : ?>
				<?php if (count($categorie->contenus) > 0) : ?>
					<div class="row">
						<a href="#categorie-<?php echo $categorie->id; ?>"><?php echo utf8_encode($categorie->nom); ?></a>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php if (count($restaurant->menus) > 0) : ?>
				<div class="row">
					<a href="#categorie-menu">Menus</a>
				</div>
			<?php endif; ?>
		</div>
		<div class="col-md-8">
			<?php foreach ($restaurant->categories as $categorie) : ?>
				<?php if (count($categorie->contenus) > 0) : ?>
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
										<div class="col-md-8">
											<?php echo utf8_encode($contenu->commentaire); ?>
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
	</div>
	<a href="javascript:history.back()" style="margin-bottom: 10px;" class="btn btn-primary">
		<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
	</a>
</div>
<div id="recherche-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<h3>Afin de commander dans ce restaurant, vous devez saisir votre adresse afin de vérifier que vous pouvez vous faire livrer le restaurant</h3>
				<form id="adress-form" action="?controler=restaurant&action=recherche" method="POST">
					<div class="input-group">
						<div class="search-block">
							<input id="full_address" class="form-control" name="adresse" type="text" placeholder="Saisissez votre adresse de livraison">
							<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
						</div>
						<span class="input-group-btn">
							<button class="btn btn-primary" type="submit">Trouvez des restaurants</button>
						</span>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
			</div>
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
<script type="text/javascript">
	$(function() {
		initialize();
		var list = [];
		
		var restoPoint = {};
		restoPoint.type = "ADRESSE";
		restoPoint.adresse = "<?php echo $restaurant->rue.', '.$restaurant->ville; ?>";
		restoPoint.content = "<?php echo utf8_encode($restaurant->nom); ?>";
		
		var circle = {};
		circle.type = "CIRCLE";
		circle.radius = 10000;
		circle.latitude = <?php echo $request->restaurant->latitude; ?>;
		circle.longitude = <?php echo $request->restaurant->longitude; ?>;
		
		list.push(restoPoint);
		list.push(circle);
		
		boundToPoints(list);
		
		$(".carte-item").click(function () {
			$("#recherche-modal").modal();
		});
		
		$(".menu-item").click(function () {
			$("#recherche-modal").modal();
		});
	});
	
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
	
	#restaurant .carte-item-title {
		font-size : 16px;
		text-align : center;
	}
	
	#restaurant .menu-item:hover {
		background-color : #CCCCCC;
		cursor : pointer;
	}
	
	#restaurant .menu-item-title {
		font-size : 16px;
		text-align : center;
	}
	
</style>