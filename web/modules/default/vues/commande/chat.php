<header class="clearfix">
	<a href="#" class="chat-close">x</a>
	<h4><?php echo $request->livreur->prenom; ?></h4>
	<span class="chat-message-counter"><?php count($request->messages); ?></span>
</header>
<div class="chat">
	<div class="chat-history">
		<?php foreach ($request->messages as $message) : ?>
			<div class="chat-message clearfix">
				<div class="chat-message-content clearfix">
					<span class="chat-time">13:35</span>
					<?php if ($message->sender == "LIVREUR") : ?>
						<h5><?php echo $request->livreur->prenom; ?></h5>
					<?php else : ?>
						<h5>Moi</h5>
					<?php endif; ?>
					<p><?php echo $message->message; ?></p>
				</div> <!-- end chat-message-content -->
			</div> <!-- end chat-message -->
			<hr>
		<?php endforeach; ?>
	</div> <!-- end chat-history -->
	<form id="chat-form" action="#" method="post">
		<fieldset>
			<input name="message" type="text" placeholder="Type your message…" autofocus>
			<input name="id_commande" type="hidden" value="<?php echo $request->id_commande; ?>">
		</fieldset>
	</form>
</div> <!-- end chat -->

<script type="text/javascript">
	$("#chat-form").submit(function(event) {
		event.preventDefault();
		$.ajax({
			type: "POST",
			url: "?controler=commande&action=sendMessage",
			dataType: "html",
			data: $("#chat-form").serialize()
		}).done(function( msg ) {
			openChatBox(<?php echo $request->id_commande; ?>);
		});
	});
</script>