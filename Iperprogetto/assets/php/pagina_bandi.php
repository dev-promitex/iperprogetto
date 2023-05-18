<?php

function custom_category_filter_bandi() {
	$args = array(
		'show_option_all' => '',
		'orderby' => 'name',
		'hierarchical' => true,
		'depth' => 0,
		'hide_empty' => 1,
		'taxonomy' => 'category',
		'walker' => new Walker_Category_Custom_bandi,
		'child_of' => 104,
		'title_li' => '',
	);
	ob_start();
	wp_list_categories( $args );
	$categories = ob_get_clean();
	return $categories;
}

class Walker_Category_Custom_bandi extends Walker_Category {

	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		$pad = str_repeat('&nbsp;', $depth );
		$cat_name = strlen($category->name) > 20 ? substr($category->name, 0, 20).'...' : $category->name;
		$output .= '<a href="'.get_permalink().'/?categoria='. $category->term_id.'" class="category-posts-filter-link tag-' . $category->slug . '" data-cat-id="'. $category->term_id.'" style="display: block;">' .  $cat_name . '</a>';
		return $output;
	}
}

function filtro_categoria_bandi($categoria_id = 104) {
	$output = '';
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;


	$meta_query = [];
	if( isset( $_GET['data-scadenza'] ) ) {

		$meta_query[] = [
			'relation' => 'AND', 
			[ 
				'key' => 'scadenza_bando', 
				'value' => $_GET['data-scadenza'],
				'compare' => '<=',
				'type' => 'DATE'
			],
		];
	}

	if( isset( $_GET['data_inizio_bando'] ) ) {
		$meta_query[] = [
			'relation' => 'AND', 
			[ 
				'key' => 'data_inizio_bando', 
				'value' => $_GET['data_inizio_bando'], 
				'compare' => '>=', 
				'type' => 'DATE' 
			],
		];
	}


	if ( isset( $_GET['categoria'] ) ) {

		$categoria_id =  $_GET['categoria'];
	}
	if ( $categoria_id ) {

		$args = array(
			'cat' => $categoria_id,
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => 4,
			'paged' => $paged
		);
		$args['meta_query'] = $meta_query;


		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {

			while ( $query->have_posts() ) {
				$query->the_post();

				$excerpt = get_the_excerpt();
				$trimmed_excerpt = wp_trim_words( $excerpt, 30, '...' );
				$scadenza = strtotime(get_field('scadenza_bando'));
				$inizioBando = strtotime(get_field('data_inizio_bando'));
				$scadenzaHtml = '';

				if(!empty($scadenza)) { 
					$scadenzaHtml = 'Scade il ' . date('d/m/Y', $scadenza);;
				}

				$output .= '<a class="category-post-item' . $tag_classes . '" href="' . get_permalink() . '" data-scadenza="'.$scadenza.'" data-inizio-bando="'.$inizioBando.'">
                            <div class="category-post-item-img" style="background: url('.get_the_post_thumbnail_url().')"></div>
                            <div class="category-post-text-wrapper">
                                <div class="category-post-title">' . get_the_title() .'</div>
                                  <div class="category-post-due-date">'.$scadenzaHtml.'</div>
                            </div>
                        </a>';

				$scadenzaHtml = '';

			}

			$output .= '<div class="pagination-wrapper"><div class="pagination">' . paginate_links( array(
				'total' => $query->max_num_pages,
				'current' => $paged,
				'prev_next' => true,  		
				'prev_text' => '←',
				'next_text' => '→',
				'mid_size'  => 1,
				'end_size' => 1,


			)) . '</div></div>';

		} else {
			$output .= 'Nessun post trovato nella tipologia ' . get_cat_name($_GET['categoria']);
		}

		wp_reset_postdata();
	} else {
		$output .= 'Tipologia non valida';
	}

	return $output;
}



function display_posts_by_category_bandi( $atts ) {
	$output = '';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
	  integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<style>


	.active-strong {
		font-weight: bold;
	} 

	.category-posts-wrapper {
		display: flex;
		flex-direction: row-reverse;
		justify-content: space-between;
		gap: 45px;
	}

	.category-posts-wrapper .category-posts-list {
		width: 70%;
		display: flex;
		flex-direction: column;
		gap: 20px;
	}

	.category-posts-wrapper .category-posts-filter {
		width: 30%;
		background: #DBE3EB;
		display: flex;
		flex-direction: column;
		gap: 10px;
		padding: 25px;
		border-radius: 15px;
		margin-bottom: auto;
	}

	.category-posts-wrapper  .category-posts-filter .category-posts-filter-label {
		color: #1D3C6E;
	}


	.category-posts-wrapper  .category-posts-filter .category-posts-filter-button  {
		background: #1D3C6E;
		text-align: left;
		padding: 9px 13px;
		border-radius: 15px;
		color: white;
		border: none;
	}

	.category-posts-wrapper .category-post-item {
		display: flex;
		gap: 40px;
		background: #EFF7EE;
		border-radius: 15px;
		overflow: hidden;
		flex-direction: row-reverse;
		padding-left: 43px;
		justify-content: space-between;
	}

	.category-posts-wrapper .category-post-item .category-post-item-img {
		min-width: 200px;
		height: 150px;
		background-position: center !important;
		background-size: cover !important;
		background-repeat: no-repeat !important;
	}

	.category-posts-wrapper .category-post-item .category-post-text-wrapper {
		display: flex;
		flex-direction: column;
		justify-content: space-around;
		gap: 5px;
	}

	.category-posts-wrapper .category-post-item .category-post-text-wrapper .category-post-title {
		font-size: 18px;
		font-weight: bold;
		color: #7A7A7A;
	}


	.category-posts-wrapper .category-post-item .category-post-text-wrapper .category-post-tag {
		background: #3f5983;
		padding: 5px;
		border-radius: 5px;
		margin: 2px;
	}

	.category-posts-wrapper .category-post-item .category-post-due-date {
		color: #1e3c6d;
	}

	.category-posts-wrapper  .category-posts-filter .category-posts-filter-list {
		display: flex;
		flex-direction: column;
	}

	.category-posts-wrapper  .category-posts-filter .category-posts-filter-list .category-posts-filter-link {
		color: #303030;
	}

	#chevron-iperpro-links {
		padding-top: 3px;
	}

	/*Paginazione */

	.pagination-wrapper {
		display:flex;
		justify-content: flex-end;
	}

	.page_button {
		background-color: #f5f5f5;
		border: solid;
		border-radius: 5px;
		border-color: white;
		width: 40px;
		height: 40px;
		border-radius: 50%;
	}

	.page_button:hover {
		background-color: #1D3C6E;
	}

	.page_button .page-link {
		color: #1D3C6E;
		font-size: 20px;
	} 

	.page_button:hover .page-link {
		color: #ffffff;
	}


	.pagination-wrapper {
		display:flex;
		justify-content: flex-end;
	}

	.page-numbers {
		background-color: #d9d9d9;
		border: solid;
		border-color: white;
		width: 40px;
		height: 40px;
		border-radius: 50%;
		color: #1D3C6E;
		display: flex;
		font-size: 16px;
		justify-content: center;
		align-items: center;
	}

	.page-numbers:hover, .page-numbers.current {
		background-color: #1D3C6E;
		color: #ffffff;

	}


	/*Commutatore */
	.btn-drop-iperpro-links {
		min-width: 100%;
		max-width: 190px;
		border-width: 0px;
		background-color: #1D3C6E;
		padding: 9px 15px 8px 15px;
		color: white;
		font-size: 15px;
		border-radius: 10px;
	}

	#collapse-download-iperpro-links {
		padding: 10px 0px 10px 25px;
	}

	.btn-drop-iperpro-links:focus, .btn-drop-iperpro-links:hover {
		color: white;
	}

</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
		crossorigin="anonymous"></script>
<script>
	jQuery(document).ready(function($) {


		var urlParams = new URLSearchParams(window.location.search);
		var dataInizioBando = urlParams.get('data-inizio-bando');
		var dataScadenzaBando = urlParams.get('data-scadenza');


		if (dataInizioBando) {
			$('#data-inizio-bando-input').val(dataInizioBando);
		}

		$('#data-inizio-bando-input').change(function() {
			var dateValue = $(this).val();
			var url = new URL(window.location.href);
			url.searchParams.set('data-inizio-bando', dateValue);
			window.location.href = url.toString();
		});

		if (dataScadenzaBando) {
			$('#data-scadenza-bando-input').val(dataScadenzaBando);
		}

		$('#data-scadenza-bando-input').change(function() {
			var dateValue = $(this).val();
			var url = new URL(window.location.href);
			url.searchParams.set('data-scadenza', dateValue);
			window.location.href = url.toString();
		});

		$('.category-posts-filter').ready(function() {
			const catLinks = $('.category-posts-filter-link');
			const urlParams = new URLSearchParams(window.location.search);

			const category = urlParams.get('categoria');
			$.each(catLinks, function(idx,elm) {

				if(elm.dataset.catId == category) {
					$(elm).addClass('active-strong');
				} else if(elm.dataset.catId !== category && category  !== null) {

					$(elm).removeClass('active-strong');
				}
			})
		})

		$(document).ready(function() {
			$('.page-numbers.current').replaceWith(function() {
				return $('<a>', {
					html: $(this).html(),
					class: $(this).attr('class')
				});
			});
		});


		// commutatore

		const dropCollapsibleIperproLinks = $('#collapse-download-iperpro-links');

		const chevronIperproLinks = $('#chevron-iperpro-links');

		dropCollapsibleIperproLinks.on('hidden.bs.collapse', event => {
			chevronIperproLinks.removeClass('et-up-arrow');
			chevronIperproLinks.addClass('et-down-arrow');
		});

		dropCollapsibleIperproLinks.on('shown.bs.collapse', event => {
			chevronIperproLinks.removeClass('et-down-arrow');
			chevronIperproLinks.addClass('et-up-arrow');
		});
	});

</script>
<?php
	$output .= '<div class="category-posts-wrapper"><div class="category-posts-list">';

	$output .= filtro_categoria_bandi( 104 ,$output);
	$output .= '</div>';

	$output .= '<div class="category-posts-filter">

    <a class="category-posts-filter-button d-flex justify-content-between" data-bs-toggle="collapse"
        href="#collapse-download-iperpro-links" role="button" aria-expanded="false"
        aria-controls="collapse-iperpro-links">
        <span>Tipologie</span>
        <i id="chevron-iperpro-links" class="et-down-arrow et-icon"></i>
    </a>

    <div class="collapse show" id="collapse-download-iperpro-links">
        <div class="category-posts-filter-list">
            <a class="category-posts-filter-link tag-all active-strong" style="display: block;" data-cat-id="104"
                href="?categoria=104">Tutte le tipologie</a>';

	$output .= '<div>'. custom_category_filter_bandi() .'</div>'; 

	$output .= '    </div> 
                  </div> 
                ';

	$output .= '<div class="category-posts-filter-date-wrapper">
                            <p class="category-posts-filter-button flex justify-content-between">
                                <span>Data di inizio</span> 
                                <i id="chevron-iperpro-links" class="et-calendar et-icon"></i>
                            </p>
                            <input id="data-inizio-bando-input" type="date" name="inizio">
                        </div>
                        <div class="category-posts-filter-date-wrapper">
                            <p class="category-posts-filter-button flex justify-content-between">
                                <span>Data di scadenza</span> 
                                <i id="chevron-iperpro-links" class="et-calendar et-icon"></i>
                            </p>
                            <input id="data-scadenza-bando-input" type="date" name="scadenza">
                        </div>';            

	$output .= '</div>
	         </div>';
	wp_reset_postdata();
	return $output;
}

add_shortcode( 'category_posts_bandi', 'display_posts_by_category_bandi' );
