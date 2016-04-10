<ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="#">Commande en cours</a></li>
  <li role="presentation"><a href="?controler=commande&action=finish">Commande réalisée(s)</a></li>
</ul>
<h2>Commandes en cours <span class="badge"></span></h2>
<div id="commandes">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Numéro de commande</th>
				<th>Restaurant</th>
				<th>Livreur</th>
				<th>Status</th>
				<th>Prix</th>
				<th>Date de commande</th>
				<th>Heure souhaité</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php include_once("enCours.php"); ?>
		</tbody>
	</table>
</div>
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
					$("h2 span.badge").html(nbModif);
					playNotificationSong ();
					$.ajax({
						type: "GET",
						url: '?controler=commande&action=enCours',
						dataType: "html"
					}).done(function( msg ) {
						$("#commandes tbody").html(msg);
					});
				}
			} else {
				$("h2 span.badge").html("");
			}
		});
	}, 30000);
</script>