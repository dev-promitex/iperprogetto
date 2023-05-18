<?php

function menu_items_voice()
{

	add_submenu_page(
		'crea voce', //slug della pagina principale
		'crea voce', //titolo della sottopagina
		'Crea', //nome della sottovoce di menu
		'manage_options', //permessi richiesti
		'create-voice', //slug della sottopagina
		'create_voice' //nome della funzione che mostra il contenuto della sottopagina
  		);
	
	add_submenu_page(
		'dashboard', //slug della pagina principale
		'Lista voci', //titolo della sottopagina
		'Lista voci', //nome della sottovoce di menu
		'manage_options', //permessi richiesti
		'voice-list', //slug della sottopagina
		'voice_list' //nome della funzione che mostra il contenuto della sottopagina
	  	);
	
	add_submenu_page(
		'Modifica voci', //slug della pagina principale
		'Modifica voci', //titolo della sottopagina
		'', //nome della sottovoce di menu
		'manage_options', //permessi richiesti
		'voice-edit', //slug della sottopagina
		'voice_edit' //nome della funzione che mostra il contenuto della sottopagina
  		);
	
	add_submenu_page(
		'elimina voci', //slug della pagina principale
		'elimina voci', //titolo della sottopagina
		'', //nome della sottovoce di menu
		'manage_options', //permessi richiesti
		'delete-voice', //nome della funzione che mostra il contenuto della sottopagina', //slug della sottopagina
		'delete_voice' //nome della funzione che mostra il contenuto della sottopagina
  		);
	
	

}

function voice_list(){
	
	global $wpdb;
	
	if (isset( $_POST['Elimina'])) {
		
	
		$id = $_POST['Elimina']; 
		$table_name = 'hfu_pur_voice';

		$wpdb->delete($table_name, array('id' => intval($id)), array('%d'));
	}
	
?>
		<head>
			
			<style>
				
			table tr td:nth-child(-n+11),
			table tr th:nth-child(-n+11) {
			  width: 75px;
			}

			table tr td:nth-child(7),
			table tr th:nth-child(7) {
			  width: 200px;
			}
			
			</style>
		
		</head>

		<body style="margin-right:10px; margin-top:25px;">
			
			<h1>Elenco voci</h1>
			<div style="margin-top:10px; margin-bottom:10px; float:right">
				<a href="https://iperprogetto.it/wp-admin/admin.php?page=create-voice">
					<span type="submit" value="Crea capitolo" class="button-primary">Crea voce</span>
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
					<th>Sottocapitolo</th>
					<th>Paragrafo</th>
					<th>Voce N.</th>
					<th>Contenuto paragrafo</th>
					<th>Unità</th>
					<th>Prezzo</th>
					<th>% IVA</th>
					<th>Azioni</th>
				</tr>
			</thead>';

    //query per leggere i dati dalla tabella
    $results = $wpdb->get_results("SELECT * FROM `hfu_pur_voice`");
	

    if ($results > 0) {

        //corpo della tabella
        echo '<tbody>';

        foreach ($results as $result) {
            echo '<tr>
                    <td >' .$result->id . '</td>
                    <td>' . $result->region . '</td>
                    <td>' . $result->chapter . '</td>
					<td>' . $result->subchapter . '</td>
					<td>' . $result->paragraphs . '</td>
					<td>' . $result->voice_id . '</td>
					<td style="white-space:nowrap; overflow:hidden; width:20px; height:20px; text-overflow:ellipsis;">' . $result->text_content . '</td>
					<td>' . $result->unit . '</td>
					<td>' . $result->price . '</td>
					<td>' . $result->vat . '</td>
                    <td>
					
                        <a href="' . admin_url( 'admin.php?page=voice-edit&id=' . $result->id ) . '"><span class="dashicons dashicons-edit"></span></a>
						
						<form method="post">
						  <!-- Campi di input per i dati -->
							<input type="hidden" name="Elimina" value="'.$result->id.'">
							<input type="hidden" name="id"  value="'.esc_attr($result->id).'"></input>
							<a href="javascript:void(0);" onclick="'. "if (confirm('Sei sicuro di voler eliminare questo record?')) { this.parentNode.submit(); }".'">
								<span class="dashicons dashicons-trash"></span>
							  </a>
						</form>

                    </td>
                </tr>';

        }
    } 
    
    else
    {

        echo '<tr><td>nulla</td><td>nulla</td><td>nulla</td></tr>';
    }
	
	
?>

	
					</tbody>
				</table>
	</body>
	


<?php
	
}

function voice_edit(){
	
	global $wpdb;
	wp_enqueue_editor();
	
	$allowed_tags = array(
		'a' => array(
			'href' => array(),
			'title' => array()
		),
		'p' => array(),
		'br' => array(),
		'em' => array(),
		'strong' => array()
	);

	
	$id = $_GET['id'];
	
	if (isset( $_POST['azione'] ) && $_POST['azione'] == 'aggiorna_dati' ) {


		$table_name ='hfu_pur_voice';


		$filtered_text_content = wp_kses( $_POST['text_content'], $allowed_tags );
		
		$data = array(
			
			"region" => $_POST['value_selectbox'],
			"chapter" => $_POST['chapter'],
			"subchapter" => $_POST['subchapter'],
			"paragraphs" => $_POST['paragraphs'],
			"voice_id" => $_POST['voice_id'],
			"text_content" => stripslashes($filtered_text_content),
			"unit" => $_POST['unit'],
			"price" => $_POST['price'],
			"vat" => $_POST['vat']
		);
		
		

		$where = array(
			'id' => intval($id),
		);


	   	$wpdb->update( $table_name, $data, $where);
		header("location: https://iperprogetto.it/wp-admin/admin.php?page=voice-list");

	}
	
	
	 //query per leggere i dati dalla tabella
    $results = $wpdb->get_results("SELECT * FROM hfu_pur_voice where id=". $id);
	
	
	$region;
	$chapter;
	$subchapter;
	$paragraphs;
	$voice_id;
	$text_content;
	$unit;
	$price;
	$vat;
				
	foreach ($results as $result) {
		
		$region = $result->region;
		$chapter = $result->chapter;
		$subchapter = $result->subchapter;
		$paragraphs = $result->paragraphs;
		$voice_id = $result->voice_id;
		$text_content = $result->text_content;
		$unit = $result->unit;
		$price = $result->price;
		$vat = $result->vat;
	
			
	}
	
	  // Imposta le opzioni per l'editor
    $settings = array(
        'textarea_name' => 'text_content',
        'textarea_rows' => 10,
        'editor_height' => 200,
        'drag_drop_upload' => true
    );
	
	$filtered_text_content = wp_kses( $text_content, $allowed_tags );

	
	?>
		<head>

		</head>

		<body>
			<div style="padding-top:25px;">
				<h1>Modifica Sottocapitolo</h1>
			</div>
		
			<div style="margin-right:20px;">
				<form method="post">
					<input type="hidden" name="azione" value="aggiorna_dati">
					<label>Regione</label>
					<select name="value_selectbox">
					<?php
						$selected = $region ? sanitize_text_field($region) : '';
						$options = array(
						  'Sicilia' => 'Sicilia',
						  'Lombardia' => 'Lombardia',
						  'Piemonte' => 'Piemonte',
						);
						foreach ( $options as $value => $label ) {
						  echo '<option value="' . esc_attr( $value ) . '"' . selected( $selected, $value, false ) . '>' . esc_html( $label ) . '</option>';
						}
					?>
					</select>
					<label>Capitolo: </label><input type="text" name="chapter" value="<?php echo esc_attr($chapter); ?>"></input>
					<label>Sottocapitolo: </label><input type="text" name="subchapter" value="<?php echo esc_attr($subchapter); ?>"></input>
					<label>Paragrafo:</label><input type="text" name="paragraphs" value="<?php echo esc_attr($paragraphs); ?>"></input>
					<label>Voce N.:</label><input type="text" name="voice_id" value="<?php echo esc_attr($voice_id); ?>"></input><br><br>
					<h2 style="font-size:25px;">Modifica voce</h2>
					<?php wp_editor( stripslashes($filtered_text_content), 'text_content', $settings ); ?><br>
					<label>Unità: </label><input type="text" name="unit" value="<?php echo esc_attr($unit); ?>"></input>
					<label>Prezzo: </label><input type="text" name="price" value="<?php echo esc_attr($price); ?>"></input>
					<label>IVA:</label><input type="text" name="vat" value="<?php echo esc_attr($vat); ?>"></input><br><br>

					<input type="submit" value="Salva" class="button-primary" style="float:right;">
				</form>

				<br>


			</div>

		</body>

<?php
	
}

function create_voice(){
	
	global $wpdb;
	wp_enqueue_editor();
	
	$allowed_tags = array(
		'a' => array(
			'href' => array(),
			'title' => array()
		),
		'p' => array(),
		'br' => array(),
		'em' => array(),
		'strong' => array()
	);

	
	if (isset( $_POST['azione'] ) && $_POST['azione'] == 'aggiungi' ) {
		
		$filtered_text_content = wp_kses( $_POST['text_content'], $allowed_tags );

		
		$data = array(
		  	"region" => $_POST['value_selectbox'],
			"chapter" => $_POST['chapter'],
			"subchapter" => $_POST['subchapter'],
			"paragraphs" => $_POST['paragraphs'],
			"voice_id" => $_POST['voice_id'],
			"text_content" => stripslashes($filtered_text_content),
			"unit" => $_POST['unit'],
			"price" => $_POST['price'],
			"vat" => $_POST['vat']
		);
		
		
		//echo var_dump($data);	

		
		$wpdb->insert('hfu_pur_voice', $data);
		header("location: https://iperprogetto.it/wp-admin/admin.php?page=voice-list");	
	}
	
	// Imposta le opzioni per l'editor
    $settings = array(
        'textarea_name' => 'text_content',
        'textarea_rows' => 10,
        'editor_height' => 200,
        'drag_drop_upload' => true
    	);
	
	
?>

	<head>
	</head>
	<body>
		
		<div>
			<h1>Crea voce</h1>
		</div>

		<div style="margin-right:20px;">

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
	
				foreach ( $options as $value => $label ) {
				  echo '<option value="' . esc_attr( $value ) . '"' . selected( $selected, $value, false ) . '>' . esc_html( $label ) . '</option>';
				}
				?>
			    </select>
				<label>Capitolo: </label><input type="text" name="chapter"></input>
				<label>Sottocapitolo: </label><input type="text" name="subchapter"></input>
				<label>Paragrafo:</label><input type="text" name="paragraphs"></input>
				<label>Voce N.:</label><input type="text" name="voice_id">"></input><br><br>
				<h2 style="font-size:18px;">Testo voce</h2>
				<?php wp_editor( '', 'text_content', $settings ); ?><br>
				<label>Unità: </label><input type="text" name="unit"></input>
				<label>Prezzo: </label><input type="text" name="price"></input>
				<label>IVA:</label><input type="text" name="vat"></input><br><br>
				<input type="submit" value="Salva" class="button-primary" style="float:right;">
			</form>

			</div>

		</body>

		<script>

		</script>	


<?php
	
	
}

function delete_voice(){
	
	global $wpdb;
	
	if ( ! isset( $_POST['id'], $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'delete_record' ) ) {
    // Non inviare la richiesta da una fonte attendibile
    return;
  }

  // Eseguire la query per eliminare il record dal database
  $wpdb->delete( $wpdb->prefix . 'hfu_pur_content', array( 'id' => intval( $_POST['id'] ) ), array( '%d' ) );

  // Reindirizzare l'utente o restituire un messaggio di successo
	
}



/*function dashboard()
{
	
?>
		<head></head>
		<body>
			<h1>Pagina della Dashboard</h1>
		</body>


<?php

}*/

add_action('admin_menu', 'menu_items_voice');

