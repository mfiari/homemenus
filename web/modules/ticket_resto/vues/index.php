<div class="col-md-12">
	<h2>Titres restaurant</h2>
	<div class="row">
		<p><b>Nombre de ticket restaurant : </b><?php echo $request->result["quantite"] == '' ? '0' : $request->result["quantite"]; ?></p>
		<p><b>Montant total : </b><?php echo $request->result["montant"] == '' ? '0,00 €' : $request->result["montant"]; ?></p>
	</div>
	<div class="row">
		<div class="col-md-10  col-md-offset-1">
			<div class="row">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Mois</th>
							<th>Restaurant</th>
							<th>Quantite</th>
							<th>Montant</th>
						</tr>
					</thead>
					<tbody>
						<?php $quantiteTotal = 0; $prixTotal = 0; ?>
						<?php foreach ($request->restaurants as $restaurant) : ?>
							<tr>
								<td><?php
									if ($restaurant['month'] == '' || $restaurant['year'] == '') {
										echo getMonthByIndex(date('m')).' '.date('Y');
									} else {
										echo getMonthByIndex($restaurant['month']).' '.$restaurant['year']; 
									}
								?></td>
								<td><?php echo utf8_encode($restaurant['nom']); ?></td>
								<td><?php echo $restaurant['quantite_total']; ?></td>
								<td><?php echo number_format($restaurant['prix_total'], 2, ',', ' ').' €'; ?></td>
							</tr>
							<?php $quantiteTotal = $restaurant['quantite_total']; ?>
							<?php $prixTotal = $restaurant['prix_total']; ?>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="2">Total</th>
							<th><?php echo $quantiteTotal; ?></th>
							<th><?php echo number_format($prixTotal, 2, ',', ' ').' €'; ?></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>