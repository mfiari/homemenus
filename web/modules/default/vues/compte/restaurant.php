<?php $restaurant = $request->restaurant; ?>
<?php $horaire = $restaurant->horaire; ?>
<div id="restaurant">
	<h2><?php echo utf8_encode($request->restaurant->nom); ?></h2>
	<a style="margin-bottom: 10px;" class="btn btn-primary" href="?controler=compte&action=commande_search">
		<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour au resultat de la recherche
	</a>
	<div class="row">
		<div class="panel panel-default panel-primary">
			<div class="panel-heading">
				Informations générales
			</div>
			<div class="row">
				<div class="col-md-6">
					<p><span>Adresse : </span><?php echo $restaurant->rue; ?>, <?php echo $restaurant->code_postal; ?> <?php echo $restaurant->ville; ?></p>
					<p><?php echo utf8_encode($restaurant->short_desc); ?></p>
					<p>
						<?php if ($horaire === false) : ?>
							<span>fermer aujourd'hui</span>
						<?php else : ?>
							<span>Ouvert de <?php echo $horaire->heure_debut; ?>h<?php echo $horaire->minute_debut; ?> à <?php echo $horaire->heure_fin; ?>h<?php echo $horaire->minute_fin; ?></span>
						<?php endif; ?>
					</p>
				</div>
				<div class="col-md-6">
					<div id="googleMap" class="col-md-10 col-md-offset-1" style="height:200px; margin-top : 10px;"></div>
				</div>
			</div>
		</div>
	</div>
	<?php $nbItem = 0; ?>
	<?php foreach ($restaurant->categories as $categorie) : ?>
		<?php if ($nbItem %3 == 0) : ?>
			<div class="row">
		<?php endif; ?>
		<div class="col-md-4">
			<div class="col-md-10 col-md-offset-1 item">
				<div class="title"><a href="?controler=compte&action=categories&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $categorie->id; ?>"><?php echo utf8_encode($categorie->nom); ?></a></div>
				<div class="vignette"><a href="?controler=compte&action=categories&id_restaurant=<?php echo $request->restaurant->id; ?>&id_categorie=<?php echo $categorie->id; ?>"><img src="<?php echo $categorie->logo; ?>"></a></div>
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
				<div class="title"><a href="?controler=compte&action=menu&id=<?php echo $request->restaurant->id; ?>">Menus</a></div>
				<div class="vignette"><a href="?controler=compte&action=menu&id=<?php echo $request->restaurant->id; ?>"><img src="<?php echo $request->restaurant->getMenuImg(); ?>"></a></div>
			</div>
		</div>
	</div>
	<a style="margin-top: 10px;" class="btn btn-primary" href="?controler=compte&action=commande_search">
		<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour au resultat de la recherche
	</a>
</div>
<script type="text/javascript">
	$(function() {
		initialize();
		var list = [];
		
		var homePoint = {};
		homePoint.type = "HOME";
		homePoint.adresse = "<?php echo $request->search_adresse; ?>";
		
		var restoPoint = {};
		restoPoint.type = "ADRESSE";
		restoPoint.adresse = "<?php echo $restaurant->rue.', '.$restaurant->ville; ?>";
		restoPoint.content = "<?php echo utf8_encode($restaurant->nom); ?>";
		
		list.push(homePoint);
		list.push(restoPoint);
		
		boundToPoints(list);
	});
</script>