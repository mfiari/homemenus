<h2><?php echo utf8_encode($request->restaurant->nom); ?></h2>
<input id="id_restaurant" value="<?php echo $request->restaurant->id; ?>" hidden="hidden" />
<div id="carte">
	<a class="btn btn-primary" href="?controler=compte&action=restaurant&id=<?php echo $request->restaurant->id; ?>">
		<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
	</a>
	<?php 
		$currentCategorie = "";
		$nbItem = 0; 
	?>
	<?php foreach ($request->restaurant->categories as $categorie) : ?>
		<div class="categorie">
			<h3><?php echo utf8_encode($categorie->nom); ?></h3>
			<?php $nbItem = 0; ?>
			<?php foreach ($categorie->contenus as $contenu) : ?>
				<?php if ($nbItem % 4 == 0) : ?>
					<div class="row" style="margin-top : 20px;">
				<?php endif; ?>
				<div class="col-md-3">
					<div class="col-md-10 col-md-offset-1 item">
						<div class="title"><a data-id="<?php echo $contenu->id; ?>"><?php echo utf8_encode($contenu->nom); ?></a><span class="disponible"></span></div>
						<div class="vignette"><a data-id="<?php echo $contenu->id; ?>"><img src="<?php echo $contenu->logo; ?>"></a></div>
					</div>
				</div>
				<?php $nbItem++; ?>
				<?php if ($nbItem % 4 == 0) : ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
	<a class="btn btn-primary" href="?controler=compte&action=restaurant&id=<?php echo $request->restaurant->id; ?>">
		<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
	</a>
</div>
<div id="carte-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
		$(".categorie .title a, .categorie .vignette a").click(function () {
			$("#loading-modal").modal();
			var id_carte = $(this).attr('data-id');
			var id_restaurant = $("#id_restaurant").val();
			$.ajax({
				type: "GET",
				url: '?controler=compte&action=carte&id='+id_restaurant+'&id_carte='+id_carte,
				dataType: "html"
			}).done(function( msg ) {
				$("#loading-modal").modal('hide');
				$("#carte-modal").modal();
				$("#carte-modal .modal-content").html(msg);
			});
		});
	})
</script>
<style>
	.categorie .title a, .categorie .vignette a {
		cursor : pointer;
	}
</style>