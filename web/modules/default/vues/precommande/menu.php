<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h2 class="modal-title"><?php echo utf8_encode($request->menu->nom); ?></h2>
	<?php if ($request->menu->commentaire != "") : ?>
		<span>(<?php echo utf8_encode($request->menu->commentaire); ?>)</span>
	<?php endif; ?>
</div>
<div class="modal-body">
	<form method="post" enctype="x-www-form-urlencoded" id="menuForm" action="">
		<input type="hidden" name="id_menu" value="<?php echo $request->menu->id; ?>" />
		<div id="stepper" class="stepper" data-min-value="1" data-max-value="5">
			<label>Quantite</label>
			<a class="stepper-less stepper-button">-</a>
			<input type="text" name="quantite" value="0" class="stepper-value">
			<a class="stepper-more stepper-button">+</a>
		</div>
		<div class="row">
			<?php if (count($request->menu->formats) == 1) : ?>
				<?php $format = $request->menu->formats[0]; ?>
				<input type="hidden" name="id_format" value="<?php echo $format->id; ?>" />
				<div>
					<p>Prix : <?php echo $format->prix; ?> €</p>
				</div>
			<?php else : ?>
				<?php foreach ($request->menu->formats as $format) : ?>
					<div class="col-md-4">
						<div><input name="id_format" type="radio" value="<?php echo $format->id; ?>"  /><?php echo utf8_encode($format->nom); ?></div>
						<div>Prix : <?php echo $format->prix; ?> €</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if (count($request->menu->formules) == 1) : ?>
				<?php $formule = $request->menu->formules[0]; ?>
				<div class="col-md-12">
					<div style="font-size: 16px; font-weight : bold;"><input name="id_formule" type="hidden" value="<?php echo $formule->id; ?>"  /><?php echo utf8_encode($formule->nom); ?></div>
					<?php foreach ($formule->categories as $categorie) : ?>
						<div class="col-md-12">
							<div style="font-weight : bold;"><?php echo utf8_encode($categorie->nom); ?></div>
							<?php foreach ($categorie->contenus as $contenu) : ?>
								<div class="col-md-12">
									<div>
										<input class="contenu_choice" name="contenu_<?php echo $categorie->id; ?>" type="radio" value="<?php echo $contenu->id; ?>" data-formule="<?php echo $formule->id; ?>" data-categorie="<?php echo $categorie->id; ?>"  />
										<?php echo utf8_encode($contenu->carte->nom); ?>
										<?php if ($contenu->limite_supplement != 0) : ?>
											<div id="contenu_supplement_<?php echo $contenu->id; ?>" class="contenu_supplement_<?php echo $categorie->id; ?>" style="display : none;">
												<div class="col-md-12">
													<?php $typeInputSplmt = $contenu->limite_supplement == 1 ? 'radio' : 'checkbox'; ?>
													<?php foreach ($contenu->carte->supplements as $supplement) : ?>
														<div>
															<input class="" name="splmt_<?php echo $contenu->id; ?>_contenu_<?php echo $categorie->id; ?>" type="<?php echo $typeInputSplmt; ?>" value="<?php echo $supplement->id; ?>"  />
															<?php echo utf8_encode($supplement->nom); ?>
														</div>
													<?php endforeach; ?>
												</div>
											</div>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<?php foreach ($request->menu->formules as $formule) : ?>
					<div class="col-md-6">
						<div style="font-size: 16px; font-weight : bold;"><input name="id_formule" class="formule_choice" type="radio" value="<?php echo $formule->id; ?>"  /><?php echo utf8_encode($formule->nom); ?></div>
						<div id="contenu_formule_<?php echo $formule->id; ?>" class="formule_contenu" style="display : none;">
							<?php foreach ($formule->categories as $categorie) : ?>
								<div class="col-md-12">
									<div style="font-weight : bold;"><?php echo utf8_encode($categorie->nom); ?></div>
									<?php foreach ($categorie->contenus as $contenu) : ?>
										<div class="col-md-12">
											<div>
												<input class="contenu_choice" name="contenu_<?php echo $categorie->id; ?>" type="radio" value="<?php echo $contenu->id; ?>"  />
												<?php echo utf8_encode($contenu->carte->nom); ?>
												<?php if ($contenu->limite_supplement != 0) : ?>
													<div id="contenu_supplement_<?php echo $contenu->id; ?>" class="contenu_supplement" style="display : none;">
														<div class="col-md-12">
															<?php $typeInputSplmt = $contenu->limite_supplement == 1 ? 'radio' : 'checkbox'; ?>
															<?php foreach ($contenu->carte->supplements as $supplement) : ?>
																<div>
																	<input class="" name="splmt_<?php echo $contenu->id; ?>_contenu_<?php echo $categorie->id; ?>" type="<?php echo $typeInputSplmt; ?>" value="<?php echo $supplement->id; ?>"  />
																	<?php echo utf8_encode($supplement->nom); ?>
																</div>
															<?php endforeach; ?>
														</div>
													</div>
												<?php endif; ?>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endforeach; ?>
						</div>
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
		Veuillez vous connecter afin d'ajouter un produit au panier
	</div>
	<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
	<?php if ($request->has_livreur_dispo === false) : ?>
		<div class="alert alert-danger" role="alert">
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
		<button id="addtocard" class="btn btn-primary" type="submit">Ajouter au panier</button>
	<?php endif; ?>
	<span style="display : none;" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
</div>
<script type="text/javascript">
	initStepper ("stepper");
	$(".formule_choice").click(function () {
		$(".formule_contenu").hide();
		$('.formule_contenu input[type="radio"]').each(function() {
			$(this).prop('checked', false);
		});
		var val = $(this).val();
		$("#contenu_formule_"+val).show();
	});
	$(".contenu_choice").click(function () {
		$(".contenu_supplement").hide();
		$('.contenu_supplement input[type="radio"]').each(function() {
			$(this).prop('checked', false);
		});
		$('.contenu_supplement input[type="checkbox"]').each(function() {
			$(this).prop('checked', false);
		});
		var val = $(this).val();
		$("#contenu_supplement_"+val).show();
	});
	$("#addtocard").click(function(event) {
		event.preventDefault();
		$("#addtocard").css('display', 'none');
		$("#menu-modal .modal-footer .glyphicon-refresh-animate").css('display', 'inline-block');
		$.ajax({
			type: "POST",
			url: "?controler=panier&action=addMenu",
			dataType: "html",
			data: $("#menuForm").serialize()
		}).done(function( msg ) {
			$("#menu-modal .modal-footer .glyphicon-refresh-animate").css('display', 'none');
			$("#menu-modal .modal-footer div.alert-success").css('display', 'inline-block');
			setTimeout(function(){ 
				$("#menu-modal").modal('hide');
			}, 3000);
		}).error(function(msg) {
			$("#menu-modal .modal-footer .glyphicon-refresh-animate").css('display', 'none');
			$("#menu-modal .modal-footer div.alert-danger").css('display', 'inline-block');
		});
	});
</script>