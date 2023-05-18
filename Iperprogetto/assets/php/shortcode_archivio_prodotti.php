<?php

function get_products_custom_last_category($category_id){

	$html = '';

	$html .= '<style>

		.products_grid_custom_iperproject{
			grid-gap: 20px;
			grid-template-columns: repeat(3, 1fr);
			display: grid;
			justify-items: start;
			/*margin-left: 50px; */
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
		$parts = explode('~', $key);

		// Se il parametro fa riferimento all'ID del vendor, aggiungi una query meta
		if (strpos($key, "vendor_user_id_filter-") !== false) {
			
			unset($meta_query);

			array_push($used_vendor, $value);
			//$used_vendor = explode(',', $value); //converte la stringa in un array
			//$used_vendor[] = $value;
			
			$stringa = implode(',', $used_vendor);
			

			
			$meta_query[] = array(
				'key' => 'vendor_user',
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

			$parts = explode("~", $key);

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
				
				
				$html .= '
					<a class="collapsible">
						<span style="color: white;  margin-right: auto; font-size: 14px; font-weight: 350;">'.$taxonomy->labels->singular_name.'</span>
						<i class="et-down-arrow et-icon" style="padding-top: 3px; color: white;"></i>
					</a>
					<div class="content">
					';
				
				foreach ( $terms as $term ) {
					$html .= '<label style="width:150px;"><input type="checkbox" name="'.$attribute_name.'~'.$count.'" value="'.$term->slug.'">'.$term->name.'</label>';
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
					
					$(".vendor_name").each(function()
					{
					  let labelValue = $(this).text().toLowerCase();
					  
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
						var parts = url.split("?");

						// Prendi solo la prima parte della URL (senza i parametri)
						var newUrl = parts[0];

						// Reindirizza lutente alla nuova URL senza i parametri
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
					
					
					});

				';
	
					
	$params2 = array();
	foreach ($_GET as $key => $value) {
		$params2[$key] = $value;
	}

	// Converti l'array in formato JSON
	$json_params = json_encode($params2);

	$html .= 'let params = '.$json_params.';
			  $.each(params, function(key, value) {
			  	let key_split = key.split("~");
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