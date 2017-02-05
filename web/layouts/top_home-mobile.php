<div class="home-background"></div>
<div class="gradient"></div>
<header class="home">
	<div class="container">
		<div>
			<a href="index.html" class="logo">
				<img id="logo" src="res/img/logo.png" alt="HoMe Menus - livraison - logo" title="logo HoMe Menus">
			</a>
		</div>
		<ul class="menu">
			<li><a href="contact.html">Contact</a></li>
			<li><a href="faq.html">FAQ</a></li>
			<li class="user"><a href="contact.html"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a></li>
		</ul>
		<div style="margin-top: 400px;">
			<h1 style="font-size : 70px">Vous aussi, profitez de la livraison de repas à domicile</h1>
		</div>
		<div style="color : #FFFFFF; font-size : 50px; margin-top: 50px; margin-bottom: 50px; text-align : center;">
			<span style="background-color: rgba(255, 42, 0, 0.7); padding: 2px 10px;">région de Mantes-la-Jolie</span>
		</div>
		<div id="adress-search">
			<form id="adress-form" action="?controler=restaurant&action=recherche" method="POST">
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
