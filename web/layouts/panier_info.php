<div id="panier-info-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Veuillez renseignez vos informations de livraison</h4>
			</div>
			<div class="modal-body">
				<form method="post" enctype="x-www-form-urlencoded" id="panierInfoForm" action="?controler=panier&action=validate">
					<input id="type_commande" name="type" value="" hidden="hidden" />
					<input id="heure_commande" name="heure_commande" value="-1" hidden="hidden" />
					<input id="minute_commande" name="minute_commande" value="0" hidden="hidden" />
					<div class="form-group">
						<label for="login">Rue<span class="required">*</span> : </label>
						<input id="rue_field" class="form-control" name="rue" type="text" required>
					</div>
					<div class="form-group">
						<label for="login">Code postal<span class="required">*</span> : </label>
						<input id="cp_field" class="form-control" name="code_postal" type="text" required>
					</div>
					<div class="form-group">
						<label for="login">Ville<span class="required">*</span> : </label>
						<input id="ville_field" class="form-control" name="ville" type="text" required>
					</div>
					<div class="form-group">
						<label for="login">Téléphone<span class="required">*</span> : </label>
						<input id="phone_field" class="form-control" name="telephone" type="text" required>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button id="validationButton" class="btn btn-primary" type="submit">Valider</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				<div style="display : none; text-align: center;" class="alert alert-danger" role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					<span class="message"></span>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#validationButton").click(function(event) {
		var rue = $("#rue_field").val();
		var cp = $("#cp_field").val();
		var ville = $("#ville_field").val();
		var phone = $("#phone_field").val();
		var type_commande = $("#type_commande").val();
		var heure_commande = $("#phone_field").val();
		var phone = $("#phone_field").val();
		if (rue.trim() == '' || cp.trim() == '' || ville.trim() == '' || phone.trim() == '') {
			alert("veuillez remplir tous les champs");
		} else {
			showLoading('validationButton', true);
			$.ajax({
				type: "POST",
				url: "?controler=panier&action=validate",
				dataType: "html",
				data: $("#panierInfoForm").serialize()
			}).done(function( msg ) {
				var data = $.parseJSON(msg);
				console.log(data);
				if (data.distance < 15) {
					document.location.href = "?controler=panier&action=finalisation";
				} else {
					$("#panier-info-modal .modal-footer div.alert-danger span.message").html("Nous ne pouvons vous livrer ce restaurant car votre adresse se trouve à plus de 15km du restaurant (" + data.distance + " km)");
					$("#panier-info-modal .modal-footer div.alert-danger").css('display', 'block');
					hideLoading('validationButton', true);
				}
			}).error(function(jqXHR, textStatus, errorThrown) {
				switch (jqXHR.status) {
					case 404 :
						$("#panier-info-modal .modal-footer div.alert-danger span.message").html("L'adresse saisie est invalide");
						break;
					default :
						$("#panier-info-modal .modal-footer div.alert-danger span.message").html("Une erreur est survenu, veuillez réessayé.");
						break;
				}
				$("#panier-info-modal .modal-footer div.alert-danger").css('display', 'block');
				hideLoading('validationButton', true);
			});
			
		}
	});
</script>