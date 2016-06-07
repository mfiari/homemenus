<?php 

	require_once WEBSITE_PATH.'res/lib/stripe/init.php';

	if (isset($_POST['stripeToken'])) {
		\Stripe\Stripe::setApiKey("sk_live_SMEkTXt4kmuUgkQoBdGBSaOD");

		// Get the credit card details submitted by the form
		$token = $_POST['stripeToken'];

		// Create the charge on Stripe's servers - this will charge the user's card
		try {
		  $charge = \Stripe\Charge::create(array(
			"amount" => 100, // amount in cents, again
			"currency" => "eur",
			"source" => $token,
			"description" => "Example charge"
			));
			echo '<div class="alert alert-success" role="alert">
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
				Paiement accepté
			</div>';
		} catch(\Stripe\Error\Card $e) {
			echo '<div class="alert alert-danger" role="alert">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				<span class="sr-only">Error:</span>
				Paiement refusé
			</div>';
		}
	}

?>

<form action="" method="POST">
  <script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
	data-label="Payer en carte"
    data-key="pk_live_BHrLI0OtgE1bdpDgBsHJJV1u"
    data-amount="100"
    data-name="HoMe Menus"
    data-description="Paiement de la commande #1"
    data-image="/web/res/img/logo_mail.png"
    data-locale="auto"
    data-zip-code="true"
    data-currency="eur">
  </script>
</form>