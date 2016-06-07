<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h2>Mail</h2>
		<div class="col-md-12">
			<div class="row">
				<fieldset>
					<div class="form-group">
						<label for="nom">From : </label>
						<span><?php echo $request->mail->from; ?></span>
					</div>
					<div class="form-group">
						<label for="prenom">Destinataire : </label>
						<span><?php echo $request->mail->to; ?></span>
					</div>
					<div class="form-group">
						<label for="prenom">Sujet : </label>
						<span><?php echo utf8_encode($request->mail->sujet); ?></span>
					</div>
				</fieldset>
			</div>
			<div class="row">
				<div>
					<?php echo utf8_encode($request->mail->contenu); ?>
				</div>
			</div>
		</div>
	</div>
</div>