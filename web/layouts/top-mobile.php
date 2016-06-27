<header class="navbar navbar-inverse navbar-fixed-top" role="banner">
	<div class="navbar-header">
		<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a href="?action=index" class="logo">
			<img id="logo" src="res/img/logo.png" alt="HoMe Menus - livraison - logo" title="logo HoMe Menus">
		</a>
	</div>
	<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
		<ul class="menu nav navbar-nav">
			<li><a href="?action=index">Accueil</a></li>
			<?php if ($request->_auth) : ?>
				<li><a href="?controler=contact">Contact</a></li>
				<li><a id="cardMenu">
					<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
					<?php if ($request->_itemsPanier && $request->_itemsPanier > 0) : ?>
						<span class="badge"><?php echo $request->_itemsPanier; ?></span>
					<?php endif; ?>
				</a></li>
				<li>
					<a href="#" data-toggle="dropdown" class="dropdown-toggle">
						<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
						<span class="badge"></span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="?controler=compte"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>Mon profil</a></li>
						<li><a href="?controler=commande"><span class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></span>Mes commandes<span class="badge"></span></a></li>
						<li><a href="?action=logout"><span class="glyphicon glyphicon-off" aria-hidden="true"></span>Déconnexion</a></li>
					</ul>
				</li>
				<li><a href="?action=faq">FAQ</a></li>
			<?php else : ?>
				<li><a href="?action=inscription">Inscription</a></li>
				<li><a data-toggle="modal" data-target="#login-modal">Connexion</a></li>
				<li><a href="?controler=contact">Contact</a></li>
				<li><a href="?action=faq">FAQ</a></li>
			<?php endif; ?>
		</ul>
	</nav>
</header>