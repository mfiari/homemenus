<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h2>Log server</h2>
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="?controler=log&action=server">Server</a></li>
			<li role="presentation"><a href="?controler=log&action=cron">Cron</a></li>
			<li role="presentation"><a href="?controler=log&action=sql">SQL</a></li>
			<li role="presentation"><a href="?controler=log&action=mail">Mail</a></li>
			<li role="presentation"><a href="?controler=log&action=ws">Webservice</a></li>
			<li role="presentation"><a href="?controler=log&action=vue">Vue</a></li>
		</ul>
		<div class="col-md-10  col-md-offset-1">
			<div id="log" style="height : 500px; width : 100%; overflow : auto;">
				<?php if ($request->logs) : ?>
					<?php foreach ($request->logs as $line) : ?>
						<p><?php echo $line; ?></p>
					<?php endforeach; ?>
				<?php else : ?>
					<p>Pas de log à ce jour</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>