<?php

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

