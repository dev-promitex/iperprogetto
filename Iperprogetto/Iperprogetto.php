<?php
/**
* Plugin Name: IperProgetto
* Plugin URI: https://www.iperprogetto.it/
* Description: Marketplace di iperprogetto.
* Version: 1.0
* Author: Promitex S.R.L.
* Author URI: https://www.promitex.it/
**/

/**
 * CRUD Capitoli P.U.R.
 */
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

function lista(){
	
	global $wpdb;
	
	if (isset( $_POST['azione'] ) && $_POST['azione'] == 'elimina_record' ) {
		
	
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
					
                        <a href="' . admin_url( 'admin.php?page=modifica_capitolo&id=' . $result->id ) . '"><span class="dashicons dashicons-edit"></span></a>
						
						<form method="post">
						  <!-- Campi di input per i dati -->
							<input type="hidden" name="azione" value="elimina_record">
							<input type="hidden" name="id" value="'.esc_attr($result->id).'"></input>
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

function modifica(){
	
	global $wpdb;
	
	$id = $_GET['id'];
	
		if (isset( $_POST['azione'] ) && $_POST['azione'] == 'aggiorna_dati' ) {

			//echo $_POST['value_selectbox'], $_POST['chapter'], $_POST['name_chapter'];
		  	
			$table_name ='hfu_pur_chapters';
		  
			$data = array(
			'region' => sanitize_text_field( $_POST['value_selectbox'] ),
			'chapter' => sanitize_text_field( $_POST['chapter'] ),
			'name_chapter' => sanitize_text_field( $_POST['name_chapter'] )
		  	);
			
		  	$where = array(
			'id' => intval($id),
		  	);
			
			echo $table_name;

	   $wpdb->update( $table_name, $data, $where);
		header("location: https://iperprogetto.it/wp-admin/admin.php?page=lista-capitoli");

	}
	
	
	 //query per leggere i dati dalla tabella
    $results = $wpdb->get_results("SELECT * FROM hfu_pur_chapters where id=". $id, OBJECT);
	
	
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
	
				foreach ( $options as $value => $label ) {
				  echo '<option value="' . esc_attr( $value ) . '"' . selected( $selected, $value, false ) . '>' . esc_html( $label ) . '</option>';
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

function delete_record(){
	
	
	global $wpdb;
	

	
	if ( ! isset( $_POST['id'], $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'delete_record' ) ) {
    // Non inviare la richiesta da una fonte attendibile
    return;
  }

  // Eseguire la query per eliminare il record dal database
  $wpdb->delete( $wpdb->prefix . 'hfu_pur_chapters', array( 'id' => intval( $_POST['id'] ) ), array( '%d' ) );

  // Reindirizzare l'utente o restituire un messaggio di successo
	
}

function create(){
	
	global $wpdb;
	
	$id = $_GET['id'];
	
		if (isset( $_POST['azione'] ) && $_POST['azione'] == 'aggiungi' ) {
			
			$table_name = 'hfu_pur_chapters';

			$data = array(
			  'region' =>  sanitize_text_field( $_POST['value_selectbox'] ),
			  'chapter' => sanitize_text_field( $_POST['chapter'] ),
			  'name_chapter' => sanitize_text_field( $_POST['name_chapter'] ),
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
	
				foreach ( $options as $value => $label ) {
				  echo '<option value="' . esc_attr( $value ) . '"' . selected( $selected, $value, false ) . '>' . esc_html( $label ) . '</option>';
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

/**
 * CRUD Paragrafi P.U.R.
 */
function menu_items()
{
	add_submenu_page(
		'crea paragrafo', //slug della pagina principale
		'crea paragrafo', //titolo della sottopagina
		'Crea', //nome della sottovoce di menu
		'manage_options', //permessi richiesti
		'create-paragraph', //slug della sottopagina
		'create_paragraph' //nome della funzione che mostra il contenuto della sottopagina
  		);
	
	add_submenu_page(
		'dashboard', //slug della pagina principale
		'Lista paragrafi', //titolo della sottopagina
		'Lista paragrafi', //nome della sottovoce di menu
		'manage_options', //permessi richiesti
		'paragraph-list', //slug della sottopagina
		'paragraph_list' //nome della funzione che mostra il contenuto della sottopagina
	  	);
	
	add_submenu_page(
		'Modifica paragrafo', //slug della pagina principale
		'Modifica paragrafo', //titolo della sottopagina
		'', //nome della sottovoce di menu
		'manage_options', //permessi richiesti
		'paragraph-edit', //slug della sottopagina
		'paragraph_edit' //nome della funzione che mostra il contenuto della sottopagina
  		);
	
	add_submenu_page(
		'elimina paragrafo', //slug della pagina principale
		'elimina paragrafo', //titolo della sottopagina
		'', //nome della sottovoce di menu
		'manage_options', //permessi richiesti
		'delete-paragraph', //nome della funzione che mostra il contenuto della sottopagina', //slug della sottopagina
		'delete_paragraph' //nome della funzione che mostra il contenuto della sottopagina
  		);
}

function paragraph_list(){
	
	global $wpdb;
	
	if (isset( $_POST['Elimina'])) {
		
	
		$id = $_POST['Elimina']; 
		//echo $id;
		$table_name = 'hfu_pur_content';

		$wpdb->delete($table_name, array('id' => intval($id)), array('%d'));
	}
	
?>
		<head>
			
			<style>
				
			table tr td:nth-child(-n+5),
			table tr th:nth-child(-n+5) {
			  width: 100px;
			}

			table tr td:nth-child(6),
			table tr th:nth-child(6) {
			  width: 200px;
			}
				
				table tr td:nth-child(7),
			table tr th:nth-child(7) {
			  width: 100px;
			}
				table tr td:nth-child(8),
			table tr th:nth-child(8) {
			  width: 100px;
			}
			
			</style>
		
		</head>

		<body style="margin-right:10px; margin-top:25px;">
			
			<h1>Elenco paragrafi</h1>
			<div style="margin-top:10px; margin-bottom:10px; float:right">
				<a href="https://iperprogetto.it/wp-admin/admin.php?page=create-paragraph">
					<span type="submit" value="Crea capitolo" class="button-primary">Crea Sottocapitolo</span>
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
					<th>Contenuto paragrafo</th>
					<th>Paorle chiavi</th>
					<th>Azioni</th>
				</tr>
			</thead>';

    //query per leggere i dati dalla tabella
    $results = $wpdb->get_results("SELECT * FROM hfu_pur_content", OBJECT);
	

    if ($results > 0) {

        //corpo della tabella
        echo '<tbody>';

        foreach ($results as $result) {
            echo '<tr>
                    <td>' . $result->id . '</td>
                    <td>' . $result->region . '</td>
                    <td>' . $result->chapter . '</td>
					<td>' . $result->subchapter . '</td>
					<td>' . $result->head_paragraphs . '</td>
					<td style="white-space:nowrap; overflow:hidden; width:20px; height:20px; text-overflow:ellipsis;">' . $result->paragraphs_content . '</td>
					<td class="editable-cell" data-id="'. $result->id .'">' . $result->keywords . '</td>
                    <td>
					
                        <a href="' . admin_url( 'admin.php?page=paragraph-edit&id=' . $result->id ) . '"><span class="dashicons dashicons-edit"></span></a>
						
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

<script>
	
	jQuery(document).ready(function($) {

		$(document).ready(function() {
		  // Aggiungi l'evento click alla cella per renderla editabile
		  $(".editable-cell").click(function() {
			$(this).attr("contentEditable", true);
		  });

		  // Aggiungi l'evento blur alla cella per inviare la richiesta Ajax quando si perde il focus
		  $(".editable-cell").blur(function() {
			$(this).attr("contentEditable", false);
			// Prendi il contenuto della cella modificata
			let keyword_content = $(this).text();
			let id = $(this).attr("data-id");

			// Invia la richiesta Ajax per salvare le modifiche
		    $.ajax({
				  url: "/wp-admin/admin-ajax.php",
				  type: "POST",
				  data: { 
					  action: "update_keyword",
					  id: id,
					  key: keyword_content 
				  },
				  success: function(response) {
					// Aggiorna l'interfaccia utente in caso di successo
					//alert("Dati aggiornati");
				  },
				  error: function(response) {
					alert("Errore");
				  }
			});
		  });
		});
	});


</script>
	


<?php
	
}

function paragraph_edit(){
	
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


		$table_name ='hfu_pur_content';
		
		
		$filtered_paragraphs_content = wp_kses( $_POST['paragraphs_content'], $allowed_tags );
		
		//echo $filtered_paragraphs_content;
		
		$data = array(
		  "region" => $_POST['value_selectbox'],
		  "chapter" => $_POST['chapter'],
		  "subchapter" => $_POST['subchapter'],
		  "head_paragraphs" =>  $_POST['head_paragraphs'],
		  "paragraphs_content" => stripslashes($filtered_paragraphs_content)
		);
		
		

		$where = array(
			'id' => intval($id),
		);


	   	$wpdb->update( $table_name, $data, $where);
		//header("location: https://iperprogetto.it/wp-admin/admin.php?page=paragraph-list");

	}
	
	
	 //query per leggere i dati dalla tabella
    $results = $wpdb->get_results("SELECT * FROM hfu_pur_content where id=". $id, OBJECT);
	
	
	$region;
	$chapter;
	$subchapter;
	$head_paragraphs;
	$paragraphs_content;
	
	
	foreach ($results as $result) {
		
		$region = $result->region;
		$chapter = $result->chapter;
		$subchapter = $result->subchapter;
		$head_paragraphs = $result->head_paragraphs;
        $paragraphs_content = $result->paragraphs_content;
			
	}
	
	  // Imposta le opzioni per l'editor
    $settings1 = array(
        'textarea_name' => 'paragraphs_content',
        'textarea_rows' => 10,
        'editor_height' => 200,
        'drag_drop_upload' => true
    );
	
	$paragraphs_content_filter = wp_kses( $paragraphs_content, $allowed_tags );
	
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
					<label>Paragrafo:</label><input type="text" name="head_paragraphs" value="<?php  echo esc_attr($head_paragraphs); ?>"></input><br><br>
					<h2 style="font-size:25px;">Modifica paragrafo</h2>
					<?php wp_editor( stripslashes($paragraphs_content_filter), 'paragraphs_content', $settings1 ); ?><br>
					<input type="submit" value="Salva" class="button-primary" style="float:right;">
				</form>

				<br>


			</div>

		</body>

<?php
	
}

function create_paragraph(){
	
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
		
		$filtered_paragraphs_content = wp_kses( $_POST['paragraphs_content'], $allowed_tags );

		
		$data = array(
		  "region" => $_POST['value_selectbox'],
		  "chapter" => $_POST['chapter'],
		  "subchapter" => $_POST['subchapter'],
		  "head_paragraphs" =>  $_POST['head_paragraphs'],
		  "paragraphs_content" => stripslashes($filtered_paragraphs_content)
		);
		
		
		//echo var_dump($data);	

		
		$wpdb->insert('hfu_pur_content', $data);
		header("location: https://iperprogetto.it/wp-admin/admin.php?page=paragraph-list");	
	}
	
	// Imposta le opzioni per l'editor
    $settings1 = array(
        'textarea_name' => 'paragraphs_content',
        'textarea_rows' => 10,
        'editor_height' => 200,
        'drag_drop_upload' => true
    	);
	

?>

	<head>
	</head>
	<body>
		
		<div>
			<h1>Crea Paragrafo</h1>
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
				<!--<label>Nome:</label><input type="text" name="name_subchapter" ></input>-->
				<label>Paragrafo:</label><input type="text" name="head_paragraphs"></input><br><br>
				<h2 style="font-size:18px;">Testo paragrafo</h2>
				<?php wp_editor( $paragraphs_content, 'paragraphs_content', $settings1 ); ?><br>
				<input type="submit" value="Salva" class="button-primary" style="float:right;">
			</form>

			</div>

		</body>

		<script>

		</script>	


<?php
	
	
}

function delete_paragraph(){
	
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

add_action('admin_menu', 'menu_items');

/**
 * get-pur-chapters-subchapter-content
 */
function process_selected_region() {
  
  
	global $wpdb;
	
	$selected_region = $_GET["selected_region"];
    
	//query per leggere i dati dalla tabella
    $results = $wpdb->get_results("SELECT * FROM hfu_pur_chapters where region='$selected_region'", OBJECT);
   
	// Codifica dei risultati in formato JSON
	if($results > 0){
		echo json_encode($results);
	}

	else{
		// Output in formato JSON
		header('Content-Type: application/json');
		echo "nulla";
	}
	
  wp_die();
	
}

function process_selected_chapter(){
	
	global $wpdb;
	
	$selected_region = $_GET["selected_region"];
	$selected_chapter = $_GET["selected_chapter"];
    
	//query per leggere i dati dalla tabella
    $results = $wpdb->get_results("SELECT subchapter, title From hfu_pur_subchapter where region ='$selected_region' AND hfu_pur_subchapter.chapter ='$selected_chapter'", OBJECT);
   
	// Codifica dei risultati in formato JSON
	if($results > 0){
		//header('Content-Type: application/json');
		echo json_encode($results);
	}

	else{
		// Output in formato JSON
		header('Content-Type: application/json');
		echo "nulla";
	}
	
  wp_die();
	
	
	
}

function process_selected_content(){
	
	global $wpdb;
	
	$selected_region = $_GET["selected_region"];
	$selected_chapter = $_GET["selected_chapter"];
	$selected_subchapter = $_GET["selected_subchapter"];
    
	//query per leggere i dati dalla tabella
    $results = $wpdb->get_results("SELECT chapter, subchapter, `head_paragraphs`,`paragraphs_content`, keywords FROM `hfu_pur_content` where `region`='$selected_region' AND `chapter`='$selected_chapter' AND `subchapter`='$selected_subchapter' ", OBJECT);
   
	// Codifica dei risultati in formato JSON
	if($results > 0){
		header('Content-Type: application/json');
		echo json_encode($results);
	}

	else{
		// Output in formato JSON
		header('Content-Type: application/json');
		echo "nulla";
	}
	
  wp_die();
	
}

function process_selected_voice(){
	
	global $wpdb;
	
	$selected_region = $_GET["selected_region"];
	$selected_chapter = $_GET["selected_chapter"];
	$selected_subchapter = $_GET["selected_subchapter"];
    

	
	$results = $wpdb->get_results("SELECT hfu_pur_voice.id, hfu_pur_voice.region, hfu_pur_content.paragraphs_content, hfu_pur_voice.chapter, hfu_pur_voice.subchapter, hfu_pur_voice.paragraphs, hfu_pur_voice.voice_id, hfu_pur_voice.text_content, hfu_pur_voice.unit, hfu_pur_voice.price, hfu_pur_voice.vat FROM hfu_pur_voice INNER JOIN hfu_pur_content ON hfu_pur_voice.region = hfu_pur_content.region AND hfu_pur_voice.chapter = hfu_pur_content.chapter AND hfu_pur_voice.subchapter = hfu_pur_content.subchapter AND hfu_pur_voice.paragraphs = hfu_pur_content.head_paragraphs WHERE hfu_pur_voice.region='$selected_region' AND hfu_pur_voice.chapter = '$selected_chapter' AND hfu_pur_voice.subchapter = '$selected_subchapter' GROUP BY hfu_pur_voice.region, hfu_pur_voice.chapter, hfu_pur_voice.subchapter, hfu_pur_voice.paragraphs, hfu_pur_voice.voice_id, hfu_pur_voice.text_content, hfu_pur_voice.unit, hfu_pur_voice.price, hfu_pur_voice.vat ORDER BY `hfu_pur_voice`.`chapter` ASC");
	
/*
 * Per far visualizzare correttamente il pur è necassario che la join sia unita indicando i parametri capitolo, sottocapitolo e parragrafo. Inoltre è necessario inserire le voci, anche vuote ma che ci siano, per ogni paragrafo al fine di far collassare correttamente e visualizzare il contenuto. Esempio attuale = 1.1.11 non collassa perché nella tabella hfu_pur_voice non esiste una voce per il paragrafo 11 anche se quest'ultimo è contenuto in hfu_pur_content. Al momento per far funzinare il pur hfu_pur_voice e hfu_pur_content dipendono l'uno dall'altra.
 * 
 * 
 * */
   
	// Codifica dei risultati in formato JSON
	if($results > 0){
				header('Content-Type: application/json');

		echo json_encode($results);
	}

	else{
		// Output in formato JSON
		header('Content-Type: application/json');
		echo "nulla";
	}
	
  wp_die();
	
}

function process_selected_voice_2(){
	
	global $wpdb;
	
	$selected_region = $_GET["selected_region"];
	$selected_chapter = $_GET["selected_chapter"];
	$selected_subchapter = $_GET["selected_subchapter"];
	$selected_paragraphs = $_GET["selected_paragraphs"];
    

	
	$results = $wpdb->get_results("SELECT hfu_pur_voice.id, hfu_pur_voice.region, hfu_pur_content.paragraphs_content, hfu_pur_voice.chapter, hfu_pur_voice.subchapter, hfu_pur_voice.paragraphs, hfu_pur_voice.voice_id, hfu_pur_voice.text_content, hfu_pur_voice.unit, hfu_pur_voice.price, hfu_pur_voice.vat FROM hfu_pur_voice INNER JOIN hfu_pur_content ON hfu_pur_voice.region = hfu_pur_content.region AND hfu_pur_voice.chapter = hfu_pur_content.chapter AND hfu_pur_voice.subchapter = hfu_pur_content.subchapter AND hfu_pur_voice.paragraphs = hfu_pur_content.head_paragraphs WHERE hfu_pur_voice.region='$selected_region' AND hfu_pur_voice.chapter = '$selected_chapter' AND hfu_pur_voice.subchapter = '$selected_subchapter' AND hfu_pur_voice.paragraphs = '$selected_paragraphs'  GROUP BY hfu_pur_voice.region, hfu_pur_voice.chapter, hfu_pur_voice.subchapter, hfu_pur_voice.paragraphs, hfu_pur_voice.voice_id, hfu_pur_voice.text_content, hfu_pur_voice.unit, hfu_pur_voice.price, hfu_pur_voice.vat ORDER BY `hfu_pur_voice`.`chapter` ASC");
	
/*
 * Per far visualizzare correttamente il pur è necassario che la join sia unita indicando i parametri capitolo, sottocapitolo e parragrafo. Inoltre è necessario inserire le voci, anche vuote ma che ci siano, per ogni paragrafo al fine di far collassare correttamente e visualizzare il contenuto. Esempio attuale = 1.1.11 non collassa perché nella tabella hfu_pur_voice non esiste una voce per il paragrafo 11 anche se quest'ultimo è contenuto in hfu_pur_content. Al momento per far funzinare il pur hfu_pur_voice e hfu_pur_content dipendono l'uno dall'altra.
 * 
 * 
 * */
   
	// Codifica dei risultati in formato JSON
	if($results > 0){
				header('Content-Type: application/json');

		echo json_encode($results);
	}

	else{
		// Output in formato JSON
		header('Content-Type: application/json');
		echo "nulla";
	}
	
  wp_die();
	
}

function save_voice_data_user(){
	
	global $wpdb;
	
	$id_ai_voice = $_POST["id_ai_voice"];
	$id_user = $_POST["id_user"];

	//query per leggere i dati dalla tabella
   	$results = $wpdb->get_results("INSERT INTO `hfu_users_note`(`user_id`, `id_ai_voice`) VALUES ('$id_user', '$id_ai_voice') ");
	
	
	//echo $results;
	
	

		
  wp_die();
	
	
	
}





add_action("wp_ajax_process_selected_region", "process_selected_region");

add_action("wp_ajax_process_selected_chapter", "process_selected_chapter");

add_action("wp_ajax_process_selected_content", "process_selected_content");

add_action("wp_ajax_process_selected_voice", "process_selected_voice");

add_action("wp_ajax_process_selected_voice_2", "process_selected_voice_2");

add_action("wp_ajax_save_voice_data_user", "save_voice_data_user");

/**
 * p.u.r. front-end v2
 */
function pur_menu(){
	
	global $wpdb;

	?>

<head>

    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css' rel='stylesheet'>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js'></script>


    <style>
		.elementor-section.elementor-section-boxed > .elementor-container {
    max-width: 1175px !important;
}
		.select_pur_iperpro {
		  background-image: url('/wp-content/uploads/2023/03/Untitled-design-1.png');
          background-position: right;
          background-size: 1500px;
          border: none;
          border-radius: 15px;
			width: 570px;
		}
		
		
    .button_up {
        border: none;
        border-radius: 24px;
        color: black;
        background-color: #F5F5F5;
        padding: 10px 10px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 10px 10px;
        cursor: pointer;
    }

    .button_up:hover {
        background-color: #1E3B88;
        color: white;
    }

    .button_coll {
        border: none;
        color: black;
        background-color: #F5F5F5;
        padding: 10px 10px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 5px 5px;
        cursor: pointer;

    }

    .button_coll:hover {
        background-color: #1E3B88;
        color: white;

    }

    .bg_blue {
        background-color: #1E3B88 !important;
        color: white !important;
    }

    .collapse {}

    .div_ {

        margin-left: 25px;
    }

    a {
        color: black
    }

    a:hover {
        color: white;
    }

    .button_up {
        border: none;
        border-radius: 24px;
        color: black;
        background-color: #F5F5F5;
        padding: 10px 10px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
    }

    .button_up:hover {
        background-color: #1E3B88;
        color: white;
    }

    .button_coll {
        border: none;
        color: black;
        background-color: #F5F5F5;
        padding: 10px 10px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
    }

    .button_coll:hover {
        background-color: #1E3B88;
        color: white;
    }

    .button_coll:active {
        background-color: #1E3B88;
        color: white;

    }

    .pur-select {
        margin-top: 10px;
    }

    .div_ {

        margin-left: 25px;
    }
		
		.btn-blue {
			padding: 10px 5px;
			color: white;
			border: none;
			border-radius: 15px;
			background:#1E3B88; 
		}

    #style-1::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0);
        border-radius: 10px;
        background-color: rgba(0, 0, 0, 0.0);
    }

    #style-1::-webkit-scrollbar {
        width: 12px;
        background-color: rgba(0, 0, 0, 0.0);
    }

    #style-1::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.0);
        background-color: #d4d4d4;
    }
    </style>

    <head>
    </head>


<body>
    <?php 
			//$results_chapter = $wpdb->get_results("SELECT * FROM hfu_pur_chapters");
			//$results_subchapter = $wpdb->get_results("SELECT * FROM hfu_pur_subchapters");
		?>

    <div class="container">
        <div class="row">
            <div class="col-sm select_pur_iperpro_container">
                <select class="col-sm select_pur_iperpro" name="region" id="regioni">
                    <option value="" disabled selected>Seleziona un'opzione</option>
                    <option value="Sicilia">Sicilia</option>
                    <option value="Piemonte">Piemonte</option>
                    <option value="Lombardia">Lombardia</option>
                    <option value="Lazio">Lazio</option>
                </select>
            </div>
            <div id="chapter_sel" class="pur-select"></div>
            <div id="sub_chapter_sel" class="pur-select"></div>
            <div id="content" class="pur-select"></div>
            <div id="voice" class="pur-select"></div>

        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {

		$(document).on("change", "select.select_pur_iperpro", function() {
			$(this).css("background-image", "url('/wp-content/uploads/2023/03/Untitled-design.png')");
		})

        $(document).on("click", "button.button_up", function() {

            let dataAttr = $(this).attr('data-bs-target');

            $('button[data-bs-target^=#paragrafo]').each(function(button) {

                $(this).removeClass('bg_blue')
            })

            $(this).addClass('bg_blue');
            //	let button = $(this)

            $("div[id^='paragrafo']").each(function() {
                var divId = $(this).attr('id');
                if (divId != dataAttr) {

                    $(this).removeClass("show");
                }


            });

        });

        $("#regioni").change(function() {
            var selected_region = $(this).val();
            $.ajax({
                url: "/wp-admin/admin-ajax.php",
                type: "GET",
                data: {
                    action: "process_selected_region",
                    selected_region: selected_region
                },
                success: function(response) {
                    let data = JSON.parse(response);
                    let html =
                        '<div><select class="select_pur_iperpro chapter_selector" name="chapter_sel_" id="chapter" ><option class="option-style" disabled selected>Seleziona capitolo</option>';
                    for (let i = 0; i < data.length; i++) {
                        if (data[i].region == selected_region) {
                            html += "<option value='" + data[i].chapter + "'>" + data[i]
                                .chapter + ". " + data[i].name_chapter + "</option>";
                        }
                    }
                    html += '</select></div>';
                    $("#chapter_sel").html(html);
                }
            });
        });

        $(document).on('change', '#chapter', function() {
            var selected_chapter = $(this).val();
            var selected_region = $('#regioni').val();
			$("div#voice").empty();
			$("div.buttons_up_container").remove();

			
            $.ajax({
                url: "/wp-admin/admin-ajax.php",
                type: "GET",
                data: {
                    action: "process_selected_chapter",
                    selected_region: selected_region,
                    selected_chapter: selected_chapter
                },
                success: function(response) {
                    let data = JSON.parse(response);
                    let html =
                        '<div><select class="select_pur_iperpro subchapter_selector" name="sub_chapter_sel_" id="subchapter"><option disabled selected>Seleziona sottocapitolo</option>';
                    for (let i = 0; i < data.length; i++) {
                        html += "<option value='" + data[i].subchapter + "'>" + data[i]
                            .subchapter + '. ' + data[i].title + "</option>";
                    }
                    html += '</select></div>';
                    $("#sub_chapter_sel").html(html);
                }
            });
        });
		
		
								

        $(document).on('change', '#subchapter', function() {
			
            let selected_subchapter = $(this).val();
            let selected_chapter = $('#chapter').val();
            let selected_region = $('#regioni').val();
            let selected_paragraph;
            let data;


            $.ajax({
                url: "/wp-admin/admin-ajax.php",
                type: "GET",
                data: {
                    action: "process_selected_content",
                    selected_region: selected_region,
                    selected_chapter: selected_chapter,
                    selected_subchapter: selected_subchapter
                },
                success: function(response) {
					
                    let coversion = JSON.stringify(response);
                    data = JSON.parse(coversion);

                    let html = '<div class="row buttons_up_container"><div class="col">';

                    for (let i = 0; i < data.length; i++) {

                        html +=
                            '<button type="button" class="button_up paragraphs_selector" id="button_head_paragraphs" data-paragraphs="'+ data[i].head_paragraphs +'" data-bs-toggle="collapse" data-bs-target="#paragrafo' +
                            data[i].head_paragraphs + '" data-bs-parent="#accordion">' +
                            data[i].head_paragraphs + '</button>';

                    }


                    html += '</div></div></div>'
                    $("#content").html(html);

                }

            });

            $.ajax({
                url: "/wp-admin/admin-ajax.php",
                type: "GET",
                data: {
                    action: "process_selected_voice",
                    selected_region: selected_region,
                    selected_chapter: selected_chapter,
                    selected_subchapter: selected_subchapter
                },
                success: function(response) {

                    let conversion = JSON.stringify(response);
                    let data2 = JSON.parse(conversion);

                    let html2 = '<div class="row"><div class="col">';

                    let count = 0;
                    let prec;
                    let prec_par;
					
                    for (let i = 0; i < data2.length; i++) {

                        if (data2[i].paragraphs_content != prec) {

                            if (data2[i].paragraphs != prec_par) {

                                html2 += '</div></div><br></div></div>';

                            }

                            html2 += '<div id="paragrafo' + data2[i].paragraphs +
                                '" class="collapse"><div class="div_"><div class="row"><div class="col"><br><h2>' +
                                data2[i].chapter + '.' + data2[i].subchapter +
                                ') Paragrafo ' + data2[i].paragraphs +
                                '</h2><p style="text-align:justify;">' + data2[i]
                                .paragraphs_content + '</p></div></div><br>';
                            html2 +=
                                '<div><div data-bs-spy="scroll" id="style-1" data-bs-target="#navbar-example2" data-bs-offset="0"class="scrollspy-example" tabindex="0" style="min-height:100px; max-height:500px; overflow-y: scroll;  overflow-x: hidden; background-color:#dbe3ec;padding:15px;border:solid;border-radius:15px;border-width:0px;border-color:#636270;">';

                            prec = data2[i].paragraphs_content;
                            prec_par = data2[i].paragraphs;
                        }
						
							let test = parseFloat(data2[i].price.replace(',','.',).replace('€',''),2) * parseFloat(data2[i].vat.replace(',','.',).replace('%',''),2)/100.00;
						
						    html2 += '<div class="row"><div class="col-5"><p><strong>' + data2[i].voice_id + ')</strong> ' + data2[i].text_content + '</p></div><div class="col text-center align-self-end p-0 mb-2"><p>' + data2[i].unit + '</p></div><div class="col text-center align-self-end p-0 mb-2"><p><strong>€ ' + data2[i].price + '</strong></p></div><div class="col text-center align-self-end p-0 mb-2"><p>' + data2[i].vat + '    <strong>(€ '+test.toFixed(2)+')</strong></p></div><div class="col text-center align-self-end p-0"><p><button id="save-note-btn" data-value-voice-id="' + data2[i].id + '" class="btn-blue" >Salva su note</button></p></div></div>';	
                    }
					
                    html2 += '</div></div>'
                    $("#voice").html(html2);
					
                } //chiusure responce success
            }); //Chiusura seconda ajax
        }); //chiusura #subchapter

		$(document).on( 'click', '#save-note-btn', function () {
			
			let id_ai_voice = $(this).attr('data-value-voice-id');
			let id_user = <?php echo get_current_user_id(); ?>;

			$.ajax({
				url: '/wp-admin/admin-ajax.php',
				type: "POST",
				data: {
					action: "save_voice_data_user",
					id_ai_voice: id_ai_voice,
					id_user: id_user
				},
				success: function(response) {

					alert("Nota salvata con successo!");
				}
			});
		});

    });
    </script>



    <?php
}

add_shortcode('pur', 'pur_menu');

/**
 * shortcode archivio prodotti
 */
function get_products_custom_last_category($category_id){

	$html = '';

	$html .= '<style>

		.products_grid_custom_iperproject{
			grid-gap: 20px;
			grid-template-columns: repeat(3, 1fr);
			display: grid;
			justify-items: start;
			margin-left: 50px;  
		}

		.product_custom_iperproject{

		}

		.product_image{
			max-width: 230px;
			max-height: 300px;
			min-width: 230px;
			min-height: 200px;
			display: flex;
			justify-content:center;
			align-items: center;
			border: 1px solid #E2E8F6;
			border-top-left-radius: 22px;
			border-top-right-radius: 22px;
			position: relative;
		}
		
		
		.stamps_wrapper {
			position: absolute;
			left: 0;
			bottom: 0;
			right: 0;
			display: flex;
		}

		.stamp_item {
			width: 60px;
		}


		.product_info{
			max-width: 230px;
			max-height: 205px;
			min-width: 230px;
			min-height: 105px;
			background: #DBE3EB;
			display: flex;
			flex-direction: column;
			justify-content: center;
			text-align: left;
			border-bottom-left-radius: 22px;
			border-bottom-right-radius: 22px;
			padding:10px;
			padding-left: 15px;
    		padding-right: 15px;
		}

		.pagination {
			display:flex;
			justify-content: flex-end;
			margin-top:20px;
		}

		.pagination-item {
			background-color: #dbe3eb;
			color: #1D3C6E;
			border: solid;
			border-radius: 5px;
			border-color: white;
			width: 40px;
			height: 40px;
			border-radius: 50%;
			font-size: 20px;
		}

		.pagination-item:hover {
			background-color: #1D3C6E;
			color: #ffffff;
		}

		.page-numbers.current .pagination-item {
		  background-color: #1D3C6E;
		  color: #ffffff;
		}

		.page-numbers.dots{
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			//display:none;
		}

		.icon_in_button{		
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
		}

		 .star-rating:before {

			color:#22222252;
		}

		.star-rating, #review_form .stars {
    --et_yellow-color: #FFE175;
		}

	</style>';

	// Crea un array vuoto per la meta query
	$meta_query = array(); 
	
	// Array di query tassonomiche
	$tax_queries = array();

	// Array di tassonomie già utilizzate
	$used_taxonomies = array();
	
	$used_vendor = array();

	// Loop sui parametri passati alla funzione
	foreach ($_GET as $key => $value) {

		// Se il parametro è vuoto, passa al prossimo
		if (empty($value)) {
			continue;
		}

		// Split del parametro
		$parts = explode('-', $key);

		// Se il parametro fa riferimento all'ID del vendor, aggiungi una query meta
		if (strpos($key, "vendor_user_id_filter-") !== false) {
			
			unset($meta_query);

			array_push($used_vendor, $value);
			//$used_vendor = explode(',', $value); //converte la stringa in un array
			//$used_vendor[] = $value;
			
			$stringa = implode(',', $used_vendor);
			

			
			$meta_query[] = array(
				'key' => 'vendor_user',
				//'value' =>  $value,
				'value' => $stringa,
				'compare' => 'IN',
			);

			// Altrimenti, crea una nuova query tassonomica
		} else {

			// Se la tassonomia è già stata utilizzata, aggiungi il termine alla query corrispondente
			if (in_array($parts[0], $used_taxonomies)) {

				$index = array_search($parts[0], array_column($tax_queries, 'taxonomy'));

				$tax_queries[$index]['terms'][] = $value;

				// Altrimenti, crea una nuova query tassonomica
			} else {

				$add_attr = array(
					'taxonomy' => $parts[0],
					'field'    => 'slug',
					'terms'    => array($value),
				);

				// Aggiungi la query tassonomica all'array delle query
				$tax_queries[] = $add_attr;

				// Aggiungi la tassonomia all'array delle tassonomie già utilizzate
				$used_taxonomies[] = $parts[0];

			}

		}

	}
	
	
	
	

	// Aggiungi le query tassonomiche al parametro tax_query dell'array $args
	$args['tax_query'] = array_merge(
		array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'term_id',
				'terms' => $category_id,
			),
		),
		$tax_queries,
	);
	

	// Aggiungi la query meta all'array $args, se presente
	if (!empty($meta_query)) {
		$args['meta_query'] = $meta_query;
	}



	// Imposta il numero di prodotti per pagina e la pagina corrente
	$args['posts_per_page'] = 9;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //ottieni la pagina corrente
	$args['paged'] = $paged;


 
	//print_r($args);


	$query = new WP_Query($args);
	

	if ( $query->have_posts() ) {

		$html .= '<div class="products_grid_custom_iperproject">';

		while ( $query->have_posts() ) {

			$query->the_post();

			$html .='<div class="product_custom_iperproject">';

			$img = get_the_post_thumbnail_url(get_the_ID(), 'medium' ) ? get_the_post_thumbnail_url(get_the_ID(), 'medium' ) : wc_placeholder_img_src( 'medium');

			$html .= '<div class="product_image"><a href="'. get_permalink().'"><img src="'.$img.'" style="object-fit: cover; border-radius: 22px 22px 0px 0px;"></a>';
			
			$html .= '<div class="stamps_wrapper">';
			// logica bollini 
			if(get_field('pur_approved') == true) {
				$html .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_pur-approved.svg"/>';
			}

			if(get_field('ecosostenibile') == true) {
				$html .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_eco.svg"/>';
			}

			if(get_field('innovativo') == true) {
				$html .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_innovativo.svg"/>';
			}

			$html .= '</div>
			
			
			
			</div>';

			$html .= '<div class="product_info">';

			// Recupera il valore del campo personalizzato "nome_campo"
			$id_user = get_field('vendor_user');
			$meta_values = get_user_meta($id_user);


			// Mostra il valore del campo personalizzato
			if ($meta_values) 
			{
				$company_name = $meta_values['billing_company'][0];

				$html .= '<div class="vendor_product" style="height:25px;"><p style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"><a href="https://iperprogetto.it/vetrina/?venditore='.$meta_values['nickname'][0].'" >'.$company_name.'</a></p></div>';
			}

			else
			{
				$html .= '<div class="vendor_product" style="height:25px;"><p>N.D.</p></div>';
			}

			$html .= '<div class="title_product" style="height:40px;"><a href="'.get_permalink().'"><h2 style="font-size: 14px;color: #303030">'.substr(get_the_title(),0,55).'</h2></a></div>';



			// Output the product reviews
			$comments = get_comments( array(
				'post_id' => get_the_ID(),
				'status' => 'approve'
			));


			$html .= '<div class="review_product" style="height:20px;">';
			$rating = 0;
			$creviews_number = 0;
			if($comments){
				foreach ( $comments as $comment ) {
					// Imposta il rating
					$rating = $rating + intval(get_comment_meta( $comment->comment_ID, 'rating', true ));
					$creviews_number = get_comments_number();
				}
				// Calcola la larghezza delle stelle in base al rating
				$width = ($rating / $creviews_number) * 20;

				// Genera le stelle
				$html .= '<div class="star-rating"><span style="width: '. $width .'%;">&#9733;&#9733;&#9733;&#9733;&#9733;</span></div><span> <a href="'. get_permalink().'#reviews" style="padding-left: 5px;"> <u>'.$creviews_number.' voti</u></a></span>';
				$html .= '';				
			}

			else
			{
				// Genera le stelle
				$html .= '<div class="star-rating" role="img"><span style="width: 0%;">&#9733;&#9733;&#9733;&#9733;&#9733;</span></div><span> <a href="'. get_permalink().'#reviews" style="padding-left: 5px;"> <u>0 voti</u></a></span>';
				$html .= '';
			}

			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}



		$html .= '</div>';

		$args_pagination = array(

			'total' => $query->max_num_pages,
			'current' => $paged,
			'prev_next' => true,  		
			'prev_text' => '<button class="pagination-item icon_in_button"><span class="dashicons dashicons-arrow-left-alt2"></span></button>',
			'next_text' => '<button class="pagination-item icon_in_button"><span class="dashicons dashicons-arrow-right-alt2"></span></button>',
			'mid_size'  => 1,
			'end_size' => 1,
			'before_page_number' => '<button class="pagination-item">',
			'after_page_number' => '</button>',
			'before_current' => '<button class="pagination-item current">',
			'after_current' => '</button>',

		);



		$pagination = paginate_links($args_pagination);

		$html .= '<div class="pagination">';
		$html .=  $pagination;
		$html .= '</div>';



		wp_reset_postdata();

	}

	else 
	{
		//$html = '<div style="width: 700px;display: flex;justify-content: center;margin-left: -155px;"><h2>Nessun prodotto trovato...</h2></div>';
		//return $html;
	}

	return $html;

}

function show_prodotti_figli_shortcode() {


	$current_term = get_queried_object();

	$children = get_terms( 
		$current_term->taxonomy, 
		array(
			'parent' => $current_term->term_id,
			'fields' => 'ids'
		)
	);

	if ( empty( $children ) ) {
		//var_dump(get_products_custom_last_category($current_term->term_id,''));
		return get_products_custom_last_category($current_term->term_id,'');
	}
	else
	{
		//return '<style>#prodotti-figli { display: none; }</style>';

		//return '<div style="width: 700px;display: flex;justify-content: center;margin-left: -155px;"><h2>Nessun prodotto trovato...</h2></div>';
	}

}

add_shortcode( 'show_products_last_children_category', 'show_prodotti_figli_shortcode' );



function get_attributes_product($subcategory_id){
	
	unset($html);
	
	$html = '<style>
	
		.filter_panel
		{
			/*
			width: 250px;
			background-color: #DBE3EB;
			border-radius: 24px;
			display: flex;
			justify-content: center;
			align-items: center;
			flex-direction: column;
			*/
			
			width: 250px;
			background: #DBE3EB;
			display: flex;
			flex-direction: column;
			gap: 10px;
			padding: 25px;
			border-radius: 15px;
			margin-bottom: auto;

		}


		.collapsible{

			/*
			display:flex;
			background-color: #1D3C6E;
			border-radius: 15px;
			padding: 12px 24px;
			font-size: 16px;
			border: none;
			height: 39px;
			width: 200px;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
			*/
			
			display:flex;
			background: #1D3C6E;
			text-align: left;
			padding: 9px 13px;
			border-radius: 15px;
			color: white;
			border: none;
			

		}
			
			
			


		.active, .collapsible:hover
		{
			/*
			background-color: #1D3C6E;
			border-radius: 15px;
			padding: 12px 24px;
			font-size: 16px;
			border: none;
			height: 39px;
			width: 200px;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
			 */

			}

			.content {
			  padding: 0 18px;
			  display: none;
			  /*margin-top: 10px;*/
			  flex-direction: column;
			}

			.right-element {
				  margin-left: auto;
			}

			.no_results{

				width:150px;
				margin-top: 5px; 
				margin-left: 5px;
			}


	</style>';

	
	// Ottieni la URL corrente
	$currentUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	// Dividi la query string in base al carattere ?
	$queryParts = explode('?', $currentUrl);

	// Prendi solo la prima parte della URL (senza la query string)
	$newUrl = $queryParts[0];

	$html .= '<div id="all_content" class="filter_panel">';
				//<div style="margin-top: 20px;margin-bottom: 15px;">';
	
	$html2 = '<div id="reset_filter" style="width: 200px; margin-bottom: 10px; display: flex; flex-direction: row-reverse;"><a id="reset_filter_button" href="'.$newUrl.'">Reset filtri</a></div>';

	

	$html2 .= '<div id="active_filter" style="width: 200px; margin-bottom: 10px;">';
	
	$tax_query = array(); // Crea un array vuoto
	$meta_query = array(); // Crea un array vuoto per la meta query
	
	foreach ($_GET as $key => $value) 
	{
		if(strpos($key, "vendor_user_id_filter-") !== false){

			$meta_query[] = array(
				'key' => 'vendor_user',
				'value' =>  $value,
				'compare' => '='
			);
			
			$billing_company = get_user_meta($value, 'billing_company', true);

			$html2 .= '<label  style="border-radius: 15px; border: 1px solid #1D3C6E; color: #1D3C6E; padding: 6px 10px 6px 10px; margin-right: 5px;">'.$billing_company.'<a id="delete_filter" name="'.$key.'" value="'.$value.'"><i class="et-icon et-delete" style="margin-left: 5px; font-size: 10px;"></i></a></label>';

		}
		
		else
		{

			$parts = explode("-", $key);

			// Aggiungi la prima query tassonomica
			$add_attr = array(
				'taxonomy' => $parts[0],
				'field'    => 'slug',
				'terms'    => $value,
			);

			$html2 .= '<label style="border-radius: 15px; border: 1px solid #1D3C6E; color: #1D3C6E; padding: 6px 10px 6px 10px; margin-right: 5px;">'.ucfirst($add_attr['terms']).'<a id="delete_filter" name="'.$key.'" value="'.$value.'"><i class="et-icon et-delete" style="margin-left: 5px; font-size: 10px;"></i></a></label>';

			$tax_query[] = $add_attr;

		}

	}

	$html2 .= "</div>";

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //ottieni la pagina corrente

	// Crea l'oggetto WP_Query con le query tassonomiche unite
	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'paged'          => $paged,
		'tax_query' => array_merge(
			array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms' => $subcategory_id,
					'include_children' => false,
				)
			),
			//$tax_query
		)
	);

	if (!empty($meta_query)) {
		//$args['meta_query'] = $meta_query;
	}
	
	if(!empty($queryParts[1])){
		$html .= $html2;
	}
	
	$products1 = new WP_Query($args);


	$attribute_taxonomies = wc_get_attribute_taxonomies();

	if($products1->have_posts()){

		foreach ( $attribute_taxonomies as $tax ) {
			
			$attribute_name = wc_attribute_taxonomy_name( $tax->attribute_name );

			$args = array(
				'orderby'    => 'name',
				'hide_empty' => true,
				'taxonomy'   => $attribute_name,
				'object_ids' => wp_list_pluck( $products1->posts, 'ID' )
			);

			$terms = get_terms($args);
			$taxonomy = get_taxonomy( $attribute_name );
			
			$count = 0;

			if ( $terms ) {
				
				/*$html .= '
		<div style=" margin-top: 0px; margin-bottom: 15px;">
				<button type="button" class="collapsible">
					<p style="color: white;  margin-right: auto; font-size: 14px;">'.$taxonomy->labels->singular_name.'</p>
					 <span class="right-element"><img src="https://iperprogetto.it/wp-content/uploads/2023/04/Vector_down.png" width="15"></span>
				</button>
					<div class="content">
					';*/
				
				$html .= '
					<a class="collapsible">
						<span style="color: white;  margin-right: auto; font-size: 14px; font-weight: 350;">'.$taxonomy->labels->singular_name.'</span>
						<i class="et-down-arrow et-icon" style="padding-top: 3px; color: white;"></i>
					</a>
					<div class="content">
					';
				
				foreach ( $terms as $term ) {
					$html .= '<label style="width:150px;"><input type="checkbox" name="'.$attribute_name.'-'.$count.'" value="'.$term->slug.'">'.$term->name.'</label>';
					$count ++;	

				}
				$html .= '
					</div>
				';
			}
				
		}
	}
	else{

		$html = '';
		return $html;
	}

	

	/*$html .= '
		<div style="margin-top: 5px; margin-bottom: 5px;">
				<button type="button" class="collapsible">
					<p style="color: white;  margin-right: auto;">Venditori</p>
					 <span class="right-element"><img src="https://iperprogetto.it/wp-content/uploads/2023/04/Vector_down.png" width="15"></span>
				</button>
					<div class="content">
		';*/
	
	$html .= '
				<a class="collapsible">
					<span style="color: white;  margin-right: auto; font-size: 14px; font-weight: 350;">Venditori</span>
						<i class="et-down-arrow et-icon" style="padding-top: 3px; color: white;"></i>
				</a>
				<div class="content">
					';

	$args = array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'term_id',
				'terms' => $subcategory_id // ID della categoria desiderata
			)
		)
	);

	$products2 = get_posts( $args );


	$vendors = array();

	$html .= '<input type="text" id="vendor_search" placeholder="Cerca brand" style="max-width: 160px; height: 28px; margin-bottom: 10px;">';

	$html .= '<div id="vendor_list" style="max-height: 150px; width: 160px; overflow-y: scroll; background-color: white;">';
	
	$count = 0;
	
	foreach ( $products2 as $product ) {
		$field_value = get_field('vendor_user', $product->ID);
		$billing_company = get_user_meta( $field_value, 'billing_company', true );
		array_push($vendors, $field_value.';'. $billing_company);
	}
	
	
	foreach(array_unique($vendors) as $vendor)
	{

		$vendor_user = explode(';', $vendor);
		$html .= '
			<label class="vendor_name" style="width:150px; margin-top: 5px; margin-left: 5px;"><input type="checkbox" name="vendor_user_id_filter-'.$count.'" value="'.$vendor_user[0].'">'.$vendor_user[1].'</label>';
	$count++;

	}
	

	$html .= '		
				</div>
			</div>
		</div>

	';

	
	$html .= '<script>

			var coll = document.getElementsByClassName("collapsible");
			var i;

			for (i = 0; i < coll.length; i++) {
			  coll[i].addEventListener("click", function() {
				this.classList.toggle("active");
				var content = this.nextElementSibling;
				if (content.style.display === "flex") {
				  content.style.display = "none";
				} else {
				  content.style.display = "flex";

				}
			  });
			}

			jQuery(document).ready(function($) {

				$(document).on("input", "#vendor_search", function() {
				  
					var searchText = $(this).val().toLowerCase();
					var foundLabels = false;
					//console.log(searchText);
					
					$(".vendor_name").each(function()
					{
					  let labelValue = $(this).text().toLowerCase();
					  console.log($(this).text());
					  
					  if (labelValue.indexOf(searchText) !== -1) 
					  {
						$(this).show();
						foundLabels = true;
					  } 
					  
					  else 
					  {
						$(this).hide();
					  }
					});
					
					if (!foundLabels) 
					{
					  if ($("#noResults").length === 0)
					  {
						$("#vendor_list").append("<p id=noResults class=no_results>Nessun venditore trovato</p>");
					  }
					} 
					
					else
					{
					  $("#noResults").remove();
					}
				  });

				$(document).on("change", "input[type=checkbox]", function() {
				
						if($(this).is(":checked")) 
						{
							let checkbox_name = $(this).attr("name");
							let checkbox_value = $(this).val();
							let url = window.location.href;
							current_url = url.replace(/\/page\/\d+\//, "/");

							if (current_url.indexOf("?") !== -1) 
							{
								window.location.replace(current_url + "&" + checkbox_name + "=" + checkbox_value);
								console.log(current_url + "&" + checkbox_name + "=" + checkbox_value);
							} 
							else 
							{				  	
								window.location.replace(current_url + "?" + checkbox_name + "=" + checkbox_value);
								console.log(current_url + "?" + checkbox_name + "=" + checkbox_value);
							}

					
						} 						
						else 
						{

							let checkbox_name = $(this).attr("name");
							let url = window.location.href;
							current_url = url.replace(/\/page\/\d+\//, "/");

							let urlObj = new URL(current_url); // crea un oggetto URL
							let searchParams = urlObj.searchParams; // ottieni i parametri di ricerca del URL

							// rimuovi il parametro "nome_parametro_da_rimuovere" dal URL
							searchParams.delete(checkbox_name);

							// ricostruisci URL senza il parametro rimosso
							let newUrl = urlObj.origin + urlObj.pathname + "?" + searchParams.toString();

							window.location.replace(newUrl);
							
							}
							
					});
							
					$(document).on("click", "#reset_filter_button", function(){
						
						let url = window.location.href;
						// Dividi la URL in base al carattere ?
						var parts = url.split("?");

						// Prendi solo la prima parte della URL (senza i parametri)
						var newUrl = parts[0];

						// Reindirizza lutente alla nuova URL senza i parametri
						window.location.replace(newUrl);
					
					
					});
					
					$(document).on("click", "label a#delete_filter", function(){
						
						let filter_name = $(this).attr("name");
						console.log(filter_name);
						let filter_value = $(this).attr("value");
						console.log(filter_value);
						
						let url = window.location.href;
						current_url = url.replace(/\/page\/\d+\//, "/");

						let urlObj = new URL(current_url); // crea un oggetto URL
						let searchParams = urlObj.searchParams; // ottieni i parametri di ricerca del URL

						// rimuovi il parametro "nome_parametro_da_rimuovere" dal URL
						searchParams.delete(filter_name);

						// ricostruisci URL senza il parametro rimosso
						let newUrl = urlObj.origin + urlObj.pathname + "?" + searchParams.toString();

						window.location.replace(newUrl);
					
					
					});

				';
	
					
	$params2 = array();
	foreach ($_GET as $key => $value) {
		$params2[$key] = $value;
	}

	// Converti l'array in formato JSON
	$json_params = json_encode($params2);

	$html .= 'let params = '.$json_params.';
				//console.log(params);
			  $.each(params, function(key, value) {
			  	let key_split = key.split("-");
				//console.log(key_split[0]);
			  	$("input[type=checkbox][name^="+key_split[0]+"][value="+ value +"]").prop("checked", true);
			  });

				});
			</script>';
	
	
	wp_reset_postdata();

	return $html;



}

function show_attributi_prodotti_figli_shortcode() {

	$current_term = get_queried_object();

	$children = get_terms( 
		$current_term->taxonomy, 
		array(
			'parent' => $current_term->term_id,
			'fields' => 'ids'
		)
	);

	if ( empty( $children ) ) {
		return get_attributes_product($current_term->term_id);
	}
	else
	{
		//return '<style>#prodotti-figli { display: none; }</style>';
	}

}

add_shortcode( 'show_attributes_products_last_children_category', 'show_attributi_prodotti_figli_shortcode' );

/**
 * CRUD Voci P.U.R.
 */
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

/**
 * CRUD Sottocapitoli P.U.R.
 */
function menu_subchapter()
{
		add_submenu_page(
		'dashboard', //slug della pagina principale
		'Lista sottocapitolo', //titolo della sottopagina
		'Lista sottocapitolo', //nome della sottovoce di menu
		'manage_options', //permessi richiesti
		'subchapter-list', //slug della sottopagina
		'subchapter_list', //nome della funzione che mostra il contenuto della sottopagina
		2 // posizione voce in elenco	
	  	);
	
	add_submenu_page(
		'crea sottocapitolo', //slug della pagina principale
		'crea sottocapitolo', //titolo della sottopagina
		'', //nome della sottovoce di menu
		'manage_options', //permessi richiesti
		'create-subchapter', //slug della sottopagina
		'create_subchapter' //nome della funzione che mostra il contenuto della sottopagina
  		);
	
	add_submenu_page(
		'modifica sottocapitolo', //slug della pagina principale
		'modifica sottocapitolo', //titolo della sottopagina
		'', //nome della sottovoce di menu
		'manage_options', //permessi richiesti
		'edit-subchapter', //slug della sottopagina
		'edit_subchapter' //nome della funzione che mostra il contenuto della sottopagina
  		);
	
	add_submenu_page(
		'elimina sottocapitolo', //slug della pagina principale
		'elimina sottocapitolo', //titolo della sottopagina
		'', //nome della sottovoce di menu
		'manage_options', //permessi richiesti
		'delete-subchapter', //slug della sottopagina
		'delete_subchapter' //nome della funzione che mostra il contenuto della sottopagina
  		);
	
	

}

function subchapter_list(){
	
	global $wpdb;
	
	if (isset( $_POST['azione'] ) && $_POST['azione'] == 'elimina_record' ) {
		
	
		$id = $_POST['id']; //$_GET['id'];
		

		$table_name = 'hfu_pur_subchapter';

		$wpdb->delete($table_name, array('id' => intval($id)), array('%d'));
	}
	
?>
		<head>
		
		</head>

		<body style="margin-right:10px;">
			
			<h1>Elenco sottocapitoli</h1>
			<div style="margin-top:10px; margin-bottom:10px; float:right">
				<a href="https://iperprogetto.it/wp-admin/admin.php?page=create-subchapter">
					<span type="submit" value="Crea capitolo" class="button-primary">Crea Sottocapitolo</span>
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
					<th>Titolo</th>
					<th>Azioni</th>
				</tr>
			</thead>';

    //query per leggere i dati dalla tabella
    $results = $wpdb->get_results("SELECT * FROM hfu_pur_subchapter");

    if ($results > 0) {

        //corpo della tabella
        echo '<tbody>';

        foreach ($results as $result) {
            echo '<tr>
                    <td>' . $result->id . '</td>
                    <td>' . $result->region . '</td>
                    <td>' . $result->chapter . '</td>
                    <td>' . $result->subchapter . '</td>
					<td>' . $result->title . '</td>
                    <td>
					
                        <a href="' . admin_url( 'admin.php?page=edit-subchapter&id=' . $result->id ) . '"><span class="dashicons dashicons-edit"></span></a>
						
						<form method="post">
						  <!-- Campi di input per i dati -->
							<input type="hidden" name="azione" value="elimina_record">
							<input type="hidden" name="id" value="'.esc_attr($result->id).'"></input>
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

function create_subchapter(){
	
	global $wpdb;
	
	//$id = $_GET['id'];
	
	if (isset( $_POST['azione'] ) && $_POST['azione'] == 'aggiungi' ) {

		$table_name = 'hfu_pur_subchapter';

		$data = array(
			'region' => sanitize_text_field( $_POST['value_selectbox'] ),
			'chapter' => sanitize_text_field( $_POST['chapter'] ),
			'subchapter' => sanitize_text_field( $_POST['subchapter'] ),
			'title' => sanitize_text_field( $_POST['title'] )
		);

		$format = array(
			'%s',	
			'%s',
			'%s'
		);

		$wpdb->insert($table_name, $data, $format);
		header("location: https://iperprogetto.it/wp-admin/admin.php?page=subchapter-list");

			
	}
	
	?>
		<head></head>

		<body>
			<div>
				<h1>Crea sottocapitolo</h1>
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
	
				foreach ( $options as $value => $label ) {
				  echo '<option value="' . esc_attr( $value ) . '"' . selected( $selected, $value, false ) . '>' . esc_html( $label ) . '</option>';
				}
?>
			    </select>
				<label>Capitolo: </label><input type="text" name="chapter"></input>
				<label>Sottocapitolo: </label><input type="text" name="subchapter"></input>
				<label>Nome Sottocapitolo: </label><input type="text" name="title"></input>
				<input type="submit" class="button-primary" value="Crea">
			</form>


			</div>

		</body>


<?php
	
	
}

function edit_subchapter(){
	
	global $wpdb;
	
	$id = $_GET['id'];
	
		if (isset( $_POST['azione'] ) && $_POST['azione'] == 'aggiorna_dati' ) {

			//echo $_POST['value_selectbox'], $_POST['chapter'], $_POST['name_chapter'];
		  	
			$table_name ='hfu_pur_subchapter';
		  
			$data = array(
				'region' => sanitize_text_field( $_POST['value_selectbox'] ),
				'chapter' => sanitize_text_field( $_POST['chapter'] ),
				'subchapter' => sanitize_text_field( $_POST['subchapter'] ),
				'title' => sanitize_text_field( $_POST['title'] )
		  	);
			
		  	$where = array(
			'id' => intval($id),
		  	);
			
			echo $table_name;

	   $wpdb->update( $table_name, $data, $where);
		header("location: https://iperprogetto.it/wp-admin/admin.php?page=subchapter-list");

	}
	
	
	 //query per leggere i dati dalla tabella
    $results = $wpdb->get_results("SELECT * FROM hfu_pur_subchapter where id=". $id, OBJECT);
	
	
	$region;
	$chapter;
	$subchapter;
	$title;
	

	foreach ($results as $result) {
		
		$region = $result->region;
		$chapter = $result->chapter;
		$subchapter = $result->subchapter;
		$title = $result->title;	
			
	}
	
	?>
		<head></head>

		<body>
			<div>
				<h1>Modifica sottocapitolo</h1>
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
	
				foreach ( $options as $value => $label ) {
				  echo '<option value="' . esc_attr( $value ) . '"' . selected( $selected, $value, false ) . '>' . esc_html( $label ) . '</option>';
				}
?>
			    </select>

				<label>Capitolo: </label><input type="text" name="chapter" value="<?php echo esc_attr($chapter); ?>"></input>
				<label>Sottocapitolo: </label><input type="text" name="subchapter" value="<?php echo esc_attr($subchapter); ?>"></input>
				<label>Nome Sottocapitolo: </label><input type="text" name="title" value="<?php echo esc_attr($title); ?>"></input>
				<input type="submit"  class="button-primary" value="Aggiorna">
			</form>


			</div>

		</body>


<?php
	

	
}

function delete_subchapter(){
	
	
	global $wpdb;
	

	
	if ( ! isset( $_POST['id'], $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'delete_record' ) ) {
    // Non inviare la richiesta da una fonte attendibile
    return;
  }

  // Eseguire la query per eliminare il record dal database
  $wpdb->delete( $wpdb->prefix . 'hfu_pur_chapters', array( 'id' => intval( $_POST['id'] ) ), array( '%d' ) );

  // Reindirizzare l'utente o restituire un messaggio di successo
	
}


/*
	function dashboard()
	{

	?>
			<head></head>
			<body>
				<h1>Pagina della Dashboard</h1>
			</body>


	<?php

	}
*/

add_action('admin_menu', 'menu_subchapter');

/**
 * funzioni per pagina lista paragrafi
 */
function update_keyword()
{
	
	global $wpdb;

	
	$id = $_POST["id"];
	$keyword = $_POST["key"];
	
	$table_name ='hfu_pur_content';
		
	$data = array(
		  "keywords" => $keyword
		);

	$where = array(
			'id' => intval($id),
		);
	
	$wpdb->update( $table_name, $data, $where);
	
	wp_die();

}

add_action("wp_ajax_update_keyword", "update_keyword");

/**
 * custom search bar ajax pur v2
 */
function custom_search_bar() {
	
	$search_term = $_POST['search_term'];

	global $wpdb;
	$query = "SELECT * FROM hfu_pur_content WHERE paragraphs_content LIKE '%".$search_term."%'";
	$results = $wpdb->get_results($query);
	
		if ($results) {
			echo json_encode($results);
		
		} else {
			$output = 'Nessun risultato trovato.';
		}
	
		
	wp_die();

}
add_action("wp_ajax_custom_search", "custom_search_bar");




function search_bar_live_search(){
?>
<head>
	<style>
		.on_hover_results{
			background-color:#f5f5f5;
		}

		
	</style>
</head>
<body>
<!--
	<div id="ex" class="serach_bar_iperpro_wrapper">
		<input type="text" id="search_bar_iperpro" name="searchTerm" placeholder="Cerca nel P.U.R.">
		<div id="content_live_search"></div>
	</div>
-->


	<script>
		
		var ajaxTimeout;
		var clickTimeout;

		jQuery(document).ready(function($) {

			$(document).on("input", '#search_bar_iperpro', function() {
				
				let searchTerm = $(this).val();

				if (searchTerm.length >= 3) {

					clearTimeout(ajaxTimeout);
					ajaxTimeout = setTimeout(function() {
						$.ajax({
							url: '/wp-admin/admin-ajax.php',
							method: 'POST',
							data: {
								action: 'custom_search',
								search_term: searchTerm
							},
							success: function (response) {

								const paragraphs = JSON.parse(response)
								console.log(paragraphs);

								let responseHtml = '';

								if (paragraphs) {

									paragraphs.map(p => {
										
										const indice =  p.paragraphs_content.indexOf(searchTerm);
										
										let inizio = Math.max(0, indice - 35); // Estrae 40 caratteri prima della parola chiave
										let fine = Math.min(p.paragraphs_content.length, indice + 35); // Estrae 40 caratteri dopo la parola chiave
										
										/*if((inizio.length - fine.length) != 0 ){
											
											if(inizio.length < 35){
												let inizio_lenght = inizio.length;
												inizio = Math.max(0, indice - (35 - inizio_lenght));
											}
											if(fine.length < 35){
													let fine_lenght = fine.length;
													fine = Math.max(0, indice - (35 - fine_lenght))
												}
										}
										else
										{

										}*/
										
										const testoRidotto = p.paragraphs_content.slice(inizio, fine) + '...';										
										const highlighted = testoRidotto.replace(searchTerm, `<strong>${searchTerm}</strong>`);




										//const content = p.paragraphs_content.slice(0, 80) + '...'
										//console.log(p)
										responseHtml += '<div class="click_for_open"><p style="margin:10px; padding:10px;" data-id="'+p.id+'" data-region="'+p.region+'" data-chapter="'+p.chapter+'"  data-subchapter="'+p.subchapter+'" data-head_paragraphs="'+p.head_paragraphs+'"><strong>' + p.region+ ': ' + p.chapter + '.' + p.subchapter + '.' + p.head_paragraphs + ')</strong> ' + highlighted + '</p></div>'
									})




									//console.log(responseHtml);

								}

								$('#content_live_search').addClass('search_bar_iperpro_results');
								$('#content_live_search').removeClass('d-none');
								$('#content_live_search').html(responseHtml);



							},
							error: function (xhr, status, error) {
								alert('Errore nella richiesta');
							}
						});
					}, 1000);

				}

				else 
				{
					if($('#content_live_search').hasClass('d-none') == false)
					{
						$('#content_live_search').removeClass('search_bar_iperpro_results');
						$('#content_live_search').addClass('d-none');
						$('#content_live_search').html('');
					}
				}

			});
			
			
			$(document).on("click", function(event) {
				if (!$(event.target).closest('#content_live_search').length) {
					if($('#content_live_search').hasClass('d-none') == false)
					{
						$('#content_live_search').removeClass('search_bar_iperpro_results');
						$('#content_live_search').addClass('d-none');
						$('#content_live_search').html('');
					}
				}
			});


			//dove le idee cagano soldi
			$(document).on("click",".click_for_open p", function(event) {
				
				$("body").append("<div id='overlay'><div id='spinner'></div></div>");

				
				if (!$(event.target).closest('#content_live_search').length) {
					if($('#content_live_search').hasClass('d-none') == false)
					{
						$('#content_live_search').removeClass('search_bar_iperpro_results');
						$('#content_live_search').addClass('d-none');
						$('#content_live_search').html('');
					}
				}
				
				let id = $(this).attr("data-id");
				let region = $(this).attr("data-region");
				let chapter = $(this).attr("data-chapter");
				let subchapter = $(this).attr("data-subchapter");
				let head_paragraphs = $(this).attr("data-head_paragraphs");
				console.log(id,region, chapter, subchapter, head_paragraphs);
				
				clearTimeout(clickTimeout);
				clickTimeout = setTimeout(function() {
					
					$("#regioni").val(region).click();
					$("#regioni").val(region).change();



					var checkChapter = setInterval(function() {
						if ($(".chapter_selector").length) {
							clearInterval(checkChapter); // rimuovi l'intervallo
							// esegui altre azioni in base all'elemento generato
							console.log("aspetto che si generi chapter");
							//$(".chapter_selector").val(chapter).click();
							$(".chapter_selector").val(chapter).change();
						}
					}, 100); // controlla ogni 100 millisecondi

					var checkChapter2 = setInterval(function() {
						if ($(".subchapter_selector").length) {
							clearInterval(checkChapter2); // rimuovi l'intervallo
							// esegui altre azioni in base all'elemento generato
							console.log("aspetto che si generi subchapter");
							//$(".subchapter_selector").val(subchapter).click();
							$(".subchapter_selector").val(subchapter).change();
							
						}
					}, 100); // controlla ogni 100 millisecondi

					var checkChapter3 = setInterval(function() {
						if ($(".paragraphs_selector[data-paragraphs='"+head_paragraphs+"']").length) {
							clearInterval(checkChapter3); // rimuovi l'intervallo
							console.log("selezione pulsante");
							$('.paragraphs_selector[data-paragraphs="'+head_paragraphs+'"]').trigger("click");
							/*if($('.paragraphs_selector[data-paragraphs="'+head_paragraphs+'"]').hasClass("bg_blue") == false){
								$('.paragraphs_selector[data-paragraphs="'+head_paragraphs+'"]').click();
								console.log("tasto cliccato");
							}*/

						}
					}, 100); // controlla ogni 100 millisecondi

					//$("#subchapter").val(subchapter).change();
					//$("#button_head_paragraphs").val(head_paragraphs).click();
				},100);
				
				
				//$('html, body').animate({scrollTop: $("#content").offset().top}, 1000);
				$('html, body').animate({scrollTop: $("#content").offset().top}, 1000, function() {
					$("#overlay").remove();
				});
				

			});



			
			$(document).on( "mouseenter", ".click_for_open", function() {
				/*let id = $(this).attr("data-id");
				let region = $(this).attr("data-region");
				let chapter = $(this).attr("data-chapter");
				console.log(id,region, chapter);*/
				//console.log("sopra risultato");
				$(this).addClass('on_hover_results');	
			});
			
			$(document).on( "mouseleave", ".click_for_open", function() {
				//console.log("fuori risultato");
				$(this).removeClass('on_hover_results');	
			});

		});

	</script>
</body>
<?php
	
	
	
}
add_shortcode('live_search_bar_paragraphs_content', 'search_bar_live_search');

/**
 * my account page "note salvate"
 */
// Aggiungi una voce custom alla pagina "My Account"
function custom_my_account_menu_items( $items ) 
{
    $new_item = array( 'note-salvate' => 'Note salvate' );
    $items = array_slice( $items, 0, 2, true ) + $new_item + array_slice( $items, 2, NULL, true );
    return $items;
}

// Registra l'endpoint per la voce custom
function custom_my_account_add_endpoint() 
{
    add_rewrite_endpoint( 'note-salvate', EP_PAGES );
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

		td, th, thead {
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
			  box-shadow: 1px 1px 2px rgba(0,0,0,0.2);
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
	
	
		foreach($results as $result)
		{
			echo '	<tr id="'. $result->id .'">
					<td><input type="checkbox" data_id_check="'. $result->id .'"></td>
					<td>'.$result->region.'</td>
					<td>'.$result->chapter.' </td>
					<td>'.$result->subchapter.' </td>
					<td>'.$result->paragraphs.' </td>
					<td>'.$result->voice_id .' </td>
					<td>'.$result->unit.' </td>
					<td>€'.$result->price.' </td>
					<td>'.$result->vat.'</td>
					<td><button id="delete_button" data-id="'. $result->id .'" class="button button-danger" style="background-color: red;  border-color: red;">Cancella nota</button></td>
					</tr>';
		}
	
	

	?>

				</tbody>
			</table>
			</div>
			<div class="row">
				<div class="column" style="margin-bottom: 15px; float:left;">
			<button  id="export_all_notes" class="button button-primary" style="background-color: green;">Esporta tutto in foglio Excel</button>
			<button  id="export_select_notes" class="button button-primary" style="background-color: green;">Esporta selezione in foglio Excel</button>
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
							$('#'+id_nota).remove(); // rimuove la riga con ID "row2"

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




add_filter( 'woocommerce_account_menu_items', 'custom_my_account_menu_items', 10, 1 );
add_action( 'init', 'custom_my_account_add_endpoint' );
add_action( 'woocommerce_account_note-salvate_endpoint', 'custom_my_account_endpoint_content' );
add_action("wp_ajax_delete_user_voice_saved_in_user_area", "delete_user_voice_saved");

/**
 * my account page "aziende salvate"
 */
// Aggiungi una voce custom alla pagina "My Account"
function custom_my_account_menu_items( $items ) 
{
    $new_item = array( 'custom-item' => 'Note salvate' );
    $items = array_slice( $items, 0, 2, true ) + $new_item + array_slice( $items, 2, NULL, true );
    return $items;
}

// Registra l'endpoint per la voce custom
function custom_my_account_add_endpoint() 
{
    add_rewrite_endpoint( 'custom-item', EP_PAGES );
}

// Mostra il contenuto della voce custom
function custom_my_account_endpoint_content()
{
	
	global $wpdb;
	
	$user_id = get_current_user_id();
	
	$results = $wpdb->get_results("SELECT hfu_users_note.id, hfu_pur_voice.region, hfu_pur_voice.chapter, hfu_pur_voice.subchapter, hfu_pur_voice.paragraphs, hfu_pur_voice.voice_id, hfu_pur_voice.unit, hfu_pur_voice.price,hfu_pur_voice.vat FROM hfu_pur_voice INNER JOIN hfu_users_note ON hfu_pur_voice.id = hfu_users_note.id_ai_voice WHERE `user_id`= '$user_id'");


	?>

	<head>
		
		<style>
		table {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  width: 100%;
		}

		td, th {
		  border: 1px solid #dddddd;
		  text-align: center;
		  padding: 8px;
			
		}

		tr:nth-child(even) {
		  background-color: #dddddd;
		}
		</style>
		
	</head>

	<body>
	
		<h2>Voci paragrafi salvate</h2>
		<div>
			<table>
			  <tr>
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
		  	
	<?php
	
	
		foreach($results as $result)
		{
			echo '<tr id="'. $result->id .'">
					<td>'.$result->region.'</td>
					<td>'.$result->chapter.' </td>
					<td>'.$result->subchapter.' </td>
					<td>'.$result->paragraphs.' </td>
					<td>'.$result->voice_id .' </td>
					<td>'.$result->unit.' </td>
					<td>€'.$result->price.' </td>
					<td>'.$result->vat.'</td>
					<td><button id="delete_button" data-id="'. $result->id .'" class="button button-danger">Cancella nota</button></td>
					</tr>';
		}
	
	

	?>

		  
			</table>
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
							$('#'+id_nota).remove(); // rimuove la riga con ID "row2"

						}

						});
					}
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





add_filter( 'woocommerce_account_menu_items', 'custom_my_account_menu_items', 10, 1 );
add_action( 'init', 'custom_my_account_add_endpoint' );
add_action( 'woocommerce_account_custom-item_endpoint', 'custom_my_account_endpoint_content' );
add_action("wp_ajax_delete_user_voice_saved_in_user_area", "delete_user_voice_saved");
add_filter( 'woocommerce_account_menu_items', 'remove_my_account_links' );

/**
 * voci rimosse da my account woocommerce
 */
function remove_my_account_orders( $menu_links ){
    unset( $menu_links['orders'] );
    return $menu_links;
}

add_filter( 'woocommerce_account_menu_items', 'remove_my_account_orders' );

/**
 * ordinamento voci menù pagina my account
 */
function custom_my_account_menu_order() {
    $menu_order = array(
        'dashboard'          => __( 'Dashboard', 'woocommerce' ),
		'note-salvate'        => __( 'Note salvate', 'woocommerce' ),
		'bp-messages'     => __( 'Messages', 'woocommerce' ),
		'wishlist'       => __( 'Preferiti', 'woocommerce' ),
		'compare'       => __( 'Confronta articoli/servizi', 'woocommerce' ), 
		'downloads'          => __( 'Downloads', 'woocommerce' ),
        //'edit-address'       => __( 'Indirizzo di fatturazione', 'woocommerce' ),
        'edit-account'       => __( 'Dettagli account', 'woocommerce' ),
        'customer-logout'    => __( 'Logout', 'woocommerce' ),
    );
    return $menu_order;
}
add_filter ( 'woocommerce_account_menu_items', 'custom_my_account_menu_order' );

/**
 * pagina lista venditori 
 */
function get_categories_product_vendor($vendor_id)
{

	// Crea una nuova istanza di WP_Query per recuperare tutti i prodotti del venditore
	$args = array(
		'user_id' => $vendor_id,
		'post_type' => 'product',
		'meta_query' => array(
			array(
				'key' => 'vendor_user', // Sostituisci con il nome dell'ACF che hai creato
				'compare' => '='
			)
		)
	);

	$products_query = new WP_Query( $args );

	// Verifica se ci sono prodotti associati al venditore
	if ( $products_query->have_posts() ) {

		// Crea un array vuoto per le categorie
		$categories = array();

		// Itera attraverso tutti i prodotti e recupera le categorie di ognuno
		while ( $products_query->have_posts() ) {
			$products_query->the_post();
			$product_id = get_the_ID();
			$product_categories = wp_get_post_terms( $product_id, 'product_cat' );

			// Aggiungi le categorie dell'attuale prodotto all'array delle categorie
			foreach ( $product_categories as $category ) {
				if ( ! in_array( $category, $categories ) ) {
					$categories[] = $category;
				}
			}
		}

		// Ripristina l'ambiente globale di WordPress
		wp_reset_postdata();

		return $categories;

	} else {
		// Nessun prodotto associato al venditore trovato
		echo "Nessun prodotto associato al venditore.";
	}
}

function vendor_list(){

?>


<style>
    
    .btn-drop-iperpro-links {
        width: 100%;
        border-width: 0px;
        background-color: #1D3C6E;
        padding: 9px 15px 8px 15px;
        color: white;
        font-size: 15px;
        border-radius: 15px;
    }
    
    #collapse-download-iperpro-links {
        padding: 10px 0px 10px 25px;
        max-width: 190px;
    }
    
    .btn-drop-iperpro-links:focus, .btn-drop-iperpro-links:hover {
        color: white;
    }

	.vendors-container-iperpro {
		display: flex;
		column-gap: 25px;
		flex-wrap: wrap;
		min-height: 500px;
	}

	.vendor-card-iperpro {
		width: 140px;
		display: flex;
		flex-direction: column;
		align-items: center;
		transition: all .2s ease-in-out;
	}

	.vendor-card-iperpro:hover { transform: scale(1.1); }

	.vendor-card-logo-iperpro {
		border: solid #ababab33 1px;
		border-radius: 11px;
		width: 100%;
		height: 140px;
		background-size: contain !important;
		background-position: center !important;
		background-repeat: no-repeat !important;
	}

	.vendor-card-name-iperpro {
		padding: 5px;
		text-align: center;
		color: #303030;
	}
	
	
	/*Paginazione */

	.pagination-wrapper {
		display:flex;
		justify-content: flex-end;
	}
	
	.page_button {
		background-color: #f5f5f5;
		border: solid;
		border-radius: 5px;
		border-color: white;
		width: 40px;
        height: 40px;
		border-radius: 50%;
	}
	
	.page_button:hover {
		background-color: #1D3C6E;
	}
	
	.page_button .page-link {
		color: #1D3C6E;
		font-size: 20px;
	} 

	.page_button:hover .page-link {
		color: #ffffff;
	}
	
</style>

<?php

	$roles = get_users(array('role' => 'venditore'));
	$vendorCards = '';
	foreach ($roles as $role) 
	{		
		$vendorCategories = get_categories_product_vendor($role->ID);
		$vendorCategoriesIds = '';

		foreach ($vendorCategories as $vendorCategory) {
			$vendorCategoriesIds .= $vendorCategory->term_id . '~';
		}

		$vendorCards .= '<a href="https://iperprogetto.it/vetrina/?venditore='. $role->user_login .'" class="vendor-card-iperpro" data-categories-vendor="'.$vendorCategoriesIds.'" data-company-name="'. $billing_company = get_user_meta( $role->ID, 'billing_company', true ) .'">
								<div class="vendor-card-logo-iperpro" style="background: url('.$campo_personalizzato = get_field('logo_venditore', 'user_' . $role->ID).');">
							</div>
							<div class="vendor-card-name-iperpro">
								<p>' . $billing_company = get_user_meta( $role->ID, 'billing_company', true ) .  '</p>
							</div>
		   				</a>';
	}

	$vendorsContainer = '<div class="vendors-container-iperpro">'.$vendorCards.'</div><div class="pagination-wrapper"><div id="pagination"></div></div></div>';
	
	?>
<script>

	// paginazione
	jQuery(document).ready(function($) {
		var pageSize = 12; // Numero di elementi da mostrare per pagina
		var pageCount = Math.ceil($('.vendor-card-iperpro').length / pageSize); // Calcola il numero totale di pagine necessarie
		var currentPage = 1; // Pagina corrente

		// Aggiungi i pulsanti di navigazione delle pagine
		$('#pagination').append('<button class="page_button"><a href="#" class="page-link" data-page="first">&laquo;</a></button>');
		$('#pagination').append('<button class="page_button"><a href="#" class="page-link" data-page="prev">&lsaquo;</a></button>');
		for(var i = 1; i <= pageCount; i++) {
			if(i == 1 || i == pageCount || (i >= currentPage - 1 && i <= currentPage + 1)) {
				$('#pagination').append('<button class="page_button"><a href="#" class="page-link' + (i == currentPage ? ' active-iper' : '') + '" data-page="' + i + '">' + i + '</a></button>');
			}
		}
		$('#pagination').append('<button class="page_button"><a href="#" class="page-link" data-page="next">&rsaquo;</a></button>');
		$('#pagination').append('<button class="page_button"><a href="#" class="page-link" data-page="last">&raquo;</a></button>');

		// Mostra la prima pagina di elementi
		showPage(currentPage);

		// Aggiungi un gestore di eventi per i pulsanti di navigazione
		$('.page-link').click(function(e) {
			e.preventDefault();
			var newPage = $(this).data('page');
			if(newPage == 'first') {
				currentPage = 1;
			} else if(newPage == 'prev') {
				if(currentPage > 1) {
					currentPage--;
				}
			} else if(newPage == 'next') {
				if(currentPage < pageCount) {
					currentPage++;
				}
			} else if(newPage == 'last') {
				currentPage = pageCount;
			} else {
				currentPage = newPage;
			}
			showPage(currentPage);
			updatePagination(currentPage, pageCount);
		});

		function showPage(page) {
			var pageSize = 12// Numero di elementi da mostrare per pagina
			var start = (page - 1) * pageSize;
			var end = start + pageSize;

			// Nascondi tutti gli elementi e mostra solo quelli nella pagina corrente
			$('.vendor-card-iperpro').hide();
			$('.vendor-card-iperpro').slice(start, end).show();
		}

		function updatePagination(currentPage, totalPages) {
			var pagination = $('#pagination');
			var range = getVisiblePageRange(currentPage, totalPages);

			// Rimuovi tutti i pulsanti numerati esistenti
			pagination.find('.page-item:not(:first-child):not(:last-child)').remove();

			// Aggiungi i pulsanti numerati necessari
			for (var i = 0; i < range.length; i++) {
				var pageLink = $('<a class="page-link" href="#" data-page="' + range[i] + '">' + range[i] + '</a>');
				var pageItem = $('<button class="page-item page_button"></button>').append(pageLink);

				// Aggiungi la classe "active-iper" al pulsante della pagina corrente
				if (range[i] === currentPage) {
					pageItem.addClass('active-iper');
				}

				pagination.find('.pagination-next').before(pageItem);
			}

			// Aggiorna la classe "disabled" per i pulsanti di navigazione precedente e successivo
			pagination.find('.pagination-prev').toggleClass('disabled', currentPage === 1);
			pagination.find('.pagination-next').toggleClass('disabled', currentPage === totalPages);
		}

	});


</script>
<?php 

	echo $vendorsContainer;
}

function vendor_searchbar() {

?>

<style>
	
	.brand-search-container-iperpro #brand-search-iperpro {
		border: none;
		background: #F5F5F5;
		border-radius: 10px;
	}

</style>
<?php
	$output = '<div class="brand-search-container-iperpro">';
	$output .= '<input type="text" id="brand-search-iperpro" placeholder="Cerca un brand">';
	$output .= '</div>';
	return $output;
}


function category_filter()
{

	$categories = get_terms( array(
		'taxonomy' => 'product_cat',
		'hide_empty' => false,
		'parent' => 0,
	) );


?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<style>
	
	div.category-list-iperpro {
		background: #DBE3EB;
		padding: 25px;
		list-style: none;
		border-radius: 15px;
		display: flex;
		flex-direction: column;
		grid-gap: 10px;
	}
	
	div.category-list-iperpro .collapse-content {
		display: flex;
		flex-direction: column;
		    grid-gap: 5px;
	}

	div.category-list-iperpro .collapse-content > a {
       color: #303030;
	}
	
	.category-column-pd .elementor-widget-wrap {
		padding-left: 15px !important;
	}


</style>

	<div class="category-list-iperpro" > 

			<a class="btn-drop-iperpro-links d-flex justify-content-between" data-bs-toggle="collapse" href="#collapse-download-iperpro-links" role="button"
			   aria-expanded="false" aria-controls="collapse-iperpro-links">
				<span>Categorie</span> 
				<i id="chevron-iperpro-links" class="et-icon et-down-arrow" style="padding-top: 3px;"></i>
			</a>

         <div class="collapse show"  id="collapse-download-iperpro-links">
             <div class="collapse-content">
				<?php

				echo '<a id="show-all1"><strong>Tutte le categorie</strong></a>';
				foreach ( $categories as $category ) 
				{
					if($category->name != 'Uncategorized')
						echo '<a class="category-filter" data-cat-id="'.$category->term_id.'">' . $category->name . '</a>';
				}
			?>
			 </div>
	     </div>
     </div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<style>
    
    .btn-drop-iperpro-links {
        width: 100%;
        border-width: 0px;
        background-color: #1D3C6E;
        padding: 9px 15px 8px 15px;
        color: white;
        font-size: 15px;
        border-radius: 10px;
    }
    
    #collapse-download-iperpro-links {
        padding: 10px 0px 10px 25px;
        max-width: 190px;
    }
    
    .btn-drop-iperpro-links:focus, .btn-drop-iperpro-links:hover {
        color: white;
    }
    
</style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
        <script>
            
            const dropCollapsibleIperproLinks = document.getElementById('collapse-download-iperpro-links')
            
            const chevronIperproLinks = document.getElementById('chevron-iperpro-links')
            
            dropCollapsibleIperproLinks.addEventListener('hidden.bs.collapse', event => {
              chevronIperproLinks.classList.remove("et-up-arrow")
              chevronIperproLinks.classList.add("et-down-arrow")
            })
            
            dropCollapsibleIperproLinks.addEventListener('shown.bs.collapse', event => {
              chevronIperproLinks.classList.remove("et-down-arrow")
              chevronIperproLinks.classList.add("et-up-arrow")
            })

		jQuery(document).ready(function($) {

			var debounceTimer;
			$("#brand-search-iperpro").on("keyup", function() {
				clearTimeout(debounceTimer);
				debounceTimer = setTimeout(function() {
					var value = $("#brand-search-iperpro").val().toLowerCase();
					$("a[data-company-name]").filter(function() {
						$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
					});
				}, 500); 
			});

			$(".category-filter").click(function(){
				var category = $(this).text().trim(); // ottieni il testo dell'elemento cliccato e rimuovi gli eventuali spazi bianchi
				var categoryId = $(this).attr('data-cat-id');
				$("[data-categories-vendor]").each(function() {
					var categories = $(this).attr("data-categories-vendor").split("~"); // suddividi l'attributo in un array di categorie
					if ($.inArray(categoryId, categories) !== -1) { // cerca il valore selezionato nell'array di categorie
						$(this).show();
					} else {
						$(this).hide();
					}
				});
			});

			$("#show-all1").click(function() {
				$("[data-categories-vendor]").show();
			});

			$("#show-all2").click(function() {
				$("[data-categories-vendor]").show();
			});



			$(".letter-filter").click(function(){
				var letter = $(this).text().trim();
				var regex = new RegExp('^' + letter, 'i'); // regular expression che verifica se l'attributo inizia con la lettera o il numero selezionato, senza considerare la maiuscola o la minuscola
				var filtered = $("a[data-company-name]").filter(function() {
					var value = $(this).data("company-name");
					return regex.test(value);
				});
				$("[data-categories-vendor]").hide(); // nascondi tutti gli elementi con l'attributo
				filtered.show(); // mostra solo gli elementi che soddisfano la condizione
			});


			$(".number-filter").click(function(){
				var number = $(this).text().trim();
				var regex = new RegExp('^[0-9]' + number, 'i'); // regular expression che verifica se l'attributo inizia con il numero selezionato, senza considerare la maiuscola o la minuscola
				var filtered = $("a[data-company-name]").filter(function() {
					var value = $(this).data("company-name");
					return regex.test(value);
				});
				$("[data-categories-vendor]").hide(); // nascondi tutti gli elementi con l'attributo
				filtered.show(); // mostra solo gli elementi che soddisfano la condizione
			});

		});


	</script>



<?php

}

function name_filter()
{
?>

<style>
	a.letter-filter, a.number-filter {
		margin-right: 16px;
		color: #7A7A7A;
        
	}
	
	a.letter-filter, a.number-filter:hover {
		color: #7A7A7A !important;
	}

	div.filter-container-iperpro {
		font-size: 20px;
	}
</style>
<?php
	echo '<div class="filter-container-iperpro"><a class="number-filter">0-9 </a>';
	for ($i = 65; $i <= 90; $i++) {

		echo '<a class="letter-filter">'. chr($i) . " " .'</a>';

	}
	echo '<a id="show-all2" style="margin-right: 20px; color:#1D3C6E; font-size: 24px;">
		<svg width="17px" version="1.1" id="Livello_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
		 viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve">
	<style type="text/css">
		.st0{fill:#646371;}
		.st1{fill:#716F7C;}
		.st2{fill:#817D8A;}
		.st3{fill:#B2AEB6;}
	</style>
	<path class="st0" d="M16.9,40c-0.4-0.2-0.9-0.2-1.3-0.2c-4.4-0.7-8.1-2.6-11.2-5.7c-0.9-0.9-0.9-2.3,0-3.3c0.9-0.9,2.3-0.9,3.2,0
		c1.6,1.6,3.4,2.9,5.5,3.6c5.9,2.1,11.3,1.1,15.9-3.1c3.7-3.3,5.3-7.6,4.9-12.6c-0.5-6.5-5.4-12.2-11.8-13.6c-4.5-1-8.6-0.2-12.3,2.4
		c-0.1,0-0.1,0.1-0.3,0.2c0.7,0,1.3,0,1.9,0c1,0,1.9,0.7,2.1,1.7c0.4,1.4-0.6,2.7-2,2.9c-0.1,0-0.2,0-0.4,0c-2.5,0-4.9,0-7.4,0
		c-1.4,0-2.4-1-2.4-2.4c0-2.5,0-5.1,0-7.6c0-1.2,0.8-2.1,2-2.3C4.5,0,5.6,0.7,5.9,1.8C6,2.1,6,2.4,6,2.8C6,3.4,6,4,6,4.6
		c0.4-0.3,0.7-0.5,1.1-0.8c2.9-2,6-3.3,9.5-3.6c7-0.6,12.8,1.8,17.4,7.1c2.6,3.1,4.1,6.7,4.5,10.8c0.6,6.5-1.6,12-6.3,16.5
		c-3.1,3-6.9,4.7-11.2,5.2c-0.3,0-0.5,0-0.8,0.1c-0.2,0-0.4,0-0.6,0c-0.1-0.1-0.3-0.1-0.4,0c-0.3,0-0.6,0-0.9,0
		c-0.2-0.1-0.4-0.1-0.5,0c-0.1,0-0.3,0-0.4,0c-0.1,0-0.1,0-0.2,0C17.2,40,17.1,40,16.9,40z"/>
	<path class="st1" d="M17.8,40c0.2-0.1,0.4-0.1,0.5,0C18.2,40,18,40,17.8,40z"/>
	<path class="st2" d="M19.2,40c0.1-0.1,0.3-0.1,0.4,0C19.5,40,19.3,40,19.2,40z"/>
	<path class="st3" d="M17.3,40c0.1-0.1,0.1-0.1,0.2,0C17.4,40,17.3,40,17.3,40z"/>
	</svg>
</a>';
	echo '</div>';
}




add_shortcode('all_vendor_content', 'vendor_list');

add_shortcode('category_filter_jquery', 'category_filter');

add_shortcode('name_filter_jquery', 'name_filter');

add_shortcode('vendor_searchbar_shortcode', 'vendor_searchbar');

/**
 * pagina vetrina venditore
 */
function products_single_vendor_count($vendor_id){
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key' => 'vendor_user', // campo personalizzato che indica il venditore
				'value' => $vendor_id, // ID del venditore
				'compare' => '='
			)
		)
	);

	$query = new WP_Query( $args );
	$products = $query->get_posts();

	return $count = count( $products );

}

function get_categories_product_vendor_sidebar_left(){

?>

<style>
	.vendor_categories_list_container {
		background: #dae3eb;
		border-radius: 15px;
		padding: 20px;
	}

	.vendor_categories_list_title {
		color: #1e3c6d;
		margin-bottom: 20px;
	}

	.vendor_categories_list {
		display: flex;
		flex-direction: column;
		gap: 10px;
		margin-top: 10px;
	}


	.vendor_category_item {
		background: #1e3c6d;
		color: white;
		padding: 11px;
		width: 100%;
		display: inline-block;
		border-radius: 15px;
	}

	.vendor_category_item:hover {
		color: #1e3c6d;
		background: #dae3eb;
		background: whitesmoke;

	}



</style>

<?php

	// Nome utente del venditore di cui vogliamo trovare i prodotti
	$vendor = $_GET['venditore'];

	// Recupera l'ID dell'utente corrispondente al nome utente del venditore
	$venditore = get_user_by('login', $vendor);
	$venditore_id = $venditore->ID;

	// Recupera i prodotti che hanno il venditore assegnato nell'ACF specifico
	$args = array(
		'post_type' => 'product',
		'meta_query' => array(
			array(
				'key' => 'vendor_user',
				'value' => $venditore_id,
				'compare' => '=',
			),
		),
	);

	$query = new WP_Query( $args );

	// Cicla sui prodotti e recupera le categorie di ciascun prodotto
	$categories = array();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$product_categories = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'names' ) );
			$categories = array_merge( $categories, $product_categories );
		}
		wp_reset_postdata();
	}

	// Rimuove eventuali categorie duplicate
	$categories = array_unique( $categories );

	echo '<div class="vendor_categories_list_container">';
	echo '<h3 class="vendor_categories_list_title">Categorie</h3>';
	echo '<div class="vendor_categories_list"><a class="vendor_category_item" id="show-all-single-product-vendor">Tutte le categorie del venditore</a>';
	// Stampa la lista delle categorie
	foreach ( $categories as $category ) {
		echo '<a class="categories-filter-product-single-vendor vendor_category_item">'. $category . '</a>';
	}
	echo '</div></div>';

?>

<body>
	<script>

		jQuery(document).ready(function($) {

			$(".categories-filter-product-single-vendor").click(function(){
				var category = $(this).text().trim(); // ottieni il testo dell'elemento cliccato e rimuovi gli eventuali spazi bianchi
				$("[data-categories-single-product-vendor]").each(function() {
					var categories = $(this).attr("data-categories-single-product-vendor").split("~"); // suddividi l'attributo in un array di categorie
					if ($.inArray(category, categories) !== -1) { // cerca il valore selezionato nell'array di categorie
						$(this).show();
					} else {
						$(this).hide();
					}
				});
			});

			$("#show-all-single-product-vendor").click(function() {
				$("[data-categories-single-product-vendor]").show();
			});

		});



	</script>

</body>

<?php




}

function vendor_info(){

	// Nome utente del venditore di cui vogliamo trovare i prodotti
	$vendor = $_GET['venditore'];

	// Recupera l'ID dell'utente corrispondente al nome utente del venditore
	$venditore = get_user_by('login', $vendor);
	$venditore_id = $venditore->ID;

?>
<head>

	<style>

		html {
			scroll-behavior: smooth;
		}

		div.head_img {
			min-height: 300px;
			margin: 0 -10px;
			background-position: center;
			background-size: cover;
		}

		.vendor_title_iperpro_container {
			margin-top: 30px;
		}

		.vendor_title_name_iperpro {
			display: flex; 
			align-items: center; 
			column-gap: 10px;
			margin-bottom: 15px;
		}

		.vendor_logo_iperpro {
			max-width: 80px !important;
		}

		.vendor_name_iperpro {
			font-size: 25px;	
			font-weight: bold;
		}

		.vendor_title_info_iperpro_container {
			display: flex;
			justify-content: space-between;
		}

		.vendor_title_info_iperpro_descr {
			flex-basis: 75%;
		}

		.vendor_title_info_iperpro_contacts {
			display: flex;
			flex-direction: column;
			gap: 5px;
		}

		.vendor_title_info_iperpro_btns {
			display: flex;
			flex-direction: column;
			gap: 15px;
		}

		.vendor_title_info_iperpro_btn {
			background: #44AC40;
    border: none;
    color: white;

    border-radius: 15px;
    padding: 10px;
    min-width: 200px;
    text-align: center;
		}

		.vendor_nav_btns_iperpro {
			display: flex;
			justify-content: space-between;
			margin: 40px 0;
		}

		.vendor_nav_btn_iperpro {
			background: #1e3c6d;
			color: white;
			border-radius: 15px;
			padding: 10px;
			min-width: 200px;
			text-align: center;
		}

		.vendor_nav_btn_iperpro:hover {
			color: #1e3c6d;
			background: #dae3eb;
		}



		.btn-save-vendor{
			float:right;
			margin-top: 15px;
			margin-right: 5px;
		}

		.vendor-page-button{
			margin-left:5px;
			margin-right:5px;
			background-color: green;
			transition: background-color 0.3s ease; /* animazione di transizione */
		}

		.vendor-page-button:hover{
			margin-left:5px;
			margin-right:5px;
			background-color: red;
		}

		.vendor-page-button:active{
			margin-left:5px;
			margin-right:5px;
			background-color: #dbe3eb;
			color: white;
		}

		.vendor_title_iperpro_wrapper {
			max-width: 1150px;
            margin: auto;
		}

	</style>

</head>
<body>
	<?php
	$immagine_id = get_field('immagine_copertina_vetrina', 'user_' . $venditore_id);
	?>


		<?php echo '<div class="head_img" style="background-image: url('.$immagine_id.')"></div>'; ?>

    <div class="vendor_title_iperpro_wrapper">
	<div class="vendor_title_iperpro_container"> 
		<?php

	$vendor_data = get_user_meta($venditore_id);

	echo '
		<div class="vendor_title_name_iperpro">
			<img class="vendor_logo_iperpro" src="' . $campo_personalizzato = get_field('logo_venditore', 'user_' . $venditore_id) .  '" width="150px">
			<span class="vendor_name_iperpro">'. get_user_meta($venditore_id, 'billing_company', true ).'</span>
		</div>
		';

	$link = get_permalink();
	echo '
		<div class="vendor_title_info_iperpro_container">
			<div class="vendor_title_info_iperpro_descr">
				<p>'.$vendor_data['description'][0] .'</p>
			</div>
			<div class="vendor_title_info_iperpro_contacts">
				<div>Totale prodotti del venditore: <strong>'.products_single_vendor_count($venditore_id).'</strong></div>
				<div>Feedback: <strong>100%</strong> positivi</div>
				<div>Followers: <strong>10.000</strong></div>


				<div class="vendor_title_info_iperpro_btns">
					<button class="vendor_title_info_iperpro_btn">Salva venditore</button>
					<button class="vendor_title_info_iperpro_btn">Contatta venditore</button>
				</div>
			</div>			
		</div>
		';

		?>
	</div>

	<div class="">

		<div class="vendor_nav_btns_iperpro">
			<a class="vendor_nav_btn_iperpro" href="#product-vendor-anchor">Prodotti</a>
			<a class="vendor_nav_btn_iperpro" href="#vendor-catalog">Cataloghi</a>
			<a class="vendor_nav_btn_iperpro" href="#eventi">Fiere/Eventi</a>
			<a class="vendor_nav_btn_iperpro" href="#news">News</a>
			<a class="vendor_nav_btn_iperpro" href="#info-vendor">Informazioni</a>
		</div>

	</div>
</div>

	<script>
		jQuery(document).ready(function($) {

			/*$('#live_search_product_vendor').on('input', function() {

			  	var searchText = $(this).val().toLowerCase();

			    $('div[data-categories-single-product-vendor]').hide(); // nascondi tutti i div con l'attributo
					$('div[data-categories-single-product-vendor]').filter(function() {
					  var productTitle = $(this).text().toLowerCase();
					  return productTitle.includes(searchText);
						}).show();

			});*/
			$('#live_search_product_vendor').on('input', function() {
				var searchText = $(this).val().toLowerCase();
				if (searchText.length >= 2) { // verificare che la ricerca contenga almeno 2 caratteri

					$('a[data-categories-single-product-vendor]').hide(); // nascondi tutti i div con l'attributo

					$('a[data-categories-single-product-vendor]').filter(function() {
						var productTitle = $(this).find('div.product_title').text().toLowerCase();
						return productTitle.includes(searchText);
					}).show();

				} else{$('a[data-categories-single-product-vendor]').show();}
			});

		});

	</script>

</body>
<?php


}

function product_single_vendor(){

	// Nome utente del venditore di cui vogliamo trovare i prodotti
	$vendor = $_GET['venditore'];

	// Recupera l'ID dell'utente corrispondente al nome utente del venditore
	$venditore = get_user_by('login', $vendor);
	$venditore_id = $venditore->ID;

	// Recupera i prodotti che hanno il venditore assegnato nell'ACF specifico
	$args = array(
		'post_type' => 'product',
		'meta_query' => array(
			array(
				'key' => 'vendor_user', // Sostituisci con il nome dell'ACF che hai creato
				'value' => $venditore_id,
				'compare' => '='
			)
		)
	);

?>
<head>

	<style>

		.page_button{

			background-color: #dbe3eb;
			color:white;
			border: solid;
			border-radius:5px;
			border-color: white;
			width:25px;
			margin-right; 5px;
		}

		.vendor_products_title_iperpro {
			display: flex;
			margin-bottom: 20px;
		}

		.vendor_products_title_iperpro > h3 {
			flex-basis: 150%;
		}

		.vendor_products_title_iperpro > input {
			background: #f5f5f5;
			border: none;
			border-radius: 15px;
			max-width: 350px;
		}

		.vendor_products_container {
			display: flex;
			flex-wrap: wrap;
			gap: 20px;
			justify-content: space-around;
		}

		.vendor_product_card_iperpro {
			width: 200px;
			height: 300px;
			background: whitesmoke;
			border-radius: 15px;
		}

		.vendor_product_card_iperpro_content {
			width: 100%;
			height: 100%;
			display: flex;
			flex-direction: column;
			align-items: center;
			padding: 5px;
		}

		.vendor_product_card_iperpro_content > .product_image {
			min-height: 150px;
			display: flex;
			justify-content: center;
			flex-direction: column;
			align-items: center;
		}


		.vendor_product_card_iperpro_content > .product_title {
			text-align:center;
		}

		.vendor_product_card_iperpro_content > .product_cat {
			margin: 10px 0;
		}

		.vendor_catalogues_container {
			display: flex;
			flex-wrap: wrap;
			justify-content: space-between;
			gap: 5px;
		}
		.vendor_catalog_link_iperpro {
			width: 200px;
			height: 300px;
			border-radius: 15px;
			overflow: hidden;
		}

		.vendor_catalog_link_iperpro > img{
			height: 100%;
			max-width: 100%;
			border: none;
			border-radius: 0;
			box-shadow: none;
			object-fit: cover;
		}

		#product-vendor-anchor {
			margin-bottom: 80px;
		}


		.vendor_title_info_iperpro_contacts_socials {
			display: flex;
			gap: 5px;
			margin-top: 10px;
		}


		.vendor_title_info_iperpro_contacts_socials > a {
			color: white;
			background: #1d3967;
			padding: 5px;
			border-radius: 5px;
			height: 30px;
			width: 30px;
			display: flex;
			justify-content: center;
			align-items: center;
			font-size: 20px;
		}

	</style>

</head>

<body>
	<div class="" id="product-vendor-anchor" >

		<div class="vendor_products_title_iperpro" >
			<input id="live_search_product_vendor" type="text" placeholder="Cerca prodotti...">
		</div>
		<div class="vendor_products_container" >
			<?php

	$query = new WP_Query($args);
	// Itera sui risultati della query
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			$cat;
			$product_title = get_the_title();
			$categories = get_the_terms(get_the_ID(), 'product_cat');
			if ($categories && !is_wp_error($categories)) {
				$category = array_shift($categories);
				$cat = $category->name;
			}

			echo '<a class="column-product  vendor_product_card_iperpro" href="'.get_permalink().'" data-categories-single-product-vendor="'. $cat .'"><div class="vendor_product_card_iperpro_content">';
			echo '<div class="product_image"><img src="'.get_the_post_thumbnail_url(get_the_ID(), 'full').'" width="150px" height="150px"></div>';
			echo '<div class="product_cat">'.$cat.'</div>';
			echo '<div class="product_title"><strong>'.$product_title.'</strong></div>';
			echo '</div></a>';
		}

		wp_reset_postdata();
	} else {
		echo "<p>Nessun prodotto trovato.</p>";
	}

			?>

		</div>
		<div style="float:right; margin-top: 20px;">
			<div id="pagination"></div>
		</div>
	</div>


	<div  id="vendor-catalog" >
		<h3>Cataloghi venditore</h3>
		<div class="vendor_catalogues_container">
			<?php
	$vendor_meta = get_user_meta($venditore_id);

	for($i = 1; $i<5; $i++){

		$link_pdf = wp_get_attachment_url($vendor_meta['catalog_vendor_'.$i][0]);
		$image_catalog = wp_get_attachment_url($vendor_meta["catalog_vendor_image_".$i][0]);

		echo '<a class="vendor_catalog_link_iperpro" href="'. $link_pdf .'" target=_blank><img src="'.$image_catalog.'"></a>';
	}

			?>			
		</div>
	</div>

	<div class="" id="eventi" style="margin-top: 100px;">
		<h3>Eventi</h3>
		<div>
			<?php

	echo 'Eventi venditore: '. $vendor_meta['eventi_vendor'][0];


			?>
		</div>
	</div>


	<div class="" id="news" style="margin-top: 100px;">
		<h3>News</h3>
		<div>
			<?php

	echo 'News venditore: '. $vendor_meta['news_vendor'][0];


			?>
		</div>
	</div>


	<div class="" id="info-vendor" style="margin-top: 100px;">
		<h3>Informazioni su <?php echo $vendor_meta['billing_company'][0] ?></h3>
		<div>
			<?php

				echo '<p>'. $vendor_meta['bio_vendor'][0]. '</p>';


			?>
		</div>
	</div>

	<div class="" style="margin-top: 100px;">
		<h2 >Dettagli aziendali</h2>
		<div class="column">
			<?php

	echo '<div>Ragione sociale: <strong>'.$vendor_meta['billing_company'][0].'</strong></div>';
	echo '<div>Nome: <strong>'.$vendor_meta['billing_first_name'][0].'</strong></div>';
	echo '<div>Cognome: <strong>'.$vendor_meta['billing_last_name'][0].'</strong></div>';
	echo '<div>Indirizzo: <strong>'.$vendor_meta['billing_address_1'][0].'</strong></div>';
	echo '<div>Numero di telefono: <strong>'.$vendor_meta['shipping_phone'][0].'</strong></div>';
	echo '<div>Email: <strong>'.$vendor_meta['billing_email'][0].'</strong></div>';

			?>
		</div>
		<div class="vendor_title_info_iperpro_contacts_socials">
			<a href="https://www.facebook.com/sharer/sharer.php?u='.$link.'" target="_blank"><i class="fa fa-facebook"></i></a>
			<a href="https://api.whatsapp.com/send?text='.$link.'" target="_blank" target="_blank"><i class="fa fa-whatsapp"></i></a>
			<a href="https://www.linkedin.com/sharing/share-offsite/?url='.$link.'" target="_blank"><i class="fa fa-linkedin"></i></a>
		</div>
	</div>

	<script>

		jQuery(document).ready(function($) {


			var pageSize = 16; // Numero di elementi da mostrare per pagina
			var pageCount = Math.ceil($('.column-product').length / pageSize); // Calcola il numero totale di pagine necessarie
			var currentPage = 1; // Pagina corrente

			// Aggiungi i pulsanti di navigazione delle pagine
			$('#pagination').append('<button class="page_button"><a href="#" class="page-link" data-page="first">&laquo;</a></button>');
			$('#pagination').append('<button class="page_button"><a href="#" class="page-link" data-page="prev">&lsaquo;</a></button>');
			for(var i = 1; i <= pageCount; i++) {
				if(i == 1 || i == pageCount || (i >= currentPage - 1 && i <= currentPage + 1)) {
					$('#pagination').append('<button class="page_button"><a href="#" class="page-link' + (i == currentPage ? ' active' : '') + '" data-page="' + i + '">' + i + '</a></button>');
				}
			}
			$('#pagination').append('<button class="page_button"><a href="#" class="page-link" data-page="next">&rsaquo;</a></button>');
			$('#pagination').append('<button class="page_button"><a href="#" class="page-link" data-page="last">&raquo;</a></button>');

			// Mostra la prima pagina di elementi
			showPage(currentPage);

			// Aggiungi un gestore di eventi per i pulsanti di navigazione
			$('.page-link').click(function(e) {
				e.preventDefault();
				var newPage = $(this).data('page');
				if(newPage == 'first') {
					currentPage = 1;
				} else if(newPage == 'prev') {
					if(currentPage > 1) {
						currentPage--;
					}
				} else if(newPage == 'next') {
					if(currentPage < pageCount) {
						currentPage++;
					}
				} else if(newPage == 'last') {
					currentPage = pageCount;
				} else {
					currentPage = newPage;
				}
				showPage(currentPage);
				updatePagination(currentPage, pageCount);
			});

			function showPage(page) {
				var pageSize = 16 // Numero di elementi da mostrare per pagina
				var start = (page - 1) * pageSize;
				var end = start + pageSize;

				// Nascondi tutti gli elementi e mostra solo quelli nella pagina corrente
				$('.column-product').hide();
				$('.column-product').slice(start, end).show();
			}

			function updatePagination(currentPage, totalPages) {
				var pagination = $('#pagination');
				var range = getVisiblePageRange(currentPage, totalPages);

				// Rimuovi tutti i pulsanti numerati esistenti
				pagination.find('.page-item:not(:first-child):not(:last-child)').remove();

				// Aggiungi i pulsanti numerati necessari
				for (var i = 0; i < range.length; i++) {
					var pageLink = $('<a class="page-link" href="#" data-page="' + range[i] + '">' + range[i] + '</a>');
					var pageItem = $('<button class="page-item page_button"></button>').append(pageLink);

					// Aggiungi la classe "active" al pulsante della pagina corrente
					if (range[i] === currentPage) {
						pageItem.addClass('active');
					}

					pagination.find('.pagination-next').before(pageItem);
				}

				// Aggiorna la classe "disabled" per i pulsanti di navigazione precedente e successivo
				pagination.find('.pagination-prev').toggleClass('disabled', currentPage === 1);
				pagination.find('.pagination-next').toggleClass('disabled', currentPage === totalPages);
			}



		});

	</script>
</body>

<?php

}


add_shortcode('get_head_vendor', 'vendor_info');

add_shortcode('get_product_single_vendor', 'product_single_vendor');

add_shortcode('sidebar_left_categories_filter_jquery', 'get_categories_product_vendor_sidebar_left');

add_shortcode('get_product_filter_city_cap', 'product_filter_city_cap');

/**
 * cookie - impression - click
 */
function post_impression()
{
	
	global $wpdb;
	
	$postId = $_POST['postId'];
	$impression_value = $_POST['impression_value'];
			
	$wpdb->insert( 'hfu_tracking_data', array(
		'type' => 'impression',
		'post_id' => $postId,
		'counter' => $impression_value,
		'data' => current_time('mysql')

	));
	
	wp_die();

}
add_action("wp_ajax_post_impression", "post_impression");

function post_click()
{
	
	global $wpdb;
	
	$postId = $_POST['postId'];
	$click_value = $_POST['click_value'];
				
	$wpdb->insert( 'hfu_tracking_data', array(
		'type' => 'click',
		'post_id' => $postId,
		'counter' => $click_value,
		'data' => current_time('mysql')
		));

	wp_die();

}
add_action("wp_ajax_post_click", "post_click");

function wpb_hook_javascript() {
	if ( ! is_admin() ) 
	{
  	?>
		<script type="text/javascript">
			
			jQuery(document).ready(function($) {
			
				function checkDOMChanges() {
			  var observer = new MutationObserver(function(mutations) {
				mutations.forEach(function(mutation) {
				  if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
					init();
				  }
				});
			  });
			  observer.observe(document.body, { childList: true, subtree: true });
			}

			  	var delay = 3000; // tempo di permanenza minimo in millisecondi
			  	var viewedProducts = [];

			  	function trackImpression(postId) {
				if (!viewedProducts.includes(postId)) {
				  // qui puoi inserire la tua chiamata per il conteggio dell'impressione
				  console.log('Impressione conteggiata per il prodotto ' + postId);
				  //
					$.ajax({
						url: "/wp-admin/admin-ajax.php",
						type: "POST",
						data: {
							action: "post_impression",
							postId: postId,
							impression_value: 1
						},
						success: function(response) {
							//console.log(response);
						}

					});
				    
					viewedProducts.push(postId);
				  	setCookie('viewed_products', JSON.stringify(viewedProducts), 30);
				}
			  }

			  	function setCookie(name, value, days) {
				var expires = '';
				if (days) {
				  var date = new Date();
				  date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
				  expires = '; expires=' + date.toUTCString();
				}
				document.cookie = name + '=' + encodeURIComponent(value) + expires + '; path=/';
			  }

			  	function getCookie(name) {
				var nameEQ = name + '=';
				var ca = document.cookie.split(';');
				for (var i = 0; i < ca.length; i++) {
				  var c = ca[i];
				  while (c.charAt(0) == ' ') c = c.substring(1, c.length);
				  if (c.indexOf(nameEQ) == 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
				}
				return null;
			  }

			  	function init() {
				  
				var viewedProductsCookie = getCookie('viewed_products');
				if (viewedProductsCookie) {
				  viewedProducts = JSON.parse(viewedProductsCookie);
				}
				  
				$(document).on('mouseenter','.etheme-product-grid-item', function() {
					var $this = $(this);
					var postId = $this.closest('.etheme-product-grid-item').attr('class').match(/post-(\d+)/);
					if (postId) {
					  postId = postId[1];
					//console.log(postId);
					  setTimeout(function() {
						trackImpression(postId);
					  }, delay);
					}
				  });
				  
				 
				 $(document).on('click','.etheme-product-grid-item', function(){
					var $this = $(this);
					var postId = $this.closest('.etheme-product-grid-item').attr('class').match(/post-(\d+)/);
					if (postId) {
					  postId = postId[1];
					  // qui puoi inserire la tua chiamata per il conteggio del click
					  //console.log('Click conteggiato per il prodotto ' + postId);
						
						$.ajax({
						url: "/wp-admin/admin-ajax.php",
						type: "POST",
						data: {
							action: "post_click",
							postId: postId,
							click_value: 1
						},
						success: function(response) {
							//console.log(response);
						}

					});
						
					}
				  });
			  }

				init();
				
			//checkDOMChanges();

			
			/*
			$(document).on('click', function(event) {
			  var elemento = event.target;
			  var tipo_evento = event.type;
			  var testo = '';
			  var id_elemento= elemento.getAttribute('id');


			  // Ottieni il testo dell'elemento cliccato
			  if (elemento.nodeName === 'BUTTON' || 
				  elemento.nodeName === 'A' || 
				   elemento.nodeName === 'P'
				 ) 
			  {
				testo = elemento.textContent.trim();

			  }


			  // Invia i dati dell'evento al server
			  var dati_evento = {
				tipo_evento: tipo_evento,
				testo: testo,
				ID: id_elemento,
				data: new Date().toISOString()
			  };
				
			console.log('dati:', dati_evento);
			
	


			  /*$.ajax({
				url: '/traccia_evento',
				type: 'POST',
				contentType: 'application/json',
				data: JSON.stringify(dati_evento)
			  });   
			});
			*/
				
			});
		</script>
	<?php
	}
	
}
add_action('wp_head', 'wpb_hook_javascript');

function test_cookie()
{
	// Recupera il gruppo di campi ACF con ID 5906
    $group = acf_get_field_group(5906);
    // Recupera tutti i campi personalizzati definiti nel gruppo
    $fields = acf_get_fields($group['ID']);

    // Aggiunge ogni campo come opzione di mappatura nell'importatore di Woocommerce
    foreach ( $fields as $field ) {
      echo  $options[ '_' . $field['name'] ] = $field['label'];
    }

    //return $options;



}
add_shortcode('get_cookie_test', 'test_cookie');

/**
 * Bottone compare in single product page  
 */
function add_btn_compare_single_product_iperpro() {
    $cookie_compare_name = 'xstore_compare_ids_0';
    $cookie_compare_value = isset($_COOKIE[$cookie_compare_name]) ? $_COOKIE[$cookie_compare_name] : '';
	global $product;
    // Dividi la stringa in base al carattere pipe
    $products_in_compare = explode('|', $cookie_compare_value);

    $opertion = 'add_to_compare';
	// Decodifica ogni stringa in un oggetto JSON
	$products = array();
	foreach ($products_in_compare as $product_in_compare_JSON) {
		$product_in_compare = json_decode(stripslashes($product_in_compare_JSON));
		$products[] = $product_in_compare;
		
		if($product->get_id() === $product_in_compare->id) {
            $opertion = 'remove_compare';
		}
		
	}
    
	$btn_compare = '<a href="/compare/?'.$opertion.'='.$product->get_id().'">'.$opertion.'</a>';

	return $btn_compare;
	
}
add_shortcode('show_cookie_value', 'add_btn_compare_single_product_iperpro');

/**
 * chart impression - Click for vendor_user
 */
function total_click_user()
{
	
	global $wpdb;
	
	$result = $wpdb->get_results("SELECT DATE(`data`) AS data, SUM(`counter`) AS somma FROM hfu_tracking_data WHERE post_id IN (SELECT post_id FROM hfu_postmeta WHERE meta_key = 'vendor_user' AND meta_value = " . get_current_user_id() . ") AND DATE(`data`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE() AND `type` = 'click' GROUP BY DATE(`data`)");
	
	if(current_user_can( 'venditore' ) || current_user_can( 'operatore' )){
	
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

/**
 * reviews account area vendor
 */
// Aggiungi una voce custom alla pagina "My Account"
function add_reviews_body_voice( $items ) 
{

	$new_item = array( 'recensioni' => 'Recensioni' );
    $items = array_slice( $items, 0, 2, true ) + $new_item + array_slice( $items, 2, NULL, true );
    return $items;
}

// Registra l'endpoint per la voce custom
function reviews_my_account_add_endpoint() 
{
    add_rewrite_endpoint( 'recensioni', EP_PAGES );
}

// Mostra il contenuto della voce custom
function reviews_body()
{
	
	global $wpdb;
	
	if(current_user_can( 'venditore' ) || current_user_can( 'operatore' ) || current_user_can( 'administrator' )){

	?>

<head>

	<style>

	</style>

</head>

<body>

	<div>
		<h2>Recensioni del venditore</h2>
	<div>
	<?php
		
		//AND meta_value =  get_current_user_id() Per DEBUG sostituire get_current_user_id() con l'id dell'utente interessato Es. id=11 utente=heliox_it
		
		
		$results = $wpdb->get_results("SELECT c.*, m.* 
		FROM `hfu_comments` AS c 
		JOIN `hfu_commentmeta` AS m ON c.`comment_ID` = m.`comment_id` 
		WHERE c.`comment_post_ID` IN (
		  SELECT post_id
		  FROM hfu_postmeta
		  WHERE meta_key = 'vendor_user'
		  AND meta_value =  11   
		)
		AND c.`comment_type` = 'review' AND m.meta_key = 'rating'");
		//var_dump($results);
		if(count($results)>0){

			echo "<br><br>";
			foreach($results as $result)
			{

				echo '<p style="margin-bottom: -1px;"><strong>'. $result->comment_author .'</strong></p>';
				echo '<p style="margin-bottom: -1px;">'. $result->comment_content .'</p>';
				echo '<p style="margin-bottom: -1px;"><a href="'.get_permalink($result->comment_post_ID).'" target="_blank">'. get_the_title($result->comment_post_ID) .'</a></p>';

				// Imposta il rating
				$rating =  intval($result->meta_value);

				// Calcola la larghezza delle stelle in base al rating
				$width = $rating / 5 * 100;

				// Genera le stelle
				echo '<div class="star-rating">';
				echo '<span style="width: '. $width .'%;">&#9733;&#9733;&#9733;&#9733;&#9733;</span>';
				echo '</div>';
				echo '<hr style="width:50%;text-align:left;margin-left:0; margin-bottom: 15px;">';



			}	
		} 
		
		else{
			echo '<p>Nessuna recesione disponibile</p>';
		}
			


	?>
		</div>
	</div>
		
</body>

<script>

	jQuery(document).ready(function($) {

	});
	
</script>

	<?php	
		
	}
	else{
		//return '<p>Nessuna recesione disponibile</p>';
	}
		
}

add_filter( 'woocommerce_account_menu_items', 'add_reviews_body_voice', 10, 1 );
add_action( 'init', 'reviews_my_account_add_endpoint' );
add_action( 'woocommerce_account_recensioni_endpoint', 'reviews_body' );

/**
 * Pagina Bandi
 */
function custom_category_filter_bandi() {
	$args = array(
		'show_option_all' => '',
		'orderby' => 'name',
		'hierarchical' => true,
		'depth' => 0,
		'hide_empty' => 1,
		'taxonomy' => 'category',
		'walker' => new Walker_Category_Custom_bandi,
		'child_of' => 104,
		'title_li' => '',
	);
	ob_start();
	wp_list_categories( $args );
	$categories = ob_get_clean();
	return $categories;
}

class Walker_Category_Custom_bandi extends Walker_Category {

	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		$pad = str_repeat('&nbsp;', $depth );
		$cat_name = strlen($category->name) > 20 ? substr($category->name, 0, 20).'...' : $category->name;
		$output .= '<a href="'.get_permalink().'/?categoria='. $category->term_id.'" class="category-posts-filter-link tag-' . $category->slug . '" data-cat-id="'. $category->term_id.'" style="display: block;">' .  $cat_name . '</a>';
		return $output;
	}
}

function filtro_categoria_bandi($categoria_id = 104) {
	$output = '';
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;


	$meta_query = [];
	if( isset( $_GET['data-scadenza'] ) ) {

		$meta_query[] = [
			'relation' => 'AND', 
			[ 
				'key' => 'scadenza_bando', 
				'value' => $_GET['data-scadenza'],
				'compare' => '<=',
				'type' => 'DATE'
			],
		];
	}

	if( isset( $_GET['data_inizio_bando'] ) ) {
		$meta_query[] = [
			'relation' => 'AND', 
			[ 
				'key' => 'data_inizio_bando', 
				'value' => $_GET['data_inizio_bando'], 
				'compare' => '>=', 
				'type' => 'DATE' 
			],
		];
	}


	if ( isset( $_GET['categoria'] ) ) {

		$categoria_id =  $_GET['categoria'];
	}
	if ( $categoria_id ) {

		$args = array(
			'cat' => $categoria_id,
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => 4,
			'paged' => $paged
		);
		$args['meta_query'] = $meta_query;


		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {

			while ( $query->have_posts() ) {
				$query->the_post();

				$excerpt = get_the_excerpt();
				$trimmed_excerpt = wp_trim_words( $excerpt, 30, '...' );
				$scadenza = strtotime(get_field('scadenza_bando'));
				$inizioBando = strtotime(get_field('data_inizio_bando'));
				$scadenzaHtml = '';

				if(!empty($scadenza)) { 
					$scadenzaHtml = 'Scade il ' . date('d/m/Y', $scadenza);;
				}

				$output .= '<a class="category-post-item' . $tag_classes . '" href="' . get_permalink() . '" data-scadenza="'.$scadenza.'" data-inizio-bando="'.$inizioBando.'">
                            <div class="category-post-item-img" style="background: url('.get_the_post_thumbnail_url().')"></div>
                            <div class="category-post-text-wrapper">
                                <div class="category-post-title">' . get_the_title() .'</div>
                                  <div class="category-post-due-date">'.$scadenzaHtml.'</div>
                            </div>
                        </a>';

				$scadenzaHtml = '';

			}

			$output .= '<div class="pagination-wrapper"><div class="pagination">' . paginate_links( array(
				'total' => $query->max_num_pages,
				'current' => $paged,
				'prev_next' => true,  		
				'prev_text' => '←',
				'next_text' => '→',
				'mid_size'  => 1,
				'end_size' => 1,


			)) . '</div></div>';

		} else {
			$output .= 'Nessun post trovato nella tipologia ' . get_cat_name($_GET['categoria']);
		}

		wp_reset_postdata();
	} else {
		$output .= 'Tipologia non valida';
	}

	return $output;
}



function display_posts_by_category_bandi( $atts ) {
	$output = '';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	  integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<style>


	.active-strong {
		font-weight: bold;
	} 

	.category-posts-wrapper {
		display: flex;
		flex-direction: row-reverse;
		justify-content: space-between;
		gap: 45px;
	}

	.category-posts-wrapper .category-posts-list {
		width: 70%;
		display: flex;
		flex-direction: column;
		gap: 20px;
	}

	.category-posts-wrapper .category-posts-filter {
		width: 30%;
		background: #DBE3EB;
		display: flex;
		flex-direction: column;
		gap: 10px;
		padding: 25px;
		border-radius: 15px;
		margin-bottom: auto;
	}

	.category-posts-wrapper  .category-posts-filter .category-posts-filter-label {
		color: #1D3C6E;
	}


	.category-posts-wrapper  .category-posts-filter .category-posts-filter-button  {
		background: #1D3C6E;
		text-align: left;
		padding: 9px 13px;
		border-radius: 15px;
		color: white;
		border: none;
	}

	.category-posts-wrapper .category-post-item {
		display: flex;
		gap: 40px;
		background: #EFF7EE;
		border-radius: 15px;
		overflow: hidden;
		flex-direction: row-reverse;
		padding-left: 43px;
		justify-content: space-between;
	}

	.category-posts-wrapper .category-post-item .category-post-item-img {
		min-width: 200px;
		height: 150px;
		background-position: center !important;
		background-size: cover !important;
		background-repeat: no-repeat !important;
	}

	.category-posts-wrapper .category-post-item .category-post-text-wrapper {
		display: flex;
		flex-direction: column;
		justify-content: space-around;
		gap: 5px;
	}

	.category-posts-wrapper .category-post-item .category-post-text-wrapper .category-post-title {
		font-size: 18px;
		font-weight: bold;
		color: #7A7A7A;
	}


	.category-posts-wrapper .category-post-item .category-post-text-wrapper .category-post-tag {
		background: #3f5983;
		padding: 5px;
		border-radius: 5px;
		margin: 2px;
	}

	.category-posts-wrapper .category-post-item .category-post-due-date {
		color: #1e3c6d;
	}

	.category-posts-wrapper  .category-posts-filter .category-posts-filter-list {
		display: flex;
		flex-direction: column;
	}

	.category-posts-wrapper  .category-posts-filter .category-posts-filter-list .category-posts-filter-link {
		color: #303030;
	}

	#chevron-iperpro-links {
		padding-top: 3px;
	}

	/*Paginazione */

	.pagination-wrapper {
		display:flex;
		justify-content: flex-end;
	}

	.page_button {
		background-color: #f5f5f5;
		border: solid;
		border-radius: 5px;
		border-color: white;
		width: 40px;
		height: 40px;
		border-radius: 50%;
	}

	.page_button:hover {
		background-color: #1D3C6E;
	}

	.page_button .page-link {
		color: #1D3C6E;
		font-size: 20px;
	} 

	.page_button:hover .page-link {
		color: #ffffff;
	}


	.pagination-wrapper {
		display:flex;
		justify-content: flex-end;
	}

	.page-numbers {
		background-color: #d9d9d9;
		border: solid;
		border-color: white;
		width: 40px;
		height: 40px;
		border-radius: 50%;
		color: #1D3C6E;
		display: flex;
		font-size: 16px;
		justify-content: center;
		align-items: center;
	}

	.page-numbers:hover, .page-numbers.current {
		background-color: #1D3C6E;
		color: #ffffff;

	}


	/*Commutatore */
	.btn-drop-iperpro-links {
		min-width: 100%;
		max-width: 190px;
		border-width: 0px;
		background-color: #1D3C6E;
		padding: 9px 15px 8px 15px;
		color: white;
		font-size: 15px;
		border-radius: 10px;
	}

	#collapse-download-iperpro-links {
		padding: 10px 0px 10px 25px;
	}

	.btn-drop-iperpro-links:focus, .btn-drop-iperpro-links:hover {
		color: white;
	}

</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
		crossorigin="anonymous"></script>
<script>
	jQuery(document).ready(function($) {


		var urlParams = new URLSearchParams(window.location.search);
		var dataInizioBando = urlParams.get('data-inizio-bando');
		var dataScadenzaBando = urlParams.get('data-scadenza');


		if (dataInizioBando) {
			$('#data-inizio-bando-input').val(dataInizioBando);
		}

		$('#data-inizio-bando-input').change(function() {
			var dateValue = $(this).val();
			var url = new URL(window.location.href);
			url.searchParams.set('data-inizio-bando', dateValue);
			window.location.href = url.toString();
		});

		if (dataScadenzaBando) {
			$('#data-scadenza-bando-input').val(dataScadenzaBando);
		}

		$('#data-scadenza-bando-input').change(function() {
			var dateValue = $(this).val();
			var url = new URL(window.location.href);
			url.searchParams.set('data-scadenza', dateValue);
			window.location.href = url.toString();
		});

		$('.category-posts-filter').ready(function() {
			const catLinks = $('.category-posts-filter-link');
			const urlParams = new URLSearchParams(window.location.search);

			const category = urlParams.get('categoria');
			$.each(catLinks, function(idx,elm) {

				console.log(elm.dataset.catId, category);
				if(elm.dataset.catId == category) {
					$(elm).addClass('active-strong');
				} else if(elm.dataset.catId !== category && category  !== null) {

					$(elm).removeClass('active-strong');
				}
			})
		})

		$(document).ready(function() {
			$('.page-numbers.current').replaceWith(function() {
				return $('<a>', {
					html: $(this).html(),
					class: $(this).attr('class')
				});
			});
		});


		// commutatore

		const dropCollapsibleIperproLinks = $('#collapse-download-iperpro-links');

		const chevronIperproLinks = $('#chevron-iperpro-links');

		dropCollapsibleIperproLinks.on('hidden.bs.collapse', event => {
			chevronIperproLinks.removeClass('et-up-arrow');
			chevronIperproLinks.addClass('et-down-arrow');
		});

		dropCollapsibleIperproLinks.on('shown.bs.collapse', event => {
			chevronIperproLinks.removeClass('et-down-arrow');
			chevronIperproLinks.addClass('et-up-arrow');
		});
	});

</script>
<?php
	$output .= '<div class="category-posts-wrapper"><div class="category-posts-list">';

	$output .= filtro_categoria_bandi( 104 ,$output);
	$output .= '</div>';

	$output .= '<div class="category-posts-filter">

    <a class="category-posts-filter-button d-flex justify-content-between" data-bs-toggle="collapse"
        href="#collapse-download-iperpro-links" role="button" aria-expanded="false"
        aria-controls="collapse-iperpro-links">
        <span>Tipologie</span>
        <i id="chevron-iperpro-links" class="et-down-arrow et-icon"></i>
    </a>

    <div class="collapse show" id="collapse-download-iperpro-links">
        <div class="category-posts-filter-list">
            <a class="category-posts-filter-link tag-all active-strong" style="display: block;" data-cat-id="104"
                href="?categoria=104">Tutte le tipologie</a>';

	$output .= '<div>'. custom_category_filter_bandi() .'</div>'; 

	$output .= '    </div> 
                  </div> 
                ';

	$output .= '<div class="category-posts-filter-date-wrapper">
                            <p class="category-posts-filter-button flex justify-content-between">
                                <span>Data di inizio</span> 
                                <i id="chevron-iperpro-links" class="et-calendar et-icon"></i>
                            </p>
                            <input id="data-inizio-bando-input" type="date" name="inizio">
                        </div>
                        <div class="category-posts-filter-date-wrapper">
                            <p class="category-posts-filter-button flex justify-content-between">
                                <span>Data di scadenza</span> 
                                <i id="chevron-iperpro-links" class="et-calendar et-icon"></i>
                            </p>
                            <input id="data-scadenza-bando-input" type="date" name="scadenza">
                        </div>';            

	$output .= '</div>
	         </div>';
	wp_reset_postdata();
	return $output;
}

add_shortcode( 'category_posts_bandi', 'display_posts_by_category_bandi' );

/**
 * import acf csv
 */
add_filter( 'woocommerce_csv_product_import_mapping_options', 'custom_acf_mapping_options' );

function custom_acf_mapping_options( $options ) {
    // Recupera il gruppo di campi ACF con ID 5906
    $group = acf_get_field_group(5906);
    // Recupera tutti i campi personalizzati definiti nel gruppo
    $fields = acf_get_fields($group['ID']);

    // Aggiunge ogni campo come opzione di mappatura nell'importatore di Woocommerce
    foreach ( $fields as $field ) {
        $options[ 'meta:' . $field['name'] ] = $field['label'];
    }

    return $options;
}

/**
 * shortcode pagina singolo prodotto
 */
function add_download_link_product_page() {
	
	$output = []; // Initializing
	$product = wc_get_product();
	
	if ( $product->is_downloadable() ) {
		// Loop through WC_Product_Download objects
		foreach( $product->get_downloads() as $key_download_id => $download ) {

			## Using WC_Product_Download methods (since WooCommerce 3)

			$download_name = $download->get_name(); // File label name
			$download_sub_name = substr($download->get_name(), 0, 10); // File label name
			$download_sub_name = strlen($download->get_name()) >= 10 ? substr($download->get_name(), 0, 10) .'... ' : $download->get_name(); // File label name
			$download_link = $download->get_file(); // File Url
			$download_id   = $download->get_id(); // File Id (same as $key_download_id)
			//$download_type = $download->get_file_type(); // File type
			$download_ext  = $download->get_file_extension(); // File extension

			## Using array properties (backward compatibility with previous WooCommerce versions)

			// $download_name = $download['name']; // File label name
			// $download_link = $download['file']; // File Url
			// $download_id   = $download['id']; // File Id (same as $key_download_id)
			
			$output[$download_id] = '<a title="'.$download_name.'" style="display: block; color: #54595f;" href="'.$download_link.'">'.$download_sub_name.'.'.$download_ext.'</a>';
		}
		// Output example
	} else  {
		$output = ['Nessun file'];
	}

        $links = implode('', $output);

		return '<div>' . $links . '</div>';

}
add_shortcode('add_dowload_link_product_page', 'add_download_link_product_page');

function display_product_data_table() {
	
 	$sku = get_post_meta( get_the_ID(), '_sku', true );
    $categories = get_the_terms( get_the_ID(), 'product_cat' );
    $tags = get_the_terms( get_the_ID(), 'product_tag' );
	$product = wc_get_product( get_the_ID() );
	$attributes = $product->get_attributes();
   	$weight = $product->get_weight();
   	$dimensions = $product->get_dimensions();
    $table_html = '<table class="attrs_product_page">';
    $table_html .= $sku ? '<tr><th><strong>SKU</strong></th><td>' . $sku . '</td></tr>' : '<tr><th><strong>SKU</strong></th><td>Non disponibile</td></tr>';
    $table_html .= '<tr><th><strong>Categorie</strong></th><td>';
    if ( $categories && ! is_wp_error( $categories ) ) {
        $category_names = array();
        foreach ( $categories as $category ) {
            $category_names[] = $category->name;
        }
        $table_html .= implode( ', ', $category_names );
    }
    $table_html .= '</td></tr>';
    $table_html .= '<tr><th><strong>Tag</strong></th><td>';
    if ( $tags && ! is_wp_error( $tags ) ) {
        $tag_names = array();
        foreach ( $tags as $tag ) {
            $tag_names[] = $tag->name;
        }
        $table_html .= implode( ', ', $tag_names );
    } 
	else {
        $table_html .= 'Nessun tag';
    }
    $table_html .= '</td></tr>';
	if ( $attributes ) {
		foreach ( $attributes as $attribute ) {
			$attribute_value = "";
			$attribute_name = wc_attribute_label( $attribute->get_name());
			$options = $attribute->get_options();
			for($i = 0; $i < count($options); $i++){
				$get_name_parms = get_term_by('id', $options[$i], $attribute->get_name() );
				if ($i == count($options) - 1) {
					$attribute_value .= $get_name_parms->name; 
				}else{
					$attribute_value .= $get_name_parms->name .', '; 
				}
			}
			$table_html .= '<tr><th><strong>'.$attribute_name.'</strong></th><td>'.$attribute_value.'</td>';
		}
	} 
    $table_html .= '</td></tr>';
    $table_html .= $weight ? '<tr><th><strong>Peso</strong></th><td>' . $weight . '</td></tr>' : '';
    $table_html .= $dimensions ? '<tr><th><strong>Dimensioni</strong></th><td>' . $dimensions . '</td></tr>' : '';
    $table_html .= '</table>';
    return $table_html;
	
}

add_shortcode('add_attr_table_product_page', 'display_product_data_table');

function campi_di_applicazione(){

	// Recupera il valore del campo personalizzato "nome_campo"
	$campo_personalizzato = get_field('campi_di_applicazione');

	// Mostra il valore del campo personalizzato
	if ($campo_personalizzato) 
	{	
		
		return $campo_personalizzato;
	}else{
		return '<p>Non disponibile</p>';
	}
}
add_shortcode('print_campi_di_applicazione', 'campi_di_applicazione');

function indicazioni_duso(){
	
	// Recupera il valore del campo personalizzato "nome_campo"
	$campo_personalizzato = get_field('indicazioni_duso');
	
	// Mostra il valore del campo personalizzato
	if ($campo_personalizzato) {
		return $campo_personalizzato;
	}else{
		return '<p>Non disponibile</p>';
	}
	
}
add_shortcode('print_indicazioni_duso', 'indicazioni_duso');

function certificazioni_e_marcature(){
	
	// Recupera il valore del campo personalizzato "nome_campo"
	$campo_personalizzato = get_field('certificazioni_e_marcature');

	// Mostra il valore del campo personalizzato
	if ($campo_personalizzato) {
		return $campo_personalizzato;
	}else{
		return '<p>Non disponibile</p>';
	}
	
}
add_shortcode('print_certificazioni_e_marcature', 'certificazioni_e_marcature');

function altre_indicazioni_duso(){
	
	// Recupera il valore del campo personalizzato "nome_campo"
	$campo_personalizzato = get_field('altre_indicazioni_duso');

	// Mostra il valore del campo personalizzato
	if ($campo_personalizzato) {
		return $campo_personalizzato;
	}else{
		return '<p>Non disponibile</p>';
	}
	
}
add_shortcode('print_altre_indicazioni_duso', 'altre_indicazioni_duso');

function voce_di_capitolato(){
	
	// Recupera il valore del campo personalizzato "nome_campo"
	$campo_personalizzato = get_field('voce_di_capitolato');

	// Mostra il valore del campo personalizzato
	if ($campo_personalizzato) {
		return $campo_personalizzato;
	}else{
		return '<p>Non disponibile</p>';
	}
	
}
add_shortcode('print_voce_di_capitolato', 'voce_di_capitolato');

function avvertenze(){
	
	// Recupera il valore del campo personalizzato "nome_campo"
	$campo_personalizzato = get_field('avvertenze');

	// Mostra il valore del campo personalizzato
	if ($campo_personalizzato) {
		return $campo_personalizzato;
	}else{
		return '<p>Non disponibile</p>';
	}
	
}
add_shortcode('print_avvertenze', 'avvertenze');

function video_product(){
	
	// Recupera il valore del campo personalizzato "nome_campo"
	$campo_personalizzato = get_field('video_prodotto');

	// Mostra il valore del campo personalizzato
	if ($campo_personalizzato) {
		return '
		<iframe width="600" height="400" src="'.$campo_personalizzato.'"></iframe>';
		
	}else{
		return '<p>Non disponibile</p>';
	}
	
}
add_shortcode('print_video_product', 'video_product');

function vendor_product(){
	
		
	// Recupera il valore del campo personalizzato "nome_campo"
	$id_user = get_field('vendor_user');
	$meta_values = get_user_meta($id_user);

	$post_id = get_the_ID();
	$title = get_the_title( $post_id );
	echo '<h1 style="font-size:20px; color: rgb(29, 60, 110);">'.esc_html( $title ).'</h1>';


	// Mostra il valore del campo personalizzato
	if ($meta_values) {
		$company_name = $meta_values['billing_company'][0];
		return '<p>Venduto da <a href="https://iperprogetto.it/vetrina/?venditore='. $meta_values['nickname'][0].'" >'.$company_name.'</a></p>';
		
	}else{
		return '<p>Non disponibile</p>';
	}
}
add_shortcode('print_vendor', 'vendor_product');

function logo_vendor(){
	
	$id_user = get_field('vendor_user');
	$meta_values = get_user_meta($id_user);

	if ($meta_values) 
	{
		$logo_venditore = $meta_values['logo_venditore'][0];
		return '<a href="https://iperprogetto.it/vetrina/?venditore='.$meta_values['nickname'][0].'"><img src="'.esc_url(wp_get_attachment_url($logo_venditore)?wp_get_attachment_url($logo_venditore):wc_placeholder_img_src( 'medium')).'" width="150" height="150"></a>';
	}
	
	else
	{
		return '<p>Non disponibile</p>';
	}
}
add_shortcode('print_logo_vendor', 'logo_vendor');

function product_location(){


// Recupera il valore del campo personalizzato "nome_campo"
	$campo_personalizzato = get_field('product_lease');
		
	if ( $campo_personalizzato ) {
		return  '<p>'.$campo_personalizzato[0]->name.'</p>';
	} else {
		return '<p>Non disponibile</p>';
	}

}
add_shortcode('print_product_location', 'product_location');

function product_datasheet(){


	// Recupera il valore del campo personalizzato "nome_campo"
	$pdf_list = get_field('schede_prodotto_pdf');
	//var_dump($pdf_list);
	echo '<br>';
	if($pdf_list){
		foreach($pdf_list as $pdf){

			echo '		
					<div style="display: grid;float:left;margin-right: 15px;border: 1px solid #AAB1BE; border-radius:1px;align-items: center;width: 200px;height: 200px;text-align: -webkit-center; background-color: #f0f0f1;">
						<a href="'.$pdf['url'].'" target="_blank">
							<div class="content">
								<div class="content_img">
									<img src="'.$pdf['icon'].'" width="50" height="50">
								</div>
								<div style="width: 180px;">
									<p style="margin: 0;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;">'.$pdf['title'].'</p>
								</div>
							</div>
						</a>
					</div>

				';
		}
	}else{
		return '<p>Non disponibili</p>';
	}
}
add_shortcode('print_product_datasheet', 'product_datasheet');

function vendor_catalog_product_page(){

	$product_id = get_the_ID(); // Ottieni l'ID della pagina prodotto
	$vendor_user = get_post_meta( $product_id, 'vendor_user', true ); // Recupera il valore del campo meta "vendor_user"
	$vendor_meta = get_user_meta($vendor_user);
	
	if($vendor_meta)
	{
		for($i = 1; $i<5; $i++)
		{
			if(wp_get_attachment_url($vendor_meta['catalog_vendor_'.$i][0]) && wp_get_attachment_url($vendor_meta["catalog_vendor_image_".$i][0])){
				//echo '<a class="vendor_catalog_link_iperpro" href="'. $link_pdf .'" target=_blank><img src="'.$image_catalog.'"></a>';

				return '<div style="display: grid;float:left;margin-right: 15px;margin-bottom: 15px;border: 1px solid #AAB1BE; border-radius:1px;align-items: center;text-align: -webkit-center; background-color: #f0f0f1;">
						<a href="'.wp_get_attachment_url($vendor_meta['catalog_vendor_'.$i][0]).'" target="_blank">
							<div class="content">
								<div class="content_img">
									<img src="'.wp_get_attachment_url($vendor_meta["catalog_vendor_image_".$i][0]).'"  width="150">
								</div>
							</div>
						</a>
					</div>';
			}
			else
			{
				return '<p>Non disponibile</p>';
			}
		}
	}
	else
	{
		return '<p>Non disponibile</p>';
	}
}
add_shortcode('print_catalog_product_page', 'vendor_catalog_product_page');

/**
 * Custom searchbar for post by category 
 */
function custom_search_filter($query) {
	if ( !is_admin() && $query->is_main_query() ) {
		if ($query->is_search) {
			$query->set('post_type', 'post');
		}
	}
}

add_action('pre_get_posts','custom_search_filter');


function custom_search_shortcode($atts) {
	$atts = shortcode_atts( array(
		'category' => '',
	), $atts );

?>

<style>
	.custom-search-wrapper .custom-search-inputs-wrapper {
		background: whitesmoke;
		display: flex;
		border-radius: 15px;

	}

	.custom-search-wrapper .custom-search-inputs-wrapper #custom-search-value {
		border: none;
		background: none;
	}

	.custom-search-wrapper .custom-search-inputs-wrapper #custom-search-button {
		min-width: 50px;
		background: none;
		border: none;
	}


	.custom-search-wrapper #custom-search-results-wrapper  {
		display: none;
		position: absolute;
		z-index: 100;
		background: whitesmoke;
		margin-top: 5px;
		left: 0;
		right: 0;
		padding: 10px;
	    border-radius: 15px;

	}

	.custom-search-wrapper #close-custom-search-results {
		padding: 5px;
		display: flex;
		justify-content: end;
	}

	.custom-search-wrapper #close-custom-search-results > div {
		background: #1e3c6d;
    height: 25px;
    width: 25px;
    color: white;
    border-radius: 100px;
    font-size: initial;
		cursor: pointer;
		text-align: center;
		
    font-size: 20px;

	}

	.custom-search-wrapper #custom-search-results {
		max-height: 400px;
		overflow-y: auto;
	}

	.custom-search-wrapper #custom-search-results .result-item {
		padding: 10px;
	}

	.custom-search-wrapper #custom-search-results .result-item .result-item-content {
		padding: 10px;
		border-radius: 15px;
		
	}	
	
	.custom-search-wrapper #custom-search-results .result-item .result-item-content a { 
	color: #1e3c6d;
	}

	.custom-search-wrapper #custom-search-results .result-item .result-item-content:hover {
		background: #dae3eb;
	}



	.custom-search-wrapper #custom-search-results .result-item:not(:last-child) {
		border-bottom: solid #DBE3EB 1px;
	}

	.custom-search-wrapper #custom-search-results .spinner-wrapper {
		width: 100%;
		display: flex;
		justify-content: center;
		padding: 20px;
	}
	
	.not-fount-results {
		text-align: center;
    color: #1e3c6d;
    font-size: 18px;
	}
	
	

	/* spinner loading */

	.lds-ring {
		display: inline-block;
		position: relative;
		width: 80px;
		height: 80px;
	}
	.lds-ring div {
		box-sizing: border-box;
		display: block;
		position: absolute;
		width: 64px;
		height: 64px;
		margin: 8px;
		border: 8px solid #1e3c6d;
		border-radius: 50%;
		animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
		border-color: #1e3c6d transparent transparent transparent;
	}
	.lds-ring div:nth-child(1) {
		animation-delay: -0.45s;
	}
	.lds-ring div:nth-child(2) {
		animation-delay: -0.3s;
	}
	.lds-ring div:nth-child(3) {
		animation-delay: -0.15s;
	}
	@keyframes lds-ring {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}

	/* ===== Scrollbar CSS ===== */
	/* Firefox */
	#custom-search-results {
		scrollbar-width: auto;
		scrollbar-color: #1e3c6d #ffffff;
	}

	/* Chrome, Edge, and Safari */
	#custom-search-results::-webkit-scrollbar {
		width: 16px;
	}

	#custom-search-results::-webkit-scrollbar-track {
		background: #ffffff;
	}

	#custom-search-results::-webkit-scrollbar-thumb {
		background-color: #1e3c6d;
		border-radius: 10px;
		border: 3px solid #ffffff;
	}
</style>
<?php

	$output = '<div class="custom-search-wrapper">';
	$output .= '<div class="custom-search-inputs-wrapper"><input type="text" id="custom-search-value" placeholder="Cerca">';
	$output .= '<button id="custom-search-button"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path d="M23.64 22.176l-5.736-5.712c1.44-1.8 2.232-4.032 2.232-6.336 0-5.544-4.512-10.032-10.032-10.032s-10.008 4.488-10.008 10.008c-0.024 5.568 4.488 10.056 10.032 10.056 2.328 0 4.512-0.792 6.336-2.256l5.712 5.712c0.192 0.192 0.456 0.312 0.72 0.312 0.24 0 0.504-0.096 0.672-0.288 0.192-0.168 0.312-0.384 0.336-0.672v-0.048c0.024-0.288-0.096-0.552-0.264-0.744zM18.12 10.152c0 4.392-3.6 7.992-8.016 7.992-4.392 0-7.992-3.6-7.992-8.016 0-4.392 3.6-7.992 8.016-7.992 4.392 0 7.992 3.6 7.992 8.016z"></path></svg></button></div>';
	$output .= '<div id="custom-search-results-wrapper"><div id="close-custom-search-results"><div>X</div></div><div id="custom-search-results"></div></div>';
	$output .= '</div>';

	$output .= '<script type="text/javascript">
	var timer = null;
	$("#custom-search-value").on("input", function() {

		var searchValue = $("#custom-search-value").val();
		var category = "'.esc_attr($atts['category']).'";
		// Se esiste un timer in esecuzione, cancellalo
		if (timer !== null) {
			clearTimeout(timer);
		}

		// Avvia un nuovo timer per avviare la chiamata Ajax dopo un secondo
		timer = setTimeout(function() {
			var query = $("#custom-search-value").val();

			// Effettua la chiamata Ajax solo se la query non è vuota
			if (query !== "") {
				$.ajax({
					type : "post",
					dataType : "html",
					url : "'.admin_url('admin-ajax.php').'",
					data : {action: "custom_search_ajax_handler", category: category, searchValue: searchValue },
					beforeSend: function() {
                        $("#custom-search-results-wrapper").css("display", "block");
						$("#custom-search-results").html("<div class=\"spinner-wrapper\"><div class=\"lds-ring\"><div></div><div></div><div></div><div></div></div></div>");
					},
					success: function(response) {
                        $("#custom-search-results-wrapper").css("display", "block");
						$("#custom-search-results").html(response);
					}
				});
			}
		}, 1000); // Imposta un debounce di 1 secondo
	});

	jQuery(document).ready(function($) {
		$("#custom-search-button").click(function() {
			var searchValue = $("#custom-search-value").val();
			var category = "'.esc_attr($atts['category']).'";
			$.ajax({
				type : "post",
				dataType : "html",
				url : "'.admin_url('admin-ajax.php').'",
				data : {action: "custom_search_ajax_handler", category: category, searchValue: searchValue },
				beforeSend: function() {
                    $("#custom-search-results-wrapper").css("display", "block");
					$("#custom-search-results").html("<div class=\"spinner-wrapper\"><div class=\"lds-ring\"><div></div><div></div><div></div><div></div></div></div>");
				},
				success: function(response) {
				    $("#custom-search-results-wrapper").css("display", "block");
					$("#custom-search-results").html(response);
				}
			});
		});

			$("#close-custom-search-results").click(function() {
				    $("#custom-search-results-wrapper").css("display", "none");

		});
	});</script>';
	return $output;
}

add_shortcode('custom_search_by_category', 'custom_search_shortcode');

function custom_search_ajax_handler() {
	$category = $_POST['category'];
	$searchValue = $_POST['searchValue'];
	$args = array(
		's' => $searchValue,
		'post_type' => 'post',
		'category_name' => $category
	);
	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			echo '<div class="result-item"><div class="result-item-content"><a href="'.get_permalink().'">'.get_the_title().'</a></div></div>';
		}
	} else {
		echo '<div class="not-fount-results">'.__('Nessun risultato trovato').'</div>';
	}

	wp_reset_postdata();
	die();
}
add_action( 'wp_ajax_custom_search_ajax_handler', 'custom_search_ajax_handler' );
add_action( 'wp_ajax_nopriv_custom_search_ajax_handler', 'custom_search_ajax_handler' );

/**
 * Rimuovere classe 'style_default' da box categorie nel catalogo [shortcode_remove_class_style_default]
 */
function remove_class_style_default( $atts ) {
?>
<script>
	jQuery(document).ready(function($) {
	 $('.category-grid').removeClass('style-default');
	});
</script>
<?php
}

add_shortcode( 'shortcode_remove_class_style_default', 'remove_class_style_default' );

/**
 * p.u.r. front-end v3
 */
function pur_menu(){

	global $wpdb;

?>

<head>

	<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css' rel='stylesheet'>

	<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js'></script>


	<style>
		.elementor-section.elementor-section-boxed > .elementor-container {
			max-width: 1175px !important;
		}
		
		.select_pur_iperpro {
			background-image: url('/wp-content/uploads/2023/03/Untitled-design-1.png');
			background-position: right;
			background-size: 1500px;
			border: none;
			border-radius: 15px;
			width: 570px;
		}
		



		.button_up {
			border: none;
			border-radius: 24px;
			color: black;
			background-color: #F5F5F5;
			padding: 10px 10px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 10px 10px;
			cursor: pointer;
		}

		.button_up:hover {
			background-color: #1E3B88;
			color: white;
		}

		.button_coll {
			border: none;
			color: black;
			background-color: #F5F5F5;
			padding: 10px 10px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 5px 5px;
			cursor: pointer;

		}

		.button_coll:hover {
			background-color: #1E3B88;
			color: white;

		}

		.bg_blue {
			background-color: #1E3B88 !important;
			color: white !important;
		}

		.collapse {}

		.div_ {

			margin-left: 25px;
		}

		a {
			color: black
		}

		a:hover {
			color: white;
		}

		.button_up {
			border: none;
			border-radius: 24px;
			color: black;
			background-color: #F5F5F5;
			padding: 10px 10px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 4px 2px;
			cursor: pointer;
		}

		.button_up:hover {
			background-color: #1E3B88;
			color: white;
		}

		.button_coll {
			border: none;
			color: black;
			background-color: #F5F5F5;
			padding: 10px 10px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 4px 2px;
			cursor: pointer;
		}

		.button_coll:hover {
			background-color: #1E3B88;
			color: white;
		}

		.button_coll:active {
			background-color: #1E3B88;
			color: white;

		}

		.pur-select {
			margin-top: 10px;
		}

		.div_ {

			margin-left: 25px;
		}

		.btn-blue {
			padding: 10px 5px;
			color: white;
			border: none;
			border-radius: 15px;
			background:#1E3B88; 
		}

		#style-1::-webkit-scrollbar-track {
			-webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0);
			border-radius: 10px;
			background-color: rgba(0, 0, 0, 0.0);
		}

		#style-1::-webkit-scrollbar {
			width: 12px;
			background-color: rgba(0, 0, 0, 0.0);
		}

		#style-1::-webkit-scrollbar-thumb {
			border-radius: 10px;
			-webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.0);
			background-color: #d4d4d4;
		}
		
		
	</style>

	<head>
	</head>


	<body>
		<?php 
	//$results_chapter = $wpdb->get_results("SELECT * FROM hfu_pur_chapters");
	//$results_subchapter = $wpdb->get_results("SELECT * FROM hfu_pur_subchapters");
		?>

		<div class="container">
			<div class="row">
				<div class="col-sm select_pur_iperpro_container">
					<select class="col-sm select_pur_iperpro" name="region" id="regioni">
						<option value="" disabled selected>Seleziona il P.U.R. della Regione</option>
						<option value="Sicilia">Sicilia</option>
					</select>
				</div>
				<div id="chapter_sel" class="pur-select"></div>
				<div id="sub_chapter_sel" class="pur-select"></div>
				<div id="content" class="pur-select"></div>

			</div>
		</div>

		<script>
			jQuery(document).ready(function($) {

				$(document).on("change", "select.select_pur_iperpro", function() {
					$(this).css("background-image", "url('/wp-content/uploads/2023/03/Untitled-design.png')");
				})

				$(document).on("click", "button.button_up", function() {

					let dataAttr = $(this).attr('data-bs-target');

					$('button[data-bs-target^=#paragrafo]').each(function(button) {

						$(this).removeClass('bg_blue')
					})

					$(this).addClass('bg_blue');
					//	let button = $(this)

					$("div[id^='paragrafo']").each(function() {
						var divId = $(this).attr('id');
						if (divId != dataAttr) {

							$(this).removeClass("show");
						}


					});

				});

				$("#regioni").change(function() {

					$('#voice').empty();
					$('#content').empty();
					$('#sub_chapter_sel').empty();
					$('#chapter_sel').empty();

					var selected_region = $(this).val();
					$.ajax({
						url: "/wp-admin/admin-ajax.php",
						type: "GET",
						data: {
							action: "process_selected_region",
							selected_region: selected_region
						},
						success: function(response) {
							let data = JSON.parse(response);
							let html =
								'<div><select class="select_pur_iperpro chapter_selector" name="chapter_sel_" id="chapter" ><option class="option-style" disabled selected>Seleziona capitolo</option>';
							for (let i = 0; i < data.length; i++) {
								let name_chapter = data[i].name_chapter.charAt(0).toUpperCase() + data[i].name_chapter.slice(1).toLowerCase();
								if (data[i].region == selected_region) {
									html += "<option value='" + data[i].chapter + "'>" + data[i]
										.chapter + ". " + name_chapter + "</option>";
								}
							}
							html += '</select></div>';
							$("#chapter_sel").html(html);
						}
					});
				});

				$(document).on('change', '#chapter', function() {
					$('#voice').empty();
					$('#content').empty();
					$('#sub_chapter_sel').empty();

					var selected_chapter = $(this).val();
					var selected_region = $('#regioni').val();
					$("div#voice").empty();
					$("div.buttons_up_container").remove();


					$.ajax({
						url: "/wp-admin/admin-ajax.php",
						type: "GET",
						data: {
							action: "process_selected_chapter",
							selected_region: selected_region,
							selected_chapter: selected_chapter
						},
						success: function(response) {
							let data = JSON.parse(response);
							let html =
								'<div><select class="select_pur_iperpro subchapter_selector" name="sub_chapter_sel_" id="subchapter"><option disabled selected>Seleziona sottocapitolo</option>';
							for (let i = 0; i < data.length; i++) {
								let title = data[i].title.charAt(0).toUpperCase() + data[i].title.slice(1).toLowerCase();
								html += "<option value='" + data[i].subchapter + "'>" + data[i]
									.subchapter + '. ' + title + "</option>";
							}
							html += '</select></div>';
							$("#sub_chapter_sel").html(html);
						}
					});
				});




				$(document).on('change', '#subchapter', function() {

					$('#voice').empty();
					$('#content').empty();

					let selected_subchapter = $(this).val();
					let selected_chapter = $('#chapter').val();
					let selected_region = $('#regioni').val();
					let selected_paragraph;
					let data;


					$.ajax({
						url: "/wp-admin/admin-ajax.php",
						type: "GET",
						data: {
							action: "process_selected_content",
							selected_region: selected_region,
							selected_chapter: selected_chapter,
							selected_subchapter: selected_subchapter
						},
						success: function(response) {

							let coversion = JSON.stringify(response);
							data = JSON.parse(coversion);

							let html = '<div><select class="select_pur_iperpro paragraphs_selector" name="paragraphs_sel_" id="paragraphs"><option disabled selected>Seleziona paragrafo</option>';

							for (let i = 0; i < data.length; i++) {

								let content = data[i].paragraphs_content.replace(/(<([^>]+)>)/ig, '');

								html += '<option id="access_css" value="' + data[i].head_paragraphs + '">' + data[i].head_paragraphs + '. ' + content.slice(0,80) + '...</option>';
								
							

							}




							html += '</select></div></div>';
							$("#content").html(html);

						}

					});

				}); //chiusura #subchapter

				$(document).on( "change", "#paragraphs", function(){



					let selected_region = $('#regioni').val();
					let selected_chapter = $('#chapter').val();
					let selected_subchapter = $('#subchapter').val();
					let selected_paragraphs = $(this).val();

					console.log(selected_region, selected_chapter, selected_subchapter, selected_paragraphs);



					$.ajax({
						url: "/wp-admin/admin-ajax.php",
						type: "GET",
						data: {
							action: "process_selected_voice_2",
							selected_region: selected_region,
							selected_chapter: selected_chapter,
							selected_subchapter: selected_subchapter,
							selected_paragraphs: selected_paragraphs

						},
						success: function(response) {

							let conversion = JSON.stringify(response);
							let data2 = JSON.parse(conversion);

							let html2 = '<div class="row"><div class="col">';

							let count = 0;
							let prec;
							let prec_par;

							for (let i = 0; i < data2.length; i++) {

								if (data2[i].paragraphs_content != prec) {

									if (data2[i].paragraphs != prec_par) {

										html2 += '</div></div><br></div></div>';

									}

									html2 += '<div id="paragrafo' + data2[i].paragraphs +
										'" class=""><div class="div_"><div class="row"><div class="col"><br><h2 style="color: #1D3C6E;">' +
										data2[i].chapter + '.' + data2[i].subchapter +
										') Paragrafo n.' + data2[i].paragraphs +
										'</h2><p style="text-align:justify;">' + data2[i]
										.paragraphs_content + '</p></div></div><br>';
									html2 +=
										'<div><div data-bs-spy="scroll" id="style-1" data-bs-target="#navbar-example2" data-bs-offset="0"class="scrollspy-example" tabindex="0" style="min-height:100px; max-height:500px; overflow-y: scroll;  overflow-x: hidden; background-color:#dbe3ec;padding:15px;border:solid;border-radius:15px;border-width:0px;border-color:#636270;">';

									prec = data2[i].paragraphs_content;
									prec_par = data2[i].paragraphs;
								}

								let test = parseFloat(data2[i].price.replace(',','.',).replace('€',''),2) * parseFloat(data2[i].vat.replace(',','.',).replace('%',''),2)/100.00;

								html2 += '<div class="row"><div class="col-5"><p style="text-align: justify;"><strong>' + data2[i].voice_id + ')</strong> ' + data2[i].text_content + '</p></div><div class="col text-center align-self-end p-0 mb-2"><p>' + data2[i].unit + '</p></div><div class="col text-center align-self-end p-0 mb-2"><p><strong>€ ' + data2[i].price + '</strong></p></div><div class="col text-center align-self-end p-0 mb-2"><p>' + data2[i].vat + '    <strong>(€ '+test.toFixed(2)+')</strong></p></div><div class="col text-center align-self-end p-0"><p><button id="save-note-btn" data-value-voice-id="' + data2[i].id + '" class="btn-blue" >Salva su note</button></p></div></div>';	
							}

							html2 += '</div></div>'
							$("#voice").html(html2);

						} //chiusure response success
					}); //Chiusura seconda ajax */


				});



				$(document).on( 'click', '#save-note-btn', function () {

					let id_ai_voice = $(this).attr('data-value-voice-id');
					let id_user = <?php echo get_current_user_id(); ?>;

					$.ajax({
						url: '/wp-admin/admin-ajax.php',
						type: "POST",
						data: {
							action: "save_voice_data_user",
							id_ai_voice: id_ai_voice,
							id_user: id_user
						},
						success: function(response) {

							alert("Nota salvata con successo!");
						}
					});
				});

			});
		</script>



		<?php
}

add_shortcode('pur', 'pur_menu');

/**
 * custom search bar ajax pur v3
 */
function custom_search_bar() {
	
	$search_term = $_POST['search_term'];

	global $wpdb;
	$query = "SELECT * FROM hfu_pur_content WHERE paragraphs_content LIKE '%".$search_term."%'";
	$results = $wpdb->get_results($query);
	
		if ($results) {
			echo json_encode($results);
		
		} else {
			$output = 'Nessun risultato trovato.';
		}
	
		
	wp_die();

}
add_action("wp_ajax_custom_search", "custom_search_bar");




function search_bar_live_search(){
?>
<head>
	<style>
		.on_hover_results{
			background-color:#f5f5f5;
		}

		
	</style>
</head>
<body>

	<script>
		
		var ajaxTimeout;
		var clickTimeout;

		jQuery(document).ready(function($) {

			$(document).on("input", '#search_bar_iperpro', function() {
				
				let searchTerm = $(this).val();

				if (searchTerm.length >= 3) {

					clearTimeout(ajaxTimeout);
					ajaxTimeout = setTimeout(function() {
						$.ajax({
							url: '/wp-admin/admin-ajax.php',
							method: 'POST',
							data: {
								action: 'custom_search',
								search_term: searchTerm
							},
							success: function (response) {

								const paragraphs = JSON.parse(response)
								console.log(paragraphs);

								let responseHtml = '';

								if (paragraphs) {

									paragraphs.map(p => {
										
										const indice =  p.paragraphs_content.indexOf(searchTerm);
										
										let inizio = Math.max(0, indice - 35); // Estrae 40 caratteri prima della parola chiave
										let fine = Math.min(p.paragraphs_content.length, indice + 35); // Estrae 40 caratteri dopo la parola chiave
										
										/*if((inizio.length - fine.length) != 0 ){
											
											if(inizio.length < 35){
												let inizio_lenght = inizio.length;
												inizio = Math.max(0, indice - (35 - inizio_lenght));
											}
											if(fine.length < 35){
													let fine_lenght = fine.length;
													fine = Math.max(0, indice - (35 - fine_lenght))
												}
										}
										else
										{

										}*/
										
										const testoRidotto = p.paragraphs_content.slice(inizio, fine) + '...';										
										const highlighted = testoRidotto.replace(searchTerm, `<strong>${searchTerm}</strong>`);




										//const content = p.paragraphs_content.slice(0, 80) + '...'
										//console.log(p)
										responseHtml += '<div class="click_for_open"><p style="margin:10px; padding:10px;" data-id="'+p.id+'" data-region="'+p.region+'" data-chapter="'+p.chapter+'"  data-subchapter="'+p.subchapter+'" data-head_paragraphs="'+p.head_paragraphs+'"><strong>' + p.region+ ': ' + p.chapter + '.' + p.subchapter + '.' + p.head_paragraphs + ')</strong> ' + highlighted + '</p></div>'
									})




									//console.log(responseHtml);

								}

								$('#content_live_search').addClass('search_bar_iperpro_results');
								$('#content_live_search').removeClass('d-none');
								$('#content_live_search').html(responseHtml);



							},
							error: function (xhr, status, error) {
								alert('Errore nella richiesta');
							}
						});
					}, 1000);

				}

				else 
				{
					if($('#content_live_search').hasClass('d-none') == false)
					{
						$('#content_live_search').removeClass('search_bar_iperpro_results');
						$('#content_live_search').addClass('d-none');
						$('#content_live_search').html('');
					}
				}

			});
			
			
			$(document).on("click", function(event) {
				if (!$(event.target).closest('#content_live_search').length) {
					if($('#content_live_search').hasClass('d-none') == false)
					{
						$('#content_live_search').removeClass('search_bar_iperpro_results');
						$('#content_live_search').addClass('d-none');
						$('#content_live_search').html('');
					}
				}
			});


			//dove le idee cagano soldi
			$(document).on("click",".click_for_open p", function(event) {
				
				$('body').append('<div id="overlay"><div id="spinner"></div></div>');

				if($('#voice').length && $('#content').length &&  $('#sub_chapter_sel').length &&  $('#chapter_sel').length){
					$('#voice').empty();
					$('#content').empty();
					$('#sub_chapter_sel').empty();
					$('#chapter_sel').empty();
				}


				
				if($('#content_live_search').hasClass('d-none') == false)
				{
					$('#content_live_search').removeClass('search_bar_iperpro_results');
					$('#content_live_search').addClass('d-none');
					$('#content_live_search').html('');
				}
				
				
				let id = $(this).attr("data-id");
				let region = $(this).attr("data-region");
				let chapter = $(this).attr("data-chapter");
				let subchapter = $(this).attr("data-subchapter");
				let head_paragraphs = $(this).attr("data-head_paragraphs");
				console.log(id,region, chapter, subchapter, head_paragraphs);
				
				clearTimeout(clickTimeout);
				clickTimeout = setTimeout(function() {
					
					$("#regioni").val(region).change();

					var checkChapter = setInterval(function() {
						if ($(".chapter_selector").length) {
							clearInterval(checkChapter); // rimuovi l'intervallo
							// esegui altre azioni in base all'elemento generato
							console.log("aspetto che si generi chapter");
							//$(".chapter_selector").val(chapter).click();
							$(".chapter_selector").val(chapter).change();
						}
					}, 100); // controlla ogni 100 millisecondi

					var checkChapter2 = setInterval(function() {
						if ($(".subchapter_selector").length) {
							clearInterval(checkChapter2); // rimuovi l'intervallo
							// esegui altre azioni in base all'elemento generato
							console.log("aspetto che si generi subchapter");
							//$(".subchapter_selector").val(subchapter).click();
							$(".subchapter_selector").val(subchapter).change();
						}
					}, 100); // controlla ogni 100 millisecondi
					
					

					var checkChapter3 = setInterval(function() {
						if ($(".paragraphs_selector").length) {
							clearInterval(checkChapter3); // rimuovi l'intervallo
							console.log("aspetto che si generino i paragrafi");
							$(".paragraphs_selector").val(head_paragraphs).change();
						}
					}, 100);

				},100); // controlla ogni 100 millisecondi
			});
		
			

			// Seleziona l'elemento da osservare
			const target = $('#voice')[0];
			// Crea un'istanza di MutationObserver e passa una funzione di callback
			const observer = new MutationObserver(function(mutations) {
				mutations.forEach(function(mutation) {
					mutation.addedNodes.forEach(function(node) {
						//onsole.log('Nuovo elemento aggiunto al div con id "myDiv":', node);
						
						/*$('html, body').animate({
							scrollTop: $('#voice').offset().top
						}, 1000);
						*/
						$("#overlay").remove();

					});
				});    
			});

			// Opzioni per l'observer
			const config = { childList: true };

			// Inizia l'osservazione del target con le opzioni specificate
			observer.observe(target, config);

			





			
			$(document).on( "mouseenter", ".click_for_open", function() {
				/*let id = $(this).attr("data-id");
				let region = $(this).attr("data-region");
				let chapter = $(this).attr("data-chapter");
				console.log(id,region, chapter);*/
				//console.log("sopra risultato");
				$(this).addClass('on_hover_results');	
			});
			
			$(document).on( "mouseleave", ".click_for_open", function() {
				//console.log("fuori risultato");
				$(this).removeClass('on_hover_results');	
			});

		});

	</script>
</body>
<?php
	
	
	
}
add_shortcode('live_search_bar_paragraphs_content', 'search_bar_live_search');

/**
 * Shortcode Pagina Catalogo
 */
function get_products_custom_vendor_label(){

	$html = '';
	
	$html .= '<style>

		
		.products_grid_custom_iperproject{
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
			grid-gap: 20px;
			justify-content: center;
			align-items: center;
		}
		
		.product_custom_iperproject{

		}
		
		.product_image{
			max-width: 330px;
			max-height: 300px;
			min-width: 230px;
			min-height: 200px;
			display: flex;
			justify-content:center;
			align-items: center;
			border: 1px solid #E2E8F6;
			border-top-left-radius: 22px;
			border-top-right-radius: 22px;
			position: relative;
		}
		
			.stamps_wrapper {
			position: absolute;
			left: 0;
			bottom: 0;
			right: 0;
			display: flex;
		}

		.stamp_item {
			width: 60px;
		}
		
		.product_info{
			max-width: 330px;
			max-height: 205px;
			min-width: 230px;
			min-height: 105px;
			background: #DBE3EB;
			display: flex;
			flex-direction: column;
			justify-content: center;
			text-align: left;
			border-bottom-left-radius: 22px;
			border-bottom-right-radius: 22px;
			padding:10px;
			padding-left: 15px;
    		padding-right: 15px;
		}
	
		.pagination {
			display:flex;
			justify-content: flex-end;
			margin-top:20px;
			float:right;
		}

		.pagination-item {
			background-color: #dbe3eb;
			color: #1D3C6E;
			border: solid;
			border-radius: 5px;
			border-color: white;
			width: 40px;
			height: 40px;
			border-radius: 50%;
			font-size: 20px;
		}

		.pagination-item:hover {
			background-color: #1D3C6E;
			color: #ffffff;
		}

		.page-numbers.current .pagination-item {
		  background-color: #1D3C6E;
		  color: #ffffff;
		}
		
		.page-numbers.dots{
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			//display:none;
		}
		
		.icon_in_button{		
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
		}
		
		 .star-rating:before {
		
			color:#22222252;
		}
		
		.star-rating, #review_form .stars {
    --et_yellow-color: #FFE175;
		}

	</style>';
	
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //ottieni la pagina corrente
	
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => 8,
		'paged' => $paged 
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		$html .= '<div class="products_grid_custom_iperproject">';

		while ( $query->have_posts() ) {

			$query->the_post();

			$html .='<div class="product_custom_iperproject">';


			$img = get_the_post_thumbnail_url(get_the_ID(), 'medium' ) ? get_the_post_thumbnail_url(get_the_ID(), 'medium' ) : wc_placeholder_img_src( 'medium');
			//$img = get_the_post_thumbnail_url(get_the_ID(), 'medium' );
			
			$html .= '<div class="product_image"><a href="'. get_permalink().'"><img src="'.$img.'" style="object-fit: cover; border-radius: 22px 22px 0px 0px;"></a>';
			
			
			$html .= '<div class="stamps_wrapper">';
			// logica bollini 
			if(get_field('pur_approved') == true) {
				$html .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_pur-approved.svg"/>';
			}

			if(get_field('ecosostenibile') == true) {
				$html .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_eco.svg"/>';
			}

			if(get_field('innovativo') == true) {
				$html .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_innovativo.svg"/>';
			}

			$html .= '</div>
			
			
			
			
			
			
			
			</div>';

			$html .= '<div class="product_info">';

			// Recupera il valore del campo personalizzato "nome_campo"
			$id_user = get_field('vendor_user');
			$meta_values = get_user_meta($id_user);

			//$html .= '<div class="excerpt_product" style=""><p>'.substr(get_post_field('post_excerpt', get_the_ID()), 0, 80).'</p></div>';

			// Mostra il valore del campo personalizzato
			if ($meta_values) 
			{
				$company_name = $meta_values['billing_company'][0];

				$html .= '<div class="vendor_product" style="height:25px;"><p style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"><a href="https://iperprogetto.it/vetrina/?venditore='.$meta_values['nickname'][0].'" >'.$company_name.'</a></p></div>';
			}

			else
			{
				$html .= '<div class="vendor_product" style="height:25px;"><p>N.D.</p></div>';
			}

			$html .= '<div class="title_product" style="height:40px;"><a href="'.get_permalink().'"><h2 style="font-size: 14px;color: #303030">'.substr(get_the_title(),0,55).'</h2></a></div>';



			// Output the product reviews
			$comments = get_comments( array(
				'post_id' => get_the_ID(),
				'status' => 'approve'
			));

			//var_dump($comments);

			$html .= '<div class="review_product" style="height:20px;">';
			$rating = 0;
			$creviews_number = 0;
			if($comments){
				foreach ( $comments as $comment ) {
					// Imposta il rating
					$rating = $rating + intval(get_comment_meta( $comment->comment_ID, 'rating', true ));
					$creviews_number = get_comments_number();
				}
				// Calcola la larghezza delle stelle in base al rating
				$width = ($rating / $creviews_number) * 20;

				// Genera le stelle
				$html .= '<div class="star-rating"><span style="width: '. $width .'%;">&#9733;&#9733;&#9733;&#9733;&#9733;</span></div><span> <a href="'. get_permalink().'#reviews" style="padding-left: 5px;"> <u>'.$creviews_number.' voti</u></a></span>';
				$html .= '';				
			}

			else
			{
				// Genera le stelle
				$html .= '<div class="star-rating" role="img"><span style="width: 0%;">&#9733;&#9733;&#9733;&#9733;&#9733;</span></div><span> <a href="'. get_permalink().'#reviews" style="padding-left: 5px;"> <u>0 voti</u></a></span>';
				$html .= '';
			}
			
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}

	
		
		$html .= '</div>';
		
		
	
	
	$args = array(
		'total' => $query->max_num_pages,
		'current' => $paged,
		'prev_next' => true,  		
		'prev_text' => '<button class="pagination-item icon_in_button"><span class="dashicons dashicons-arrow-left-alt2"></span></button>',
		'next_text' => '<button class="pagination-item icon_in_button"><span class="dashicons dashicons-arrow-right-alt2"></span></button>',
		'mid_size'  => 1,
		'end_size' => 1,
		'before_page_number' => '<button class="pagination-item">',
		'after_page_number' => '</button>',
		'before_current' => '<button class="pagination-item current">',
		'after_current' => '</button>',
	);


	
	$pagination = paginate_links($args);
	
	$html .= '<div class="pagination">';
	$html .=  $pagination;
	$html .= '</div>';

		
	} 

	else 
	{
		// No posts found
	}
	
	

	wp_reset_postdata();
	

	return $html;

	
}

add_shortcode('products_custom_vendor_label', 'get_products_custom_vendor_label');


function reviews_user(){
?>
<head>
	<style>
		.et-reviews-images{
			
			display:none;
		}
	</style>
</head>

<?php
	
    comments_template();

}
add_shortcode('reviews', 'reviews_user');

/**
 * placeholder woocommerce
 */
add_filter('woocommerce_placeholder_img_src', 'custom_woocommerce_placeholder_img_src');

function custom_woocommerce_placeholder_img_src( $src ) {
	
	$src = 'https://iperprogetto.it/wp-content/plugins/woocommerce/assets/images/placeholder.png';
    return $src;

}

/**
 * Pagina Letteratura tecnica 
 */
function custom_category_filter() {
	$args = array(
		'show_option_all' => '',
		'orderby' => 'name',
		'hierarchical' => true,
		'depth' => 0,
		'hide_empty' => 1,
		'taxonomy' => 'category',
		'walker' => new Walker_Category_Custom,
		'child_of' => 98,
		'title_li' => '',
	);
	ob_start();
	wp_list_categories( $args );
	$categories = ob_get_clean();
	return $categories;
}

class Walker_Category_Custom extends Walker_Category {

	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		$pad = str_repeat('&nbsp;', $depth );
		$cat_name = strlen($category->name) > 20 ? substr($category->name, 0, 20).'...' : $category->name;
		$output .= '<a href="'.get_permalink().'/?categoria='. $category->term_id.'" class="category-posts-filter-link tag-' . $category->slug . '" data-cat-id="'. $category->term_id.'" style="display: block;">' .  $cat_name . '</a>';
		return $output;
	}
}
function filtro_categoria($categoria_id = 98) {
	$output = '';
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	// Verifica se il parametro categoria è presente nell'URL
	if ( isset( $_GET['categoria'] ) ) {

		// Ottieni l'ID della categoria dalla URL
		$categoria_id =  $_GET['categoria'];
	}
	// Verifica se l'ID della categoria è valido
	if ( $categoria_id ) {

		// Costruisci gli argomenti della query per ottenere i post della categoria
		$args = array(
			'cat' => $categoria_id,
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => 4,
			'paged' => $paged
		);

		// Esegui la query
		$query = new WP_Query( $args );

		// Verifica se ci sono post nella categoria
		if ( $query->have_posts() ) {

			// Mostra i post
			while ( $query->have_posts() ) {
				$query->the_post();

				$excerpt = get_the_excerpt();
				$trimmed_excerpt = wp_trim_words( $excerpt, 30, '...' );

				$output .= '<a class="category-post-item" href="' . get_permalink() . '" >
                                    <div class="category-post-text-wrapper">
                                        <div class="category-post-title">' . get_the_title() .'</div>
                                        <div class="category-post-excerpt">'.$trimmed_excerpt.'</div>
                                        <div class="category-post-read-more">Leggi tutto →</div>
                                    </div>
                                </a>';
			}

			// Add pagination
			$output .= '<div class="pagination-wrapper"><div class="pagination">' . paginate_links( array(
				'total' => $query->max_num_pages,
				'current' => $paged,
				'prev_next' => true,  		
				//'prev_text' => '<button class="pagination-item icon_in_button"><span class="dashicons dashicons-arrow-left-alt2"></span></button>',
				// 				'prev_text' => '<button class="page_button"><a class="page-link-number"><</a></button>',
				// 				'next_text' => '<button class="page_button"><a class="page-link-number">></a></button>',
				// 				'mid_size'  => 1,
				// 				'end_size' => 1,
				// 				'before_page_number' => '<button class="page_button"><a class="page-link-number">',
				// 				'after_page_number' => '</a></button>',
				// 				'before_current' => '<button class="page_button active"><a class="page-link-number">',
				// 				'after_current' => '</a></button>',
				'prev_text' => '<',
				'next_text' => '>',
				'mid_size'  => 1,
				'end_size' => 1,


			)) . '</div></div>';

		} else {
			$output .= 'Nessun post trovato nella categoria ' . get_cat_name($_GET['categoria']);
		}

		// Ripristina le impostazioni di query originali di WordPress
		wp_reset_postdata();
	} else {
		$output .= 'Categoria non valida';
	}

	return $output;
}



function display_posts_by_category_letteratura( $atts ) {
	$output = '';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	  integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<style>

	.space-on-cat {
		margin-left: 20px;
	}

	.category-posts-wrapper {
		display: flex;
		flex-direction: row-reverse;
		justify-content: space-between;
		gap: 45px;
	}

	.category-posts-wrapper .category-posts-list {
		width: 70%;
		display: flex;
		flex-direction: column;
		gap: 20px;
	}

	.category-posts-wrapper .category-posts-filter {
		width: 30%;
		background: #DBE3EB;
		display: flex;
		flex-direction: column;
		gap: 10px;
		padding: 25px;
		border-radius: 15px;
		margin-bottom: auto;
	}

	.category-posts-wrapper  .category-posts-filter .category-posts-filter-label {
		color: #1D3C6E;
	}


	.category-posts-wrapper  .category-posts-filter .category-posts-filter-button  {
		background: #1D3C6E;
		text-align: left;
		padding: 9px 13px;
		border-radius: 15px;
		color: white;
		border: none;
	}

	.category-posts-wrapper .category-post-item {
		display: flex;
		/* gap: 40px; */
		background: #F5F5F5;
		border-radius: 15px;
		overflow: hidden;
		/* flex-direction: row-reverse; */
		/* padding-left: 43px; */
		justify-content: space-between;
		padding: 20px 25px;
	}

	.category-posts-wrapper .category-post-item .category-post-item-img {
		min-width: 200px;
		height: 150px;
		background-position: center !important;
		background-size: cover !important;
		background-repeat: no-repeat !important;
	}

	.category-posts-wrapper .category-post-item .category-post-text-wrapper {
		display: flex;
		flex-direction: column;
		justify-content: space-around;
		gap: 5px;
		width: 100%;
	}

	.category-posts-wrapper .category-post-item .category-post-text-wrapper .category-post-title {
		font-size: 18px;
		font-weight: bold;
		color: #1D3C6E;
		margin-bottom: 20px;
	}


	.category-posts-wrapper .category-post-item .category-post-text-wrapper .category-post-tag {
		background: #3f5983;
		padding: 5px;
		border-radius: 5px;
		margin: 2px;
	}

	.category-posts-wrapper .category-post-item .category-post-excerpt {
		color: #303030;
	}

	.category-posts-wrapper .category-post-item .category-post-read-more {
		text-align: right;
		color: #1e3c6d;
	}


	.category-posts-wrapper  .category-posts-filter .category-posts-filter-list {
		display: flex;
		flex-direction: column;
	}

	.category-posts-wrapper  .category-posts-filter .category-posts-filter-list .category-posts-filter-link {
		color: #303030;
	}

	#chevron-iperpro-links {
		padding-top: 3px;
	}
	.active-strong {
		font-weight: bold;
	}

	/*Paginazione */

	.pagination-wrapper {
		display:flex;
		justify-content: flex-end;
	}

	.page-numbers {
		background-color: #f5f5f5;
		border: solid;
		border-color: white;
		width: 40px;
		height: 40px;
		border-radius: 50%;
		color: #1D3C6E;
		display: flex;
		font-size: 20px;
		justify-content: center;
		align-items: center;
	}

	.page-numbers:hover, .page-numbers.current {
		background-color: #1D3C6E;
		color: #ffffff;
		
	}


	/*Commutatore */
	.btn-drop-iperpro-links {
		min-width: 100%;
		max-width: 190px;
		border-width: 0px;
		background-color: #1D3C6E;
		padding: 9px 15px 8px 15px;
		color: white;
		font-size: 15px;
		border-radius: 10px;
	}

	#collapse-download-iperpro-links {
		padding: 10px 0px 10px 25px;
	}

	.btn-drop-iperpro-links:focus, .btn-drop-iperpro-links:hover {
		color: white;
	}

</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
		crossorigin="anonymous"></script>
<script>
	jQuery(document).ready(function($) {


		$('.category-posts-filter').ready(function() {
			const catLinks = $('.category-posts-filter-link');
			const urlParams = new URLSearchParams(window.location.search);

			const category = urlParams.get('categoria');
			$.each(catLinks, function(idx,elm) {
				if(elm.dataset.catId == category) {
					$(elm).addClass('active-strong');
				} else if(elm.dataset.catId !== category && category  !== null) {
					
					$(elm).removeClass('active-strong');
				}
			})
		})

		$(document).ready(function() {
			$('.page-numbers.current').replaceWith(function() {
				return $('<a>', {
					html: $(this).html(),
					class: $(this).attr('class')
				});
			});
		});


		// commutatore

		const dropCollapsibleIperproLinks = $('#collapse-download-iperpro-links');

		const chevronIperproLinks = $('#chevron-iperpro-links');

		dropCollapsibleIperproLinks.on('hidden.bs.collapse', event => {
			chevronIperproLinks.removeClass('et-up-arrow');
			chevronIperproLinks.addClass('et-down-arrow');
		});

		dropCollapsibleIperproLinks.on('shown.bs.collapse', event => {
			chevronIperproLinks.removeClass('et-down-arrow');
			chevronIperproLinks.addClass('et-up-arrow');
		});
	});

</script>
<?php
	$output .= '<div class="category-posts-wrapper"><div class="category-posts-list">';

	$output .= filtro_categoria( 98 ,$output);
	$output .= '</div>';

	$output .= '<div class="category-posts-filter">

    <a class="category-posts-filter-button d-flex justify-content-between" data-bs-toggle="collapse"
        href="#collapse-download-iperpro-links" role="button" aria-expanded="false"
        aria-controls="collapse-iperpro-links">
        <span>Categorie</span>
        <i id="chevron-iperpro-links" class="et-down-arrow et-icon"></i>
    </a>

    <div class="collapse show" id="collapse-download-iperpro-links">
        <div class="category-posts-filter-list">
            <a class="category-posts-filter-link tag-all active-strong" style="display: block;" data-cat-id="98"
                href="?categoria=98">Tutte le categorie</a>';

	$output .= '<div>'. custom_category_filter() .'</div>'; //'<a class="category-posts-filter-link tag-' . $tag->slug . '">' . $tag->name . '</a>';

	$output .= '    </div> 
                  </div> 
                </div>';

	$output .= '</div>';
	wp_reset_postdata();
	return $output;
}

add_shortcode( 'category_posts_letteratura', 'display_posts_by_category_letteratura' );

/**
 * Carosello prodotti
 */
function my_product_carousel_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'limit' => 12,
		'category' => 'prodotti-nanotecnologici',
		'orderby' => 'date',
		'order' => 'DESC'

	), $atts, 'product-carousel' );

	// Query WooCommerce products
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => $atts['limit'],
		'orderby' => $atts['orderby'],
		'order' => $atts['order']
	);

	if ( ! empty( $atts['category'] ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'slug',
				'terms' => $atts['category']
			)
		);
	}

	$query = new WP_Query( $args );
	$output = '';

	// Build the product carousel HTML
	ob_start(); ?>

<style>

	.products_grid_custom_iperproject{
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
		grid-gap: 20px;
		justify-content: center;
		align-items: center;
	}

	.product_custom_iperproject{
		/* 		margin: 0 10px; */
		width: 235px !important;
	}



	.slick-prev:before, .slick-next:before {
		content: none !important;
	}

	.slick-prev, .slick-next {
		color: #1e3c6d !important;
		font-size: 16px !important;
		width: 40px !important;
		height: 40px !important;
		background: #d9d9d9 !important;
		border-radius: 50% !important;
	}

	.slick-prev:hover, .slick-next:hover {
		color: #ffffff !important;
		background: #1e3c6d !important;
	}

	.slick-next {
		right: -45px !important;
	}

	.slick-prev {
		left: -45px !important;
	}

	.product-item {
		display: flex !important;
		justify-content: center !important;
	}

	.product_image{
		/* 		max-width: 330px; */
		max-height: 300px;
		/* 	    min-width: 230px; */
		width: 100%; 
		min-height: 236px;
		display: flex;
		justify-content:center;
		align-items: center;
		border: 1px solid #E2E8F6;
		border-top-left-radius: 22px;
		border-top-right-radius: 22px;
		position: relative;
	}


	.stamps_wrapper {
		position: absolute;
		left: 0;
		bottom: 0;
		right: 0;
		display: flex;
	}

	.stamp_item {
		width: 60px;
	}

	.product_info{
		/* 		max-width: 330px;  */
		max-height: 205px;
		/* 		min-width: 230px;  */
		width: 100%; 
		min-height: 105px;
		background: #DBE3EB;
		display: flex;
		flex-direction: column;
		justify-content: center;
		text-align: left;
		border-bottom-left-radius: 22px;
		border-bottom-right-radius: 22px;
		padding:10px;
		padding-left: 15px;
		padding-right: 15px;
	}



	.icon_in_button{		
		display: flex;
		align-items: center;
		justify-content: center;
		text-align: center;
	}

	.star-rating:before {

		color:#22222252;
	}

	.star-rating, #review_form .stars {
		--et_yellow-color: #FFE175;
	}

</style>


<?php

	$output .= '<div class="product-carousel">';

	if ( $query->have_posts() ) {

		while ( $query->have_posts() ) {

			$query->the_post();

			$output .='<div class="product-item"><div class="product_custom_iperproject">';

			$img = get_the_post_thumbnail_url(get_the_ID(), 'medium' ) ? get_the_post_thumbnail_url(get_the_ID(), 'medium' ) : wc_placeholder_img_src( 'medium');
			//$img = get_the_post_thumbnail_url(get_the_ID(), 'medium' );

			$output .= '<div class="product_image"><a href="'. get_permalink().'"><img src="'.$img.'" style="object-fit: cover; border-radius: 22px 22px 0px 0px;"></a>';

			$output .= '<div class="stamps_wrapper">';
			// logica bollini 
			if(get_field('pur_approved') == true) {
				$output .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_pur-approved.svg"/>';
			}

			if(get_field('ecosostenibile') == true) {
				$output .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_eco.svg"/>';
			}

			if(get_field('innovativo') == true) {
				$output .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_innovativo.svg"/>';
			}

			$output .= '</div>';

			$output .= '</div>';

			$output .= '<div class="product_info">';

			// Recupera il valore del campo personalizzato "nome_campo"
			$id_user = get_field('vendor_user');
			$meta_values = get_user_meta($id_user);

			//$output .= '<div class="excerpt_product" style=""><p>'.substr(get_post_field('post_excerpt', get_the_ID()), 0, 80).'</p></div>';

			// Mostra il valore del campo personalizzato
			if ($meta_values) 
			{
				$company_name = $meta_values['billing_company'][0];

				$output .= '<div class="vendor_product" style="height:25px;"><p style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"><a href="https://iperprogetto.it/vetrina/?venditore='.$meta_values['nickname'][0].'" >'.$company_name.'</a></p></div>';
			}

			else
			{
				$output .= '<div class="vendor_product" style="height:25px;"><p>N.D.</p></div>';
			}

			$output .= '<div class="title_product" style="height:40px;"><a href="'.get_permalink().'"><h2 style="font-size: 14px;color: #303030">'.substr(get_the_title(),0,55).'</h2></a></div>';



			// Output the product reviews
			$comments = get_comments( array(
				'post_id' => get_the_ID(),
				'status' => 'approve'
			));

			$output .= '<div class="review_product" style="height:20px;">';
			$rating = 0;
			$creviews_number = 0;
			if($comments){
				foreach ( $comments as $comment ) {
					// Imposta il rating
					$rating = $rating + intval(get_comment_meta( $comment->comment_ID, 'rating', true ));
					$creviews_number = get_comments_number();
				}
				// Calcola la larghezza delle stelle in base al rating
				$width = ($rating / $creviews_number) * 20;

				// Genera le stelle
				$output .= '<div class="star-rating"><span style="width: '. $width .'%;">&#9733;&#9733;&#9733;&#9733;&#9733;</span></div><span> <a href="'. get_permalink().'#reviews" style="padding-left: 5px;"> <u>'.$creviews_number.' voti</u></a></span>';
				$output .= '';				
			}

			else
			{
				// Genera le stelle
				$output .= '<div class="star-rating" role="img"><span style="width: 0%;">&#9733;&#9733;&#9733;&#9733;&#9733;</span></div><span> <a href="'. get_permalink().'#reviews" style="padding-left: 5px;"> <u>0 voti</u></a></span>';
				$output .= '';
			}

			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
		}


	} 

	$output .= '</div>';



	wp_reset_postdata();
	$output .= ob_get_clean();

	// Enqueue Slick and Font Awesome CSS and JS
	wp_enqueue_style( 'slick-css', 'https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css' );
	wp_enqueue_style( 'slick-theme-css', 'https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick-theme.css' );
	wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css' );
	wp_enqueue_script( 'slick-js', 'https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js', array( 'jquery' ), '1.6.0', true );

	// Enqueue custom JS for Slick
	wp_add_inline_script( 'slick-js', '
        jQuery(".product-carousel").slick({
            slidesToShow: 4,
            slidesToScroll: 4,
            arrows: true,
            prevArrow: \'<button type="button" style="color: red; font-size: 16px;" class="slick-prev" >←</button>\',
            nextArrow: \'<button type="button" style="color: red; font-size: 16px;" class="slick-next">→</button>\',
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        infinite: true,
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    ' );

	return $output;
}
add_shortcode( 'product-carousel', 'my_product_carousel_shortcode' );

/**
 * product_shortcode_homepage
 */
function get_products_custom_vendor_label_homepage(){

	$html = '';
	
	$html .= '<style>

		
		.products_grid_custom_iperproject{
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
			grid-gap: 20px;
			justify-content: center;
			align-items: center;
		}
		
		.product_custom_iperproject{

		}
		
		.product_image{
			max-width: 330px;
			max-height: 300px;
			min-width: 230px;
			min-height: 200px;
			display: flex;
			justify-content:center;
			align-items: center;
			border: 1px solid #E2E8F6;
			border-top-left-radius: 22px;
			border-top-right-radius: 22px;
			position: relative;
		}
		
		/*.product_image_img {
			object-fit: cover;
			border-radius: 22px 22px 0px 0px;
		
		}*/
		
		.product_info{
			max-width: 330px;
			max-height: 205px;
			min-width: 230px;
			min-height: 105px;
			background: #DBE3EB;
			display: flex;
			flex-direction: column;
			justify-content: center;
			text-align: left;
			border-bottom-left-radius: 22px;
			border-bottom-right-radius: 22px;
			padding:10px;
			padding-left: 15px;
    		padding-right: 15px;
		}
	
		.pagination {
			display:flex;
			justify-content: flex-end;
			margin-top:20px;
			float:right;
		}

		.pagination-item {
			background-color: #1D3C6E;
			color: #dbe3eb;
			border: solid;
			border-radius: 24px;
			border-color: white;
			font-size: 20px;
			padding: 10px;
		}

		.pagination-item:hover {
			background-color: #dbe3eb;
			color: #1D3C6E;
		}

		.page-numbers.current .pagination-item {
		  background-color: #dbe3eb;
		  color: #1D3C6E;
		}
		
		.page-numbers.dots{
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			//display:none;
		}
		
		.icon_in_button{		
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
		}
		
		 .star-rating:before {
		
			color:#22222252;
		}
		
		.star-rating, #review_form .stars {
    --et_yellow-color: #FFE175;
		}
		
		
	.stamps_wrapper {
		position: absolute;
		left: 0;
		bottom: 0;
		right: 0;
		display: flex;
	}

	.stamp_item {
		width: 60px;
	}

	</style>';
	
	
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => 8,
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		$html .= '<div class="products_grid_custom_iperproject">';

		while ( $query->have_posts() ) {

			$query->the_post();

			$html .='<div class="product_custom_iperproject">';


			$img = get_the_post_thumbnail_url(get_the_ID(), 'medium' ) ? get_the_post_thumbnail_url(get_the_ID(), 'medium' ) : wc_placeholder_img_src( 'medium');
			//$img = get_the_post_thumbnail_url(get_the_ID(), 'medium' );
			
			$html .= '<div class="product_image"><a href="'. get_permalink().'"><img src="'.$img.'" style="object-fit: cover; border-radius: 22px 22px 0px 0px;"></a>';
			
			$html .= '<div class="stamps_wrapper">';
			// logica bollini 
			if(get_field('pur_approved') == true) {
				$html .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_pur-approved.svg"/>';
			}

			if(get_field('ecosostenibile') == true) {
				$html .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_eco.svg"/>';
			}

			if(get_field('innovativo') == true) {
				$html .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_innovativo.svg"/>';
			}

			$html .= '</div>
			
			</div>';

			$html .= '<div class="product_info">';

			// Recupera il valore del campo personalizzato "nome_campo"
			$id_user = get_field('vendor_user');
			$meta_values = get_user_meta($id_user);

			//$html .= '<div class="excerpt_product" style=""><p>'.substr(get_post_field('post_excerpt', get_the_ID()), 0, 80).'</p></div>';

			// Mostra il valore del campo personalizzato
			if ($meta_values) 
			{
				$company_name = $meta_values['billing_company'][0];

				$html .= '<div class="vendor_product" style="height:25px;"><p style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"><a href="https://iperprogetto.it/vetrina/?venditore='.$meta_values['nickname'][0].'" >'.$company_name.'</a></p></div>';
			}

			else
			{
				$html .= '<div class="vendor_product" style="height:25px;"><p>N.D.</p></div>';
			}

			$html .= '<div class="title_product" style="height:40px;"><a href="'.get_permalink().'"><h2 style="font-size: 14px;color: #303030">'.substr(get_the_title(),0,55).'</h2></a></div>';



			// Output the product reviews
			$comments = get_comments( array(
				'post_id' => get_the_ID(),
				'status' => 'approve'
			));

			//var_dump($comments);

			$html .= '<div class="review_product" style="height:20px;">';
			$rating = 0;
			$creviews_number = 0;
			if($comments){
				foreach ( $comments as $comment ) {
					// Imposta il rating
					$rating = $rating + intval(get_comment_meta( $comment->comment_ID, 'rating', true ));
					$creviews_number = get_comments_number();
				}
				// Calcola la larghezza delle stelle in base al rating
				$width = ($rating / $creviews_number) * 20;

				// Genera le stelle
				$html .= '<div class="star-rating"><span style="width: '. $width .'%;">&#9733;&#9733;&#9733;&#9733;&#9733;</span></div><span> <a href="'. get_permalink().'#reviews" style="padding-left: 5px;"> <u>'.$creviews_number.' voti</u></a></span>';
				$html .= '';				
			}

			else
			{
				// Genera le stelle
				$html .= '<div class="star-rating" role="img"><span style="width: 0%;">&#9733;&#9733;&#9733;&#9733;&#9733;</span></div><span> <a href="'. get_permalink().'#reviews" style="padding-left: 5px;"> <u>0 voti</u></a></span>';
				$html .= '';
			}
			
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}

	
		
		$html .= '</div>';
		
		
	
	


	
	//$pagination = paginate_links($args);
	
	$html .= '<div class="pagination">';
	$html .= '<a href="https://iperprogetto.it/catalogo/"><button class="pagination-item icon_in_button">Vedi altro <span class="dashicons dashicons-arrow-right-alt2"></span></button></a>';
	$html .= '</div>';

		
	} 

	else 
	{
		// No posts found
	}
	
	

	wp_reset_postdata();
	

	return $html;

	
}

add_shortcode('products_custom_vendor_label_homepage', 'get_products_custom_vendor_label_homepage');

/**
 * Bollini in pagina prodotto
 */
function bollini_product_page( $atts ) {
     global $product;
    
    
    $ecosostenibile_value = get_field('ecosostenibile');
    $pur_approved_value = get_field('pur_approved');
    $innovativo_value = get_field('innovativo');
	
	$output = '<style>
	
	
		.stamps_wrapper {
		display: flex;
	}

	.stamp_item {
		width: 80px;
	}
	
	</style>';
	
	$output .= '<div class="stamps_wrapper">';
			// logica bollini 
			if(get_field('pur_approved') == true) {
				$output .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_pur-approved.svg"/>';
			}

			if(get_field('ecosostenibile') == true) {
				$output .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_eco.svg"/>';
			}

			if(get_field('innovativo') == true) {
				$output .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_innovativo.svg"/>';
			}

			$output .= '</div>';
	
     return $output;
}
add_shortcode( 'bollini_product_page_shortcode', 'bollini_product_page' );
