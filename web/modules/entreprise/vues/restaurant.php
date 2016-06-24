<?php $restaurant = $request->restaurant; ?>
<div id="restaurant">
	<?php $nbItem = 0; ?>
	<?php foreach ($restaurant->categories as $categorie) : ?>
		<?php if ($nbItem %3 == 0) : ?>
			<div class="row">
		<?php endif; ?>
		<div class="col-md-4">
			<div class="col-md-10 col-md-offset-1 item">
				<div class="title"><a href="?controler=restaurant&action=categories&id_categorie=<?php echo $categorie->id; ?>"><?php echo utf8_encode($categorie->nom); ?></a></div>
				<div class="vignette"><a href="?controler=restaurant&action=categories&id_categorie=<?php echo $categorie->id; ?>"><img src="<?php echo $categorie->logo; ?>"></a></div>
			</div>
		</div>
		<?php if ($nbItem %3 == 2) : ?>
			</div>
		<?php endif; ?>
		<?php $nbItem++; ?>
	<?php endforeach; ?>
	<?php if ($nbItem %3 == 0) : ?>
		<div class="row">
	<?php endif; ?>
		<div class="col-md-4">
			<div class="col-md-10 col-md-offset-1 item">
				<div class="title"><a href="?controler=restaurant&action=menu">Menus</a></div>
				<div class="vignette"><a href="?controler=restaurant&action=menu"><img src="<?php echo $restaurant->getMenuImg(); ?>"></a></div>
			</div>
		</div>
	</div>
</div>