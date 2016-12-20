<?php

	DEFINE ("ENVIRONNEMENT", "DEV");

	DEFINE ("GTM_INTERVAL", 1);

	/* path config */
	DEFINE ("ROOT_PATH", "D:/wamp/www/projets/homemenus/website/");
	DEFINE ("MODEL_PATH", ROOT_PATH."models/");
	DEFINE ("WEBSITE_PATH", ROOT_PATH."web/");
	DEFINE ("WEBSERVICES_PATH", ROOT_PATH."webservices/");
	
	/* WEB rootes */
	DEFINE ("WEBSITE_URL", "http://localhost/projets/homemenus/website/web/");

	/* Webservices config */
	DEFINE ("WS_URL", "http://localhost/projets/homemenus/website/webservice/");
	DEFINE ("WS_AUTH_LOGIN", "");
	DEFINE ("WS_AUTH_PASSWORD", "");
	
	/* MySQL config */
	DEFINE ("MYSQL_HOST", "localhost");
	DEFINE ("MYSQL_DBNAME", "homemenus");
	DEFINE ("MYSQL_LOGIN", "root");
	DEFINE ("MYSQL_PASSWORD", "");
	
	DEFINE ("MYSQL_TEST_DBNAME", "homemenus_test");
	DEFINE ("MYSQL_DEMO_DBNAME", "homemenus_demo");
	DEFINE ("MYSQL_RECETTE_DBNAME", "homemenus_recette");
	
	/* GCM config */
	DEFINE ("GOOGLE_API_KEY", "AIzaSyA2OFGXi3t5i1_mVyyHBw8OBp20ZsY9Lh0");
	
	/* recaptcha */
	DEFINE ("RECAPTCHA_SITE_KEY", "6LfchhsTAAAAADMbJz4UTn1TvRhwUSLq9tfA4zLt");
	DEFINE ("RECAPTCHA_SECRET_KEY", "6LfchhsTAAAAACbjpNAmPNm0j_1yuyhhY3HpL-T-");
	
	/* LOG config */
	DEFINE ("SQL_LOG", "SQL");
	DEFINE ("VIEW_LOG", "VIEW");
	DEFINE ("SERVER_LOG", "SERVER");
	DEFINE ("WS_LOG", "WS");
	DEFINE ("CRON_LOG", "CRON");
	DEFINE ("MAIL_LOG", "MAIL");
	
	DEFINE ("LOG_LEVEL_INFO", "INFO");
	DEFINE ("LOG_LEVEL_WARNING", "WARNING");
	DEFINE ("LOG_LEVEL_ERROR", "ERROR");
	
	/* Constante */
	DEFINE ("USER_ADMIN", "ADMIN");
	DEFINE ("USER_ADMIN_INFO", "ADMIN_INFO");
	DEFINE ("USER_ADMIN_CLIENT", "ADMIN_CLIENT");
	DEFINE ("USER_CLIENT", "USER");
	DEFINE ("USER_LIVREUR", "LIVREUR");
	DEFINE ("USER_ADMIN_RESTAURANT", "ADMIN_RESTAURANT");
	DEFINE ("USER_RESTAURANT", "RESTAURANT");
	DEFINE ("USER_ADMIN_ENTREPRISE", "ADMIN_ENTREPRISE");
	DEFINE ("USER_ENTREPRISE", "ENTREPRISE");
	
	/* MAIL */
	DEFINE ("SEND_MAIL", false);
	DEFINE ("MAIL_FROM_DEFAULT", "test@homemenus.fr");
	DEFINE ("MAIL_CONTACT", "test@homemenus.fr");
	DEFINE ("MAIL_RESTAURANT", "test@homemenus.fr");
	DEFINE ("MAIL_LIVREUR", "test@homemenus.fr");
	DEFINE ("MAIL_PRO", "test@homemenus.fr");
	DEFINE ("MAIL_ADMIN", "test@homemenus.fr");
	
	/* PAYPAL */
	DEFINE ("PAYPAL_ENV", "PROD");
	DEFINE ("PAYPAL_USER", "homemenus.inbox_api1.gmail.com");
	DEFINE ("PAYPAL_PASSWORD", "C29KYXQ9RNCB6ZLR");
	DEFINE ("PAYPAL_SIGNATURE", "AFcWxV21C7fd0v3bYYYRCpSSRl31AvgsNoywKGG.vIR-KOkSRKLCbGw2");
	
	/* STRIPE */
	DEFINE("STRIPE_PUBLIC_KEY", "pk_test_M0qVCl3qzju6XEHOi5jIDWg8");
	DEFINE("STRIPE_SECRET_KEY", "sk_test_lIqBT1ebwHGK3sN2xkHIX8kc");
	
	/* CLICKATELL */
	DEFINE ("SEND_SMS", false);
	DEFINE("CLICKATELL_REST_API_KEY", "g2qvIp01iGV16u7l9Kc9mhdRfU2gHeTaambzE1aIyXXEYHH3w9hUTWdxQcCaJ44GNHkOBgMmmUu");

?>