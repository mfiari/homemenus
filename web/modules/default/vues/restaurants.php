<h2>Liste des restaurants</h2>
<div id="search-bar">
	<form id="restaurant-filter-form" class="form-inline" action="?controler=restaurant&action=recherche" method="POST">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="adresse">Adresse : </label>
					<input id="full_address" class="form-control" name="adresse" type="text" placeholder="Entrez votre adresse" value="<?php echo $request->search_ardresse; ?>">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="city">Ville : </label>
					<select class="form-control search-filter" name="city">
						<option value="">Tous</option>
						<?php foreach ($request->villes as $ville) : ?>
							<option value="<?php echo $ville; ?>" <?php echo $request->ville == $ville ? "selected" : ""; ?>><?php echo $ville; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="distance">Distance : </label>
					<select class="form-control search-filter" name="distance">
						<?php for ($i = 5 ; $i <= 20 ; $i += 5) :?>
							<option value="<?php echo $i; ?>" <?php echo $request->distance == $i ? "selected" : ""; ?>><?php echo $i; ?> km</option>
						<?php endfor; ?>
					</select>
				</div>
			</div>
		</div>
		<div class="row search-more">
			<a>Plus de critère</a>
		</div>
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
			<div class="col-md-6">
				<?php foreach ($request->tags as $tag) : ?>
					<div class="input-group">
						<input name="tag_<?php echo $tag->id; ?>" type="checkbox" value="<?php echo $tag->id; ?>" <?php echo in_array($tag->id, $request->tagsFilter) ? 'checked' : ''; ?>  /><?php echo utf8_encode($tag->nom); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="row search-button">
			<button class="btn btn-primary" type="submit">Rechercher</button>
		</div>
	</form>
</div>
<div id="restaurants">
	<?php if (count($request->restaurants) == 0) : ?>
		<div class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<span class="sr-only">Error:</span>
			Votre recherche n'a retourné aucun résultat.
		</div>
	<?php else : ?>
		<?php
			$current_heure = date('H');
			$current_minute = date('i');
			$indice = 0;
		?>
		<p><?php echo count($request->restaurants) ?> restaurants trouvés</p>
		<?php foreach ($request->restaurants as $restaurant) : ?>
			<?php $horaire = $restaurant->horaire; ?>
			<?php if ($indice %2 == 0) : ?>
				<div class="row">
			<?php endif; ?>
			<div class="col-md-6 col-sm-12 item">
				<span class="title">
					<a href="?controler=restaurant&action=index&id=<?php echo $restaurant->id; ?>">
						<?php echo utf8_encode($restaurant->nom); ?>
					</a>
				</span>
				<?php if (!$restaurant->has_livreur_dispo) : ?>
					<span class="closed">Pas de livreur disponible</span>
				<?php elseif ($horaire->heure_debut <= $current_heure && $horaire->heure_fin >= $current_heure) : ?>
					<span class="open">Ouvert</span>
				<?php else : ?>
					<span class="closed">Fermé</span>
				<?php endif; ?>
				<div class="logo_restaurant">
					<a href="?controler=restaurant&action=index&id=<?php echo $restaurant->id; ?>">
						<img src="res/img/restaurant/<?php echo $restaurant->logo; ?>">
					</a>
				</div>
				<div class="col-md-12 center">
					<p><?php echo utf8_encode($restaurant->short_desc); ?></p>
					<p><?php echo utf8_encode($restaurant->rue); ?>, <?php echo utf8_encode($restaurant->code_postal); ?> <?php echo utf8_encode($restaurant->ville); ?></p>
					<p>Ouvert de <?php echo $horaire->heure_debut; ?>:<?php echo $horaire->minute_debut; ?> à <?php echo $horaire->heure_fin; ?>:<?php echo $horaire->minute_fin; ?></p>
					<p>distance : <?php echo utf8_encode($restaurant->distance); ?> km</p>
				</div>
			</div>
			<?php if ($indice %2 == 1) : ?>
				</div>
			<?php endif; ?>
			<?php $indice++; ?>
		<?php endforeach; ?>
		<?php if ($indice %2 == 1) : ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>
<script type="text/javascript">
	$(function() {
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
	#full_address {
		width: 450px;
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
		max-height: 180px;
		max-width: 100%;
	}
	
</style>