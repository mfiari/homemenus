<div id="forgot-password-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">J'ai oublié mon mot de passe</h4>
			</div>
			<div class="modal-body">
				<p>Saisissez votre identifiant (adresse mail). Vous recevrez ensuite un mail vous permettant de renouveler votre mot de passe</p>
				<form method="post" enctype="x-www-form-urlencoded" id="forgotPasswordForm" action="">
					<div class="form-group">
						<label for="login">email<span class="required">*</span> : </label>
						<input id="login_field" class="form-control" name="login" type="text" required>
					</div>
					<button id="submitButton" class="btn btn-primary" type="submit">Envoyer</button>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#forgotPasswordForm").submit(function(event) {
		event.preventDefault();
		showLoading('submitButton', true);
		$.ajax({
			type: "POST",
			url: "?controler=compte&action=forgot_password",
			dataType: "html",
			data: $("#forgotPasswordForm").serialize()
		}).done(function( msg ) {
			
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
			$("#login-modal .modal-footer .glyphicon-refresh-animate").css('display', 'none');
			$("#login-modal .modal-footer div.alert-danger").css('display', 'block');
			$("#loginButton").css('display', 'block');
		});
	});
</script>