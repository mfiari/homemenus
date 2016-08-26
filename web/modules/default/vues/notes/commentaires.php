<div>
	<?php foreach ($request->commentaires as $commentaire) : ?>
		<div class="row">
			<div class="col-md-12">
				<?php echo utf8_encode($commentaire->commentaire); ?>
			</div>
			<div class="col-md-12">
				<?php if (!$commentaire->anonyme) : ?>
					par <?php echo $commentaire->user->nom; ?> <?php echo $commentaire->user->prenom; ?>
				<?php endif; ?>
				Le <?php echo formatTimestampToDateHeure($commentaire->date); ?>
			</div>
		</div>
		<hr />
	<?php endforeach; ?>

</div>