<div class="row">
	<div class="col-md-12">
		<a class="btn btn-primary" href="?controler=user&action=livreurs">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
		<div class="row">
			<h2>Coordonnées</h2>
			<p>Nom : <?php echo utf8_encode($request->livreur->nom); ?> <?php echo utf8_encode($request->livreur->prenom); ?></p>
			<p>Email : <?php echo $request->livreur->email; ?></p>
			<p>Telephone : <?php echo $request->livreur->telephone; ?></p>
		</div>
		<div class="row">
			<h2>Disponibilité</h2>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Adresse</th>
						<th>Jour</th>
						<th>Debut</th>
						<th>Fin</th>
						<th>Vehicule</th>
						<th>perimetre</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($request->livreur->dispos as $dispo) : ?>
						<tr>
							<td><?php echo $dispo->rue; ?>, <?php echo $dispo->code_postal; ?> <?php echo $dispo->ville; ?></td>
							<td><?php echo $dispo->id_jour; ?></td>
							<td><?php echo $dispo->heure_debut; ?>h<?php echo $dispo->minute_debut; ?></td>
							<td><?php echo $dispo->heure_fin; ?>h<?php echo $dispo->minute_fin; ?></td>
							<td><?php echo $dispo->vehicule; ?></td>
							<td><?php echo $dispo->perimetre; ?> km</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<div id="disponibilite">
				<form id="ajoutDispoForm" method="post" action="?controler=user&amp;action=add_dispo">
					<input name="id_livreur" value="<?php echo $request->livreur->id; ?>" hidden>
					<input id="rue" name="rue" hidden>
					<input id="ville" name="ville" hidden>
					<input id="code_postal" name="code_postal" hidden>
					<input id="latitude" name="latitude" hidden>
					<input id="longitude" name="longitude" hidden>
					<input id="full_address" name="adresse">
					<select name="day">
						<option value="1">Lundi</option>
						<option value="2">Mardi</option>
						<option value="3">Mercredi</option>
						<option value="4">Jeudi</option>
						<option value="5">Vendredi</option>
						<option value="6">Samedi</option>
						<option value="7">Dimanche</option>
					</select>
					<select name="km">
						<option value="3">3 km</option>
						<option value="5">5 km</option>
						<option value="10">10 km</option>
						<option value="15">15 km</option>
					</select>
					<select name="vehicule">
						<option value="VOITURE">VOITURE</option>
						<option value="SCOOTER">SCOOTER</option>
						<option value="MOTO">MOTO</option>
						<option value="VELO">VELO</option>
					</select>
					<select name="heure_debut">
						<?php for ($i = 0 ; $i <= 23 ; $i++) : ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>h
					<select name="minute_debut">
						<?php for ($i = 0 ; $i <= 59 ; $i++) : ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>
					<select name="heure_fin">
						<?php for ($i = 0 ; $i <= 23 ; $i++) : ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>h
					<select name="minute_fin">
						<?php for ($i = 0 ; $i <= 59 ; $i++) : ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>
					<button id="dispo-button" type="button">Valider</button></form>
			</div>
		</div>
		<div class="row">
			<h2>Trajet</h2>
		</div>
		<div class="row">
			<h2>Commande du jour</h2>
			<div id="commandes">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Numéro</th>
						<th>Client</th>
						<th>Restaurant</th>
						<th>Ville</th>
						<th>Date de commande</th>
						<th>Prix</th>
						<th>Note</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php $total = 0; ?>
					<?php foreach ($request->commandes as $commande) : ?>
						<tr>
							<td><a href="?controler=commande&action=view&id_commande=<?php echo $commande->id; ?>"><?php echo $commande->id; ?></a></td>
							<td><a href="?controler=user&action=view&id_user=<?php echo $commande->client->id; ?>"><?php echo utf8_encode($commande->client->nom); ?> <?php echo utf8_encode($commande->client->prenom); ?></a>
							</td>
							<td><a href="?controler=restaurant&action=view&id_restaurant=<?php echo $commande->restaurant->id; ?>"><?php echo utf8_encode($commande->restaurant->nom); ?></a></td>
							<td><?php echo utf8_encode($commande->restaurant->ville); ?> (<?php echo $commande->restaurant->code_postal; ?>)</td>
							<td><?php echo $commande->date_commande; ?></td>
							<td><?php echo $commande->prix; ?> €</td>
							<td><?php echo $commande->note; ?> / 5</td>
							<td>
								<a href="?controler=commande&action=view&id_commande=<?php echo $commande->id; ?>">
									<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
								</a>
								<a href="?controler=user&action=delete&id_user=<?php echo $commande->id; ?>">
									<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
								</a>
							</td>
						</tr>
						<?php $total += $commande->prix; ?>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="5">Total : </th>
						<th><?php echo $total; ?> €</th>
						<th></th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		</div>
		</div>
		<div class="row">
			<h2>Historique des commandes</h2>
			<div id="commandes">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Numéro</th>
							<th>Livreur</th>
							<th>Restaurant</th>
							<th>Ville</th>
							<th>Date de commande</th>
							<th>Prix</th>
							<th>Note</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php $total = 0; ?>
					<?php foreach ($request->commandesHistory as $commande) : ?>
						<tr>
							<td><a href="?controler=commande&action=view&id_commande=<?php echo $commande->id; ?>"><?php echo $commande->id; ?></a></td>
							<td><a href="?controler=user&action=view&id_user=<?php echo $commande->client->id; ?>"><?php echo utf8_encode($commande->client->nom); ?> <?php echo utf8_encode($commande->client->prenom); ?></a>
							</td>
							<td><a href="?controler=restaurant&action=view&id_restaurant=<?php echo $commande->restaurant->id; ?>"><?php echo utf8_encode($commande->restaurant->nom); ?></a></td>
							<td><?php echo utf8_encode($commande->restaurant->ville); ?> (<?php echo $commande->restaurant->code_postal; ?>)</td>
							<td><?php echo $commande->date_commande; ?></td>
							<td><?php echo $commande->prix; ?> €</td>
							<td><?php echo $commande->note; ?> / 5</td>
							<td>
								<a href="?controler=commande&action=view&id_commande=<?php echo $commande->id; ?>">
									<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
								</a>
								<a href="?controler=user&action=delete&id_user=<?php echo $commande->id; ?>">
									<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
								</a>
							</td>
						</tr>
						<?php $total += $commande->prix; ?>
					<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="5">Total : </th>
							<th><?php echo $total; ?> €</th>
							<th></th>
							<th></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<a class="btn btn-primary" href="?controler=user&action=livreurs">
			<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
		</a>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		enableAutocomplete("full_address");
		$("#dispo-button").click(function(event) {
			var addressComponents = getAdresseElements();
			var fullAdresse = $("#full_address").val();
			if (addressComponents === false && fullAdresse != '') {
				$("#full_address").addClass( "error" );
				if ($("#full_address-error").length == 0) {
					$("#full_address").after('<label id="full_address-error" class="error" for="full_address">L\'adresse saisie est invalide</label>');
				}
				return false;
			}
			$("#full_address-error").remove();
			if (addressComponents !== false) {
				console.log(addressComponents);
				var rue = addressComponents.street_number + ' ' + addressComponents.route;
				var ville = addressComponents.locality;
				var code_postal = addressComponents.postal_code;
				var latitude = addressComponents.lat;
				var longitude = addressComponents.lon;
				
				$('#rue').val(rue);
				$('#ville').val(ville);
				$('#code_postal').val(code_postal);
				$('#latitude').val(latitude);
				$('#longitude').val(longitude);
			}
			$("#ajoutDispoForm").submit();
		});
	});
</script>