<?php

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