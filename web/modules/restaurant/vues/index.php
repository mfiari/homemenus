<div class="col-md-12">
	<h2>Commandes reçu <span class="badge"></span></h2>
	<div id="commandes">
		filter par : <select>
			<option value="">Toutes</option>
			<option value="recu">Reçu</option>
			<option value="cours">En cours</option>
		</select>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Numéro de commande</th>
					<th>Livreur</th>
					<th>Heure souhaité</th>
					<th>Etat</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php include_once("enCours.php"); ?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	window.setInterval(function() {
		$.ajax({
			type: "GET",
			url: '?action=nonVue',
			dataType: "html"
		}).done(function( msg ) {
			if (msg != "" && msg > 0) {
				$("h2 span.badge").html(msg);
				playNotificationSong ();
				$.ajax({
					type: "GET",
					url: '?action=enCours',
					dataType: "html"
				}).done(function( msg ) {
					$("#commandes tbody").html(msg);
				});
			} else {
				$("h2 span.badge").html("");
			}
		});
	}, 30000);
	
	function filtre () {
		
	}
</script>