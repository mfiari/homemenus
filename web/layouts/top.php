<div class="background"></div>
<header>
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
					<li class="user"><a href="?controler=compte"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a></li>
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
	</div>
</header>
