<?php

function verify_query_string()
{

	/*Ottieni la URL corrente*/
	$currentUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	/*Dividi la query string in base al carattere ?*/
	$url_split =  explode('?', $currentUrl);

	/*Prendi solo la prima parte della URL (senza la query string)*/
	//$newUrl = $url_split[0];

	//dividi i parametri della query string
	if (!empty($url_split[1])) {
		$parms_split = explode('&', $url_split[1]);
		if (strpos($parms_split[0], "venditore=") === false) {
			return 0;
		}
	} else {
		return 0;
	}

	return 1;
}

function vendor_info()
{

	if (verify_query_string() != 1) {
		return;
	}

	// Nome utente del venditore di cui vogliamo trovare i prodotti
	$vendor = $_GET['venditore'];

	// Recupera l'ID dell'utente corrispondente al nome utente del venditore
	$venditore = get_user_by('login', $vendor);
	$venditore_id = $venditore->ID;
	if (!$venditore_id) {
		return;
	}

?>


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



		.btn-save-vendor {
			float: right;
			margin-top: 15px;
			margin-right: 5px;
		}

		.vendor-page-button {
			margin-left: 5px;
			margin-right: 5px;
			background-color: green;
			transition: background-color 0.3s ease;
			/* animazione di transizione */
		}

		.vendor-page-button:hover {
			margin-left: 5px;
			margin-right: 5px;
			background-color: red;
		}

		.vendor-page-button:active {
			margin-left: 5px;
			margin-right: 5px;
			background-color: #dbe3eb;
			color: white;
		}

		.vendor_title_iperpro_wrapper {
			max-width: 1150px;
			margin: auto;
		}
	</style>


	<?php
	$immagine_id = get_field('immagine_copertina_vetrina', 'user_' . $venditore_id);
	?>


	<?php echo '<div class="head_img" style="background-image: url(' . $immagine_id . ')"></div>'; ?>

	<div class="vendor_title_iperpro_wrapper">
		<div class="vendor_title_iperpro_container">
			<?php

			$vendor_data = get_user_meta($venditore_id);

			echo '
				<div class="vendor_title_name_iperpro">
					<img class="vendor_logo_iperpro" src="' . get_field('logo_venditore', 'user_' . $venditore_id) .  '" width="150px">
					<span class="vendor_name_iperpro">' . get_user_meta($venditore_id, 'billing_company', true) . '</span>
				</div>
					<div class="vendor_title_info_iperpro_container">
						<div class="vendor_title_info_iperpro_descr">
							<p>' . $vendor_data['description'][0] . '</p>
						</div>';

			$args = array(
				'post_type' => 'product', // Sostituisci 'product' con il tipo di post del tuo prodotto
				'posts_per_page' => -1,
				'meta_query' => array(
					array(
						'key'     => 'vendor_user', // Sostituisci 'vendor_user' con la chiave meta del venditore
						'value'   => $venditore_id, // Sostituisci $vendor_id con l'ID del venditore desiderato
						'compare' => '=',
					),
				),
			);

			$products_query = new WP_Query($args);

			echo '						<div class="vendor_title_info_iperpro_contacts">
			<div>Totale prodotti del venditore: <strong>' . $products_query->post_count . '</strong>
		</div>';

			$rating = 0;
			$creviews_number = 0;
			$count = 0;

			// Verifica se sono stati trovati dei prodotti
			if ($products_query->have_posts()) {
				while ($products_query->have_posts()) {
					$products_query->the_post();
					$product_id = get_the_ID();

					// Recupera tutte le recensioni associate al prodotto
					$comments_args = array(
						'post_id' => $product_id,
						'status' => 'approve', // Puoi specificare lo stato delle recensioni da recuperare (approve, spam, trash, etc.)
					);
					$comments = get_comments($comments_args);

					// Loop attraverso le recensioni e fai qualcosa con esse
					foreach ($comments as $comment) {
						$rating = $rating + intval(get_comment_meta($comment->comment_ID, 'rating', true));
						$creviews_number = get_comments_number();
						//conta il numero di cicli che corrisponde al numero delle recensioni
						$count++;
					}
				}
			}

			// Ripristina le informazioni globali di WordPress
			wp_reset_postdata();

			if ($rating > 0 && $creviews_number > 0) {
				//la media del venditore si calcola (punteggio totale/ n.di recensioni)
				$width = ($rating / $count) * 20;
				echo '<div>Valutazione: <strong>' . $rating / $count . '/5 </strong><div class="star-rating"><span style="width:' . $width . '%;">&#9733;&#9733;&#9733;&#9733;&#9733;</span></div></div>';
			} else {
				echo '<div>Valutazione: <strong>' . $rating . '/5 </strong><div class="star-rating"><span style="width: 0%;">&#9733;&#9733;&#9733;&#9733;&#9733;</span></div></div>';
			}

			echo '
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
<?php
}
add_shortcode('get_head_vendor', 'vendor_info');

function products_single_vendor_count($vendor_id)
{

	if (verify_query_string() == 1 && $vendor_id) {

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

		$products = get_posts($args);

		//$products = $query->get_posts();

		return count($products);
	} else {
		return;
	}
}

function search_bar_prodotti_vetrina_()
{
?>
	<style>
		.custom-search-inputs-wrapper {
			background: whitesmoke;
			display: flex;
			border-radius: 15px;
		}

		#search-sellers-input {
			border: none;
			background: none;
		}

		#custom-search-button {
			min-width: 50px;
			background: none;
			border: none;
		}



		#search-sellers-results-wrapper {

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

		#close-search-sellers-results {

			padding: 5px;
			display: flex;
			justify-content: end;

		}

		#search-sellers-results {

			max-height: 400px;
			overflow-y: auto;
		}
	</style>


	<div class="custom-search-wrapper">
		<div class="custom-search-inputs-wrapper">
			<input type="text" id="search-sellers-input" placeholder="Cerca">
			<button id="custom-search-button"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
					<path d="M23.64 22.176l-5.736-5.712c1.44-1.8 2.232-4.032 2.232-6.336 0-5.544-4.512-10.032-10.032-10.032s-10.008 4.488-10.008 10.008c-0.024 5.568 4.488 10.056 10.032 10.056 2.328 0 4.512-0.792 6.336-2.256l5.712 5.712c0.192 0.192 0.456 0.312 0.72 0.312 0.24 0 0.504-0.096 0.672-0.288 0.192-0.168 0.312-0.384 0.336-0.672v-0.048c0.024-0.288-0.096-0.552-0.264-0.744zM18.12 10.152c0 4.392-3.6 7.992-8.016 7.992-4.392 0-7.992-3.6-7.992-8.016 0-4.392 3.6-7.992 8.016-7.992 4.392 0 7.992 3.6 7.992 8.016z"></path>
				</svg>
			</button>
		</div>
		<div id="search-sellers-results-wrapper">
			<div id="close-search-sellers-results">
				<div>X</div>
			</div>
			<div id="search-sellers-results"></div>
		</div>
	</div>

	<script>
		jQuery(document).ready(function($) {

			var searchInput = $('#search-sellers-input');

			//var resultsContainer_old = $('#product-vendor-anchor').html();
			//var resultsContainer_new = $('#product-vendor-anchor');

			//console.log(resultsContainer_old);


			searchInput.on('input', function() {
				var searchQuery = searchInput.val();
				$.ajax({
					url: '/wp-admin/admin-ajax.php',
					type: 'POST',
					data: {
						action: 'search_sellers_callback_vendor_window',
						search_query: searchQuery,
					},
					beforeSend: function() {

						// $("#search-sellers-results-wrapper").css("display", "block");
						// $("#search-sellers-results").html("<div class=\"spinner-wrapper\"><div class=\"lds-ring\"><div></div><div></div><div></div><div></div></div></div>");
					},
					success: function(response) {

						console.log(searchQuery);
						console.log(response);
						$("#product-vendor-anchor").html(response);





						//resultsContainer_new.empty();

						/*$("#product-vendor-anchor").html(response);
						if (response.length > 0) {
							$.each(response, function(index, product) {

								resultsContainer_new.append('<div class="result-item"><div class="result-item-content"><a href="https://iperprogetto.it/vetrina/?venditore=' + vendor_login + '">' + product.titolo + '</a></div></div>');
							});
						} else {

						}*/
					}
				});
			});


			/*$('#search-sellers-input').on('blur', function() {

				var inputValue = $(this).val();

				if (inputValue === '') {
					// Esegui l'azione desiderata
					$("#product-vendor-anchor").html(resultsContainer_old);
				}

			});*/





		});
	</script>

<?php
}
add_shortcode('search_bar_prodotti_vetrina', 'search_bar_prodotti_vetrina_');







// Aggiungi la funzione per la chiamata AJAX
function search_sellers_callback_vendor_window()
{
	product_single_vendor($_POST['search_query']);
	//echo 'TEST = '. $_POST['search_query'];
}
add_action('wp_ajax_search_sellers_callback_vendor_window', 'search_sellers_callback_vendor_window');
add_action('wp_ajax_nopriv_search_sellers_callback_vendor_window', 'search_sellers_callback_vendor_window');





function get_filter_products_vendor_sidebar_left_()
{

	if (verify_query_string() != 1) {
		return;
	}

	$html = '
	
	<style>

		.filter_panel {

			width: 250px;
			background: #DBE3EB;
			display: flex;
			flex-direction: column;
			gap: 10px;
			padding: 25px;
			border-radius: 15px;
			margin-bottom: auto;
		}

		.collapsible {

			display: flex;
			background: #1D3C6E;
			text-align: left;
			padding: 9px 13px;
			border-radius: 15px;
			color: white;
			border: none;

		}

		.content_panel_filter {
			padding: 0 18px;
			display: none;
			flex-direction: column;
		}

		.right-element {
			margin-left: auto;
		}

		.no_results {

			width: 150px;
			margin-top: 5px;
			margin-left: 5px;
		} 

	</style>';

	/*Ottieni la URL corrente*/
	$currentUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	/*Dividi la query string in base al carattere ?*/
	$url_split =  explode('?', $currentUrl);

	/*Prendi solo la prima parte della URL (senza la query string)*/
	$newUrl = $url_split[0];

	/*Prendi solo la seconda parte della URL (query string)*/
	$parms_split = explode('&', $url_split[1]);

	$html .= '<div id="all_content" class="filter_panel">';

	$html2 = '<div id="reset_filter" style="width: 200px; margin-bottom: 10px; display: flex; flex-direction: row-reverse;"><a id="reset_filter_button">Reset filtri</a></div>';

	$html2 .= '<div id="active_filter" style="width: 200px; margin-bottom: 10px;">';

	$meta_query = array(); /*Crea un array vuoto per la meta query*/

	foreach ($_GET as $key => $value) {

		//cerca il nome paremetro venditore
		if ($key == "venditore") {

			//verifica esistenza venditore in DB
			$venditore = get_user_by('login', $value);
			$venditore_id = $venditore->ID;

			if (!$venditore_id) {
				//se null l'utente non esiste coseguenza return
				return;
			}

			//se esistente aggiungi l'id venditore all'array
			$meta_query[] = array(
				'key' => 'vendor_user',
				'value' =>  $venditore_id,
				'compare' => '='
			);
		} else {
			$parts = explode("~", $key);

			$term = get_term_by('slug', $value, $parts[0]);

			if ($term) {
				$term_name = $term->name; // Ottieni il nome del termine

				//aggiungi tasto reset e icona con nome filtro attivo cliccabile
				$html2 .= '<label style="border-radius: 15px; border: 1px solid #1D3C6E; color: #1D3C6E; padding: 6px 10px 6px 10px; margin-right: 5px;">' . $term_name . '<a id="delete_filter" name="' . $key . '" value="' . $value . '"><i class="et-icon et-delete" style="margin-left: 5px; font-size: 10px;"></i></a></label>';
			} else {
				// Termine non trovato
			}
		}
	}

	//chiudi div contenitore filtri attivi
	$html2 .= "</div>";

	//ottieni la pagina corrente
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	/*Crea l'oggetto WP_Query con le query tassonomiche unite*/
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'paged'          => $paged,
		'meta_query' => $meta_query
	);


	//se sono presenti parametri oltre 'venditore' aggiungi $html2 al resto del contenuto dinamico
	if (!empty($parms_split[1])) {
		$html .= $html2;
	}

	//ottieni prodotti con get_posts()
	$products = get_posts($args);

	$attribute_taxonomies = wc_get_attribute_taxonomies();

	$filters = array();

	if ($products) {

		foreach ($products as $product) {

			foreach ($attribute_taxonomies as $tax) {
				//problemi nel recupero corretto degli attributi per ogni prodotto continuare ad analizzare. c'è qualcosa che non va.

				$attribute_name = wc_attribute_taxonomy_name($tax->attribute_name);
				//echo 'attribute_name:' . $attribute_name . '<br>';

				$args = array(
					'orderby'    => 'name',
					'hide_empty' => true,
					'taxonomy'   => $attribute_name,
					'object_ids' => wp_list_pluck($product->ID, 'ID')
				);

				$terms = get_terms($args);


				$taxonomy = get_taxonomy($attribute_name);

				$count = 0;

				if (has_term(null, $attribute_name, $product->ID)) {

					if (!in_array($attribute_name, $filters)) {

						array_push($filters, $attribute_name);

						$html .= '
					<a class="collapsible">
						<span style="color: white;  margin-right: auto; font-size: 14px; font-weight: 350;">' . $taxonomy->labels->singular_name . '</span>
						<i class="et-down-arrow et-icon" style="padding-top: 3px; color: white;"></i>
					</a>
					<div class="content_panel_filter">
					';

						foreach ($terms as $select_term) {
							//var_dump($select_term);
							if (has_term($select_term->name, $attribute_name, $product->ID)) {

								$html .= '<label style="width:150px;"><input type="checkbox" name="' . $attribute_name . '~' . $count . '" value="' . $select_term->slug . '">' . $select_term->name . '</label>';
								$count++;
							}
						}
						$html .= '</div>';
					}
				} else {
				}
			}
		}
	}

	//var_dump($filters);




	$html .= '		
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
							} 
							else 
							{				  	
								window.location.replace(current_url + "?" + checkbox_name + "=" + checkbox_value);
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
						//var parts = url.split("?");

						// Prendi solo la prima parte della URL (senza i parametri)
						var newUrl = "' . $newUrl . '?' . $parms_split[0] . '";

						// Reindirizza utente alla nuova URL senza i parametri
						window.location.replace(newUrl);


					});

					$(document).on("click", "label a#delete_filter", function(){

						let filter_name = $(this).attr("name");
						let filter_value = $(this).attr("value");

						let url = window.location.href;
						current_url = url.replace(/\/page\/\d+\//, "/");

						let urlObj = new URL(current_url); // crea un oggetto URL
						let searchParams = urlObj.searchParams; // ottieni i parametri di ricerca del URL

						// rimuovi il parametro "nome_parametro_da_rimuovere" dal URL
						searchParams.delete(filter_name);

						// ricostruisci URL senza il parametro rimosso
						let newUrl = urlObj.origin + urlObj.pathname + "?" + searchParams.toString();

						window.location.replace(newUrl);


					});';

	$params2 = array();
	foreach ($_GET as $key => $value) {
		$params2[$key] = $value;
	}

	// Converti larray in formato JSON
	$json_params = json_encode($params2);

	$html .= 'let params = ' . $json_params . ';
							  $.each(params, function(key, value) {
								  let key_split = key.split("~");
								  $("input[type=checkbox][name^="+key_split[0]+"][value="+ value +"]").prop("checked", true);
							  });

			});

					</script>';





	wp_reset_postdata();

	return $html;
}
add_shortcode('sidebar_left_filter_vendor_window', 'get_filter_products_vendor_sidebar_left_');


function product_single_vendor($search_query)
{

	/*if (verify_query_string() != 1) {
		return;
	}*/


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
			right: 0;    
			display: flex;    
			gap: 7px;    
			padding: 10px;    
			top: 0;    
			flex-direction: column;
        }


		.stamp_item {
			width: 48px;
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

	// crea array per query
	$args = array();

	// Crea un array vuoto per la meta query
	$meta_query = array();

	// Array di query tassonomiche
	$tax_queries = array();

	// Array di tassonomie già utilizzate
	$used_taxonomies = array();

	if (!empty($search_query)) {
		$args['s'] = $search_query;
		var_dump($_GET);
	}

	// Loop sui parametri passati alla funzione
	foreach ($_GET as $key => $value) {

		// Se il parametro è vuoto, passa al prossimo
		if (empty($value)) {
			continue;
		}

		// Split del parametro


		// Se il parametro fa riferimento all'ID del vendor, aggiungi una query meta

		if ($key == "venditore") {

			$venditore = get_user_by('login', $value);
			$venditore_id = $venditore->ID;

			if (!$venditore_id) {
				return;
			}

			unset($meta_query);

			$meta_query[] = array(
				'key' => 'vendor_user',
				'value' => $venditore_id,
				'compare' => 'IN',
			);

			// Altrimenti, crea una nuova query tassonomica
		} else {

			$parts = explode('~', $key);

			// Se la tassonomia è già stata utilizzata, aggiungi il termine alla query corrispondente
			if (in_array($parts[0], $used_taxonomies)) {

				$index = array_search($parts[0], array_column($tax_queries, 'taxonomy'));

				$tax_queries[$index]['terms'][] = $value;

				// Altrimenti, crea una nuova query tassonomica
			} else {

				//non utilizzare come separatore di parole nello slug il '-' in quanto wp_query lo rimuove preferibilmente usare un unica parola nello slug
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
		$tax_queries,
	);

	// aggiunge il parametro meta_query che contine l'ID venditore per recuperare solo i suoi prodotti
	if (!empty($meta_query)) {
		$args['meta_query'] = $meta_query;
	}

	$args['posts_per_page'] = 9; //Imposta il numero di prodotti per pagina
	$args['paged'] = $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //ottieni la pagina corrente
	$args['post_type'] = 'product'; // indicai  ltipo di post da recuperare

	$query = new WP_Query($args);

	if ($query->have_posts()) {


		$html .= '<div class="products_grid_custom_iperproject" id="product-vendor-anchor">';

		while ($query->have_posts()) {

			$query->the_post();

			$html .= '<div class="product_custom_iperproject">';

			$img = get_the_post_thumbnail_url(get_the_ID(), 'medium') ? get_the_post_thumbnail_url($product->ID, 'medium') : wc_placeholder_img_src('medium');

			$html .= '<div class="product_image"><a href="' . get_permalink() . '"><img src="' . $img . '" style="object-fit: cover; border-radius: 22px 22px 0px 0px;"></a>';

			$html .= '<div class="stamps_wrapper">';

			// logica bollini 
			if (get_field('pur_approved') == true) {
				$html .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_pur-approved.svg"/>';
			}

			if (get_field('ecosostenibile') == true) {
				$html .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_eco.svg"/>';
			}

			if (get_field('innovativo') == true) {
				$html .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_innovativo.svg"/>';
			}

			$html .= '</div>
					</div>';


			//inizio generazione parte box inferiore con info del prodotto
			$html .= '<div class="product_info">';

			// Recupera il valore del campo personalizzato "nome_campo"
			$id_user = get_field('vendor_user');
			//recupera tutti i valori associati all'utente
			$meta_values = get_user_meta($id_user);


			// Mostra il valore del campo personalizzato
			if ($meta_values) {
				$company_name = $meta_values['billing_company'][0];

				$html .= '<div class="vendor_product" style="height:25px;"><p style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"><a href="https://iperprogetto.it/vetrina/?venditore=' . $meta_values['nickname'][0] . '" >' . $company_name . '</a></p></div>';
			} else {
				$html .= '<div class="vendor_product" style="height:25px;"><p>N.D.</p></div>';
			}

			$html .= '<div class="title_product" style="height:40px;"><a href="' . get_permalink($product->ID) . '"><h2 style="font-size: 14px;color: #303030">' . substr(get_the_title(), 0, 55) . '</h2></a></div>';



			// Output the product reviews
			$comments = get_comments(array(
				'post_id' => get_the_ID(),
				'status' => 'approve'
			));


			$html .= '<div class="review_product" style="height:20px;">';
			$rating = 0;
			$creviews_number = 0;

			if ($comments) {
				foreach ($comments as $comment) {
					// Imposta il rating
					$rating = $rating + intval(get_comment_meta($comment->comment_ID, 'rating', true));
					$creviews_number = get_comments_number();
				}
				// Calcola la larghezza delle stelle in base al rating
				$width = ($rating / $creviews_number) * 20;

				// Genera le stelle
				$html .= '<div class="star-rating"><span style="width: ' . $width . '%;">&#9733;&#9733;&#9733;&#9733;&#9733;</span></div><span> <a href="' . get_permalink() . '#reviews" style="padding-left: 5px;"> <u>' . $creviews_number . ' voti</u></a></span>';
				$html .= '';
			} else {
				// Genera le stelle
				$html .= '<div class="star-rating" role="img"><span style="width: 0%;">&#9733;&#9733;&#9733;&#9733;&#9733;</span></div><span> <a href="' . get_permalink() . '#reviews" style="padding-left: 5px;"> <u>0 voti</u></a></span>';
				$html .= '';
			}

			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}



		$html .= '</div>';

		if (empty($search_query)) {

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
		}

		wp_reset_postdata();

	} 
	
	else 
	{
		$html = '<div style="width: 700px;display: flex;justify-content: center;margin-left: -155px;"><h2>Nessun prodotto trovato...</h2></div>';
		return;
	}

	return $html;
}
add_shortcode('get_product_single_vendor', 'product_single_vendor');





function get_assets_vendor()
{

	if (verify_query_string() != 1) {
		return;
	}

	// Nome utente del venditore di cui vogliamo trovare i prodotti
	$vendor = $_GET['venditore'];

	// Recupera l'ID dell'utente corrispondente al nome utente del venditore
	$venditore = get_user_by('login', $vendor);
	$venditore_id = $venditore->ID;
	if (!$venditore_id) {
		return;
	}


?>
	<style>
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

		.vendor_catalog_link_iperpro>img {
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


		.vendor_title_info_iperpro_contacts_socials>a {
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
	<?php


	$vendor_meta = get_user_meta($venditore_id);

	var_dump($vendor_meta);

	if ($vendor_meta) {

		echo '<div id="vendor-catalog">
				<h3>Cataloghi venditore</h3>
				<div class="vendor_catalogues_container">
				';

		for ($i = 1; $i < 5; $i++) {

			$link_pdf = wp_get_attachment_url($vendor_meta['catalog_vendor_' . $i][0]);
			$image_catalog = wp_get_attachment_url($vendor_meta["catalog_vendor_image_" . $i][0]);

			echo '<a class="vendor_catalog_link_iperpro" href="' . $link_pdf . '" target=_blank><img src="' . $image_catalog . '"></a>';
		}
	}

	?>
	</div>
	</div>

	<div class="" id="eventi" style="margin-top: 100px;">
		<h3>Eventi</h3>
		<div>
			<?php

			echo 'Eventi venditore: ' . $vendor_meta['eventi_vendor'][0];


			?>
		</div>
	</div>


	<div class="" id="news" style="margin-top: 100px;">
		<h3>News</h3>
		<div>
			<?php

			echo 'News venditore: ' . $vendor_meta['news_vendor'][0];


			?>
		</div>
	</div>


	<div class="" id="info-vendor" style="margin-top: 100px;">
		<h3>Informazioni su <?php echo $vendor_meta['billing_company'][0] ?></h3>
		<div>
			<?php

			echo '<p>' . $vendor_meta['bio_vendor'][0] . '</p>';


			?>
		</div>
	</div>

	<div class="" style="margin-top: 100px;">
		<h2>Dettagli aziendali</h2>
		<div class="column">
			<?php

			echo '<div>Ragione sociale: <strong>' . $vendor_meta['billing_company'][0] . '</strong></div>';
			echo '<div>Nome: <strong>' . $vendor_meta['billing_first_name'][0] . '</strong></div>';
			echo '<div>Cognome: <strong>' . $vendor_meta['billing_last_name'][0] . '</strong></div>';
			echo '<div>Indirizzo: <strong>' . $vendor_meta['billing_address_1'][0] . '</strong></div>';
			echo '<div>Numero di telefono: <strong>' . $vendor_meta['shipping_phone'][0] . '</strong></div>';
			echo '<div>Email: <strong>' . $vendor_meta['billing_email'][0] . '</strong></div>';

			?>
		</div>
		<div class="vendor_title_info_iperpro_contacts_socials">
			<a href="https://www.facebook.com/sharer/sharer.php?u='.$link.'" target="_blank"><i class="fa fa-facebook"></i></a>
			<a href="https://api.whatsapp.com/send?text='.$link.'" target="_blank" target="_blank"><i class="fa fa-whatsapp"></i></a>
			<a href="https://www.linkedin.com/sharing/share-offsite/?url='.$link.'" target="_blank"><i class="fa fa-linkedin"></i></a>
		</div>
	</div>

<?php

}
add_shortcode('get_assets_vendor_', 'get_assets_vendor');
