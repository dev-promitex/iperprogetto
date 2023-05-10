<?php

// Aggiungi una voce custom alla pagina "My Account"
function custom_my_account_menu_items_($items)
{

	$new_item = array('note-salvate' => 'Note salvate');
	$items = array_slice($items, 0, 2, true) + $new_item + array_slice($items, 2, NULL, true);
	return $items;
}

// Registra l'endpoint per la voce custom
function custom_my_account_add_endpoint()
{
	add_rewrite_endpoint('note-salvate', EP_PAGES);
}

// Mostra il contenuto della voce custom
function custom_my_account_endpoint_content()
{

	global $wpdb;

	$user_id = get_current_user_id();

	$results = $wpdb->get_results("SELECT hfu_users_note.id, hfu_pur_voice.region, hfu_pur_voice.chapter, hfu_pur_voice.subchapter, hfu_pur_voice.paragraphs, hfu_pur_voice.voice_id, hfu_pur_voice.unit, hfu_pur_voice.price,hfu_pur_voice.vat FROM hfu_pur_voice INNER JOIN hfu_users_note ON hfu_pur_voice.id = hfu_users_note.id_ai_voice WHERE `user_id`= '$user_id'");


?>

	<head>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.8/xlsx.full.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/blob@0.0.2/Blob.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/jszip@3.7.1/dist/jszip.min.js"></script>


		<style>
			table {
				font-family: arial, sans-serif;
				border-collapse: collapse;
				width: 100%;
			}

			td,
			th,
			thead {
				border-top: 1px solid #dddddd;
				border-bottom: 1px solid #dddddd;
				text-align: center;
				padding: 15px;

			}

			tr:nth-child(even) {
				background-color: #dddddd;
			}

			.button-pag {
				display: inline-block;
				text-align: center;
				padding: 5px 10px;
				margin: 0 5px;
				background-color: #0073aa;
				color: #fff;
				border: none;
				border-radius: 3px;
				box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
				cursor: pointer;
			}

			.button-pag:hover {
				background-color: #006395;
			}
		</style>

	</head>

	<body>

		<div class="row">
			<h2>Voci paragrafi salvate</h2>
			<table id="my-table">
				<thead id="head_table">
					<tr>
						<th>Seleziona</th>
						<th>Regione</th>
						<th>Capitolo</th>
						<th>Sottocapitolo</th>
						<th>Paragarafo</th>
						<th>Voce N.</th>
						<th>Unità</th>
						<th>Prezzo</th>
						<th>% IVA</th>
						<th>Azione</th>
					</tr>
				</thead>
				<tbody>



					<?php


					foreach ($results as $result) {
						echo '	<tr id="' . $result->id . '">
					<td><input type="checkbox" data_id_check="' . $result->id . '"></td>
					<td>' . $result->region . '</td>
					<td>' . $result->chapter . ' </td>
					<td>' . $result->subchapter . ' </td>
					<td>' . $result->paragraphs . ' </td>
					<td>' . $result->voice_id . ' </td>
					<td>' . $result->unit . ' </td>
					<td>€' . $result->price . ' </td>
					<td>' . $result->vat . '</td>
					<td><button id="delete_button" data-id="' . $result->id . '" class="button button-danger" style="background-color: red;  border-color: red;">Cancella nota</button></td>
					</tr>';
					}



					?>

				</tbody>
			</table>
		</div>
		<div class="row">
			<div class="column" style="margin-bottom: 15px; float:left;">
				<button id="export_all_notes" class="button button-primary" style="background-color: green;">Esporta tutto in foglio Excel</button>
				<button id="export_select_notes" class="button button-primary" style="background-color: green;">Esporta selezione in foglio Excel</button>
			</div>

			<div class="column" style="margin-bottom: 15px; float:right;">
				<div id="pagination-container" style="float:right;">
					<button id="prev-page" class="button-pag">Indietro</button>
					<span></span>
					<button id="next-page" class="button-pag">Avanti</button>
				</div>


			</div>

		</div>
	</body>

	<script>
		jQuery(document).ready(function($) {

			$(document).on('click', '#delete_button', function() {

				if (confirm("Sei sicuro di voler rimuovere questa riga?")) {
					var id_nota = $(this).attr('data-id');
					console.log(id_nota);
					$.ajax({
						url: "/wp-admin/admin-ajax.php",
						type: "POST",
						data: {
							action: "delete_user_voice_saved_in_user_area",
							id_nota: id_nota
						},
						success: function(response) {
							$('#' + id_nota).remove(); // rimuove la riga con ID "row2"

						}

					});
				}
			});

			//codice paginazione tabella
			//
			var table = $('#my-table');
			var tbody = table.find('tbody');
			var rowsPerPage = 6;
			var currentPage = 1;
			var totalPages = Math.ceil(tbody.find('tr').length / rowsPerPage);

			function showPage(page) {
				tbody.find('tr').hide();
				var start = (page - 1) * rowsPerPage;
				var end = start + rowsPerPage;
				tbody.find('tr').slice(start, end).show();
				currentPage = page;
				updatePagination();
			}

			function updatePagination() {
				$('#pagination-container').empty();

				if (currentPage > 1) {
					$('<button id="prev-page" class="button-pag">Indietro</button>').appendTo('#pagination-container');
				}

				$('<span>Pagina ' + currentPage + ' di ' + totalPages + '</span>').appendTo('#pagination-container');

				if (currentPage < totalPages) {
					$('<button id="next-page" class="button-pag">Avanti</button>').appendTo('#pagination-container');
				}

				// Disabilita il pulsante "Indietro" quando si è sulla prima pagina
				if (currentPage == 1) {
					$('#prev-page').prop('disabled', true);
				} else {
					$('#prev-page').prop('disabled', false);
				}

				// Disabilita il pulsante "Avanti" quando si è sull'ultima pagina
				if (currentPage == totalPages) {
					$('#next-page').prop('disabled', true);
				} else {
					$('#next-page').prop('disabled', false);
				}
			}

			showPage(currentPage);

			$('#pagination-container').on('click', '#prev-page', function() {
				if (currentPage > 1) {
					showPage(currentPage - 1);
				}
			});

			$('#pagination-container').on('click', '#next-page', function() {
				if (currentPage < totalPages) {
					showPage(currentPage + 1);
				}
			});

			updatePagination();


			$(document).on('click', '#export_all_notes', function() {
				// Ottieni la tabella dal DOM
				var table = document.getElementById("my-table");

				// Crea un array per contenere le righe selezionate
				var selectedRows = [];

				// Itera attraverso le righe della tabella
				for (var i = 1; i < table.rows.length; i++) {
					// Ottieni la casella di controllo per la riga corrente

					// Se la casella di controllo è stata selezionata, aggiungi la riga all'array delle righe selezionate
					selectedRows.push({
						'REGIONE': table.rows[i].cells[1].textContent,
						'CAPITOLO': table.rows[i].cells[2].textContent,
						'SOTTOCAPITOLO': table.rows[i].cells[3].textContent,
						'PARAGARAFO': table.rows[i].cells[4].textContent,
						'VOCE N.': table.rows[i].cells[5].textContent,
						'UNITÀ': table.rows[i].cells[6].textContent,
						'PREZZO': table.rows[i].cells[7].textContent,
						'% IVA': table.rows[i].cells[8].textContent
						// Aggiungi qui le colonne che desideri esportare
					});

				}

				// Crea un nuovo foglio di calcolo con le righe selezionate
				var worksheet = XLSX.utils.json_to_sheet(selectedRows);

				// Crea un nuovo libro di lavoro e aggiungi il foglio di calcolo
				var workbook = XLSX.utils.book_new();
				XLSX.utils.book_append_sheet(workbook, worksheet, "Righe selezionate");

				// Esporta il file Excel sul computer dell'utente
				XLSX.writeFile(workbook, "righe_selezionate.xlsx");
			});

			$(document).on('click', '#export_select_notes', function() {

				// Ottieni la tabella dal DOM
				var table = document.getElementById("my-table");

				// Crea un array per contenere le righe selezionate
				var selectedRows = [];

				// Itera attraverso le righe della tabella
				for (var i = 1; i < table.rows.length; i++) {
					// Ottieni la casella di controllo per la riga corrente
					var checkbox = table.rows[i].cells[0].getElementsByTagName("input")[0];

					// Se la casella di controllo è stata selezionata, aggiungi la riga all'array delle righe selezionate
					if (checkbox.checked) {
						selectedRows.push({
							'REGIONE': table.rows[i].cells[1].textContent,
							'CAPITOLO': table.rows[i].cells[2].textContent,
							'SOTTOCAPITOLO': table.rows[i].cells[3].textContent,
							'PARAGARAFO': table.rows[i].cells[4].textContent,
							'VOCE N.': table.rows[i].cells[5].textContent,
							'UNITÀ': table.rows[i].cells[6].textContent,
							'PREZZO': table.rows[i].cells[7].textContent,
							'% IVA': table.rows[i].cells[8].textContent
							// Aggiungi qui le colonne che desideri esportare
						});
					}
				}

				// Crea un nuovo foglio di calcolo con le righe selezionate
				var worksheet = XLSX.utils.json_to_sheet(selectedRows);

				// Crea un nuovo libro di lavoro e aggiungi il foglio di calcolo
				var workbook = XLSX.utils.book_new();
				XLSX.utils.book_append_sheet(workbook, worksheet, "Righe selezionate");

				// Esporta il file Excel sul computer dell'utente
				XLSX.writeFile(workbook, "righe_selezionate.xlsx");
			});



		});
	</script>

<?php

}


function delete_user_voice_saved()
{
	global $wpdb;

	$id = $_POST['id_nota'];

	$wpdb->query("DELETE FROM `hfu_users_note` WHERE `id`='$id'");

	wp_die();
}





add_filter('woocommerce_account_menu_items', 'custom_my_account_menu_items_', 10, 1);
add_action('init', 'custom_my_account_add_endpoint');
add_action('woocommerce_account_note-salvate_endpoint', 'custom_my_account_endpoint_content');
add_action("wp_ajax_delete_user_voice_saved_in_user_area", "delete_user_voice_saved");
