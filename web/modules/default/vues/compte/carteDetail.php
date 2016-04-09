<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<div class="vignette"><img style="float : left; width : 130px;" src="<?php echo $request->carte->logo; ?>"></div>
	<h2 class="modal-title"><?php echo utf8_encode($request->carte->nom); ?></h2>
	<?php if ($request->carte->commentaire != "") : ?>
		<div style="text-align : center;"><span>(<?php echo utf8_encode($request->carte->commentaire); ?>)</span></div>
	<?php endif; ?>
	<div style="clear : both;"></div>
</div>
<div class="modal-body">
	<form method="post" enctype="x-www-form-urlencoded" id="carteForm" action="">
		<input type="hidden" name="id_carte" value="<?php echo $request->carte->id; ?>" />
		<input type="hidden" name="id_restaurant" value="<?php echo $request->id_restaurant; ?>" />
		<div id="stepper" class="stepper" data-min-value="1" data-max-value="5">
			<label>Quantite</label>
			<a class="stepper-less stepper-button">-</a>
			<input type="text" name="quantite" value="0" class="stepper-value">
			<a class="stepper-more stepper-button">+</a>
		</div>
		<?php if (count($request->carte->formats) == 1) : ?>
			<?php $format = $request->carte->formats[0]; ?>
			<input type="hidden" name="format" value="<?php echo $format->id; ?>" />
			<div>
				<p>Prix : <?php echo $format->prix; ?> €</p>
			</div>
		<?php else : ?>
			<div class="row">
				<?php foreach ($request->carte->formats as $format) : ?>
					<div class="col-md-4">
						<div><input type="radio" name="format" value="<?php echo $format->id; ?>"><?php echo utf8_encode($format->nom); ?></div>
						<div>Prix : <?php echo $format->prix; ?> €</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<div class="row">
			<?php if (count($request->carte->options) > 0) : ?>
				<?php foreach ($request->carte->options as $option) : ?>
					<h3>Choisissez votre <?php echo utf8_encode($option->nom); ?> </h3>
					<?php foreach ($option->values as $value) : ?>
						<div>
							<input type="radio" name="check_option_<?php echo $option->id; ?>_<?php echo $value->id; ?>"/>
							<span><?php echo utf8_encode($value->nom); ?></span>
						</div>
					<?php endforeach; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if (count($request->carte->accompagnements) > 0) : ?>
				<h3>Accompagnement</h3>
				<?php foreach ($request->carte->accompagnements as $accompagnement) : ?>
					<?php $typeChoix = $accompagnement->limite == 1 ? 'radio' : 'checkbox'; ?>
					<?php foreach ($accompagnement->cartes as $carte) : ?>
						<div>
							<input type="<?php echo $typeChoix; ?>" name="check_accompagnement_<?php echo $carte->id; ?>"/>
							<span><?php echo utf8_encode($carte->nom); ?></span>
						</div>
					<?php endforeach; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if (count($request->carte->supplements) > 0) : ?>
				<h3>Suppléments</h3>
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
	</form>
</div>
<div class="modal-footer">
	<div style="display : none;" class="alert alert-success" role="alert">
		<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
		Votre produit a bien été ajouté au panier
	</div>
	<div style="display : none;" class="alert alert-danger" role="alert">
		<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
		<span class="sr-only">Error:</span>
		Veuillez vous connecter afin d'ajouter un produit au panier, <a data-toggle="modal" onclick="$('#carte-modal').modal('hide');" data-target="#login-modal">Connectez-vous</a>
	</div>
	<button id="addtocard" class="btn btn-primary" type="submit">Ajouter à la commande</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
	
	<span style="display : none;" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
</div>
<script type="text/javascript">
	initStepper ("stepper");
	$("#addtocard").click(function(event) {
		event.preventDefault();
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
			setTimeout(function(){ 
				$("#carte-modal").modal('hide');
			}, 3000);
		}).error(function(msg) {
			$("#carte-modal .modal-footer .glyphicon-refresh-animate").css('display', 'none');
			$("#carte-modal .modal-footer div.alert-danger").css('display', 'inline-block');
		});
	});
</script>