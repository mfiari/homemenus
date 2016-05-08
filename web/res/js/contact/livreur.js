
$(function() {
	$("#contactForm").validate({
		messages: {
			nom: {
				required: "Le nom est requis"
			},
			prenom: {
				required: "Le prénom est requis"
			},
			telephone: {
				required: "Le numéro de téléphone est requis"
			},
			email: {
				required: "Le champ email est requis",
				email: "Veuillez entrez une adresse email valide."
			}
		}
	});
});