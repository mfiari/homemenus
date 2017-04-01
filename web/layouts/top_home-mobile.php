<div class="home-background"></div>
<div class="gradient"></div>
<header class="home">
	<div class="container">
		<nav class="navbar navbar-inverse" style="background-color : transparent; border-color : transparent;">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a href="index.html" class="navbar-brand logo">
						<img id="logo" src="res/img/logo.png" alt="HoMe Menus - livraison - logo" title="logo HoMe Menus">
					</a>
				</div>
				<div id="navbar" class="collapse navbar-collapse">
					<ul class="nav navbar-nav" style="background-color : #222; border-color : #080808;">
						<li><a href="contact.html">Contact</a></li>
						<li><a href="faq.html">FAQ</a></li>
						<li><a href="faq.html">Compte</a></li>
						<li><a href="index.php?controler=restaurant&action=panier">Panier</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</nav>
		<div style="margin-top: 20px;">
			<h1 style="font-size : 25px">Vous aussi, profitez de la livraison de repas à domicile</h1>
		</div>
		<div style="color : #FFFFFF; font-size : 15px; margin-top: 30px; margin-bottom: 30px; text-align : center;">
			<span style="background-color: rgba(255, 42, 0, 0.7); padding: 2px 10px;">région de Mantes-la-Jolie</span>
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
	</div>
</header>
