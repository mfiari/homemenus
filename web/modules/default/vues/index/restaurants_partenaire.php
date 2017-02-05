<h1>Nos restaurants partenaire</h1>
<div class="row">
	<div class="col-md-7 col-sm-7">
		<div id="restaurants">
			<?php $ville = ""; ?>
			<?php foreach ($request->restaurants as $restaurant) : ?>
				<?php if ($ville != $restaurant->ville) : ?>
					<h2><?php echo $restaurant->ville; ?></h2>
					<?php $ville = $restaurant->ville; ?>
				<?php endif; ?>
				<div class="row" style="background-color : #F4F4F4;">
					<div class="col-md-5 col-sm-5">
						<a href="<?php echo restaurantToLink($restaurant, $ville); ?>">
							<img style="width : 100%" src="<?php echo getLogoRestaurant($restaurant->id); ?>" alt="HoMe Menus - <?php echo utf8_encode($restaurant->nom); ?>" Title="HoMe Menus - <?php echo utf8_encode($restaurant->nom); ?>">
						</a>
					</div>
					<div class="col-md-7 col-sm-7">
						<h2>
							<a href="<?php echo restaurantToLink($restaurant, $ville); ?>">
								<?php echo utf8_encode($restaurant->nom); ?>
							</a>
						</h2>
						<span><?php echo utf8_encode($restaurant->short_desc); ?></span><br />
						<hr />
						<span><?php echo utf8_encode($restaurant->rue); ?>, <?php echo $restaurant->code_postal; ?> <?php echo $restaurant->ville; ?></span>
						<hr />
						<div>
							<?php foreach ($restaurant->certificats as $certificat) : ?>
								<a href="<?php echo $certificat->url; ?>" target="_blank" data-toggle="tooltip" title="<?php echo utf8_encode($certificat->description); ?>">
									<img style="width : 70px" src="res/img/<?php echo $certificat->logo; ?>">
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="col-md-4 col-md-offset-1 col-sm-4 col-sm-offset-1">
		<h2>Vous ne trouvez pas votre restaurant, faites nous part de vos suggestions</h2>
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
<style>
	
	span.title {
		display: block;
		font-size: 25px;
		text-align: center;
	}
	
	.logo_restaurant {
		padding: 20px;
		text-align: center;
	}
	
	.logo_restaurant img {
		height: 120px;
		width: 200px;
	}
	
</style>