<div id="login-modal">
	<div class="col-md-10  col-md-offset-1">
		<h2>Connexion</h2>
		<form method="post" enctype="x-www-form-urlencoded" id="loginForm" action="?action=login">
			<div class="form-group">
				<label for="login">Identifiant<span class="required">*</span> : </label>
				<input id="login_field" class="form-control" name="login" type="text" required>
			</div>
			<div class="form-group">
				<label for="password">Mot de passe<span class="required">*</span> : </label>
				<input id="password_field" class="form-control" name="password" type="password" required>
			</div>
			<button id="loginButton" class="validate-button" type="submit">Connexion</button>
		</form>
	</div>
	<div style="margin-top : 10px; margin-bottom : 10px;">
		<span>Vous n'avez pas de compte ? <a href="?action=inscription">Inscrivez-vous</a>.</span><br />
		<div style="display : none; text-align: center;" class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<span class="sr-only">Error:</span>
			<span class="message"></span>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#loginForm").submit(function(event) {
		event.preventDefault();
		showLoading('loginButton', true);
		$.ajax({
			type: "POST",
			url: "?action=login",
			dataType: "html",
			data: $("#loginForm").serialize()
		}).done(function( msg ) {
			document.location.href = "?action=index";
		}).error(function(jqXHR, textStatus, errorThrown) {
			switch (jqXHR.status) {
				case 400 :
					$("#login-modal .modal-footer div.alert-danger span.message").html("Login ou mot de passe vide");
					break;
				case 403 :
					$("#login-modal .modal-footer div.alert-danger span.message").html("<p>Votre compte est désactivé</p><p>Vérifiez dans votre boite mail que vous avez bien reçu le mail de confirmation d'inscription</p><p>Sinon <a href='?controler=contact'>contactez-nous</a></p>");
					break;
				case 404 :
					$("#login-modal .modal-footer div.alert-danger span.message").html("Login ou mot de passe incorrect");
					break;
				default :
					$("#login-modal .modal-footer div.alert-danger span.message").html("Une erreur est survenu, veuillez réessayé.");
					break;
			}
			$("#login-modal .modal-footer div.alert-danger").css('display', 'block');
			hideLoading('loginButton', true);
		});
	});
</script>