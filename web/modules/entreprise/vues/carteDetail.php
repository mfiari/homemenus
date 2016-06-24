<a class="btn btn-primary" href="?controler=restaurant&action=categories&id_categorie=<?php echo $request->id_categorie; ?>">
	<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
</a>
<div class="row">
	<div class="vignette"><img style="float : left; width : 130px;" src="<?php echo $request->carte->logo; ?>"></div>
	<h2><?php echo utf8_encode($request->carte->nom); ?></h2>
	<?php if ($request->carte->commentaire != "") : ?>
		<div style="text-align : center;"><span>(<?php echo utf8_encode($request->carte->commentaire); ?>)</span></div>
	<?php endif; ?>
	<div style="clear : both;"></div>
</div>
<div class="row">
	<form method="post" enctype="x-www-form-urlencoded" id="carteForm" action="">
		<div class="row">
			<?php foreach ($request->carte->formats as $format) : ?>
				<div class="col-md-4">
					<div><?php echo utf8_encode($format->nom); ?></div>
					<div>Prix : <?php echo $format->prix; ?> €</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="row">
			<?php if (count($request->carte->options) > 0) : ?>
				<h3>Options</h3>
				<?php foreach ($request->carte->options as $option) : ?>
					<h3><?php echo utf8_encode($option->nom); ?></h3>
					<?php foreach ($option->values as $value) : ?>
						<div>
							<span><?php echo utf8_encode($value->nom); ?></span>
						</div>
					<?php endforeach; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if (count($request->carte->accompagnements) > 0) : ?>
				<h3>Accompagnement</h3>
				<?php foreach ($request->carte->accompagnements as $accompagnement) : ?>
					<?php foreach ($accompagnement->cartes as $carte) : ?>
						<div>
							<span><?php echo utf8_encode($carte->nom); ?></span>
						</div>
					<?php endforeach; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if (count($request->carte->supplements) > 0) : ?>
				<h3>Suppléments</h3>
				<?php foreach ($request->carte->supplements as $supplement) : ?>
					<div>
						<span><?php echo utf8_encode($supplement->nom); ?></span>
						<span><?php echo $supplement->prix; ?> €</span>
						<?php if ($supplement->commentaire != "") : ?>
							<span>(<?php echo utf8_encode($supplement->commentaire); ?>)</span>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</form>
</div>
<a class="btn btn-primary" href="?controler=restaurant&action=categories&id_categorie=<?php echo $request->id_categorie; ?>">
	<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
</a>