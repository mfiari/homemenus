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
				<img id="logo" src="res/img/logo.png">
			</a>
			<img id="slogan" src="res/img/slogan.png">
		</div>
		<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
			<ul class="menu nav navbar-nav">
				<li><a href="?action=index">Accueil</a></li>
				<li>
					<a href="#" data-toggle="dropdown" class="dropdown-toggle">
						<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="?controler=compte"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>Mon profil</a></li>
						<li><a href="?controler=commande"><span class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></span>mes commandes</a></li>
						<li><a href="?controler=commande"><span class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></span>Résultats</a></li>
						<li><a href="?action=logout"><span class="glyphicon glyphicon-off" aria-hidden="true"></span>deconnexion</a></li>
					</ul>
				</li>
			</ul>
		</nav>
	</div>
</header>


