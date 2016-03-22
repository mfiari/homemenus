<h2><?php echo utf8_encode($request->restaurant->nom); ?></h2>
<input id="id_restaurant" value="<?php echo $request->restaurant->id; ?>" hidden="hidden" />
<div id="carte">
	<?php 
		$currentCategorie = "";
		$nbItem = 0; 
	?>
	<?php foreach ($request->restaurant->carte as $categorie => $value) : ?>
		<div class="categorie">
			<h3><?php echo utf8_encode($categorie); ?></h3>
			<?php $nbItem = 0; ?>
			<?php foreach ($value as $carte) : ?>
				<?php if ($nbItem % 4 == 0) : ?>
					<div class="row" style="margin-top : 20px;">
				<?php endif; ?>
				<div class="col-md-3">
					<div class="col-md-10 col-md-offset-1 item">
						<div class="title"><a data-id="<?php echo $carte->id; ?>"><?php echo utf8_encode($carte->nom); ?></a><span class="disponible"></span></div>
						<div class="vignette"><a data-id="<?php echo $carte->id; ?>"><img src="res/img/home.png"></a></div>
					</div>
				</div>
				<?php $nbItem++; ?>
				<?php if ($nbItem % 4 == 0) : ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
</div>

<div id="carte-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
		$(".categorie .title a, .categorie .vignette a").click(function () {
			var id_carte = $(this).attr('data-id');
			var id_restaurant = $("#id_restaurant").val();
			$.ajax({
				type: "GET",
				url: '?controler=restaurant&action=carte&id='+id_restaurant+'&id_carte='+id_carte,
				dataType: "html"
			}).done(function( msg ) {
				$("#carte-modal .modal-content").html(msg);
				$("#carte-modal").modal();
			});
		});
	})
</script>