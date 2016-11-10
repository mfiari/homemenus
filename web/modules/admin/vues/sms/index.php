<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h2>SMS</h2>
		<div>
			<form method="post" enctype="x-www-form-urlencoded" action="">
				<span>Début : </span><input class="datepicker" type="text" name="date_debut" value="<?php echo $request->date_debut ? $request->date_debut : ''; ?>">
				<span>Fin : </span><input class="datepicker" type="text" name="date_fin" value="<?php echo $request->date_fin ? $request->date_fin : ''; ?>">
				<button class="btn btn-primary" type="submit">Valider</button>
			</form>
		</div>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Destinataire</th>
					<th>Date d'envoie</th>
					<th>Envoyée</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($request->smsList as $sms) : ?>
					<tr>
						<td><?php echo $sms->telephone; ?></td>
						<td><?php echo $sms->date_envoi; ?></td>
						<td><?php echo $sms->is_send ? '<span style="color : green; ">Oui</span>' : '<span style="color : red; ">Non</span>'; ?></td>
						<td>
							<a href="?controler=sms&action=detail&id=<?php echo $sms->id; ?>">
								<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
<script>
	$(function() {
		$(".datepicker").datepicker({
			closeText: 'Fermer',
			prevText: 'Précédent',
			nextText: 'Suivant',
			currentText: 'Aujourd\'hui',
			monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
			monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
			dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
			dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
			dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
			weekHeader: 'Sem.',
			dateFormat: 'dd/mm/yy'
		});
	});
</script>