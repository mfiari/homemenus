<div class="row">
	<h1>Liste des restaurants</h1>
</div>
<div class="row">
	<form id="restaurant-filter-form" class="form-inline" action="?controler=restaurant&action=recherche" method="POST">
		<div class="row">
			<div class="col-md-5 col-sm-5">
				<div class="form-group">
					<label for="adresse">Adresse : </label>
					<input id="full_address" class="form-control" name="adresse" type="text" placeholder="Entrez votre adresse" value="<?php echo $request->search_adresse; ?>">
				</div>
			</div>
			<div class="col-md-2 col-sm-2">
				<div class="form-group">
					<label for="distance">Distance : </label>
					<select class="form-control search-filter" name="distance">
						<?php for ($i = 5 ; $i <= MAX_KM ; $i += 5) :?>
							<option value="<?php echo $i; ?>" <?php echo $request->distance == $i ? "selected" : ""; ?>><?php echo $i; ?> km</option>
						<?php endfor; ?>
					</select>
				</div>
			</div>
			<div class="col-md-3 col-sm-3">
				<?php if (count($request->restaurants) > 0) : ?>
					<div class="form-group">
						<label for="city">Ville : </label>
						<select class="form-control search-filter" name="city">
							<option value="">Tous</option>
							<?php foreach ($request->villes as $ville) : ?>
								<option value="<?php echo $ville; ?>" <?php echo $request->ville == $ville ? "selected" : ""; ?>><?php echo $ville; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>
			</div>
			<div class="col-md-2 col-sm-2">
				<button class="validate-button" type="submit">Rechercher</button>
			</div>
		</div>
	</form>
</div>
<div class="row" style="margin-top : 50px;">
	<div class="col-md-8 col-sm-8">
		<?php 
			$restaurantIds = array();
			$totalRestaurantOuvert = 0;
		?>
		<?php if ($request->adressError) : ?>
			<div class="alert alert-danger" role="alert">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				<span class="sr-only">Error:</span>
				L'adresse saisie est incorrecte.
			</div>
		<?php elseif (count($request->restaurants) == 0) : ?>
			<div class="alert alert-danger" role="alert">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				<span class="sr-only">Error:</span>
				Votre recherche n'a retourné aucun résultat. Peut-être devriez-vous augmenter la distance de recherche.
				<p>Rendez-vous sur notre page <a href="?action=restaurants_partenaire">restaurants partenaires</a> afin de voir les restaurants avec lesquels nous travaillons</p>
				
			</div>
		<?php else : ?>
			<?php
				$current_heure = date('H');
				$current_minute = date('i');
				$totalRestaurant = count($request->restaurants);
			?>
			<?php foreach ($request->restaurants as $restaurant) : ?>
				<?php $horaire = $restaurant->horaire; ?>
				<?php if ($horaire->heure_debut != '' && $restaurant->distance < (MAX_KM +1) && $restaurant->distance > 0) : ?>
					<div class="row" style="background-color : #F4F4F4;">
						<div class="col-md-5 col-sm-5">
							<a href="<?php echo restaurantToLink($restaurant, $restaurant->ville); ?>">
								<img style="width : 100%" src="res/img/restaurant/<?php echo $restaurant->logo; ?>" alt="HoMe Menus - <?php echo utf8_encode($restaurant->nom); ?>" Title="HoMe Menus - <?php echo utf8_encode($restaurant->nom); ?>">
							</a>
						</div>
						<div class="col-md-7 col-sm-7">
							<h2>
								<a href="<?php echo restaurantToLink($restaurant, $restaurant->ville); ?>">
									<?php echo utf8_encode($restaurant->nom); ?>
								</a>
							</h2>
							<span><?php echo utf8_encode($restaurant->short_desc); ?></span><br />
							<hr />
							<span>Horaires d'ouverture <?php echo formatHeureMinute($horaire->heure_debut,$horaire->minute_debut); ?> - <?php echo formatHeureMinute($horaire->heure_fin,$horaire->minute_fin); ?></span>
							<hr />
							<div>
								<?php foreach ($restaurant->certificats as $certificat) : ?>
									<a href="<?php echo $certificat->url; ?>" target="_blank" data-toggle="tooltip" title="<?php echo utf8_encode($certificat->description); ?>">
										<img style="width : 70px" src="res/img/<?php echo $certificat->logo; ?>">
									</a>
								<?php endforeach; ?>
							</div>
						</div>
					</div><br />
					<?php $totalRestaurantOuvert++; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<div class="col-md-4 col-sm-4">
		<?php if ((count($request->restaurants) > 0) && ($totalRestaurantOuvert < $totalRestaurant)) : ?>
			<?php 
				$totalRestaurantNA = $totalRestaurantOuvert;
				foreach ($request->restaurants as $restaurant) {
					$horaire = $restaurant->horaire;
					if ($horaire->heure_debut != '' && ($restaurant->distance >= 16 || $restaurant->distance < 0)) {
						$totalRestaurantNA++;
					}
				}
			?>
			<?php if ((count($request->restaurants) > 0) && ($totalRestaurantOuvert < $totalRestaurant)) : ?>
				<div style="margin-bottom : 100px;">
					<h3>Restaurants fermé aujourd'hui mais correspondant à votre recherche</h3>
					<table class="table table-striped">
						<tbody>
							<?php foreach ($request->restaurants as $restaurant) : ?>
								<?php $horaire = $restaurant->horaire; ?>
								<?php if ($horaire->heure_debut == '') : ?>
									<tr>
										<td>
											<a href="<?php echo restaurantToLink($restaurant, $restaurant->ville); ?>">
												<?php echo utf8_encode($restaurant->nom); ?>
											</a>
										</td>
										<td><?php echo utf8_encode($restaurant->short_desc); ?></td>
										<td><?php echo $restaurant->distance > 0 ? $restaurant->distance.' Km' : 'Distance non trouvée'; ?></td>
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>	
			<?php if ($totalRestaurantNA > $totalRestaurantOuvert) : ?>
				<div style="margin-bottom : 100px;">
					<h3>Les restaurants ci-dessous ne peuvent vous être livré car ils se trouvent en dehors de notre périmètre de livraison (qui est de <?php echo MAX_KM; ?>km).
					Si vous souhaitez tout de même vous les faires livrer, merci de passer par la <a href="?controler=contact&action=evenement">commande spécial</a>.</h3>
					<table class="table table-striped">
						<tbody>
							<?php foreach ($request->restaurants as $restaurant) : ?>
								<?php $horaire = $restaurant->horaire; ?>
								<?php if ($horaire->heure_debut != '' && ($restaurant->distance >= 16 || $restaurant->distance < 0)) : ?>
									<tr>
										<td>
											<a href="<?php echo restaurantToLink($restaurant, $restaurant->ville); ?>">
												<?php echo utf8_encode($restaurant->nom); ?>
											</a>
										</td>
										<td><?php echo utf8_encode($restaurant->short_desc); ?></td>
										<td><?php echo $restaurant->distance > 0 ? $restaurant->distance.' Km' : 'Distance non trouvée'; ?></td>
									</tr>
									<?php $totalRestaurantOuvert++; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<h3>Vous ne trouvez pas votre restaurant, faites nous part de vos suggestions</h3>
		<?php if (isset($_GET['avis_send']) && $_GET['avis_send'] == 'success') : ?>
			<div class="alert alert-success" role="alert">
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
				Votre suggestion a bien été transmise aux équipes d'HoMe Menus
			</div>
		<?php endif; ?>
		<form method="post" enctype="x-www-form-urlencoded" id="contactForm" action="?controler=contact&action=avis">
			<fieldset>
				<div class="form-group">
					<label for="sujet">Nom du restaurant<span class="required">*</span> : </label>
					<input class="form-control" name="nom" type="text" value="" required>
				</div>
				<div class="form-group">
					<label for="sujet">Ville du restaurant<span class="required">*</span> : </label>
					<input class="form-control" name="ville" type="text" value="" required>
				</div>
				<div class="form-group">
					<label for="sujet">Votre ville<span class="required">*</span> : </label>
					<input class="form-control" name="ville_user" type="text" value="" required>
				</div>
				<div class="form-group">
					<label for="email">Votre email<span class="required">*</span> : </label>
					<input class="form-control" name="email" type="email" value="" required>
				</div>
				<button class="send-button" type="submit">Envoyer <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></button>
			</fieldset>
		</form>
	</div>
</div>



<script type="text/javascript">
	$(function() {
		enableAutocomplete("full_address");
		$(".search-more a").click(function () {
			if ($(".advence-search").is(":visible")) {
				$(".advence-search").hide();
				$(this).html("Plus de critère");
			} else {
				$(".advence-search").show();
				$(this).html("Moins de critère");
			}
			
		});
		$('#tags').autocomplete({
			source: function( request, response ) {
				$.ajax({
					dataType: "json",
					type : 'Get',
					url: '?controler=restaurant&action=autocompleteTag',
					data : {
						term: request.term,
						restaurant : <?php echo json_encode($restaurantIds); ?>
					},
					success: function(data) {
						response(data);
					},
					error: function(data) {
						alert(data);
					}
				});
			},
			minLength : 1,
			select: function(event, ui) {
				$("#field_tag_"+ui.item.id).prop("checked", true);
				$("#restaurant-filter-form").submit();
			}
		}).autocomplete( "instance" )._renderItem = function( ul, item ) {
		  return $("<li>")
			.append( "<a>" + item.value + "</a>" )
			.appendTo( ul );
		};
		$('#restaurantsField').autocomplete({
			source: function( request, response ) {
				$.ajax({
					dataType: "json",
					type : 'Get',
					url: '?controler=restaurant&action=autocompleteRestaurant',
					data : {
						term: request.term,
						restaurant : <?php echo json_encode($restaurantIds); ?>
					},
					success: function(data) {
						response(data);
					},
					error: function(data) {
						alert(data);
					}
				});
			},
			minLength : 1,
			select: function(event, ui) {
				
			}
		}).autocomplete( "instance" )._renderItem = function( ul, item ) {
		  return $("<li>")
			.append( "<a>" + item.value + "</a>" )
			.appendTo( ul );
		};
	});
	
	function removeTag(id) {
		$("#field_tag_"+id).prop("checked", false);
		$("#restaurant-filter-form").submit();
	}
</script>
<style>
	#full_address {
		width: 380px;
	}
</style>