<?php

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