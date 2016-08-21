<ul class="nav nav-tabs">
  <li role="presentation"><a href="?controler=notes">Commandes</a></li>
  <li role="presentation" class="active"><a href="#">Restaurants</a></li>
  <li role="presentation"><a href="?controler=notes&action=plats">Plats</a></li>
</ul>
<h2>Restaurants</h2>
<div id="commandes">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Nom</th>
				<th>Adresse</th>
				<th>Notes</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($request->restaurants as $restaurant) : ?>
				<tr class="restaurant">
					<td><a href=""><?php echo utf8_encode($restaurant->nom); ?></a></td>
					<td><?php echo utf8_encode($restaurant->rue); ?>, <?php echo $restaurant->code_postal; ?> <?php echo utf8_encode($restaurant->ville); ?></td>
					<td>
						<?php if ($restaurant->commentaire->note == 0) : ?>
							<a onclick="enableRatingRestaurant(<?php echo $restaurant->id;?>)" class="btn btn-primary">Noter le restaurant</button>
						<?php else : ?>
							<?php echo $restaurant->commentaire->note; ?> / 5
						<?php endif; ?>
						
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<div id="notation-modal-restaurant" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Noter le restaurant</span></h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="rating_id_restaurant" />
				<div id="stars-default"></div>
				<div class="form-group">
					<label for="commentaire">Commentaire : </label>
					<textarea id="ratingComment" class="form-control" name="commentaire"></textarea>
				</div>
				<div class="form-group">
					<input id="checkboxAnonyme" name="anonyme" type="checkbox">Soumettre mon commentaire de façon anonyme
					<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" 
					title="Si vous cocher cette case, votre nom et votre prénom ne seront pas affiché au niveau des commentaires"></span>
				</div>
			</div>
			<div class="modal-footer">
				<button id="ratingButtonRestaurant" class="btn btn-primary" type="button">Noter</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		$("#ratingButtonRestaurant").click(function () {
			var note = $("#stars-default").attr('data-rating');
			var commentaire = $("#ratingComment").val();
			var id_restaurant = $("#rating_id_restaurant").val();
			var anonyme = $("#checkboxAnonyme").is(":checked");
			$.ajax({
				type: "POST",
				url: '?controler=notes&action=noterRestaurant',
				dataType: "html",
				data: {note : note, commentaire : commentaire, id_restaurant : id_restaurant, anonyme : anonyme}
			}).done(function( msg ) {
				$("#notation-modal-restaurant").modal('hide');
				location.reload();
			});
		});
	});

	function enableRatingRestaurant (id_restaurant) {
		$("#notation-modal-restaurant #rating_id_restaurant").val(id_restaurant);
		$("#notation-modal-restaurant").modal();
		if ($("#notation-modal-restaurant #stars-default").children().length == 0) {
			$("#notation-modal-restaurant #stars-default").rating();
		}
	}
</script>