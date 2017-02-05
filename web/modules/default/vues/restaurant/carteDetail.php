<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h2 class="modal-title"><?php echo utf8_encode($request->carte->nom); ?></h2>
	<?php if ($request->carte->commentaire != "") : ?>
		<div style="text-align : center;"><span><i><?php echo utf8_encode($request->carte->commentaire); ?></i></span></div>
	<?php endif; ?>
</div>
<div class="modal-body">
	<form method="post" enctype="x-www-form-urlencoded" id="carteForm" action="">
		<input type="hidden" name="id_carte" value="<?php echo $request->carte->id; ?>" />
		<input type="hidden" name="id_restaurant" value="<?php echo $request->id_restaurant; ?>" />
		<?php if (count($request->carte->formats) == 1) : ?>
			<?php $format = $request->carte->formats[0]; ?>
			<input type="hidden" name="format" value="<?php echo $format->id; ?>" />
			<div>
				<p style="text-align : center"><b><?php echo formatPrix($format->prix); ?></b></p>
			</div>
		<?php else : ?>
			<div>
				<?php foreach ($request->carte->formats as $format) : ?>
					<div class="row center">
						<input type="radio" name="format" value="<?php echo $format->id; ?>"><span style="margin-left : 10px;"><?php echo utf8_encode($format->nom); ?></span>
						<b><?php echo formatPrix($format->prix); ?></b>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<div class="row" style="margin-bottom : 50px; margin-right : auto; margin-left : auto;">
			<?php if (count($request->carte->options) > 0) : ?>
				<?php foreach ($request->carte->options as $option) : ?>
					<span><b>Choisissez votre <?php echo utf8_encode($option->nom); ?> </b></span>
					<input type="hidden" class="option_ids" value="<?php echo $option->id; ?>" />
					<?php foreach ($option->values as $value) : ?>
						<div style="margin-left : 20px;">
							<input type="radio" name="check_option_<?php echo $option->id; ?>" value="<?php echo $value->id; ?>"/>
							<span><?php echo utf8_encode($value->nom); ?></span>
						</div>
					<?php endforeach; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if (count($request->carte->accompagnements) > 0) : ?>
				<span><b>Accompagnement</b></span>
				<?php foreach ($request->carte->accompagnements as $accompagnement) : ?>
					<?php $typeChoix = $accompagnement->limite == 1 ? 'radio' : 'checkbox'; ?>
					<?php foreach ($accompagnement->cartes as $carte) : ?>
						<div>
							<input type="<?php echo $typeChoix; ?>" name="check_accompagnement_<?php echo $accompagnement->id; ?>" value="<?php echo $carte->id; ?>"/>
							<span><?php echo utf8_encode($carte->nom); ?></span>
						</div>
					<?php endforeach; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if (count($request->carte->supplements) > 0) : ?>
				<span><b>Suppléments</b></span>
				<?php foreach ($request->carte->supplements as $supplement) : ?>
					<div>
						<input type="checkbox" name="check_supplement_<?php echo $supplement->id; ?>"/>
						<span><?php echo utf8_encode($supplement->nom); ?></span>
						<span><?php echo $supplement->prix; ?> €</span>
						<?php if ($supplement->commentaire != "") : ?>
							<span>(<?php echo utf8_encode($supplement->commentaire); ?>)</span>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<div id="stepper" class="stepper" data-min-value="1" data-max-value="5">
			<a class="stepper-less stepper-button">-</a>
			<input type="text" name="quantite" value="0" class="stepper-value">
			<a class="stepper-more stepper-button">+</a>
		</div>
	</form>
</div>
<div class="modal-footer">
	<div style="display : none;" class="alert alert-success" role="alert">
		<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
		Votre produit a bien été ajouté au panier
	</div>
	<div class="row">
		<div class="col-md-6 col-sm-6 center">
			<?php if ($request->has_livreur_dispo === false) : ?>
				<div class="alert alert-danger" role="alert" style="text-align: center;">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					Il n'y a aucun livreur disponible pour vous livrer ce restaurant
				</div>
			<?php elseif ($request->_id_restaurant_panier !== false && $request->id_restaurant != $request->_id_restaurant_panier) : ?>
				<div class="alert alert-danger" role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					Vous avez une commande en cours dans un autre restaurant. Veuillez finaliser votre commande avant de commander dans ce restaurant
				</div>
			<?php else : ?>
				<button id="addtocard" class="validate-button" type="submit">Ajouter au panier</button>
			<?php endif; ?>
		</div>
		<div class="col-md-6 col-sm-6 center">
			<button type="button" class="close-button" data-dismiss="modal">Fermer <span class="glyphicon glyphicon-remove" aria-hidden="true"></button>
		</div>
	</div>
	<span style="display : none;" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
</div>
<script type="text/javascript">
	initStepper ("stepper");
	$("#addtocard").click(function(event) {
		event.preventDefault();
		var format = '';
		if ($('input[name=format]').is(':radio')) {
			if ($('input[name=format]').is(':checked')) {
				format = $("input[name=format]:checked").val();
				console.log(format);
			}
		} else {
			format = $('input[name=format]').val();
		}
		if (format == '') {
			alert('Veuillez choisir le format');
			return;
		}
		var optionChecked = true;
		$( ".option_ids" ).each(function(index) {
			var option_id = $(this).val();
			if (!$('input[name=check_option_'+option_id+']').is(':checked')) {
				optionChecked = false;
			}
		});
		if (!optionChecked) {
			alert('Vous n\'avez pas choisi vos option.');
			return;
		}
		$("#addtocard").css('display', 'none');
		$("#carte-modal .modal-footer .glyphicon-refresh-animate").css('display', 'inline-block');
		$.ajax({
			type: "POST",
			url: "?controler=panier&action=addCarte",
			dataType: "html",
			data: $("#carteForm").serialize()
		}).done(function( msg ) {
			$("#carte-modal .modal-footer .glyphicon-refresh-animate").css('display', 'none');
			$("#carte-modal .modal-footer div.alert-success").css('display', 'inline-block');
			reloadPanier ();
			setTimeout(function(){ 
				$("#carte-modal").modal('hide');
				//location.reload();
			}, 2000);
		}).error(function(msg) {
			$("#carte-modal .modal-footer .glyphicon-refresh-animate").css('display', 'none');
			$("#carte-modal .modal-footer div.alert-danger").css('display', 'inline-block');
		});
	});
</script>