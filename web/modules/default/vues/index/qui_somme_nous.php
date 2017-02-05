<h1>HoMe Menus - Qui sommes-nous</h1>

<div class="row" style="background-color : #DDDDDD;">
	<div class="col-md-10 col-md-offset-1">
		<div class="row">
			<h2>L'entreprise</h2>
			<div>
				<p>HoMe Menus est un service de livraison de plats de vos restaurants préférés proches ou éloignés de chez vous.</p>
				<p>Notre objectif principal est de vous permettre de savourer et déguster la cuisine des meilleurs restaurants aux environs de chez vous 
				en vous les livrant. Génial non ?! évidemment !</p>
				<p>Nous choisissons méticuleusement les restaurants avec lesquels nous travaillons. Nous ne sélectionnons que les meilleurs restaurants, 
				mais plus particulièrement ceux que vous préférez ! Rejoignez la communauté afin de nous faire part de vos envies !</p>
				<p>HoMe Menus c’est également ses livreurs et la passion qui les poussent à réaliser un travail hors norme pour livrer vos envies à vélo, 
				en scooter, a moto, en voiture, contre vent et marée !</p>
			</div>
		</div>
		<div class="row">
			<h2>Nos restaurants partenaires</h2>
			<div>
				<p>HoMe Menus a sélectionné pour vous les meilleurs restaurants de votre région.<p>
				<p>Rendez-vous sur la page de <a class="link" href="restaurants-partenaire.html">nos restaurants partenaires</a> afin de voir tous les restaurants avec lesquels nous travaillons</p>
			</div>
		</div>
		<div class="row">
			<h2>Nous contactez</h2>
			<div class="row">
				<div class="col-md-8">
					<p>Vous avez une question. N'hésitez pas à nous joindre via notre <a class="link" href="contact.html">page de contact</a>.</p>
					<p>Vous souhaitez devenir livreur pour HoMe Menus. Postulez via notre <a class="link" href="contact-livreur.html">page de contact pour les livreurs</a>.</p>
					<p>Vous souhaitez devenir restaurant partenaire. Envoyez-nous votre demande via notre <a class="link" href="contact-restaurant.html">page de contact pour les restaurants</a>.</p>
					<p>Vous souhaitez créer un compte entreprise pour vos salariés. Envoyez-nous votre demande via notre <a class="link" href="contact-entreprise.html">page de contact pour les entreprises</a>.</p>
					<p>Vous souhaitez organiser un évènement. Contactez-nous via notre <a class="link" href="contact-evenement.html">page de contact</a>.</p>
					<p>Nous sommes également joignable par téléphone tous les jours de 10h à 23h au 06 61 45 97 33</p>
				</div>
				<div class="col-md-4">
					<h3>Nos locaux</h3>
					<div id="googleMap" class="col-md-10 col-md-offset-1" style="height:200px; margin-top : 10px;"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		initialize();
		var list = [];
		
		var restoPoint = {};
		restoPoint.type = "ADRESSE";
		restoPoint.adresse = "2 rue des aubépines, 78440 Issou";
		restoPoint.content = "HoMe Menus";
		
		list.push(restoPoint);
		
		boundToPoints(list);
	});
</script>

<style>

	#main-content h2 {
		color : #FF2A00;
	}

	#main-content div p {
		text-align : justify;
	}
	
</style>