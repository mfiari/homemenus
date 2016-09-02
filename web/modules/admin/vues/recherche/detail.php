<a class="btn btn-primary" href="?controler=recherche">
	<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
</a>
<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h2>Recherche</h2>
		<div class="col-md-12">
			<div class="row">
				<p>Adresse : <?php echo utf8_encode($request->recherche->recherche); ?></p>
				<p>Distance : <?php echo $request->recherche->distance; ?></p>
				<p>Date : <?php echo $request->recherche->date_recherche; ?></p>
			</div>
			<div class="row">
				<div class="col-md-12" style="padding : 30px;">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Restaurants</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($request->recherche->restaurants as $restaurant) : ?>
								<tr>
									<td><a href="?controler=restaurant&action=view&id_restaurant=<?php echo $restaurant->id;?>"><?php echo utf8_encode($restaurant->nom); ?></a></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<a class="btn btn-primary" href="?controler=recherche">
	<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
</a>