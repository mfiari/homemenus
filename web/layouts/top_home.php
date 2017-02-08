<div class="home-background"></div>
<div class="gradient"></div>
<header class="home">
	<div class="container">
		<div>
			<a href="index.html" class="logo">
				<img id="logo" src="res/img/logo.png" alt="HoMe Menus - livraison - logo" title="logo HoMe Menus">
			</a>
			<div id="menu-link">
				<a href="contact-livreur.html">Devenir livreur</a>
				<a href="contact-restaurant.html">Devenir restaurant partenaire</a>
			</div>
			<ul class="menu">
				<li><a href="contact.html">Contact</a></li>
				<li><a href="faq.html">FAQ</a></li>
				<?php if ($request->_auth) : ?>
					<li class="user"><a href="index.php?controler=compte"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a></li>
				<?php else : ?>
					<li class="user"><a data-toggle="modal" data-target="#login-modal"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a></li>
				<?php endif; ?>
				<li style="color : #FF2A00;"><a id="cardMenu">
					<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
					<?php if ($request->_itemsPanier && $request->_itemsPanier > 0) : ?>
						<span class="badge"><?php echo $request->_itemsPanier; ?></span>
					<?php endif; ?>
				</a></li>
				<?php if ($request->_auth) : ?>
					<li style="color : #FF2A00;"><a href="index.php?action=logout"><span class="glyphicon glyphicon-off" aria-hidden="true"></span></a></li>
				<?php endif; ?>
			</ul>
		</div>
		<div style="margin-top: 100px;">
			<h1>Vous aussi, profitez de la livraison<br />de repas à domicile</h1>
		</div>
		<div id="adress-search">
			<form id="adress-form" action="restaurants.html" method="POST">
				<div class="search-block">
					<?php
						$adresse = "";
						if (($request->_auth) && $request->_auth->parametre->default_adresse_search) {
							if ($request->_auth->rue != '') {
								$adresse = utf8_encode($request->_auth->rue).', '.$request->_auth->code_postal.' '.utf8_encode($request->_auth->ville);
							}
						}
					?>
					<a class="search-button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>
					<input id="full_address" class="form-control" name="adresse" type="text" placeholder="Saisissez votre adresse" value="<?php echo $adresse;?>">
				</div>
			</form>
		</div>
		<div style="color : #FFFFFF; margin-top: 50px; text-align : center;">
			<p>Cherchez parmis une selection de restaurants</p>
			<span style="background-color: rgba(255, 42, 0, 0.7); font-weight : bold; padding: 2px 10px;">dans la région de Mantes-la-Jolie</span>
		</div>
	</div>
</header>
