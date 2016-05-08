
$(function() {
	$("#contactForm").validate({
		messages: {
			restaurant: {
				required: "Le nom de restaurant est requis"
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
			fonction: {
				required: "La fonction est requise"
			}
		}
	});
});