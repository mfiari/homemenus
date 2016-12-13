<header class="navbar navbar-inverse navbar-fixed-top" role="banner">
	<div class="container">
		<div class="navbar-header">
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="?action=index" class="logo">
				<img id="logo" src="res/img/logo.png">
			</a>
		</div>
		<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
			<ul class="menu nav navbar-nav">
				<li><a href="?action=stats">Resultats</a></li>
				<li><a href="?controler=recherche">recherches</a></li>
				<li><a href="?controler=restaurant">Restaurants</a></li>
				<li><a href="?controler=user&action=livreurs">Livreurs</a></li>
				<li><a href="?controler=user&action=clients">Clients</a></li>
				<li><a href="?controler=panier">Panier</a></li>
				<li><a href="?controler=commande">Commandes</a></li>
				<li>
					<a href="#" data-toggle="dropdown" class="dropdown-toggle">
						<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="?controler=codePromo">code promo</a></li>
						<li><a href="?controler=mail">mails</a></li>
						<li><a href="?controler=mail&action=emailing">emailing</a></li>
						<li><a href="?controler=sms">SMS</a></li>
						<li><a href="?controler=sms&action=smsling">SMSling</a></li>
						<li><a href="?controler=commentaire">Commentaire</a></li>
						<li><a href="?action=logout">deconnexion</a></li>
					</ul>
				</li>
			</ul>
		</nav>
	</div>
</header>

