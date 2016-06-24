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