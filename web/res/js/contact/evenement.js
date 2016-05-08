
$(function() {
	$("#contactForm").validate({
		messages: {
			entreprise: {
				required: "Le nom de l'entreprise est requis"
			},
			code_postal: {
				required: "Le code postal est requis"
			},
			ville: {
				required: "La ville est requise"
			},
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
			},
			fonction: {
				required: "La fonction est requise"
			}
		}
	});
});