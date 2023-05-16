<?php

function total_click_user()
{
	
	global $wpdb;
	
	$result = $wpdb->get_results("SELECT DATE(`data`) AS data, SUM(`counter`) AS somma FROM hfu_tracking_data WHERE post_id IN (SELECT post_id FROM hfu_postmeta WHERE meta_key = 'vendor_user' AND meta_value = " . get_current_user_id() . ") AND DATE(`data`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE() AND `type` = 'click' GROUP BY DATE(`data`)");
	
	if(current_user_can( 'venditore' ) || current_user_can( 'operatore' ) ){
	
	?>	

		<div>
			<h2>Click sui miei prodotti</h2>
		  	<canvas id="myChart2" width="400" height="100" ></canvas>
		</div>

		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

		<script>

			// recupera i dati dalla query e inizializza gli array
			var dati = <?php echo json_encode($result); ?>;
			var labels = [];
			var valori = [];

			// itera attraverso i risultati della query e popola gli array di etichette e valori
			for (var i = 0; i < dati.length; i++) {
				labels.push(dati[i].data);
				valori.push(dati[i].somma);
			}

			
			
			const ctx2 = document.getElementById('myChart2');

			new Chart(ctx2, {
				type: 'bar',
				data: {
					labels:  labels,
					datasets: [{
						label: 'Click ultimi 30 giorni',
						data: valori,
						borderWidth: 1
					}]
				},
				options: {
					scales: {
						y: {
							beginAtZero: true
						}
					}
				}
			});
		</script>
	<?php
	}	
}
add_shortcode('total_click_chart_user', 'total_click_user');

function total_impression_user()
{
	
	global $wpdb;
	
	
	
	$result = $wpdb->get_results("SELECT DATE(`data`) AS data, SUM(`counter`) AS somma FROM hfu_tracking_data WHERE post_id IN (SELECT post_id FROM hfu_postmeta WHERE meta_key = 'vendor_user' AND meta_value = " . get_current_user_id() . ") AND DATE(`data`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE() AND `type` = 'impression' GROUP BY DATE(`data`)");
	
	if(current_user_can( 'venditore' ) || current_user_can( 'operatore' )){
	
	?>	

		<div>
			<h2>Impressioni sui miei prodotti</h2>
		  	<canvas id="myChart" width="400" height="100" ></canvas>
		</div>

		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

		<script>

			// recupera i dati dalla query e inizializza gli array
			var dati = <?php echo json_encode($result); ?>;
			var labels = [];
			var valori = [];

			// itera attraverso i risultati della query e popola gli array di etichette e valori
			for (var i = 0; i < dati.length; i++) {
				labels.push(dati[i].data);
				valori.push(dati[i].somma);
			}

			
			
			const ctx1 = document.getElementById('myChart');

			new Chart(ctx1, {
				type: 'bar',
				data: {
					labels:  labels,
					datasets: [{
						label: 'Impressioni ultimi 30 giorni',
						data: valori,
						borderWidth: 1
					}]
				},
				options: {
					scales: {
						y: {
							beginAtZero: true
						}
					}
				}
			});
		</script>
	<?php
	}
}
add_shortcode('total_impression_chart_user', 'total_impression_user');