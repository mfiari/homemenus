<div class="row">
	<div class="col-md-12">
		<h2>Emailing</h2>
		<div id="emailing">
			<form method="post" enctype="x-www-form-urlencoded" action="?controler=mail&action=send">
				<fieldset>
					<div class="form-group">
						<label for="sujet">Sujet<span class="required">*</span> : </label>
						<input class="form-control" type="text" name="sujet" value="<?php echo $request->fieldSujet !== false ? $request->fieldSujet : ''; ?>" required>
					</div>
					<div class="form-group">
						<label for="message">Message<span class="required">*</span> : </label>
						<textarea class="form-control" name="message" rows="8" cols="45" required><?php echo $request->fieldMessage !== false ? $request->fieldMessage : ''; ?></textarea>
					</div>
					<div class="form-group" style="max-height : 300px; overflow : scroll;">
						<input style="margin-right : 15px;" id="check_all" type="checkbox" checked>Tous cocher<br />
						<?php foreach ($request->users as $user) : ?>
							<input style="margin-right : 15px;" class="user_check" name="user-<?php echo $user->id; ?>" type="checkbox" checked>
								<?php echo utf8_encode($user->nom); ?> <?php echo utf8_encode($user->prenom); ?> (<?php echo $user->email; ?>)<br />
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
		tinymce.init({
			selector: 'textarea',
			height: 500,
			plugins: [
				'advlist autolink lists link image charmap print preview anchor',
				'searchreplace visualblocks code fullscreen',
				'insertdatetime media table contextmenu paste code template'
			],
			menubar: "insert",
			toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | template',
			content_css: '//www.tinymce.com/css/codepen.min.css',
			templates: [
				{title: 'Template 1', description: 'template', url: '../mails/avis.html'}
			],
			setup : function (editor) {
				editor.on('change', function () {
					editor.save();
				});
			}
		});
		
		$("#check_all").click(function () {
			if ($(this).is(':checked')) {
				$(".user_check").prop('checked', true);
			} else {
				$(".user_check").removeAttr('checked');
			}
		});
	});
</script>