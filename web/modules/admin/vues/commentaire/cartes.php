<ul class="nav nav-tabs">
  <li role="presentation"><a href="?controler=notes">Commandes</a></li>
  <li role="presentation"><a href="?controler=notes&action=restaurants">Restaurants</a></li>
  <li role="presentation" class="active"><a href="#">Plats</a></li>
</ul>
<h2>Plats</h2>
<div id="plats">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Restaurant</th>
				<th>Nom</th>
				<th>Notes</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($request->restaurants as $restaurant) : ?>
				<?php foreach ($restaurant->categories as $categorie) : ?>
					<?php foreach ($categorie->contenus as $contenu) : ?>
						<tr class="carte">
							<td><a href=""><?php echo utf8_encode($restaurant->nom); ?></a></td>
							<td><?php echo utf8_encode($contenu->nom); ?></td>
							<td>
								<?php if ($contenu->commentaire->note == 0) : ?>
									<a onclick="enableRatingCarte(<?php echo $contenu->id;?>)" class="btn btn-primary">Noter le plat</button>
								<?php else : ?>
									<?php echo $contenu->commentaire->note; ?> / 5
								<?php endif; ?>
								
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endforeach; ?>
				<?php foreach ($restaurant->menus as $menu) : ?>
					<tr class="menu">
						<td><a href=""><?php echo utf8_encode($restaurant->nom); ?></a></td>
						<td><?php echo utf8_encode($menu->nom); ?></td>
						<td>
							<?php if ($menu->commentaire->note == 0) : ?>
								<a onclick="enableRatingMenu(<?php echo $menu->id;?>)" class="btn btn-primary">Noter le menu</button>
							<?php else : ?>
								<?php echo $menu->commentaire->note; ?> / 5
							<?php endif; ?>
							
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<div id="notation-modal-carte" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Noter le plat</span></h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="rating_id_carte" />
				<div id="stars-default"></div>
				<div class="form-group">
					<label for="commentaire">Commentaire : </label>
					<textarea id="ratingComment" class="form-control" name="commentaire"></textarea>
				</div>
				<div class="form-group">
					<input id="checkboxAnonymeCarte" name="anonyme" type="checkbox">Soumettre mon commentaire de façon anonyme
					<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" 
					title="Si vous cocher cette case, votre nom et votre prénom ne seront pas affiché au niveau des commentaires"></span>
				</div>
			</div>
			<div class="modal-footer">
				<button id="ratingButtonPlat" class="btn btn-primary" type="button">Noter</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
<div id="notation-modal-menu" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Noter le menu</span></h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="rating_id_menu" />
				<div id="stars-default"></div>
				<div class="form-group">
					<label for="commentaire">Commentaire : </label>
					<textarea id="ratingComment" class="form-control" name="commentaire"></textarea>
				</div>
				<div class="form-group">
					<input id="checkboxAnonymeMenu" name="anonyme" type="checkbox">Soumettre mon commentaire de façon anonyme
					<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" 
					title="Si vous cocher cette case, votre nom et votre prénom ne seront pas affiché au niveau des commentaires"></span>
				</div>
			</div>
			<div class="modal-footer">
				<button id="ratingButtonMenu" class="btn btn-primary" type="button">Noter</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		$("#ratingButtonPlat").click(function () {
			var note = $("#notation-modal-carte #stars-default").attr('data-rating');
			var commentaire = $("#notation-modal-carte #ratingComment").val();
			var id_carte = $("#rating_id_carte").val();
			var anonyme = $("#checkboxAnonymeCarte").is(":checked");
			$.ajax({
				type: "POST",
				url: '?controler=notes&action=noterCarte',
				dataType: "html",
				data: {note : note, commentaire : commentaire, id_carte : id_carte, anonyme : anonyme}
			}).done(function( msg ) {
				$("#notation-modal-carte").modal('hide');
				location.reload();
			});
		});
		$("#ratingButtonMenu").click(function () {
			var note = $("#notation-modal-menu #stars-default").attr('data-rating');
			var commentaire = $("#notation-modal-menu #ratingComment").val();
			var id_menu = $("#rating_id_menu").val();
			var anonyme = $("#checkboxAnonymeMenu").is(":checked");
			$.ajax({
				type: "POST",
				url: '?controler=notes&action=noterMenu',
				dataType: "html",
				data: {note : note, commentaire : commentaire, id_menu : id_menu, anonyme : anonyme}
			}).done(function( msg ) {
				$("#notation-modal-menu").modal('hide');
				location.reload();
			});
		});
	});

	function enableRatingCarte (id_carte) {
		$("#notation-modal-carte #rating_id_carte").val(id_carte);
		$("#notation-modal-carte").modal();
		if ($("#notation-modal-carte #stars-default").children().length == 0) {
			$("#notation-modal-carte #stars-default").rating();
		}
	}

	function enableRatingMenu (id_menu) {
		$("#notation-modal-menu #rating_id_menu").val(id_menu);
		$("#notation-modal-menu").modal();
		if ($("#notation-modal-menu #stars-default").children().length == 0) {
			$("#notation-modal-menu #stars-default").rating();
		}
	}
</script>