
$(function() {
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
	/*$("#subscribe-button").click(function(event) {
		$("#subscribeForm").submit();
	});*/
});