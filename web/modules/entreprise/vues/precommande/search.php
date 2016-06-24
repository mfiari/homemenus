<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h2>Recherche</h2>
		<form method="post" enctype="x-www-form-urlencoded" id="subscribeForm" action="?controler=precommande&action=commande_search">
			<fieldset>
				<div class="form-group">
					<label for="rue">Adresse : </label>
					<div class="search-block">
						<input id="full_address" class="form-control" name="adresse" type="text" value="<?php echo $request->_auth->rue.', '.$request->_auth->code_postal.' '.$request->_auth->ville; ?>" placeholder="Entrez votre adresse">
						<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
					</div>
					<input id="rue" name="rue" type="text" value="<?php echo $request->_auth->rue !== false ? $request->_auth->rue : ''; ?>" hidden="hidden">
					<input id="ville" name="ville" type="text" value="<?php echo $request->_auth->ville !== false ? $request->_auth->ville : ''; ?>" hidden="hidden">
					<input id="code_postal" name="code_postal" type="text" value="<?php echo $request->_auth->code_postal !== false ? $request->_auth->code_postal : ''; ?>" hidden="hidden">
				</div>
				<div class="form-group">
					<label for="nom">Date<span class="required">*</span> : </label>
					<input class="form-control" name="date" type="text" value="<?php echo $request->date; ?>" maxlength="32" required>
				</div>
				<div class="form-group">
					<label for="prenom">heure<span class="required">*</span> : </label>
					<select name="hour">
						<?php for ($i = 18 ; $i <= 23 ; $i++) : ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>h
					<select name="minute">
						<?php for ($i = 0 ; $i <= 59 ; $i++) : ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>
				</div>
				<div>
					<span class="required">* Obligatoire</span>
				</div>
				<button id="subscribe-button" class="btn btn-primary" type="submit">Recherche</button>
			</fieldset>
		</form>
	</div>
</div>