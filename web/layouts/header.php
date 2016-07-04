
<head>
	<title><?php echo $request->title; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="HoMe Menus, site de livraison à domicile de plats de restaurants dans les Yvelines 78 à Mantes la jolie. 
	Restaurants japonais, Italien, Indien, provençale."/>
	<meta http-equiv="Content-Language" content="fr" />
	<meta name="Publisher" content="HoMe Menus" />
	<?php if ($request->_noindex) : ?>
		<meta name="robots" content="none" />
		<meta name="googlebot" content="none" />
	<?php else : ?>
		<meta name="robots" content="all" />
	<?php endif; ?>
	<link rel="icon" href="res/img/favicon.ico" />
	<script type="text/javascript" src="res/js/jquery-2.1.1.js"></script>
	<script type="text/javascript" src="res/jquery-ui/jquery-ui.js"></script>
	<script type="text/javascript" src="res/js/site.js"></script>
	<script type="text/javascript" src="res/bootstrap/js/bootstrap.js"></script>
	<?php 
		if ($request->hasProperty('javascripts')) {
			foreach ($request->javascripts as $js) {
				echo '<script type="text/javascript" src="'.$js.'"></script>';
			}
		}
	?>
		
	<link rel="stylesheet" href="res/css/site.css" type="text/css"/>
	<link rel="stylesheet" href="res/css/live-chat.css" type="text/css"/>
	<link rel="stylesheet" href="res/jquery-ui/jquery-ui.css" type="text/css"/>
	<link rel="stylesheet" href="res/bootstrap/css/bootstrap.min.css" type="text/css"/>
	<?php
		if ($request->hasProperty('stylesheets')) {
			foreach ($request->stylesheets as $css) {
				echo '<link rel="stylesheet" href="'.$css.'" type="text/css"/>';
			}
		}
	?>
	<?php if (ENVIRONNEMENT == "PROD") : ?>
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-75124157-1', 'auto');
			ga('send', 'pageview');
		</script>
	<?php endif; ?>
</head>