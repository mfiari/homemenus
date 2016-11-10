<div class="row">
	<div class="col-md-12">
		<h2>SMSling</h2>
		<div id="smsling">
			<form method="post" enctype="x-www-form-urlencoded" action="?controler=sms&action=send">
				<fieldset>
					<div class="form-group">
						<label for="message">Message<span class="required">*</span> : </label>
						<textarea class="form-control" name="message" rows="8" cols="45" required><?php echo $request->fieldMessage !== false ? $request->fieldMessage : ''; ?></textarea>
					</div>
					<div class="form-group" style="max-height : 300px; overflow : scroll;">
						<input style="margin-right : 15px;" id="check_all" type="checkbox" checked>Tous cocher<br />
						<?php foreach ($request->users as $user) : ?>
							<input style="margin-right : 15px;" class="user_check" name="user-<?php echo $user->id; ?>" type="checkbox" checked>
								<?php echo utf8_encode($user->nom); ?> <?php echo utf8_encode($user->prenom); ?> (<?php echo $user->telephone; ?>)<br />
						<?php endforeach; ?>
					</div>
					<button class="btn btn-primary" type="submit">Envoyer</button>
				</fieldset>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		$("#check_all").click(function () {
			if ($(this).is(':checked')) {
				$(".user_check").prop('checked', true);
			} else {
				$(".user_check").removeAttr('checked');
			}
		});
	});
</script>