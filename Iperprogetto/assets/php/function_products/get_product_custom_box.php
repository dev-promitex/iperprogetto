<?php

function get_product_custom_box($visited_products, $custom_name_css_class)
{

    $html = '';

	$html .= '<style>

		.'.$custom_name_css_class.'{
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

    $args = array(
        'post_type' => 'product',
        'post__in' => $visited_products,
        'orderby' => 'post__in'
    );

    $query = new WP_Query($args);

    //var_dump($query);

    if ($query->have_posts()) {


        $html .= '<div class="'.$custom_name_css_class.'">';
        
        while ($query->have_posts()) {

            $query->the_post();

            $html .= '<div class="product_custom_iperproject">';


            $img = get_the_post_thumbnail_url(get_the_ID(), 'medium') ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : wc_placeholder_img_src('medium');
            //$img = get_the_post_thumbnail_url(get_the_ID(), 'medium' );

            $html .= '<div class="product_image"><a href="' . get_permalink() . '"><img src="' . $img . '" style="height:228px; width: auto; object-fit: cover; border-radius: 22px 22px 0px 0px;"></a>';


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

            $html .= '<div class="product_info">';

            // Recupera il valore del campo personalizzato "nome_campo"
            $id_user = get_field('vendor_user');
            $meta_values = get_user_meta($id_user);

            //$html .= '<div class="excerpt_product" style=""><p>'.substr(get_post_field('post_excerpt', get_the_ID()), 0, 80).'</p></div>';

            // Mostra il valore del campo personalizzato
            if ($meta_values) {
                $company_name = $meta_values['billing_company'][0];

                $html .= '<div class="vendor_product" style="height:25px;"><p style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"><a href="https://iperprogetto.it/vetrina/?venditore=' . $meta_values['nickname'][0] . '" >' . $company_name . '</a></p></div>';
            } else {
                $html .= '<div class="vendor_product" style="height:25px;"><p>N.D.</p></div>';
            }

            $html .= '<div class="title_product" style="height:40px;"><a href="' . get_permalink() . '"><h2 style="font-size: 14px;color: #303030">' . substr(get_the_title(), 0, 55) . '</h2></a></div>';



            // Output the product reviews
            $comments = get_comments(array(
                'post_id' => get_the_ID(),
                'status' => 'approve'
            ));

            //var_dump($comments);

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
        
        wp_reset_postdata();
        
        echo $html;
    } 
    
    else  
    {
        //return "<p>Nessun prodotto</p>";
    }

}

?>