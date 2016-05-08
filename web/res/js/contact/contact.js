
$(function() {
	$("#contactForm").validate({
		messages: {
			email: {
				required: "Le champ email est requis",
				email: "Veuillez entrez une adresse email valide."
			},
			message: {
				required: "Le champ message est requis"
			}
		}
	});
});