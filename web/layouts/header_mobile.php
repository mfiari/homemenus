
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="HoMe Menus, livraison à domicile de plats de restaurants dans les Yvelines 78 à Mantes la jolie. 
	Restaurants japonais, Italien, Indien, provençale."/>
	<meta name="viewport" content="width=device-width"/>
	<link rel="icon" href="res/img/favicon.ico" />
	<title><?php echo $request->title; ?></title>
	<script type="text/javascript" src="res/js/jquery-2.1.1.js"></script>
	<script type="text/javascript" src="res/jquery-ui/jquery-ui.js"></script>
	<script type="text/javascript" src="res/js/site.js"></script>
	<script type="text/javascript" src="res/bootstrap/js/bootstrap.js"></script>
	<script type="text/javascript" src="res/js/bootstrap-star-rating.js"></script>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>
	<?php 
		if ($request->hasProperty('javascripts')) {
			foreach ($request->javascripts as $js) {
				echo '<script type="text/javascript" src="'.$js.'"></script>';
			}
		}
	?>
		
	<link rel="stylesheet" href="res/jquery-ui/jquery-ui.css" type="text/css"/>
	<link rel="stylesheet" href="res/bootstrap/css/bootstrap.min.css" type="text/css"/>
	<link rel="stylesheet" href="res/css/site-mobile.css" type="text/css"/>
	<?php
		if ($request->hasProperty('stylesheets')) {
			foreach ($request->stylesheets as $css) {
				echo '<link rel="stylesheet" href="'.$css.'" type="text/css"/>';
			}
		}
	?>
	<?php if (ENVIRONNEMENT == "PROD") : ?>
		<!-- Google Analytics Tracking Code for https://homemenus.fr -->
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-75124157-1', 'auto');
			ga('send', 'pageview');
		</script>
		<?php if (!$request->_auth || ($request->_auth && $request->_auth->status !== USER_ADMIN)) : ?>
			<!-- Hotjar Tracking Code for https://homemenus.fr -->
			<script>
				(function(h,o,t,j,a,r){
					h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
					h._hjSettings={hjid:264499,hjsv:5};
					a=o.getElementsByTagName('head')[0];
					r=o.createElement('script');r.async=1;
					r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
					a.appendChild(r);
				})(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
			</script>
		<?php endif; ?>
	<?php endif; ?>
</head>