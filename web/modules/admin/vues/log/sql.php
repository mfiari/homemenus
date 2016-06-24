<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h2>Log SQL</h2>
		<div>
			<form method="post" enctype="x-www-form-urlencoded" action="">
				<span>date : </span><input class="datepicker" type="text" name="date" value="<?php echo $request->date_log ? $request->date_log : ''; ?>">
				<button class="btn btn-primary" type="submit">Valider</button>
			</form>
		</div>
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="?controler=log&action=server">Server</a></li>
			<li role="presentation"><a href="?controler=log&action=cron">Cron</a></li>
			<li role="presentation" class="active"><a href="?controler=log&action=sql">SQL</a></li>
			<li role="presentation"><a href="?controler=log&action=mail">Mail</a></li>
			<li role="presentation"><a href="?controler=log&action=ws">Webservice</a></li>
			<li role="presentation"><a href="?controler=log&action=vue">Vue</a></li>
		</ul>
		<div class="col-md-6">
			<div id="log" style="height : 500px; width : 100%; overflow : auto;">
				<?php if ($request->logs) : ?>
					<table class="table table-striped">
						<tbody>
						<?php foreach ($request->logs as $log) : ?>
							<tr>
								<td><?php echo $log->level; ?></td>
								<td><?php echo $log->date_log; ?></td>
								<td><?php echo $log->filepath; ?></td>
								<td><?php echo $log->fonction; ?></td>
								<td><?php echo $log->line; ?></td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				<?php else : ?>
					<p>Pas de log à ce jour</p>
				<?php endif; ?>
			</div>
		</div>
		<div class="col-md-6">
			<div id="log-detail">
				<?php if ($request->logs) : ?>
					<?php foreach ($request->logs as $log) : ?>
						<div class="log-detail log-detail-<?php echo $log->id; ?>">
							<h3>Message</h3>
							<?php foreach ($log->message as $value) : ?>
								<p><?php echo $value; ?></p>
							<?php endforeach; ?>
							<h3>Texte</h3>
							<?php foreach ($log->texte as $value) : ?>
								<p><?php echo $value; ?></p>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
		</div>
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