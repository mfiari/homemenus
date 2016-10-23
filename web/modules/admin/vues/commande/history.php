<div class="row">
	<div class="col-md-12">
		<h2>Historiques des commandes</h2>
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="?controler=commande">Commande du jour</a></li>
			<li role="presentation" class="active"><a>Historique des commandes</a></li>
		</ul>
		<div>
			<form method="GET" enctype="x-www-form-urlencoded" action="">
				<input type="text" name="controler" value="commande" hidden>
				<input type="text" name="action" value="history" hidden>
				<span>Début : </span><input class="datepicker" type="text" name="date_debut" value="<?php echo $request->date_debut ? $request->date_debut : ''; ?>">
				<span>Fin : </span><input class="datepicker" type="text" name="date_fin" value="<?php echo $request->date_fin ? $request->date_fin : ''; ?>">
				<button class="btn btn-primary" type="submit">Valider</button>
			</form>
		</div>
		<div id="commandes">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Numéro</th>
						<th>Livreur</th>
						<th>Restaurant</th>
						<th>client</th>
						<th>Date de commande</th>
						<th>Temps</th>
						<th>Prix</th>
						<th>Note</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php $total = 0; ?>
					<?php $totalPrix = 0; ?>
					<?php foreach ($request->commandes as $commande) : ?>
						<?php 
							if ($commande->date_validation_restaurant != '' && $commande->date_livraison != '' && 
							$commande->date_validation_restaurant != '0000-00-00 00:00:00' && $commande->date_livraison != '0000-00-00 00:00:00') {
								$d1 = new DateTime($commande->date_validation_restaurant);
								$d2 = new DateTime($commande->date_livraison);
								$diff = $d1->diff($d2);
								$temps = ($diff->h * 60) + $diff->i;
							} else {
								$temps = -1;
							}
						?>
						<tr>
							<td><a href="?controler=commande&action=viewHistory&id_commande=<?php echo $commande->id; ?>">#<?php echo $commande->id; ?></a></td>
							<td><a href="?controler=user&action=view&id_user=<?php echo $commande->livreur->id; ?>">
								<?php echo utf8_encode($commande->livreur->prenom); ?> (<?php echo utf8_encode($commande->livreur->login); ?>)
							</a></td>
							<td><a href="?controler=restaurant&action=view&id_restaurant=<?php echo $commande->restaurant->id; ?>">
								<?php echo utf8_encode($commande->restaurant->nom); ?> (<?php echo utf8_encode($commande->restaurant->ville); ?>)
							</a></td>
							<td><a href="?controler=user&action=client&id_user=<?php echo $commande->client->id; ?>">
								<?php echo utf8_encode($commande->client->nom); ?> <?php echo utf8_encode($commande->client->prenom); ?>
								(<?php echo utf8_encode($commande->ville); ?>)
							</a></td>
							<td><?php echo $commande->date_commande; ?></td>
							<td><?php echo $temps == -1 ? 'NA' : $temps.' min'; ?></td>
							<td><?php echo $commande->prix; ?> €</td>
							<td><?php echo $commande->note; ?> / 5</td>
							<td>
								<a href="?controler=commande&action=viewHistory&id_commande=<?php echo $commande->id; ?>">
									<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
								</a>
							</td>
						</tr>
						<?php $total++; ?>
						<?php $totalPrix += $commande->prix; ?>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<th>Total : </th>
						<th colspan="5"><?php echo $total; ?> commande(s)</th>
						<th><?php echo $totalPrix; ?> €</th>
						<th></th>
					</tr>
					<tr>
						<?php
							$totalPage = ceil($request->totalRows / $request->nbItem);
							if ($totalPage == 0) {
								$totalPage = 1;
							}
						?>
						<td></td>
						<td colspan="2"><a class="btn btn-primary" href="?controler=commande&action=history&date_debut=<?php echo $request->date_debut; ?>&date_fin=<?php echo $request->date_fin; ?>&page=<?php echo $request->page-1; ?>" <?php echo $request->page == 1 ? 'disabled' : ''; ?>>Page précédente</a></td>
						<td colspan="2"><?php echo $request->page; ?> / <?php echo $totalPage; ?></td>
						<td colspan="2"><a class="btn btn-primary" href="?controler=commande&action=history&date_debut=<?php echo $request->date_debut; ?>&date_fin=<?php echo $request->date_fin; ?>&page=<?php echo $request->page+1; ?>" <?php echo $request->page == $totalPage ? 'disabled' : ''; ?>>Page suivante</a></td>
						<td></td>
					</tr>
				</tfoot>
			</table>
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
<style>

	a[disabled] {
		pointer-events: none;
		background-color: gray;
	}

</style>