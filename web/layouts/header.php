
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="icon" href="res/img/favicon.ico" />
	<title><?php echo $request->title; ?></title>
	
	<script type="text/javascript" src="res/js/jquery-2.1.1.js"></script>
	<script type="text/javascript" src="res/jquery-ui/jquery-ui.js"></script>
	<script type="text/javascript" src="res/js/site.js"></script>
	<script type="text/javascript" src="res/bootstrap/js/bootstrap.js"></script>
	<script type="text/javascript" src="res/js/bootstrap-star-rating.js"></script>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places"></script>
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
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-73886326-1', 'auto');
	  ga('send', 'pageview');
	</script>
</head>