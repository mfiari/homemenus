<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h2>Mon calendrier</h2>
		<?php if ($request->errorMessage) : ?>
			<?php foreach ($request->errorMessage as $key => $value) : ?>
				<div class="alert alert-danger" role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					<?php echo $value; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<div class="col-md-10">
			<div id="myCalendar">
			</div>
		</div>
		<div class="col-md-2">
			<ul>
				<li>Mon solde</li>
				<li><a href="?controler=compte&action=calendrier">Mon calendrier</a></li>
			</ul>
		</div>
	</div>
</div>
<?php
	$adresse = "";
	if ($request->_auth) {
		$adresse = $request->_auth->rue.', '.$request->_auth->code_postal.' '.$request->_auth->ville;
	}
?>
<script type="text/javascript">
	$(document).ready(function () {
		$('#myCalendar').MyCalendar({
			adresse : '<?php echo $adresse; ?>'
		});
	});

</script>