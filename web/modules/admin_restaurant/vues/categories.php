<div id="carte">
	<a class="btn btn-primary" href="?controler=restaurant">
		<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
	</a>
	<?php 
		$currentCategorie = "";
		$nbItem = 0; 
	?>
	<?php foreach ($request->restaurant->categories as $categorie) : ?>
		<div class="categorie">
			<h3><?php echo utf8_encode($categorie->nom); ?></h3>
			<?php $nbItem = 0; ?>
			<?php foreach ($categorie->contenus as $contenu) : ?>
				<?php if ($nbItem % 4 == 0) : ?>
					<div class="row" style="margin-top : 20px;">
				<?php endif; ?>
				<div class="col-md-3">
					<div class="col-md-10 col-md-offset-1 item">
						<div class="title"><a href="?controler=restaurant&action=carte&id_categorie=<?php echo $request->categorie->id; ?>&id_carte=<?php echo $contenu->id; ?>"><?php echo utf8_encode($contenu->nom); ?></a></div>
						<div class="vignette"><a href="?controler=restaurant&action=carte&id_categorie=<?php echo $request->categorie->id; ?>&id_carte=<?php echo $contenu->id; ?>"><img src="<?php echo $contenu->logo; ?>"></a></div>
					</div>
				</div>
				<?php $nbItem++; ?>
				<?php if ($nbItem % 4 == 0) : ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
	<a class="btn btn-primary" href="?controler=restaurant">
		<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
	</a>
</div>
<style>
	.categorie .title a, .categorie .vignette a {
		cursor : pointer;
	}
</style>