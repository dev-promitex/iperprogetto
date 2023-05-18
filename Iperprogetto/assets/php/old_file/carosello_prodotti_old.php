<?php

function my_product_carousel_shortcode($atts)
{

	$atts = shortcode_atts(array(
		'limit' => 12,
		'category' => 'prodotti-nanotecnologici',
		'orderby' => 'date',
		'order' => 'DESC'

	), $atts, 'product-carousel');

	// Query WooCommerce products
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => $atts['limit'],
		'orderby' => $atts['orderby'],
		'order' => $atts['order']
	);

	if (!empty($atts['category'])) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'slug',
				'terms' => $atts['category']
			)
		);
	}

	$query = new WP_Query($args);
	$output = '';

	// Build the product carousel HTML
	ob_start(); ?>

	<style>
		.products_grid_custom_iperproject {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
			grid-gap: 20px;
			justify-content: center;
			align-items: center;
		}

		.product_custom_iperproject {
			/* 		margin: 0 10px; */
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
			/* 		max-width: 330px; */
			max-height: 300px;
			/* 	    min-width: 230px; */
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

		.product_info {
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


<?php

	$output .= '<div class="product-carousel">';

	if ($query->have_posts()) {

		while ($query->have_posts()) {

			$query->the_post();

			$output .= '<div class="product-item"><div class="product_custom_iperproject">';

			$img = get_the_post_thumbnail_url(get_the_ID(), 'medium') ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : wc_placeholder_img_src('medium');
			//$img = get_the_post_thumbnail_url(get_the_ID(), 'medium' );

			$output .= '<div class="product_image"><a href="' . get_permalink() . '"><img src="' . $img . '" style="object-fit: cover; border-radius: 22px 22px 0px 0px;"></a>';

			$output .= '<div class="stamps_wrapper">';
			// logica bollini 
			if (get_field('pur_approved') == true) {
				$output .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_pur-approved.svg"/>';
			}

			if (get_field('ecosostenibile') == true) {
				$output .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_eco.svg"/>';
			}

			if (get_field('innovativo') == true) {
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
			if ($meta_values) {
				$company_name = $meta_values['billing_company'][0];

				$output .= '<div class="vendor_product" style="height:25px;"><p style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"><a href="https://iperprogetto.it/vetrina/?venditore=' . $meta_values['nickname'][0] . '" >' . $company_name . '</a></p></div>';
			} else {
				$output .= '<div class="vendor_product" style="height:25px;"><p>N.D.</p></div>';
			}

			$output .= '<div class="title_product" style="height:40px;"><a href="' . get_permalink() . '"><h2 style="font-size: 14px;color: #303030">' . substr(get_the_title(), 0, 55) . '</h2></a></div>';



			// Output the product reviews
			$comments = get_comments(array(
				'post_id' => get_the_ID(),
				'status' => 'approve'
			));

			$output .= '<div class="review_product" style="height:20px;">';
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
				$output .= '<div class="star-rating"><span style="width: ' . $width . '%;">&#9733;&#9733;&#9733;&#9733;&#9733;</span></div><span> <a href="' . get_permalink() . '#reviews" style="padding-left: 5px;"> <u>' . $creviews_number . ' voti</u></a></span>';
				$output .= '';
			} else {
				// Genera le stelle
				$output .= '<div class="star-rating" role="img"><span style="width: 0%;">&#9733;&#9733;&#9733;&#9733;&#9733;</span></div><span> <a href="' . get_permalink() . '#reviews" style="padding-left: 5px;"> <u>0 voti</u></a></span>';
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
	wp_enqueue_style('slick-css', 'https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css');
	wp_enqueue_style('slick-theme-css', 'https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick-theme.css');
	wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');
	wp_enqueue_script('slick-js', 'https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js', array('jquery'), '1.6.0', true);

	// Enqueue custom JS for Slick
	wp_add_inline_script('slick-js', '
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
    ');

	return $output;
}

add_shortcode('product-carousel', 'my_product_carousel_shortcode');
