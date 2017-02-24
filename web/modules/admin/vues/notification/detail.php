<div class="row">
	<div class="col-md-12">
		<a class="btn btn-primary" href="?controler=sms">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
	</div>
	<div class="col-md-10  col-md-offset-1">
		<h2>SMS</h2>
		<div class="col-md-12">
			<div class="row">
				<fieldset>
					<div class="form-group">
						<label for="prenom">Téléphone : </label>
						<span><?php echo $request->sms->telephone; ?></span>
					</div>
				</fieldset>
			</div>
			<div class="row">
				<div>
					<?php echo utf8_encode($request->sms->message); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<a class="btn btn-primary" href="?controler=sms">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
	</div>
</div>