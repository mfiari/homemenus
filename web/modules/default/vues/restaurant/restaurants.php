<h2>Liste des restaurants</h2>
<div id="search-bar">
	<form id="restaurant-filter-form" class="form-inline" action="?controler=restaurant&action=recherche" method="POST">
		<div class="row">
			<div class="col-md-5">
				<div class="form-group">
					<label for="adresse">Adresse : </label>
					<input id="full_address" class="form-control" name="adresse" type="text" placeholder="Entrez votre adresse" value="<?php echo $request->search_adresse; ?>">
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label for="distance">Distance : </label>
					<select class="form-control search-filter" name="distance">
						<?php for ($i = 5 ; $i <= 15 ; $i += 5) :?>
							<option value="<?php echo $i; ?>" <?php echo $request->distance == $i ? "selected" : ""; ?>><?php echo $i; ?> km</option>
						<?php endfor; ?>
					</select>
				</div>
			</div>
			<div class="col-md-3">
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
			<div class="col-md-2">
				<button class="btn btn-primary" type="submit">Rechercher</button>
			</div>
		</div>
		<!--<div class="row search-more">
			<a>Plus de critère</a>
		</div>-->
		<div class="row advence-search">
			<div class="col-md-2">
				<div class="input-group">
					<input name="open" type="checkbox" <?php echo $request->ouvert ? "checked" : ""; ?> />Ouvert
				</div>
			</div>
			<div class="col-md-2">
				<div class="input-group">
					<input name="livreur_dispo" type="checkbox" <?php echo $request->livreur_dispo ? "checked" : ""; ?>  />Livreur disponible
				</div>
			</div>
			<div class="col-md-4">
				<div class="input-group">
					<label for="tags">Catégorie : </label>
					<input id="tags" name="tags" type="text"  />
					<div>
						<?php foreach ($request->tags as $tag) : ?>
							<?php if (in_array($tag->id, $request->tagsFilter)) : ?>
								<p><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color : #FF0000; cursor : pointer" onclick="removeTag(<?php echo $tag->id; ?>)"></span><?php echo utf8_encode($tag->nom); ?>
							<?php endif ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="input-group">
					<label for="tags">Restaurant : </label>
					<input id="restaurantsField" name="tags" type="text"  />
				</div>
			</div>
			<?php foreach ($request->tags as $tag) : ?>
				<input id="field_tag_<?php echo $tag->id; ?>" name="tag_<?php echo $tag->id; ?>" type="checkbox" value="<?php echo $tag->id; ?>" <?php echo in_array($tag->id, $request->tagsFilter) ? 'checked' : ''; ?> style="display : none;" />
			<?php endforeach; ?>
		</div>
		<!--<div class="row search-button">
			<button class="btn btn-primary" type="submit">Rechercher</button>
		</div>-->
	</form>
</div>
<div id="restaurants">
	<?php if (isset($_GET['avis_send']) && $_GET['avis_send'] == 'success') : ?>
		<div class="alert alert-success" role="alert">
			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			Votre suggestion a bien été transmise aux équipes d'HoMe Menus
		</div>
	<?php endif; ?>
	<?php $restaurantIds = array(); ?>
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
			$indice = 0;
			$totalRestaurant = count($request->restaurants);
			$totalRestaurantOuvert = 0;
		?>
		<?php foreach ($request->restaurants as $restaurant) : ?>
			<?php $horaire = $restaurant->horaire; ?>
			<?php if ($horaire->heure_debut != '' && $restaurant->distance < 16 && $restaurant->distance > 0) : ?>
				<?php if ($indice %4 == 0) : ?>
					<div class="row">
				<?php endif; ?>
				<div class="col-md-3 col-sm-12 item">
					<span class="title">
						<a href="?controler=restaurant&action=index&id=<?php echo $restaurant->id; ?>">
							<?php echo utf8_encode($restaurant->nom); ?>
						</a>
					</span>
					<div class="logo_restaurant">
						<a href="?controler=restaurant&action=index&id=<?php echo $restaurant->id; ?>">
							<img src="res/img/restaurant/<?php echo $restaurant->logo; ?>">
						</a>
					</div>
					<div class="col-md-12 center">
						<p><?php echo utf8_encode($restaurant->short_desc); ?></p>
					</div>
					<div class="col-md-12" style="height : 50px">
						<?php foreach ($restaurant->certificats as $certificat) : ?>
							<a href="<?php echo $certificat->url; ?>" target="_blank" data-toggle="tooltip" title="<?php echo utf8_encode($certificat->description); ?>">
								<img src="res/img/<?php echo $certificat->logo; ?>">
							</a>
						<?php endforeach; ?>
					</div>
				</div>
				<?php if ($indice %4 == 3) : ?>
					</div>
				<?php endif; ?>
				<?php $indice++; ?>
				<?php $totalRestaurantOuvert++; ?>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php if ($indice %4 == 1) : ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>
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
	<?php if ($totalRestaurantNA > $totalRestaurantOuvert) : ?>
		<div style="margin-top : 100px;">
			<h4>Les restaurants ci-dessous ne peuvent vous être livré car ils se trouvent en dehors de notre périmètre de livraison (qui est de 15km).
			Si vous souhaitez tout de même vous les faires livrer, merci de passer par la <a href="?controler=contact&action=evenement">commande spécial</a>.</h4>
			<table class="table table-striped">
				<tbody>
					<?php foreach ($request->restaurants as $restaurant) : ?>
						<?php $horaire = $restaurant->horaire; ?>
						<?php if ($horaire->heure_debut != '' && ($restaurant->distance >= 16 || $restaurant->distance < 0)) : ?>
							<tr>
								<td>
									<a href="?action=restaurant_partenaire&id=<?php echo $restaurant->id; ?>">
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
<?php if ((count($request->restaurants) > 0) && ($totalRestaurantOuvert < $totalRestaurant)) : ?>
	<div style="margin-top : 100px;">
		<h4>Restaurants fermé aujourd'hui mais correspondant à votre recherche</h4>
		<table class="table table-striped">
			<tbody>
				<?php foreach ($request->restaurants as $restaurant) : ?>
					<?php $horaire = $restaurant->horaire; ?>
					<?php if ($horaire->heure_debut == '') : ?>
						<tr>
							<td>
								<a href="?action=restaurant_partenaire&id=<?php echo $restaurant->id; ?>">
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
<div style="margin-top : 50px;">
	<h3>Vous ne trouvez pas votre restaurant, faites nous part de vos suggestions</h3>
	<p style="text-align : center">Nous vous communiquerons lorsqu'un restaurant ouvrira ses portes près de chez vous.</p>
	<p style="text-align : center">Abonnées vous à nos réseaux sociaux Facebook et Twitter pour suivre notre actualité.</p>
	<p style="text-align : center">Rendez-vous sur notre page <a href="?action=restaurants_partenaire">restaurants partenaires</a> afin de voir les restaurants avec lesquels nous travaillons</p>
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
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
					<button class="btn btn-primary" type="submit">Envoyer</button>
				</fieldset>
			</form>
		</div>
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
	
	.advence-search {
		display : none;
	}
	
	.search-more {
		text-align : center;
	}
	
	.search-more a {
		cursor : pointer;
	}
	
	#restaurant-filter-form .search-button {
		text-align : right;
	}
	
	#restaurant-filter-form .row {
		margin-bottom : 10px;
	}
	
	
	span.title {
		display: block;
		font-size: 25px;
		text-align: center;
	}
	
	span.closed {
		background-color: #FF0000;
		border-radius: 5px;
		color: #ffffff;
		display: block;
		font-size: 20px;
		font-weight: bold;
		height: 30px;
		margin-top: 5px;
		text-align: center;
		width: 100%;
	}
	
	span.open {
		background-color: #00FF00;
		border-radius: 5px;
		color: #ffffff;
		display: block;
		font-size: 20px;
		font-weight: bold;
		height: 30px;
		margin-top: 5px;
		text-align: center;
		width: 100%;
	}
	
	.logo_restaurant {
		padding: 20px;
		text-align: center;
	}
	
	.logo_restaurant img {
		height: 120px;
		width: 200px;
	}
	
	.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
	.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
	.autocomplete-selected { background: #F0F0F0; }
	.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
	
</style>