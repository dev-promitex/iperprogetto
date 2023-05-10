<?php

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

