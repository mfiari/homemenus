<div class="background"></div>
<div class="gradient"></div>
<header class="home">
	<div class="container">
		<div>
			<a href="?action=index">
				<img id="logo" src="res/img/logo.png">
			</a>
			<ul class="menu">
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
							<li><a href="?controler=user"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>Mon profil</a></li>
							<li><a href="?controler=commande"><span class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></span>mes commandes<span class="badge"></span></a></li>
							<li><a href="?action=logout"><span class="glyphicon glyphicon-off" aria-hidden="true"></span>deconnexion</a></li>
						</ul>
					</li>
				<?php else : ?>
					<li><a href="?action=inscription">Inscription</a></li>
					<li><a data-toggle="modal" data-target="#login-modal">Connexion</a></li>
					<li><a href="?controler=contact">Contact</a></li>
				<?php endif; ?>
			</ul>
		</div>
		<div>
			<h1>Vos envies sont servis</h1>
		</div>
		<div id="adress-search" class="col-md-8 col-md-offset-2">
			<form id="adress-form" action="?controler=restaurant&action=recherche" method="POST">
				<div class="input-group">
					<?php
						$adresse = "";
						if ($request->_auth) {
							$adresse = $request->_auth->rue.', '.$request->_auth->code_postal.' '.$request->_auth->ville;
						}
					?>
					<div class="search-block">
						<input id="full_address" class="form-control" name="adresse" type="text" value="<?php echo $adresse; ?>" placeholder="Entrez votre adresse">
						<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
					</div>
					<span class="input-group-btn">
						<button class="btn btn-primary" type="submit">Trouvez votre restaurant</button>
					</span>
				</div>
			</form>
		</div>
	</div>
</header>
