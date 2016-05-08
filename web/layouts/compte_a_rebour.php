<div class="row">
	<div class="col-md-10  col-md-offset-1">
		<h2>Ouverture Dans</h2>
		<div id="date" style="font-size: 70px; text-align: center; font-family: monospace;">
			
		</div>
	</div>
</div>

<script type="text/javascript">
	showTime ();
	window.setInterval(function(){ 
		showTime ();
	}, 1000);
	
	function showTime () {
		console.log("showTime");
		var now = new Date();
		var date_fin = new Date(2016, 5, 3, 0, 0, 0, 0);
		var one_day = 1000 * 60 * 60 * 24;
		var now_ms = now.getTime();
		var date_fin_ms = date_fin.getTime();
		var diff_ms = date_fin_ms - now_ms;
		var diffDay = (Math.round(diff_ms / one_day)) -1;
		var hour = 24 - (now.getHours() +1);
		var min = 60 - (now.getMinutes() +1);
		var seconde = 60 - (now.getSeconds() +1);
		$("#date").html(diffDay + "J " + hour + "H " + min + "M " + seconde + "S");
	}
</script>