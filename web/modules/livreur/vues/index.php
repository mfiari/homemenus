<h2>Commandes en cours <span class="badge"></span></h2>
<div id="commandes">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Numéro de commande</th>
				<th>Restaurant</th>
				<th>Client</th>
				<th>Status</th>
				<th>Date</th>
				<th>Heure souhaité</th>
				<th></th>
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
			}
		});
	}, 30000);
	window.setInterval(function() {
		$.ajax({
			type: "POST",
			url: '?action=position',
			dataType: "html"
		}).done(function( msg ) {
			
		});
	}, 60000);
</script>