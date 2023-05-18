<?php

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

