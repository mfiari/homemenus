<!DOCTYPE html>
<html>
	<?php include('header_mobile.php'); ?>
	<body>
		<div id="page-container">
			<?php 
				if ($request->home) {
					include('top_home-mobile.php');
				} else {
					if ($request->_auth) {
						if ($request->_auth->status == USER_ADMIN) {
							include('top_admin.php');
						} else if ($request->_auth->status == USER_ADMIN_INFO) {
							include('top_admin_info-mobile.php');
						} else if ($request->_auth->status == USER_ADMIN_CLIENT) {
							include('top_admin_client.php');
						} else if ($request->_auth->status == USER_LIVREUR) {
							include('top_livreur.php');
						} else if ($request->_auth->status == USER_RESTAURANT) {
							include('top_restaurant.php');
						} else if ($request->_auth->status == USER_ADMIN_RESTAURANT) {
							include('top_admin_restaurant.php');
						} else {
							include('top-mobile.php');
						}
					} else {
						include('top-mobile.php');
					}
				}
			?>
			<div id="main-content">
				<div class="col-md-12 col-xs-12">
					<div class="row">
						<div class="col-md-12 col-xs-12">
							<?php include($request->vue); ?>
						</div>
					</div>
				</div>
			</div>
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
						include('footer-mobile.html');
					}
				} else {
					include('footer-mobile.html');
				}
			?>
		</div>
	</body>
</html>
