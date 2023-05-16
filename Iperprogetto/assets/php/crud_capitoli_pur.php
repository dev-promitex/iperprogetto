<?php
function register_my_menu_item()
{

	add_menu_page(
		'Dashboard', //titolo della pagina
		'IperProgetto', //nome della voce di menu
		'manage_options', //permessi richiesti
		'dashboard', //slug della pagina
		'dashboard', //nome della funzione che mostra il contenuto della pagina
		'', //icona della voce di menu
		6 //posizione della voce di menu nel menu di amministrazione
	);

	add_submenu_page(
		'crea capitolo', //slug della pagina principale
		'crea capitolo', //titolo della sottopagina
		'Crea', //nome della sottovoce di menu
		'manage_options', //permessi richiesti
		'crea_capitolo', //slug della sottopagina
		'create' //nome della funzione che mostra il contenuto della sottopagina
	);

	add_submenu_page(
		'dashboard', //slug della pagina principale
		'Lista capitoli', //titolo della sottopagina
		'Lista capitoli', //nome della sottovoce di menu
		'manage_options', //permessi richiesti
		'lista-capitoli', //slug della sottopagina
		'lista' //nome della funzione che mostra il contenuto della sottopagina
	);

	add_submenu_page(
		'modifica capitolo', //slug della pagina principale
		'modifica capitolo', //titolo della sottopagina
		'', //nome della sottovoce di menu
		'manage_options', //permessi richiesti
		'modifica_capitolo', //slug della sottopagina
		'modifica' //nome della funzione che mostra il contenuto della sottopagina
	);

	add_submenu_page(
		'elimina capitolo', //slug della pagina principale
		'elimina capitolo', //titolo della sottopagina
		'', //nome della sottovoce di menu
		'manage_options', //permessi richiesti
		'elimina_capitolo', //slug della sottopagina
		'delete' //nome della funzione che mostra il contenuto della sottopagina
	);
}

function lista()
{

	global $wpdb;

	if (isset($_POST['azione']) && $_POST['azione'] == 'elimina_record') {


		$id = $_POST['id']; //$_GET['id'];


		$table_name = 'hfu_pur_chapters';

		$wpdb->delete($table_name, array('id' => intval($id)), array('%d'));
	}

?>

	<head>

	</head>

	<body style="margin-right:10px;">

		<h1>Elenco capitoli</h1>
		<div style="margin-top:10px; margin-bottom:10px; float:right">
			<a href="https://iperprogetto.it/wp-admin/admin.php?page=crea_capitolo">
				<span type="submit" value="Crea capitolo" class="button-primary">Crea capitolo</span>
			</a>
		</div>
		<div>
			<table class="wp-list-table widefat fixed striped posts">

				<?php

				//intestazione della tabella
				echo '<thead>
				<tr>
					<th>ID</th>
					<th>Regione</th>
					<th>Capitolo</th>
					<th>Nome</th>
					<th>Azioni</th>
				</tr>
			</thead>';

				//query per leggere i dati dalla tabella
				$results = $wpdb->get_results("SELECT * FROM hfu_pur_chapters", OBJECT);

				if ($results > 0) {

					//corpo della tabella
					echo '<tbody>';

					foreach ($results as $result) {
						echo '<tr>
                    <td>' . $result->id . '</td>
                    <td>' . $result->region . '</td>
                    <td>' . $result->chapter . '</td>
                    <td>' . $result->name_chapter . '</td>
                    <td>
					
                        <a href="' . admin_url('admin.php?page=modifica_capitolo&id=' . $result->id) . '"><span class="dashicons dashicons-edit"></span></a>
						
						<form method="post">
						  <!-- Campi di input per i dati -->
							<input type="hidden" name="azione" value="elimina_record">
							<input type="hidden" name="id" value="' . esc_attr($result->id) . '"></input>
							<a href="javascript:void(0);" onclick="' . "if (confirm('Sei sicuro di voler eliminare questo record?')) { this.parentNode.submit(); }" . '">
								<span class="dashicons dashicons-trash"></span>
							  </a>
						</form>

                    </td>
                </tr>';
					}
				} else {

					echo '<tr><td>nulla</td><td>nulla</td><td>nulla</td></tr>';
				}


				?>


				</tbody>
			</table>
	</body>



<?php




}

function modifica()
{

	global $wpdb;

	$id = $_GET['id'];

	if (isset($_POST['azione']) && $_POST['azione'] == 'aggiorna_dati') {

		//echo $_POST['value_selectbox'], $_POST['chapter'], $_POST['name_chapter'];

		$table_name = 'hfu_pur_chapters';

		$data = array(
			'region' => sanitize_text_field($_POST['value_selectbox']),
			'chapter' => sanitize_text_field($_POST['chapter']),
			'name_chapter' => sanitize_text_field($_POST['name_chapter'])
		);

		$where = array(
			'id' => intval($id),
		);

		echo $table_name;

		$wpdb->update($table_name, $data, $where);
		header("location: https://iperprogetto.it/wp-admin/admin.php?page=lista-capitoli");
	}


	//query per leggere i dati dalla tabella
	$results = $wpdb->get_results("SELECT * FROM hfu_pur_chapters where id=" . $id, OBJECT);


	$region;
	$chapter;
	$name_chapter;


	foreach ($results as $result) {

		$region = $result->region;
		$chapter = $result->chapter;
		$name_chapter = $result->name_chapter;
	}

?>

	<head></head>

	<body>
		<div>
			<h1>Modifica capitolo</h1>
		</div>

		<div>

			<form method="post">
				<!-- Campi di input per i dati -->
				<input type="hidden" name="azione" value="aggiorna_dati">

				<select name="value_selectbox">
					<?php
					$selected = $region ? sanitize_text_field($region) : '';
					$options = array(
						'Sicilia' => 'Sicilia',
						'Lombardia' => 'Lombardia',
						'Piemonte' => 'Piemonte',
					);

					foreach ($options as $value => $label) {
						echo '<option value="' . esc_attr($value) . '"' . selected($selected, $value, false) . '>' . esc_html($label) . '</option>';
					}
					?>
				</select>

				<input type="text" name="chapter" value="<?php echo esc_attr($chapter); ?>"></input>
				<input type="text" name="name_chapter" value="<?php echo esc_attr($name_chapter); ?>"></input>
				<input type="submit" value="Aggiorna">
			</form>


		</div>

	</body>


<?php



}

function delete_record()
{


	global $wpdb;



	if (!isset($_POST['id'], $_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'delete_record')) {
		// Non inviare la richiesta da una fonte attendibile
		return;
	}

	// Eseguire la query per eliminare il record dal database
	$wpdb->delete($wpdb->prefix . 'hfu_pur_chapters', array('id' => intval($_POST['id'])), array('%d'));

	// Reindirizzare l'utente o restituire un messaggio di successo

}

function create()
{

	global $wpdb;

	$id = $_GET['id'];

	if (isset($_POST['azione']) && $_POST['azione'] == 'aggiungi') {

		$table_name = 'hfu_pur_chapters';

		$data = array(
			'region' =>  sanitize_text_field($_POST['value_selectbox']),
			'chapter' => sanitize_text_field($_POST['chapter']),
			'name_chapter' => sanitize_text_field($_POST['name_chapter']),
		);

		$format = array(
			'%s',
			'%s',
			'%s'
		);

		$wpdb->insert($table_name, $data, $format);
		header("location: https://iperprogetto.it/wp-admin/admin.php?page=lista-capitoli");
	}

?>

	<head></head>

	<body>
		<div>
			<h1>Crea capitolo</h1>
		</div>

		<div>

			<form method="post">
				<!-- Campi di input per i dati -->
				<input type="hidden" name="azione" value="aggiungi">

				<select name="value_selectbox">
					<?php
					$selected = $region ? sanitize_text_field($region) : '';
					$options = array(
						'Sicilia' => 'Sicilia',
						'Lombardia' => 'Lombardia',
						'Piemonte' => 'Piemonte',
					);

					foreach ($options as $value => $label) {
						echo '<option value="' . esc_attr($value) . '"' . selected($selected, $value, false) . '>' . esc_html($label) . '</option>';
					}
					?>
				</select>

				<input type="text" name="chapter"></input>
				<input type="text" name="name_chapter"></input>
				<input type="submit" value="Crea">
			</form>


		</div>

	</body>


<?php


}



function dashboard()
{

?>

	<head></head>

	<body>
		<h1>Pagina della Dashboard</h1>
	</body>


<?php

}

add_action('admin_menu', 'register_my_menu_item');
