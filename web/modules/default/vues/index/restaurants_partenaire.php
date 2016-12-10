<h1>Nos restaurants partenaire</h1>
<div id="restaurants">
	<?php $ville = ""; $indice = 0; ?>
	<?php foreach ($request->restaurants as $restaurant) : ?>
		<?php if ($ville != $restaurant->ville) : ?>
			<?php if ($indice != 0) : ?>
				</div>
			<?php endif; ?>
			<h2><?php echo $restaurant->ville; ?></h2>
			<?php $ville = $restaurant->ville; ?>
			<?php $indice = 0; ?>
		<?php endif; ?>
		<?php if ($indice %4 == 0) : ?>
			<div class="row">
		<?php endif; ?>
		<div class="col-md-3 col-sm-12 item">
			<span class="title">
				<a href="restaurant-<?php echo $restaurant->id; ?>-<?php echo str_replace(' ', '-', $ville); ?>-<?php echo str_replace(' ', '-', utf8_encode($restaurant->nom)); ?>.html">
					<?php echo utf8_encode($restaurant->nom); ?>
				</a>
			</span>
			<div class="logo_restaurant">
				<a href="restaurant-<?php echo $restaurant->id; ?>-<?php echo str_replace(' ', '-', $ville); ?>-<?php echo str_replace(' ', '-', utf8_encode($restaurant->nom)); ?>.html">
					<img src="<?php echo getLogoRestaurant($restaurant->id); ?>">
				</a>
			</div>
			<div class="col-md-12 center">
				<p><?php echo utf8_encode($restaurant->short_desc); ?></p>
			</div>
		</div>
		<?php if ($indice %4 == 3) : ?>
			</div>
		<?php endif; ?>
		<?php $indice++; ?>
	<?php endforeach; ?>
	<?php if ($indice %4 == 1) : ?>
		</div>
	<?php endif; ?>
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