<?php

function process_selected_region()
{


	global $wpdb;

	$selected_region = $_GET["selected_region"];

	//query per leggere i dati dalla tabella
	$results = $wpdb->get_results("SELECT * FROM hfu_pur_chapters where region='$selected_region'", OBJECT);

	// Codifica dei risultati in formato JSON
	if ($results > 0) {
		echo json_encode($results);
	} else {
		// Output in formato JSON
		header('Content-Type: application/json');
		echo "nulla";
	}

	wp_die();
}

function process_selected_chapter()
{

	global $wpdb;

	$selected_region = $_GET["selected_region"];
	$selected_chapter = $_GET["selected_chapter"];

	//query per leggere i dati dalla tabella
	$results = $wpdb->get_results("SELECT subchapter, title From hfu_pur_subchapter where region ='$selected_region' AND hfu_pur_subchapter.chapter ='$selected_chapter'", OBJECT);

	// Codifica dei risultati in formato JSON
	if ($results > 0) {
		//header('Content-Type: application/json');
		echo json_encode($results);
	} else {
		// Output in formato JSON
		header('Content-Type: application/json');
		echo "nulla";
	}

	wp_die();
}

function process_selected_content()
{

	global $wpdb;

	$selected_region = $_GET["selected_region"];
	$selected_chapter = $_GET["selected_chapter"];
	$selected_subchapter = $_GET["selected_subchapter"];

	//query per leggere i dati dalla tabella
	$results = $wpdb->get_results("SELECT chapter, subchapter, `head_paragraphs`,`paragraphs_content`, keywords FROM `hfu_pur_content` where `region`='$selected_region' AND `chapter`='$selected_chapter' AND `subchapter`='$selected_subchapter' ", OBJECT);

	// Codifica dei risultati in formato JSON
	if ($results > 0) {
		header('Content-Type: application/json');
		echo json_encode($results);
	} else {
		// Output in formato JSON
		header('Content-Type: application/json');
		echo "nulla";
	}

	wp_die();
}

function process_selected_voice()
{

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
	if ($results > 0) {
		header('Content-Type: application/json');
		echo json_encode($results);
	} else {
		// Output in formato JSON
		header('Content-Type: application/json');
		echo "nulla";
	}

	wp_die();
}

function process_selected_voice_2()
{

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
	if ($results > 0) {
		header('Content-Type: application/json');
		echo json_encode($results);
	} else {
		// Output in formato JSON
		header('Content-Type: application/json');
		echo "nulla";
	}

	wp_die();
}

function save_voice_data_user()
{

	global $wpdb;

	$id_ai_voice = $_POST["id_ai_voice"];
	$id_user = $_POST["id_user"];

	//query per leggere i dati dalla tabella
	$results = $wpdb->get_results("INSERT INTO `hfu_users_note`(`user_id`, `id_ai_voice`) VALUES ('$id_user', '$id_ai_voice') ");


	//echo $results;




	wp_die();
}


function carosello_prodotti_pur()
{

	$category_product = $_POST["category_product"];

	//echo $category_product.'<br>';

	/*$atts = shortcode_atts( array(
		'limit' => 12,
		'category' => 'prodotti-nanotecnologici',
		'orderby' => 'date',
		'order' => 'DESC'

	), $atts, 'product-carousel' );*/

	// Query WooCommerce products
	/*$args = array(
		'post_type' => 'product',
		'posts_per_page' => $atts['limit'],
		'orderby' => $atts['orderby'],
		'order' => $atts['order']
	);

	if ( ! empty( $category_product ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'name',
				'terms' => $category_product
			)
		);
	}*/

	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => 8,
		'tax_query' => array(
			'taxonomy' => 'product_cat',
			'field' => 'name',
			'terms' => $category_product
		)

	);

	$query = new WP_Query($args);




	$output = '';


	// Build the product carousel HTML
	ob_start();


	echo '
	<head>

		

		<style>
			.products_grid_custom_iperproject {
				display: grid;
				grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
				grid-gap: 20px;
				justify-content: center;
				align-items: center;
			}

			.product_custom_iperproject {
				width: 235px !important;
			}



			.slick-prev:before,
			.slick-next:before {
				content: none !important;
			}

			.slick-prev,
			.slick-next {
				color: #1e3c6d !important;
				font-size: 16px !important;
				width: 40px !important;
				height: 40px !important;
				background: #d9d9d9 !important;
				border-radius: 50% !important;
			}

			.slick-prev:hover,
			.slick-next:hover {
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

			.product_image {
				max-height: 300px;
				width: 100%;
				min-height: 236px;
				display: flex;
				justify-content: center;
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

			.product_info {
				max-height: 205px;
				width: 100%;
				min-height: 105px;
				background: #DBE3EB;
				display: flex;
				flex-direction: column;
				justify-content: center;
				text-align: left;
				border-bottom-left-radius: 22px;
				border-bottom-right-radius: 22px;
				padding: 10px;
				padding-left: 15px;
				padding-right: 15px;
			}



			.icon_in_button {
				display: flex;
				align-items: center;
				justify-content: center;
				text-align: center;
			}

			.star-rating:before {

				color: #22222252;
			}

			.star-rating,
			#review_form .stars {
				--et_yellow-color: #FFE175;
			}
		</style>
	</head>
<body>';


	echo '<h2>Prodotti correlati</h2>';
	echo '<div class="product-carousel">';

	if ($query->have_posts()) {

		while ($query->have_posts()) {

			$query->the_post();

			echo '<div class="product-item"><div class="product_custom_iperproject">';

			$img = get_the_post_thumbnail_url(get_the_ID(), 'medium') ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : wc_placeholder_img_src('medium');
			//$img = get_the_post_thumbnail_url(get_the_ID(), 'medium' );

			echo '<div class="product_image"><a href="' . get_permalink() . '"><img src="' . $img . '" style="object-fit: cover; border-radius: 22px 22px 0px 0px;"></a>';

			echo '<div class="stamps_wrapper">';
			// logica bollini 
			if (get_field('pur_approved') == true) {
				echo '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_pur-approved.svg"/>';
			}

			if (get_field('ecosostenibile') == true) {
				echo '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_eco.svg"/>';
			}

			if (get_field('innovativo') == true) {
				echo '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_innovativo.svg"/>';
			}

			echo '</div>';

			echo '</div>';

			echo '<div class="product_info">';

			// Recupera il valore del campo personalizzato "nome_campo"
			$id_user = get_field('vendor_user');
			$meta_values = get_user_meta($id_user);

			//$output .= '<div class="excerpt_product" style=""><p>'.substr(get_post_field('post_excerpt', get_the_ID()), 0, 80).'</p></div>';

			// Mostra il valore del campo personalizzato
			if ($meta_values) {
				$company_name = $meta_values['billing_company'][0];

				echo '<div class="vendor_product" style="height:25px;"><p style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"><a href="https://iperprogetto.it/vetrina/?venditore=' . $meta_values['nickname'][0] . '" >' . $company_name . '</a></p></div>';
			} else {
				echo '<div class="vendor_product" style="height:25px;"><p>N.D.</p></div>';
			}

			echo '<div class="title_product" style="height:40px;"><a href="' . get_permalink() . '"><h2 style="font-size: 14px;color: #303030">' . substr(get_the_title(), 0, 55) . '</h2></a></div>';



			// Output the product reviews
			$comments = get_comments(array(
				'post_id' => get_the_ID(),
				'status' => 'approve'
			));

			echo '<div class="review_product" style="height:20px;">';
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
				echo '<div class="star-rating"><span style="width: ' . $width . '%;">&#9733;&#9733;&#9733;&#9733;&#9733;</span></div><span> <a href="' . get_permalink() . '#reviews" style="padding-left: 5px;"> <u>' . $creviews_number . ' voti</u></a></span>';
				echo '';
			} else {
				// Genera le stelle
				echo '<div class="star-rating" role="img"><span style="width: 0%;">&#9733;&#9733;&#9733;&#9733;&#9733;</span></div><span> <a href="' . get_permalink() . '#reviews" style="padding-left: 5px;"> <u>0 voti</u></a></span>';
				echo '';
			}

			echo '</div>
				</div>
				</div>
				</div>';
		}
	}

	echo '</div>
			</body>';


	wp_reset_postdata();



	// Enqueue Slick and Font Awesome CSS and JS
	/*
	wp_enqueue_style( 'slick-css', 'https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css' );
	wp_enqueue_style( 'slick-theme-css', 'https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick-theme.css' );
	wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css' );
	wp_enqueue_script( 'slick-js', 'https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js', array( 'jquery' ), '1.6.0', true );
	*/

	// Enqueue custom JS for Slick

	$output = ob_get_clean();

	echo $output;

	wp_die();
}





add_action("wp_ajax_process_selected_region", "process_selected_region");

add_action("wp_ajax_process_selected_chapter", "process_selected_chapter");

add_action("wp_ajax_process_selected_content", "process_selected_content");

add_action("wp_ajax_process_selected_voice", "process_selected_voice");

add_action("wp_ajax_process_selected_voice_2", "process_selected_voice_2");

add_action("wp_ajax_save_voice_data_user", "save_voice_data_user");

add_action("wp_ajax_carosello_prodotti_pur", "carosello_prodotti_pur");
