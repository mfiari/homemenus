<h1>Plats favoris</h1>
<div id="plats">
	<div class="row">
		<?php $item = 0; ?>
		<?php foreach ($request->cartes as $carte) : ?>
			<?php $item++; ?>
			<div class="col-md-6 col-sm-12 item">
				<span class="title"><?php echo utf8_encode($carte->nom); ?></span>
				<span style="display : block; text-align: center;">(<?php echo utf8_encode($carte->categorie->nom); ?>)</span>
				<div class="row">
					<div id="item<?php echo $item; ?>" class="col-md-6 col-sm-12">
						<div class="logo_restaurant">
							<img src="<?php echo $carte->logo; ?>">
						</div>
					</div>
					<div class="col-md-6 col-sm-12 center-vertical" data-base="item<?php echo $item; ?>">
						<p><?php echo utf8_encode($carte->commentaire); ?></p>
					</div>
				</div>
				<div class="col-md-12 center">
					<p>
						Voir la carte complète du restaurant 
						<a href="restaurant-<?php echo $carte->restaurant->id; ?>-<?php echo str_replace(' ', '-', $carte->restaurant->ville); ?>-<?php echo str_replace(' ', '-', utf8_encode($carte->restaurant->nom)); ?>.html">
							<?php echo utf8_encode($carte->restaurant->nom); ?>
						</a>
					</p>
				</div>
			</div>
		<?php endforeach; ?>
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
<script>
	$(function() {
		$('.center-vertical').each(function () {
			var baseId = $(this).attr('data-base');
			var baseHeight = $("#"+baseId).height();
			var height = $(this).height();
			$(this).css("margin-top", ((baseHeight / 2) - (height / 2)));
		});
	});
</script>