<h2>Liste des restaurants</h2>
<div id="search-bar">
	<div id="adress-search">
		<form id="restaurant-filter-form" action="restaurants.html" method="POST">
			<div class="search-block">
				<a class="search-button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>
				<input id="full_address" class="form-control" name="adresse" type="text" placeholder="Saisissez votre adresse" value="<?php echo $request->search_adresse; ?>">
			</div>
		</form>
	</div>
</div>
<div id="restaurants">
	<?php if (isset($_GET['avis_send']) && $_GET['avis_send'] == 'success') : ?>
		<div class="alert alert-success" role="alert">
			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			Votre suggestion a bien été transmise aux équipes d'HoMe Menus
		</div>
	<?php endif; ?>
	<?php 
		$restaurantIds = array();
		$totalRestaurantOuvert = 0;
		$totalRestaurant = count($request->restaurants);
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
		?>
		<?php foreach ($request->restaurants as $restaurant) : ?>
			<?php $horaire = $restaurant->horaire; ?>
			<?php if ($horaire->heure_debut != '' && $restaurant->distance < 16 && $restaurant->distance > 0) : ?>
				<div class="row" style="background-color : #F4F4F4;">
					<div class="col-md-5 col-sm-5 col-xs-5">
						<a href="<?php echo restaurantToLink($restaurant, $restaurant->ville); ?>">
							<img style="width : 100%" src="res/img/restaurant/<?php echo $restaurant->logo; ?>" alt="HoMe Menus - <?php echo utf8_encode($restaurant->nom); ?>" Title="HoMe Menus - <?php echo utf8_encode($restaurant->nom); ?>">
						</a>
					</div>
					<div class="col-md-7 col-sm-7 col-xs-7">
						<h3>
							<a href="<?php echo restaurantToLink($restaurant, $restaurant->ville); ?>">
								<?php echo utf8_encode($restaurant->nom); ?>
							</a>
						</h3>
						<span><?php echo utf8_encode($restaurant->short_desc); ?></span>
						<hr />
						<span>Horaires d'ouverture <?php echo formatHeureMinute($horaire->heure_debut,$horaire->minute_debut); ?> - <?php echo formatHeureMinute($horaire->heure_fin,$horaire->minute_fin); ?></span>
						<hr />
					</div>
				</div><br />
				<?php $totalRestaurantOuvert++; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
<?php if ((count($request->restaurants) > 0) && ($totalRestaurantOuvert < $totalRestaurant)) : ?>
	<?php 
		$totalRestaurantNA = $totalRestaurantOuvert;
		foreach ($request->restaurants as $restaurant) {
			$horaire = $restaurant->horaire;
			if ($horaire->heure_debut != '' && ($restaurant->distance >= (MAX_KM +1) || $restaurant->distance < 0)) {
				$totalRestaurantNA++;
			}
		}
	?>
	<?php if ($totalRestaurantNA > $totalRestaurantOuvert) : ?>
		<div style="margin-top : 50px;">
			<h4>Les restaurants ci-dessous ne peuvent vous être livré car ils se trouvent en dehors de notre périmètre de livraison (qui est de <?php echo MAX_KM; ?>km).
			Si vous souhaitez tout de même vous les faires livrer, merci de passer par la <a href="?controler=contact&action=evenement">commande spécial</a>.</h4>
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
<?php if ((count($request->restaurants) > 0) && ($totalRestaurantOuvert < $totalRestaurant)) : ?>
	<div style="margin-top : 50px;">
		<h4>Restaurants fermé aujourd'hui mais correspondant à votre recherche</h4>
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
							<td><?php echo $restaurant->distance; ?> Km</td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endif; ?>
<div style="margin-top : 50px;">
	<h4>Vous ne trouvez pas votre restaurant, faites nous part de vos suggestions</h4>
	<p style="font-size : 14px; text-align : center">Rendez-vous sur notre page <a href="restaurants-partenaire.html">restaurants partenaires</a> afin de voir les restaurants avec lesquels nous travaillons</p>
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
					<button class="send-button" type="submit">Envoyer <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></button>
				</fieldset>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		enableAutocomplete("full_address");
	});
</script>
<style>

	#search-bar {
		margin-bottom : 15px;
	}
	
	#restaurants h3 {
		font-size : 22px;
		margin-top : 5px;
		margin-bottom : 5px;
	}
	
	#restaurants hr {
		margin-top : 5px;
		margin-bottom : 5px;
	}

	#restaurants span {
		font-size : 8px;
	}
	
</style>