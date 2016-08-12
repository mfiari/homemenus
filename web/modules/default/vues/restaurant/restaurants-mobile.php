<h2>Liste des restaurants</h2>
<div id="search-bar">
	<form id="restaurant-filter-form" action="?controler=restaurant&action=recherche" method="POST">
		<div class="row">
			<div class="col-sm-9">
				<div class="form-group">
					<input id="full_address" class="form-control" name="adresse" type="text" placeholder="Entrez votre adresse" value="<?php echo $request->search_adresse; ?>">
				</div>
			</div>
			<div class="col-sm-3">
				<button class="btn btn-primary" type="submit">Rechercher</button>
			</div>
		</div>
	</form>
</div>
<div id="restaurants">
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
			<?php if ($horaire->heure_debut != '') : ?>
				<div class="row">
					<div class="col-sm-12 item">
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
					</div>
				</div>
				<?php $indice++; ?>
				<?php $totalRestaurantOuvert++; ?>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php if ($indice %4 == 1) : ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>
<?php if ($totalRestaurantOuvert < $totalRestaurant) : ?>
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
							<td><?php echo $restaurant->distance; ?> Km</td>
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
	});
</script>
<style>
	
	#restaurant-filter-form #full_address {
		height : 100px;
		font-size : 35px;
	}

	#restaurant-filter-form button.btn {
		font-size : 35px;
		height : 100px;
		width : 100%;
	}

	span.title {
		display: block;
		font-size: 50px;
		text-align: center;
	}
	
	.logo_restaurant {
		padding: 10px;
		text-align: center;
	}
	
	.logo_restaurant img {
		width: 90%;
	}
	
</style>