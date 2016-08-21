<ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="#">Commandes</a></li>
  <li role="presentation"><a href="?controler=notes&action=restaurants">Restaurants</a></li>
  <li role="presentation"><a href="?controler=notes&action=plats">Plats</a></li>
</ul>
<h2>Commandes</h2>
<div id="commandes">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Numéro de commande</th>
				<th>Restaurant</th>
				<th>Livreur</th>
				<th>Prix</th>
				<th>Date de commande</th>
				<th>Notes</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($request->commandes as $commande) : ?>
				<tr class="commande">
					<td>#<?php echo $commande->id; ?></td>
					<td><?php echo utf8_encode($commande->restaurant->nom); ?></td>
					<td><?php echo utf8_encode($commande->livreur->prenom); ?></td>
					<td><?php echo formatPrix($commande->prix); ?></td>
					<td><?php echo $commande->date_commande; ?></td>
					<td>
						<?php if ($commande->note == 0) : ?>
							<a onclick="enableRatingCommande(<?php echo $commande->id;?>)" class="btn btn-primary">Noter la commande</button>
						<?php else : ?>
							<?php echo $commande->note; ?> / 5
						<?php endif; ?>
						
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<div id="notation-modal-commande" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Noter la commande #<span id="rating_span_id_commande"></span></h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="rating_id_commande" />
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
				<button id="ratingButtonCommande" class="btn btn-primary" type="button">Noter</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		$("#ratingButtonCommande").click(function () {
			var note = $("#stars-default").attr('data-rating');
			var commentaire = $("#ratingComment").val();
			var id_commande = $("#rating_id_commande").val();
			var anonyme = $("#checkboxAnonyme").is(":checked");
			$.ajax({
				type: "POST",
				url: '?controler=notes&action=noter',
				dataType: "html",
				data: {note : note, commentaire : commentaire, id_commande : id_commande, anonyme : anonyme}
			}).done(function( msg ) {
				$("#notation-modal-commande").modal('hide');
				location.reload();
			});
		});
	});

	function enableRatingCommande (id_commande) {
		$("#notation-modal-commande #rating_span_id_commande").html(id_commande);
		$("#notation-modal-commande #rating_id_commande").val(id_commande);
		$("#notation-modal-commande").modal();
		if ($("#notation-modal-commande #stars-default").children().length == 0) {
			$("#notation-modal-commande #stars-default").rating();
		}
	}
</script>