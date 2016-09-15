<div class="row">
	<div class="col-md-12">
		<h2>Historiques des commandes</h2>
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="?controler=commande">Commande du jour</a></li>
			<li role="presentation" class="active"><a>Historique des commandes</a></li>
		</ul>
		<div>
			<form method="post" enctype="x-www-form-urlencoded" action="">
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
						<th>Ville</th>
						<th>Date de commande</th>
						<th>Prix</th>
						<th>Note</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php $total = 0; ?>
					<?php $totalPrix = 0; ?>
					<?php foreach ($request->commandes as $commande) : ?>
						<tr>
							<td><a href="?controler=commande&action=viewHistory&id_commande=<?php echo $commande->id; ?>">#<?php echo $commande->id; ?></a></td>
							<td><a href="?controler=user&action=view&id_user=<?php echo $commande->livreur->id; ?>"><?php echo utf8_encode($commande->livreur->login); ?></a></td>
							<td><a href="?controler=restaurant&action=view&id_restaurant=<?php echo $commande->restaurant->id; ?>"><?php echo utf8_encode($commande->restaurant->nom); ?></a></td>
							<td><a href="?controler=user&action=client&id_user=<?php echo $commande->client->id; ?>"><?php echo $commande->client->id; ?></a></td>
							<td><?php echo utf8_encode($commande->restaurant->ville); ?> (<?php echo $commande->restaurant->code_postal; ?>)</td>
							<td><?php echo $commande->date_commande; ?></td>
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