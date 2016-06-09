<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h2 class="modal-title">Mon panier</h2>
</div>
<div class="modal-body">
	<?php if ($request->panier) : ?>
		<form method="post" enctype="x-www-form-urlencoded" id="panierFormModal" action="">
			<input type="hidden" id="id_panier" name="id_panier" value="<?php echo $request->panier->id; ?>" />
			<?php
				$current_heure = date('G')+1;
				$current_minute = date('i');
				$horaire = $request->panier->restaurant->horaire;
			?>
			<div>
				<?php if ($horaire->heure_debut > $current_heure || ($horaire->heure_debut == $current_heure && $horaire->minute_debut > $current_minute)) : ?>
					<input type="hidden" name="type_commande" value="pre_commande">
					<span style="color : red;">
						Le <?php echo utf8_encode($request->panier->restaurant->nom); ?> est actuellement fermé. Ouverture
							de <?php echo formatHeureMinute($horaire->heure_debut, $horaire->minute_debut); ?> 
							à <?php echo formatHeureMinute($horaire->heure_fin, $horaire->minute_fin); ?></span><br /><br />
					<span><b>heure de livraison souhaité : </b></span>
					<?php 
						if ($horaire->heure_debut < $current_heure) {
							$beginHour = $current_heure;
						} else {
							$beginHour = $horaire->heure_debut;
						}
					?>
					<select name="heure_commande">
						<?php for ($i = $beginHour ; $i <= $horaire->heure_fin ; $i++) : ?>
							<option><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>
					<select name="minute_commande">
						<?php for ($i = 0 ; $i <= 60 ; $i++) : ?>
							<option <?php echo $i == $horaire->minute_debut ? 'selected' : ''; ?>><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>
				<?php else : ?>
					<input type="radio" name="type_commande" value="now" checked>Au plus tôt
					<input type="radio" name="type_commande" value="pre_commande">Pré-commande
					<span>heure de commande</span>
					<?php 
						if ($horaire->heure_debut < $current_heure) {
							$beginHour = $current_heure;
						} else {
							$beginHour = $horaire->heure_debut;
						}
					?>
					<select name="heure_commande">
						<?php for ($i = $beginHour ; $i <= $horaire->heure_fin ; $i++) : ?>
							<option><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>
					<select name="minute_commande">
						<?php for ($i = 0 ; $i <= 60 ; $i++) : ?>
							<option <?php echo $i == $horaire->minute_debut ? 'selected' : ''; ?>><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>
				<?php endif; ?>
			</div><br />
			<div class="panel panel-default panel-primary">
				<div class="panel-heading">
					Restaurant <?php echo utf8_encode($request->panier->restaurant->nom); ?>
				</div>
				<div style="max-height : 300px; overflow : auto;">
					<?php $totalQte = 0; ?>
					<?php $totalPrix = 0; ?>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Nom</th>
								<th>Quantité</th>
								<th>prix</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($request->panier->carteList as $carte) : ?>
								<tr id="tr_carte_<?php echo $carte->id; ?>">
									<td><?php echo utf8_encode($carte->nom); ?></td>
									<td><?php echo $carte->quantite; ?></td>
									<td><?php echo $carte->prix; ?> €</td>
									<td><a class="carte-item-delete" data-id="<?php echo $carte->id; ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
								</tr>
								<?php $totalQte += $carte->quantite; ?>
								<?php $totalPrix += $carte->prix; ?>
							<?php endforeach; ?>
							<?php foreach ($request->panier->menuList as $menu) : ?>
								<tr>
									<td><?php echo utf8_encode($menu->nom); ?></td>
									<td><?php echo $menu->quantite; ?></td>
									<td><?php echo $menu->prix; ?> €</td>
									<td><a class="menu-item-delete" data-id="<?php echo $menu->id; ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
								</tr>
								<?php $totalQte += $menu->quantite; ?>
								<?php $totalPrix += $menu->prix; ?>
							<?php endforeach; ?>
							<?php
								$prix_livraison = $request->panier->prix_livraison;
								if ($request->_auth->is_premium) {
									$prix_livraison -= $request->panier->reduction_premium;
								}
							?>
							<tr>
								<td>prix de livraison</td>
								<td></td>
								<td><?php echo $prix_livraison; ?> €</td>
								<td></td>
							</tr>
							<?php $totalPrix += $prix_livraison; ?>
						</tbody>
						<tfoot>
							<tr>
								<th>Total :</th>
								<th><?php echo $totalQte; ?></th>
								<th><?php echo $totalPrix; ?> €</th>
								<td></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</form>
	<?php else : ?>
		<span>(vide)</span>
	<?php endif; ?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
	<?php if ($request->panier) : ?>
		<?php if ($request->panier->prix_minimum > ($totalPrix - $prix_livraison)) : ?>
			<div class="alert alert-danger" role="alert">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				<span class="sr-only">Error:</span>
				Le montant minimum pour commander est de <?php echo $request->panier->prix_minimum; ?> €
			</div>
		<?php else : ?>
			<?php if ($horaire->heure_debut > $current_heure || ($horaire->heure_debut == $current_heure && $horaire->minute_debut > $current_minute)) : ?>
				<button id="command" class="btn btn-primary" type="submit">Pré-Commande</button>
			<?php else : ?>
				<button id="command" class="btn btn-primary" type="submit">Commander</button>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
	<div id="accept_cgv_error_message" class="alert alert-danger" role="alert" style="display : none;">
		<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
		<span class="sr-only">Error:</span>
		Vous devez accepter les conditions générales de vente pour pouvoir continuer
	</div>
</div>
<script type="text/javascript">
	$("#command").click(function(event) {
		event.preventDefault();
		var type_commande = $('#panierFormModal input[name=type_commande]').val();
		var heure_commande = $('#panierFormModal select[name=heure_commande]').val();
		var minute_commande = $('#panierFormModal select[name=minute_commande]').val();
		
		$("#panier-info-modal #type_commande").val(type_commande);
		$("#panier-info-modal #heure_commande").val(heure_commande);
		$("#panier-info-modal #minute_commande").val(minute_commande);
		$("#panier-info-modal").modal();
		/*event.preventDefault();
		document.location.href="?controler=panier&action=validate";*/
		/*if ($("#accept_cgv").is(":checked")) {
			$.ajax({
				type: "POST",
				url: "?controler=panier&action=commande",
				dataType: "html",
				data: $("#panierForm").serialize()
			}).done(function( msg ) {
				$("#panier-modal").modal('hide');
				document.location.href="?controler=paypal"
			});
		} else {
			$("#accept_cgv_error_message").show();
		}*/
	});
	$(".carte-item-show").click(function(event) {
		var id_panier = $("#id_panier").val();
		var id = $(this).attr('data-id');
		$.ajax({
			type: "POST",
			url: "?controler=panier&action=showCarteDetail",
			dataType: "html",
			data: {id_panier : id_panier, id_panier_carte : id}
		}).done(function( msg ) {
			openCard ();
		}).error(function(msg) {
			alert("error");
		});
	});
	$(".carte-item-delete").click(function(event) {
		var id_panier = $("#id_panier").val();
		var id = $(this).attr('data-id');
		$.ajax({
			type: "POST",
			url: "?controler=panier&action=removeCarte",
			dataType: "html",
			data: {id_panier : id_panier, id_panier_carte : id}
		}).done(function( msg ) {
			openCard ();
		}).error(function(msg) {
			alert("error");
		});
	});
	$(".menu-item-delete").click(function(event) {
		var id_panier = $("#id_panier").val();
		var id = $(this).attr('data-id');
		$.ajax({
			type: "POST",
			url: "?controler=panier&action=removeMenu",
			dataType: "html",
			data: {id_panier : id_panier, id_panier_menu : id}
		}).done(function( msg ) {
			openCard ();
		}).error(function(msg) {
			alert("error");
		});
	});
</script>
<style>
	a.carte-item, a.menu-item {
		cursor : pointer;
	}
</style>