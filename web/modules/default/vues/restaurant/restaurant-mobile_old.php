<?php 
	$restaurant = $request->restaurant;
	$horaire = $restaurant->horaire;
	$current_heure = date('G')+GTM_INTERVAL;
	$current_minute = date('i');
?>
<input id="id_restaurant" value="<?php echo $restaurant->id; ?>" hidden="hidden" />
<div id="restaurant">
	<h2><?php echo utf8_encode($request->restaurant->nom); ?></h2>
	<div class="row">
		<?php if (!$restaurant->has_livreur_dispo) : ?>
			<div class="alert alert-danger" role="alert">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				<span class="sr-only">Error:</span>
				Il n'y a pour le moment aucun livreur disponible pour vous livrer ce restaurant. Veuillez réessayer dans quelques minutes.
			</div>
		<?php elseif ($horaire->heure_debut > $current_heure || ($horaire->heure_debut == $current_heure && $horaire->minute_debut > $current_minute)) : ?>
			<div class="alert alert-warning" role="alert">
				<span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
				<span class="sr-only">Error:</span>
				Le restaurant est actuellement fermé. Il ouvrira à partir de <?php echo formatHeureMinute($horaire->heure_debut,$horaire->minute_debut); ?>.
				Mais vous pouvez dès a présent faire une précommande en indiquant l'heure à laquelle vous souhaitez être livré.
				Celle-ci sera prise en compte dès l'ouverture du restaurant.
			</div>
		<?php endif; ?>
	</div>
	<div class="row">
		<div class="col-sm-12">
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
								<div class="col-md-12 col-sm-12">
									<div class="row">
										<div class="col-md-8 col-sm-8 col-xs-8">
											<p class="carte-item-title"><?php echo utf8_encode($contenu->nom); ?></p>
											<span><i><?php echo utf8_encode($contenu->commentaire); ?></i></span>
										</div>
										<div class="col-md-2 col-sm-2 col-xs-2">
											<b><?php echo formatPrix($contenu->prix); ?></b>
										</div>
										<div class="col-md-2 col-sm-2 col-xs-2">
											<span class="add-button" style="display : none;">+</span>
										</div>
									</div>
								</div>
							</div>
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
									<div class="col-sm-3">
										<div class="row">
											<div class="vignette"><img src="<?php echo $menu->logo; ?>"></div>
										</div>
										<div class="row">
											<span>Prix : <?php echo formatPrix($menu->prix); ?></span>
										</div>
									</div>
									<div class="col-sm-7">
										<?php echo utf8_encode($menu->commentaire); ?>
									</div>
									<div class="col-sm-2">
										<span class="add-button">+</span>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<div id="panier">
	<a href="?controler=restaurant&action=panier">Voir le panier</a>
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
				$("#carte-modal").modal({
					backdrop: 'static',
					keyboard: true
				});
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
	});
	
	function reloadPanier () {
		
	}
	
	$('#carte-modal').on('shown.bs.modal', function() {
		$("#panier").hide();
	});
	
	$('#carte-modal').on('hidden.bs.modal', function () {
		$("#panier").show();
	});
	
	$('#menu-modal').on('shown.bs.modal', function() {
		$("#panier").hide();
	});
	
	$('#menu-modal').on('hidden.bs.modal', function () {
		$("#panier").show();
	});
	
	$(window).scroll(function(){
		if ($(this).scrollTop() > 450) {
			$('#restaurant .categories').addClass('fixed');
			$('#restaurant .panier').addClass('fixed');
		} else {
			$('#restaurant .categories').removeClass('fixed');
			$('#restaurant .panier').removeClass('fixed');
		}
	});
	
</script>
<style>
	#restaurant .vignette img{
		height: 80px;
		width: 80px;
	}
	
	#restaurant .carte-item:hover {
		background-color : #F4F4F4;
		cursor : pointer;
	}
	
	#restaurant .carte-item .add-button {
		display : block !important;
		background-color: #FF2A00;
		color: #FFFFFF;
		font-size: 28px;
		border-radius : 20px;
		text-align : center;
		width : 40px;
	}
	
	#restaurant .carte-item-title {
		font-size : 16px;
		text-align : center;
	}
	
	#restaurant .menu-item:hover {
		background-color : #F4F4F4;
		cursor : pointer;
	}
	
	#restaurant .menu-item .add-button {
		display : block !important;
		background-color: #FF2A00;
		color: #FFFFFF;
		font-size: 28px;
		border-radius : 20px;
		text-align : center;
		width : 40px;
	}
	
	#restaurant .menu-item-title {
		font-size : 16px;
		text-align : center;
	}
	
	#restaurant-block hr {
		margin-top : 5px;
		margin-bottom : 5px;
	}
	
	#restaurant .fixed {
		position: fixed; 
		top : 0;
	}
	
	#panier {
		bottom : 20px;
		color : #FFFFFF;
		position : fixed;
		text-align : center;
		width : 100%;
		z-index : 200;
	}
	
	#panier a {
		background-color : #CCCCCC;
		-moz-border-radius:16px;
		-webkit-border-radius:16px;
		border-radius:16px;
		border:1px solid #CCCCCC;
		font-size : 70px;
	}
	
</style>