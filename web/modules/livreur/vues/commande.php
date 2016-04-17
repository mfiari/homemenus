<?php $commande = $request->commande; ?>
<h2>Commande #<?php echo $request->commande->id; ?></h2>
<a class="btn btn-primary" href="?action=index">
	<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
</a>
<div id="restaurant">
	<h3>Restaurant</h3>
	<?php $restaurant = $request->commande->restaurant; ?>
	<span><?php echo utf8_encode($restaurant->nom); ?></span><br />
	<?php $adresse = $restaurant->rue.', '.$restaurant->code_postal.' '.$restaurant->ville; ?>
	<span>Adresse : <?php echo utf8_encode($adresse); ?></span><br />
	<span>Téléphone : <?php echo $restaurant->telephone; ?></span><br />
	<a class="btn btn-primary" onclick="$('#restaurantMapContener').show(); showItineraire('restaurantMap', 'restaurantPanel', '<?php echo utf8_encode($adresse); ?>')">Itinéraire</a>
	<div id="restaurantMapContener" class="row" style="display : none;">
		<div class="col-md-6">
			<div class="panel" id="restaurantPanel"></div>
		</div>
		<div class="col-md-6">
			<div class="map" id="restaurantMap">
				<p>Veuillez patienter pendant le chargement de la carte...</p>
			</div>
		</div>
	</div>
</div>
<div id="client">
	<h3>Client</h3>
	<?php $client = $request->commande->client; ?>
	<span><?php echo utf8_encode($client->nom); ?> <?php echo utf8_encode($client->prenom); ?></span><br />
	<?php $adresse = $commande->rue.', '.$commande->code_postal.' '.$commande->ville; ?>
	<span>Adresse : <?php echo utf8_encode($adresse); ?></span><br />
	<span>Téléphone : <?php echo $commande->telephone; ?></span><br />
	<a class="btn btn-primary" onclick="$('#clientMapContener').show(); showItineraire('clientMap', 'clientPanel', '<?php echo utf8_encode($adresse); ?>')">Itinéraire</a>
	<div id="clientMapContener" class="row" style="display : none;">
		<div class="col-md-6">
			<div class="panel" id="clientPanel"></div>
		</div>
		<div class="col-md-6">
			<div class="map" id="clientMap">
				<p>Veuillez patienter pendant le chargement de la carte...</p>
			</div>
		</div>
	</div>
</div>
<div id="commande">
	<h3>Commande</h3>
	<?php $totalPrix = 0; ?>
	<?php $totalQte = 0; ?>
	<?php foreach ($request->commande->menus as $menu) : ?>
		<div class="row">
			<div class="col-md-8 col-sm-8">
				<?php echo utf8_encode($menu->nom); ?>
				<?php if (count($menu->formats) == 1 && $menu->formats[0]->nom != "") : ?>
					(<?php echo utf8_encode($menu->formats[0]->nom); ?>)
				<?php endif; ?>
				<?php foreach ($menu->formules as $formule) : ?>
					<div class="row">
						<div class="col-md-offset-1 col-md-11 col-sm-11">
							<span><?php echo utf8_encode($formule->nom); ?></span>
							<?php foreach ($formule->categories as $categorie) : ?>
								<div class="row">
									<div class="col-md-offset-1 col-md-11 col-sm-11">
										<span><?php echo utf8_encode($categorie->nom); ?></span>
										<?php foreach ($categorie->contenus as $contenu) : ?>
											<span><?php echo utf8_encode($contenu->nom); ?></span>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="col-md-4 col-sm-4">
				<div class="row">
					<div class="col-md-6 col-sm-6">
						X <?php echo $menu->quantite; ?>
						<?php $totalQte += $menu->quantite; ?>
					</div>
					<div class="col-md-6 col-sm-6">
						<?php echo $menu->prix; ?> €
						<?php $totalPrix += $menu->prix; ?>
					</div>
				</div>
			</div>
		</div>
		<hr />
	<?php endforeach; ?>
	<?php foreach ($request->commande->cartes as $carte) : ?>
		<div class="row">
			<div class="col-md-8 col-sm-8">
				<?php echo utf8_encode($carte->nom); ?>
				<?php if (count($carte->formats) == 1 && $carte->formats[0]->nom != "") : ?>
					(<?php echo utf8_encode($carte->formats[0]->nom); ?>)
				<?php endif; ?>
				<?php if (count($carte->supplements) > 0) : ?>
					<div class="row">
						<div class="col-md-offset-1 col-md-11 col-sm-11">
							<span>Suppléments : </span>
							<div class="row">
								<div class="col-md-offset-1 col-md-11 col-sm-11">
									<?php foreach ($carte->supplements as $supplement) : ?>
										<span><?php echo utf8_encode($supplement->nom); ?></span>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<div class="col-md-4 col-sm-4">
				<div class="row">
					<div class="col-md-6 col-sm-6">
						X <?php echo $carte->quantite; ?>
						<?php $totalQte += $carte->quantite; ?>
					</div>
					<div class="col-md-6 col-sm-6">
						<?php echo $carte->prix; ?> €
						<?php $totalPrix += $carte->prix; ?>
					</div>
				</div>
			</div>
		</div>
		<hr />
	<?php endforeach; ?>
	<div class="row">
		<div class="col-md-8 col-sm-8">
			<span>Prix de livraison : </span>
		</div>
		<div class="col-md-4 col-sm-4">
			<div class="row">
				<div class="col-md-6 col-sm-6"></div>
				<div class="col-md-6 col-sm-6">
					<?php echo $request->commande->prix_livraison; ?> €
				</div>
			</div>
		</div>
		<?php $totalPrix += $request->commande->prix_livraison; ?>
	</div>
	<hr />
	<div class="row">
		<div class="col-md-8 col-sm-8">
			<span>Total : </span>
		</div>
		<div class="col-md-4 col-sm-4">
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<?php echo $totalQte; ?>
				</div>
				<div class="col-md-6 col-sm-6">
					<?php echo $totalPrix; ?> €
				</div>
			</div>
		</div>
	</div>
</div>
<a class="btn btn-primary" href="?action=index">
	<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
</a>
<style>
	.map {
		width:700px;
		height:500px;
		margin:auto;
	}
	
	.panel {
		width:700px;
		margin:auto;
	}
</style>
<script type="text/javascript">

	var map;
	var panel;
	var direction;
	var lat;
	var lon;

	$(document).ready(function () {
		setInterval(function(){
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(getPosition);
			} else {
				
			}
		}, 10000);
	});
	function getPosition(position) {
		lat = position.coords.latitude;
		lon = position.coords.longitude;
		
		/*var latLng = new google.maps.LatLng(lat, lon); // Correspond au coordonnées de Lille
		var myOptions = {
			zoom      : 14, // Zoom par défaut
			center    : latLng, // Coordonnées de départ de la carte de type latLng 
			mapTypeId : google.maps.MapTypeId.TERRAIN, // Type de carte, différentes valeurs possible HYBRID, ROADMAP, SATELLITE, TERRAIN
			maxZoom   : 20
		};
		  
		map      = new google.maps.Map(document.getElementById('map'), myOptions);
		panel    = document.getElementById('panel');
		
		direction = new google.maps.DirectionsRenderer({
			map   : map,
			panel : panel // Dom element pour afficher les instructions d'itinéraire
		});
		
		var request = {
			origin      : latLng,
			destination : "22 rue du commerce, 78820 Juziers",
			travelMode  : google.maps.DirectionsTravelMode.DRIVING // Mode de conduite
		}
		var directionsService = new google.maps.DirectionsService(); // Service de calcul d'itinéraire
		directionsService.route(request, function(response, status){ // Envoie de la requête pour calculer le parcours
			if(status == google.maps.DirectionsStatus.OK) {
				$("#panel").html('');
				direction.setDirections(response); // Trace l'itinéraire sur la carte et les différentes étapes du parcours
				map.setCenter(latLng);
				map.setZoom(18);
			}
		});*/
	}
	
	function showItineraire (mapDivId, panelDivId, adresse) {
		var latLng = new google.maps.LatLng(lat, lon); // Correspond au coordonnées de Lille
		var myOptions = {
			zoom      : 14, // Zoom par défaut
			center    : latLng, // Coordonnées de départ de la carte de type latLng 
			mapTypeId : google.maps.MapTypeId.TERRAIN, // Type de carte, différentes valeurs possible HYBRID, ROADMAP, SATELLITE, TERRAIN
			maxZoom   : 20
		};
		  
		map      = new google.maps.Map(document.getElementById(mapDivId), myOptions);
		panel    = document.getElementById(panelDivId);
		
		direction = new google.maps.DirectionsRenderer({
			map   : map,
			panel : panel // Dom element pour afficher les instructions d'itinéraire
		});
		
		var request = {
			origin      : latLng,
			destination : adresse,
			travelMode  : google.maps.DirectionsTravelMode.DRIVING // Mode de conduite
		}
		var directionsService = new google.maps.DirectionsService(); // Service de calcul d'itinéraire
		directionsService.route(request, function(response, status){ // Envoie de la requête pour calculer le parcours
			if(status == google.maps.DirectionsStatus.OK) {
				$("#"+panelDivId).html('');
				direction.setDirections(response); // Trace l'itinéraire sur la carte et les différentes étapes du parcours
				map.setCenter(latLng);
				map.setZoom(18);
			}
		});
	}
</script>