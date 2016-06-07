<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h2>Mails</h2>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Destinataire</th>
					<th>Sujet</th>
					<th>Date d'envoie</th>
					<th>Envoyée</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($request->mails as $mail) : ?>
					<tr>
						<td><?php echo $mail->to; ?></td>
						<td><?php echo utf8_encode($mail->sujet); ?></td>
						<td><?php echo $mail->date_envoi; ?></td>
						<td><?php echo $mail->is_send ? '<span style="color : green; ">Oui</span>' : '<span style="color : red; ">Non</span>'; ?></td>
						<td>
							<a href="?controler=mail&action=detail&id=<?php echo $mail->id; ?>">
								<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>