<div class="row background-violet">
	<div class="row">
		<h2>HoMe Menus, Livraison de repas à domicile</h2>
	</div>
	<div class="row center">
		<div class="col-md-12 col-sm-12">
			<div class="col-lg-3 col-lg-offset-1 col-md-3 col-md-offset-1 col-sm-3 col-sm-offset-1 col-xs-12 col-xs-offset-0">
				<div class="row">
					<a href="inscription.html"><img src="res/img/particulier.png" alt="HoMe Menus - particuliers" Title="HoMe Menus - particuliers"></a>
				</div>
				<div class="row">
					<h3><a href="inscription.html" style="color : #FFFFFF;">Particuliers</a></h3>
				</div>
				<div class="row">
					<p>Savourez et dégustez la cuisine des meilleurs restaurants aux environs de chez vous en vous les faisant livrer.</p>
				</div>
			</div>
			<div class="col-lg-3 col-lg-offset-1 col-md-3 col-md-offset-1 col-sm-3 col-sm-offset-1 col-xs-12 col-xs-offset-0">
				<div class="row">
					<a href="contact-entreprise.html"><img src="res/img/entreprises.png" alt="HoMe Menus - Entreprises" Title="HoMe Menus - Entreprises"></a>
				</div>
				<div class="row">
					<h3><a href="entreprises.html" style="color : #FFFFFF;">Entreprises</a></h3>
				</div>
				<div class="row">
					<p>Bons repas au bureau ou déjeuners d'équipe ? HoMe Menus a prévu une gamme exceptionnelle de produits et services.</p>
				</div>
			</div>
			<div class="col-lg-3 col-lg-offset-1 col-md-3 col-md-offset-1 col-sm-3 col-sm-offset-1 col-xs-12 col-xs-offset-0">
				<div class="row">
					<a href="contact-evenement.html"><img src="res/img/evenement.png" alt="HoMe Menus - particuliers" Title="HoMe Menus - particuliers"></a>
				</div>
				<div class="row">
					<h3><a href="contact-evenement.html" style="color : #FFFFFF;">Evénement</a></h3>
				</div>
				<div class="row">
					<p>Un repas d'anniversaire, une réunion, un événement, la commande spéciale est faite pour vous.</p>
				</div>
			</div>
		</div>
		<div class="col-md-12 col-sm-12">
			<a class="link-more" href="">En savoir plus <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
		</div>
	</div>
</div>
<div class="row">
	<div class="row">
		<h2>Les nouveaux restaurants</h2>
	</div>
	<div style="width : 100%; height : 100%; overflow : hidden;">
		<div class="row" style="margin-bottom : 20px; position: relative; width : 200%;">
			<div style="bottom : 0; color : #FFFFFF; left : 20px; position : absolute; z-index : 2;">
				<h3>Le volonté</h3>
				<p>Restaurant chinois</p>
			</div>
			<a href="restaurant-14-Mantes-la-Jolie-Le-volonte.html" style="margin-right : 20px;">
				<img style="height : 450px;" src="res/img/le-volonte.png" alt="HoMe Menus - Le volonté" Title="HoMe Menus - Le volonté">
			</a>
			<div style="bottom : 50px; color : #FFFFFF; left : 650px; position : absolute; z-index : 2;">
				<h3>La Villa Mantes</h3>
				<p>Restaurant franco asiatique</p>
			</div>
			<a href="restaurant-10-Mantes-la-Ville-La-Villa-Mantes.html" style="margin-right : 20px;">
				<img style="height : 360px;" src="res/img/la-villa-mantes.png" alt="HoMe Menus - La villa mantes" Title="HoMe Menus - La villa mantes">
			</a>
		</div>
		<div class="row" style="margin-bottom : 20px; position: relative; width : 200%;">
			<div style="bottom : 50px; color : #FFFFFF; left : 20px; position : absolute; z-index : 2;">
				<h3>Comme à la maison</h3>
				<p>Restaurant Hispano italien</p>
			</div>
			<a href="restaurant-15-Mantes-la-Jolie-Comme-a-la-maison.html" style="margin-right : 20px;">
				<img style="height : 360px;" src="res/img/comme-a-la-maison.png" alt="HoMe Menus - Comme à la maison" Title="HoMe Menus - Comme à la maison">
			</a>
			<div style="bottom : 0; color : #FFFFFF; left : 550px; position : absolute; z-index : 2;">
				<h3>La brasserie des halles</h3>
				<p>Brasserie</p>
			</div>
			<a href="restaurant-16-Mantes-la-Jolie-La-brasserie-des-halles.html" style="margin-right : 20px;">
				<img style="height : 450px;" src="res/img/brasserie.png" alt="HoMe Menus - Brasserie des halles" Title="HoMe Menus - Brasserie des halles">
			</a>
		</div>
	</div>
	<div class="row center">
		<a class="link" href="restaurants-partenaire.html"><b>Voir</b> tous les restaurants</a>
	</div>
</div>
<div class="row">
	<div class="row">
		<h2>Nos news <br />A la Une</h2>
	</div>
	<div id="myCarousel" class="carousel slide" data-ride="carousel">
		<!-- Indicators -->
		<ol class="carousel-indicators">
			<?php $index = 0; ?>
			<?php foreach ($request->news as $news) : ?>
				<li data-target="#myCarousel" data-slide-to="<?php echo $index; ?>" class="<?php echo $index == 0 ? 'active' : ''; ?>"></li>
				<?php $index++; ?>
			<?php endforeach; ?>
		</ol>

		<!-- Wrapper for slides -->
		<div class="carousel-inner" role="listbox">
			<?php $index = 0; ?>
			<?php foreach ($request->news as $news) : ?>
				<div class="row item <?php echo $index == 0 ? 'active' : ''; ?>">
					<div class="col-md-6 col-sm-6" style="background-color : #F4F4F4; color : #000000; padding : 80px;">
						<h3><?php echo utf8_encode($news->titre); ?></h3>
						<p><?php echo utf8_encode($news->text); ?></p>
						<a class="link" href="<?php echo $news->link_url; ?>"><?php echo $news->link_text; ?></a>
					</div>
					<div class="col-md-6 col-sm-6" style="margin-top: 50px; margin-left: -80px;">
						<img style="width : 600px" src="<?php echo $news->image; ?>" alt="HoMe Menus - <?php echo utf8_encode($news->titre); ?>" Title="HoMe Menus - <?php echo utf8_encode($news->titre); ?>">
					</div>
				</div>
				<?php $index++; ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<div class="row" style="position: relative; height: 300px; margin-top : 100px;">
	<div class="div-livreur"></div>
	<div class="gradient-livreur"></div>
	<div class="col-md-6 col-sm-6" style="color: #FFFFFF; text-align: center; z-index : 2;">
		<div class="row">
			<h3>Devenir livreur</h3>
		</div>
		<div class="row">
			<p>Vous êtes sportif, dynamique et avez envie de vous faire un extra, rejoignez l’aventure HoMe Menus.</p>
		</div>
		<div class="row">
			<a class="link-more" href="contact-livreur.html">Devenir livreur <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
		</div>
	</div>
	<div class="div-restaurant"></div>
	<div class="gradient-restaurant"></div>
	<div class="col-md-6 col-sm-6" style="color: #FFFFFF; text-align: center; z-index : 2;">
		<div class="row">
			<h3>Devenir restaurant partenaire</h3>
		</div>
		<div class="row">
			<p>Besoin d’offrir une nouvelle dimension à votre restaurant. Envie de faire plus de commandes et d’avoir plus de couverts, 
			sans faire d’effort, ni même faire d’investissement, avec HoMe Menus c’est garanti.</p>
			<p>Faites nous confiance nous nous occupons de tout !</p>
			<p>Contactez-nous dès maintenant</p>
		</div>
		<div class="row">
			<a class="link-more" href="contact-restaurant.html" style="width : 260px;">Devenir restaurant partenaire <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
		</div>
	</div>
</div>