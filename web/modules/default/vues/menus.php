<h2><?php echo utf8_encode($request->restaurant->nom); ?></h2>
<input id="id_restaurant" value="<?php echo $request->restaurant->id; ?>" hidden="hidden" />
<div id="menus">
	<a class="btn btn-primary" href="?controler=restaurant&action=index&id=<?php echo $request->restaurant->id; ?>">
		<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
	</a>
	<?php $nbItem = 0; ?>
	<div class="menu">
		<?php foreach ($request->restaurant->menus as $menu) : ?>
			<?php if ($nbItem % 4 == 0) : ?>
				<div class="row" style="margin-top : 20px;">
			<?php endif; ?>
			<div class="col-md-3">
				<div class="col-md-10 col-md-offset-1 item">
					<div class="title"><a data-id="<?php echo $menu->id; ?>"><?php echo utf8_encode($menu->nom); ?></a><span class="disponible"></span></div>
					<div class="vignette"><a data-id="<?php echo $menu->id; ?>"><img src="<?php echo $menu->logo; ?>"></a></div>
				</div>
			</div>
			<?php $nbItem++; ?>
			<?php if ($nbItem % 4 == 0) : ?>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<a class="btn btn-primary" href="?controler=restaurant&action=index&id=<?php echo $request->restaurant->id; ?>">
		<span style="margin-right: 10px;" class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>retour
	</a>
</div>

<div id="menu-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
		$(".menu .title a, .menu .vignette a").click(function () {
			$("#loading-modal").modal();
			var id_menu = $(this).attr('data-id');
			var id_restaurant = $("#id_restaurant").val();
			$.ajax({
				type: "GET",
				url: '?controler=restaurant&action=menu&id='+id_restaurant+'&id_menu='+id_menu,
				dataType: "html"
			}).done(function( msg ) {
				$("#loading-modal").modal('hide');
				$("#menu-modal .modal-content").html(msg);
				$("#menu-modal").modal();
			});
		});
	})
</script>
<style>
	.menu .title a, .menu .vignette a {
		cursor : pointer;
	}
</style>