<?php


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