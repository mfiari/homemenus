<?php 
	$restaurant = $request->restaurant;
	$horaire = $restaurant->horaire;
	$current_heure = date('G')+1;
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
								<div class="col-md-12">
									<p class="carte-item-title"><?php echo utf8_encode($contenu->nom); ?></p>
									<div class="row">
										<div class="col-sm-3">
											<div class="row">
												<div class="vignette"><img src="<?php echo $contenu->logo; ?>"></div>
											</div>
											<div class="row">
												<span>Prix : <?php echo formatPrix($contenu->prix); ?></span>
											</div>
										</div>
										<div class="col-sm-7">
											<?php echo utf8_encode($contenu->commentaire); ?>
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
		
		initDeleteCarteItem ();
		initDeleteMenuItem ();
		initPanierCommande();
		initCodePromo ();
		
	});
	
	function initDeleteCarteItem () {
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
			var id = $(this).attr('data-id');
			$.ajax({
				type: "POST",
				url: "?controler=panier&action=removeMenu",
				dataType: "html",
				data: {id_panier : id_panier, id_panier_menu : id}
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
			$.ajax({
				type: "POST",
				url: "?controler=panier&action=addCodePromo",
				dataType: "html",
				data: {code_promo : codePromo}
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
	
</script>
<style>
	#restaurant .vignette img{
		height: 150px;
		width: 150px;
	}
	
	#restaurant .carte-item:hover {
		background-color : #CCCCCC;
	}
	
	#restaurant .carte-item .add-button {
		display : block !important;
		color: green;
		font-size: 40px;
	}
	
	#restaurant .carte-item-title {
		font-size : 20px;
		text-align : center;
	}
	
	#restaurant .menu-item:hover {
		background-color : #CCCCCC;
	}
	
	#restaurant .menu-item .add-button {
		display : block !important;
		color: green;
		font-size: 60px;
	}
	
	#restaurant .menu-item-title {
		font-size : 20px;
		text-align : center;
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