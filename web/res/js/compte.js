
$(function() {
	enableAutocomplete("full_address");
	$('[data-toggle="tooltip"]').tooltip();
	$("#subscribeForm").validate({
		rules: {
			confirm_password: {
				equalTo: "#password"
			}
		},
		messages: {
			nom: {
				required: "Le champ nom est requis"
			},
			prenom: {
				required: "Le champ prénom est requis"
			},
			login: {
				required: "Le champ login est requis"
			},
			password: {
				required: "Le champ mot de passe est requis"
			},
			confirm_password: {
				required: "Le champ confirmer mot de passe est requis",
				equalTo: "Les mots de passe sont différents"
			}
		}
	});
	$("#loginForm").validate({
		messages: {
			login: {
				required: "Le champ login est requis"
			},
			password: {
				required: "Le champ mot de passe est requis"
			}
		}
	});
	$("#subscribe-button").click(function(event) {
		var addressComponents = getAdresseElements();
		var fullAdresse = $("#full_address").val();
		if (addressComponents === false && fullAdresse != '') {
			$("#full_address").addClass( "error" );
			if ($("#full_address-error").length == 0) {
				$("#full_address").after('<label id="full_address-error" class="error" for="full_address">L\'adresse saisie est invalide</label>');
			}
			return false;
		}
		$("#full_address-error").remove();
		if (addressComponents !== false) {
			console.log(addressComponents);
			var rue = addressComponents.street_number + ' ' + addressComponents.route;
			var ville = addressComponents.locality;
			var code_postal = addressComponents.postal_code;
			
			$('#rue').val(rue);
			$('#ville').val(ville);
			$('#code_postal').val(code_postal);
		}
		$("#subscribeForm").submit();
	});
});