<div class="row">
	<div class="col-md-12">
		<h2>Panier</h2>
		<div id="paniers">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Client</th>
						<th>Restaurant</th>
						<th>Total</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($request->paniers as $panier) : ?>
						<tr>
							<td>
								<?php if ($panier->uid != -1) : ?>
									<a href="?controler=user&action=client&id_user=<?php echo $panier->user->id; ?>">
										<?php echo utf8_encode($panier->user->nom); ?> <?php echo utf8_encode($panier->user->prenom); ?>
									</a>
								<?php else : ?>
									<span><?php echo $panier->adresse_ip; ?></span>
								<?php endif; ?>
							</td>
							<td><a href="?controler=restaurant&action=view&id_restaurant=<?php echo $panier->restaurant->id; ?>">
								<?php echo utf8_encode($panier->restaurant->nom); ?>
							</a></td>
							<td>0</td>
							<td>
								<a href="?controler=panier&action=view&id_panier=<?php echo $panier->id; ?>">
									<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>