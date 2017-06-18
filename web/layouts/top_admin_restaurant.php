<header class="navbar navbar-inverse navbar-fixed-top" role="banner">
	<div class="container">
		<div class="navbar-header" style="width : 300px;">
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="?action=index" class="logo">
				<img id="logo" src="res/img/logo-min.png">
			</a>
			<img style="height : 50px;" src="<?php echo getLogoRestaurant ($request->_restaurant->id); ?>">
		</div>
		<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
			<ul class="menu nav navbar-nav">
				<li><a href="?action=index">Administration</a></li>
				<li><a href="?controler=commande">Commandes</a></li>
				<li><a href="?controler=restaurant">Carte</a></li>
				<li><a href="?controler=compte">Mon compte</a></li>
				<li><a href="?action=logout">deconnexion</a></li>
			</ul>
		</nav>
	</div>
</header>

