<!DOCTYPE html>
<html>
	<?php include('header.php'); ?>
	<body>
		<?php 
			if ($request->_auth) {
				include('panier.html');
			} else {
				include('login.html');
				include('forgot_password.php');
			}
			include(WEBSITE_PATH.'layouts/panier_info.php');
		 ?>
		<div id="page-container">
			<?php 
				if ($request->home) {
					include('top_home.php');
				} else {
					if ($request->_auth) {
						if ($request->_auth->status == USER_ADMIN) {
							include('top_admin.php');
						} else if ($request->_auth->status == USER_ADMIN_INFO) {
							include('top_admin_info.php');
						} else if ($request->_auth->status == USER_ADMIN_CLIENT) {
							include('top_admin_client.php');
						} else if ($request->_auth->status == USER_LIVREUR) {
							include('top_livreur.php');
						} else if ($request->_auth->status == USER_RESTAURANT) {
							include('top_restaurant.php');
						} else if ($request->_auth->status == USER_ADMIN_RESTAURANT) {
							include('top_admin_restaurant.php');
						} else {
							include('top.php');
						}
					} else {
						include('top.php');
					}
				}
			?>
			<div id="main-content">
				<div class="container">
					<?php 
						if (!$request->vue || $request->vue == '') {
							$userStatus = "utilisateur non connecté";
							if ($request->_auth) {
								$userStatus = "compte ".$request->_auth->status;
							}
							writeLog(VIEW_LOG, "URL non trouvée : ".$_SERVER['REQUEST_URI'], LOG_LEVEL_ERROR, $userStatus);
						}
						include($request->vue);
					?>
				</div>
			</div>
			<div id="live-chat"></div>
			<div id="loading-modal" class="modal fade" role="dialog">
				<div class="modal-dialog modal-sm">
					<div style="text-align : center" class="modal-content">
						<span style = "font-size : 50px;" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
						<p>Chargement ...</p>
					</div>
				</div>
			</div>
			<div id="notation-modal" class="modal fade" role="dialog">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Noter commande #<span id="rating_span_id_commande"></span></h4>
						</div>
						<div class="modal-body">
							<input type="hidden" id="rating_id_commande" />
							<div id="stars-default"></div>
							<div class="form-group">
								<label for="commentaire">Commentaire : </label>
								<textarea id="ratingComment" class="form-control" name="commentaire"></textarea>
							</div>
						</div>
						<div class="modal-footer">
							<button id="ratingButton" class="btn btn-primary" type="button">Noter</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
						</div>
					</div>
				</div>
			</div>
			<?php
				if ($request->_auth) {
					if ($request->_auth->status == USER_ADMIN) {
						include('footer_admin.html');
					} else if ($request->_auth->status == USER_LIVREUR) {
						include('footer_livreur.html');
					} else if ($request->_auth->status == USER_RESTAURANT) {
						include('footer_restaurant.html');
					} else if ($request->_auth->status == USER_ADMIN_RESTAURANT) {
						include('footer_admin_restaurant.html');
					} else {
						include('footer.html');
					}
				} else {
					include('footer.html');
				}
			?>
		</div>
		<?php if ($request->_auth && $request->_auth->status == USER_CLIENT && $request->_hasCommandeEnCours) : ?>
			<script type="text/javascript">
				window.setInterval(function() {
					$.ajax({
						type: "GET",
						url: '?controler=commande&action=modified',
						dataType: "html"
					}).done(function( msg ) {
						if (msg != "") {
							var nbModif = 0;
							response = JSON.parse(msg);
							$.each(response, function( key, value ) {
								nbModif++;
								if (value == 4) {
									enableRating(key);
								}
							});
							if (nbModif > 0) {
								$(".menu li span.badge").html(nbModif);
								playNotificationSong ();
							}
						} else {
							$(".menu li span.badge").html("");
						}
					});
				}, 30000);
			</script>
		<?php endif; ?>
		<?php if ($request->_auth && $request->_hasCommandeEnCours) : ?>
			<?php foreach ($request->_idCommandes AS $id_commande) : ?>
				<script type="text/javascript">
					window.setInterval(function() {
						$.ajax({
							type: "GET",
							url: 'index.php?controler=commande&action=hasChat&id_commande=<?php echo $id_commande; ?>',
							dataType: "html"
						}).done(function( msg ) {
							if (msg != "" && msg != "0") {
								openChatBox (<?php echo $id_commande; ?>);
								playMessageSong ();
							}
						});
					}, 20000);
				</script>
			<?php endforeach; ?>
		<?php endif; ?>
	</body>
</html>
