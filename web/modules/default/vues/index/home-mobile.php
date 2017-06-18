<div class="col-md-12 background-violet">
	<div class="row">
		<h2>HoMe Menus, Livraison de repas à domicile</h2>
	</div>
	<div class="row center">
		<div id="myCarousel" class="carousel slide" data-ride="carousel">
			<!-- Indicators -->
			<ol class="carousel-indicators">
				<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
				<li data-target="#myCarousel" data-slide-to="1"></li>
				<li data-target="#myCarousel" data-slide-to="2"></li>
			</ol>

			<!-- Wrapper for slides -->
			<div class="carousel-inner" role="listbox">
				<div class="col-xs-12 item active">
					<div class="row">
						<img src="res/img/particulier-min.png" alt="HoMe Menus - particuliers" Title="HoMe Menus - particuliers">
					</div>
					<div class="row">
						<h3>Particuliers</h3>
					</div>
					<div class="row">
						<p>Savourez et dégustez la cuisine des meilleurs restaurants aux environs de chez vous en vous les faisant livrer.</p>
					</div>
				</div>
				<div class="col-xs-12 item">
					<div class="row">
						<img src="res/img/entreprises-min.png" alt="HoMe Menus - Entreprises" Title="HoMe Menus - Entreprises">
					</div>
					<div class="row">
						<h3>Entreprises</h3>
					</div>
					<div class="row">
						<p>Bons repas au bureau ou déjeuners d'équipe ? HoMe Menus a prévu une gamme exceptionnelle de produits et services.</p>
					</div>
				</div>
				<div class="col-xs-12 item">
					<div class="row">
						<img src="res/img/evenement-min.png" alt="HoMe Menus - particuliers" Title="HoMe Menus - particuliers">
					</div>
					<div class="row">
						<h3>Evénement</h3>
					</div>
					<div class="row">
						<p>Un repas d'anniversaire, une réunion, un événement, la commande spéciale est faite pour vous.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div>
	<h2>Les nouveaux restaurants</h2>
	<div id="restaurants-carousel" class="carousel slide" data-ride="carousel">
		<!-- Indicators -->
		<ol class="carousel-indicators">
			<li data-target="#restaurants-carousel" data-slide-to="0" class="active"></li>
			<li data-target="#restaurants-carousel" data-slide-to="1"></li>
			<li data-target="#restaurants-carousel" data-slide-to="2"></li>
			<li data-target="#restaurants-carousel" data-slide-to="3"></li>
		</ol>

		<!-- Wrapper for slides -->
		<div class="carousel-inner" role="listbox">
			<div class="item active" style="">
				<div style="bottom : 0; color : #FFFFFF; left : 20px; position : absolute; z-index : 2;">
					<h3>Dalla Famiglia</h3>
					<p>Restaurant Italien traditionnel</p>
				</div>
				<a href="restaurant-8-Mantes-la-Jolie-Dalla-Famiglia.html">
					<img style="width : 100%;" src="res/img/dalla-famiglia-min.jpg" alt="HoMe Menus - Dalla Famiglia" Title="HoMe Menus - Dalla Famiglia">
				</a>
			</div>
			<div class="item" style="">
				<div style="bottom : 0; color : #FFFFFF; left : 20px; position : absolute; z-index : 2;">
					<h3>La brasserie des halles</h3>
					<p>Brasserie</p>
				</div>
				<a href="restaurant-16-Mantes-la-Jolie-La-brasserie-des-halles.html">
					<img style="width : 100%;" src="res/img/brasserie-min.png" alt="HoMe Menus - Brasserie des halles" Title="HoMe Menus - Brasserie des halles">
				</a>
			</div>
			<div class="item" style="">
				<div style="bottom : 0; color : #FFFFFF; left : 20px; position : absolute; z-index : 2;">
					<h3>Le volonté</h3>
					<p>Restaurant chinois</p>
				</div>
				<a href="restaurant-14-Mantes-la-Jolie-Le-volonte.html">
					<img style="width : 100%;" src="res/img/le-volonte-min.png" alt="HoMe Menus - Le volonté" Title="HoMe Menus - Le volonté">
				</a>
			</div>
			<div class="item" style="">
				<div style="bottom : 0; color : #FFFFFF; left : 20px; position : absolute; z-index : 2;">
					<h3>Comme à la maison</h3>
					<p>Restaurant Hispano italien</p>
				</div>
				<a href="restaurant-15-Mantes-la-Jolie-Comme-a-la-maison.html">
					<img style="width : 100%;" src="res/img/comme-a-la-maison.png" alt="HoMe Menus - Comme à la maison" Title="HoMe Menus - Comme à la maison">
				</a>
			</div>
		</div>
		
		<!-- Left and right controls -->
		<a class="left carousel-control" href="#restaurants-carousel" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#restaurants-carousel" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
	<div class="center">
		<a class="link" href="restaurants-partenaire.html"><b>Voir</b> tous les restaurants</a>
	</div>
</div>
<div>
	<h2>Nos news <br />A la Une</h2>
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
					<div class="col-md-12" style="background-color : #F4F4F4; color : #000000; padding : 30px;">
						<h3><?php echo utf8_encode($news->titre); ?></h3>
						<div class="col-md-12">
							<img style="width : 100%" src="<?php echo $news->image; ?>" alt="HoMe Menus - <?php echo utf8_encode($news->titre); ?>" Title="HoMe Menus - <?php echo utf8_encode($news->titre); ?>">
						</div>
						<p><?php echo utf8_encode($news->text); ?></p>
						<a class="link" href="<?php echo $news->link_url; ?>"><?php echo $news->link_text; ?></a>
					</div>
				</div>
				<?php $index++; ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>