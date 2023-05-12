<?php

function get_categories_product_vendor($vendor_id)
{

	// Crea una nuova istanza di WP_Query per recuperare tutti i prodotti del venditore
	$args = array(
		'user_id' => $vendor_id,
		'post_type' => 'product',
		'meta_query' => array(
			array(
				'key' => 'vendor_user', // Sostituisci con il nome dell'ACF che hai creato
				'compare' => '='
			)
		)
	);

	$products_query = new WP_Query( $args );

	// Verifica se ci sono prodotti associati al venditore
	if ( $products_query->have_posts() ) {

		// Crea un array vuoto per le categorie
		$categories = array();

		// Itera attraverso tutti i prodotti e recupera le categorie di ognuno
		while ( $products_query->have_posts() ) {
			$products_query->the_post();
			$product_id = get_the_ID();
			$product_categories = wp_get_post_terms( $product_id, 'product_cat' );

			// Aggiungi le categorie dell'attuale prodotto all'array delle categorie
			foreach ( $product_categories as $category ) {
				if ( ! in_array( $category, $categories ) ) {
					$categories[] = $category;
				}
			}
		}

		// Ripristina l'ambiente globale di WordPress
		wp_reset_postdata();

		return $categories;

	} else {
		// Nessun prodotto associato al venditore trovato
		echo "Nessun prodotto associato al venditore.";
	}
}

function vendor_list(){

?>


<style>
    
    .btn-drop-iperpro-links {
        width: 100%;
        border-width: 0px;
        background-color: #1D3C6E;
        padding: 9px 15px 8px 15px;
        color: white;
        font-size: 15px;
        border-radius: 15px;
    }
    
    #collapse-download-iperpro-links {
        padding: 10px 0px 10px 25px;
        max-width: 190px;
    }
    
    .btn-drop-iperpro-links:focus, .btn-drop-iperpro-links:hover {
        color: white;
    }

	.vendors-container-iperpro {
		display: flex;
		column-gap: 25px;
		flex-wrap: wrap;
		min-height: 500px;
	}

	.vendor-card-iperpro {
		width: 140px;
		display: flex;
		flex-direction: column;
		align-items: center;
		transition: all .2s ease-in-out;
	}

	.vendor-card-iperpro:hover { transform: scale(1.1); }

	.vendor-card-logo-iperpro {
		border: solid #ababab33 1px;
		border-radius: 11px;
		width: 100%;
		height: 140px;
		background-size: contain !important;
		background-position: center !important;
		background-repeat: no-repeat !important;
	}

	.vendor-card-name-iperpro {
		padding: 5px;
		text-align: center;
		color: #303030;
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
	
</style>

<?php

	$roles = get_users(array('role' => 'venditore'));
	$vendorCards = '';
	foreach ($roles as $role) 
	{		
		$vendorCategories = get_categories_product_vendor($role->ID);
		$vendorCategoriesIds = '';

		foreach ($vendorCategories as $vendorCategory) {
			$vendorCategoriesIds .= $vendorCategory->term_id . '~';
		}

		$vendorCards .= '<a href="https://iperprogetto.it/vetrina/?venditore='. $role->user_login .'" class="vendor-card-iperpro" data-categories-vendor="'.$vendorCategoriesIds.'" data-company-name="'. $billing_company = get_user_meta( $role->ID, 'billing_company', true ) .'">
								<div class="vendor-card-logo-iperpro" style="background: url('.$campo_personalizzato = get_field('logo_venditore', 'user_' . $role->ID).');">
							</div>
							<div class="vendor-card-name-iperpro">
								<p>' . $billing_company = get_user_meta( $role->ID, 'billing_company', true ) .  '</p>
							</div>
		   				</a>';
	}

	$vendorsContainer = '<div class="vendors-container-iperpro">'.$vendorCards.'</div><div class="pagination-wrapper"><div id="pagination"></div></div></div>';
	
	?>
<script>

	// paginazione
	jQuery(document).ready(function($) {
		var pageSize = 12; // Numero di elementi da mostrare per pagina
		var pageCount = Math.ceil($('.vendor-card-iperpro').length / pageSize); // Calcola il numero totale di pagine necessarie
		var currentPage = 1; // Pagina corrente

		// Aggiungi i pulsanti di navigazione delle pagine
		$('#pagination').append('<button class="page_button"><a href="#" class="page-link" data-page="first">&laquo;</a></button>');
		$('#pagination').append('<button class="page_button"><a href="#" class="page-link" data-page="prev">&lsaquo;</a></button>');
		for(var i = 1; i <= pageCount; i++) {
			if(i == 1 || i == pageCount || (i >= currentPage - 1 && i <= currentPage + 1)) {
				$('#pagination').append('<button class="page_button"><a href="#" class="page-link' + (i == currentPage ? ' active-iper' : '') + '" data-page="' + i + '">' + i + '</a></button>');
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
			var pageSize = 12// Numero di elementi da mostrare per pagina
			var start = (page - 1) * pageSize;
			var end = start + pageSize;

			// Nascondi tutti gli elementi e mostra solo quelli nella pagina corrente
			$('.vendor-card-iperpro').hide();
			$('.vendor-card-iperpro').slice(start, end).show();
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

				// Aggiungi la classe "active-iper" al pulsante della pagina corrente
				if (range[i] === currentPage) {
					pageItem.addClass('active-iper');
				}

				pagination.find('.pagination-next').before(pageItem);
			}

			// Aggiorna la classe "disabled" per i pulsanti di navigazione precedente e successivo
			pagination.find('.pagination-prev').toggleClass('disabled', currentPage === 1);
			pagination.find('.pagination-next').toggleClass('disabled', currentPage === totalPages);
		}

	});


</script>
<?php 

	echo $vendorsContainer;
}

function vendor_searchbar() {

?>

<style>
	
	.brand-search-container-iperpro #brand-search-iperpro {
		border: none;
		background: #F5F5F5;
		border-radius: 10px;
	}

</style>
<?php
	$output = '<div class="brand-search-container-iperpro">';
	$output .= '<input type="text" id="brand-search-iperpro" placeholder="Cerca un brand">';
	$output .= '</div>';
	return $output;
}


function category_filter()
{

	$categories = get_terms( array(
		'taxonomy' => 'product_cat',
		'hide_empty' => false,
		'parent' => 0,
	) );


?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<style>
	
	div.category-list-iperpro {
		background: #DBE3EB;
		padding: 25px;
		list-style: none;
		border-radius: 15px;
		display: flex;
		flex-direction: column;
		grid-gap: 10px;
	}
	
	div.category-list-iperpro .collapse-content {
		display: flex;
		flex-direction: column;
		    grid-gap: 5px;
	}

	div.category-list-iperpro .collapse-content > a {
       color: #303030;
	}
	
	.category-column-pd .elementor-widget-wrap {
		padding-left: 15px !important;
	}


</style>

	<div class="category-list-iperpro" > 

			<a class="btn-drop-iperpro-links d-flex justify-content-between" data-bs-toggle="collapse" href="#collapse-download-iperpro-links" role="button"
			   aria-expanded="false" aria-controls="collapse-iperpro-links">
				<span>Categorie</span> 
				<i id="chevron-iperpro-links" class="et-icon et-down-arrow" style="padding-top: 3px;"></i>
			</a>

         <div class="collapse show"  id="collapse-download-iperpro-links">
             <div class="collapse-content">
				<?php

				echo '<a id="show-all1"><strong>Tutte le categorie</strong></a>';
				foreach ( $categories as $category ) 
				{
					if($category->name != 'Uncategorized')
						echo '<a class="category-filter" data-cat-id="'.$category->term_id.'">' . $category->name . '</a>';
				}
			?>
			 </div>
	     </div>
     </div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<style>
    
    .btn-drop-iperpro-links {
        width: 100%;
        border-width: 0px;
        background-color: #1D3C6E;
        padding: 9px 15px 8px 15px;
        color: white;
        font-size: 15px;
        border-radius: 10px;
    }
    
    #collapse-download-iperpro-links {
        padding: 10px 0px 10px 25px;
        max-width: 190px;
    }
    
    .btn-drop-iperpro-links:focus, .btn-drop-iperpro-links:hover {
        color: white;
    }
    
</style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
        <script>
            
            const dropCollapsibleIperproLinks = document.getElementById('collapse-download-iperpro-links')
            
            const chevronIperproLinks = document.getElementById('chevron-iperpro-links')
            
            dropCollapsibleIperproLinks.addEventListener('hidden.bs.collapse', event => {
              chevronIperproLinks.classList.remove("et-up-arrow")
              chevronIperproLinks.classList.add("et-down-arrow")
            })
            
            dropCollapsibleIperproLinks.addEventListener('shown.bs.collapse', event => {
              chevronIperproLinks.classList.remove("et-down-arrow")
              chevronIperproLinks.classList.add("et-up-arrow")
            })

		jQuery(document).ready(function($) {

			var debounceTimer;
			$("#brand-search-iperpro").on("keyup", function() {
				clearTimeout(debounceTimer);
				debounceTimer = setTimeout(function() {
					var value = $("#brand-search-iperpro").val().toLowerCase();
					$("a[data-company-name]").filter(function() {
						$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
					});
				}, 500); 
			});

			$(".category-filter").click(function(){
				var category = $(this).text().trim(); // ottieni il testo dell'elemento cliccato e rimuovi gli eventuali spazi bianchi
				var categoryId = $(this).attr('data-cat-id');
				$("[data-categories-vendor]").each(function() {
					var categories = $(this).attr("data-categories-vendor").split("~"); // suddividi l'attributo in un array di categorie
					if ($.inArray(categoryId, categories) !== -1) { // cerca il valore selezionato nell'array di categorie
						$(this).show();
					} else {
						$(this).hide();
					}
				});
			});

			$("#show-all1").click(function() {
				$("[data-categories-vendor]").show();
			});

			$("#show-all2").click(function() {
				$("[data-categories-vendor]").show();
			});



			$(".letter-filter").click(function(){
				var letter = $(this).text().trim();
				var regex = new RegExp('^' + letter, 'i'); // regular expression che verifica se l'attributo inizia con la lettera o il numero selezionato, senza considerare la maiuscola o la minuscola
				var filtered = $("a[data-company-name]").filter(function() {
					var value = $(this).data("company-name");
					return regex.test(value);
				});
				$("[data-categories-vendor]").hide(); // nascondi tutti gli elementi con l'attributo
				filtered.show(); // mostra solo gli elementi che soddisfano la condizione
			});


			$(".number-filter").click(function(){
				var number = $(this).text().trim();
				var regex = new RegExp('^[0-9]' + number, 'i'); // regular expression che verifica se l'attributo inizia con il numero selezionato, senza considerare la maiuscola o la minuscola
				var filtered = $("a[data-company-name]").filter(function() {
					var value = $(this).data("company-name");
					return regex.test(value);
				});
				$("[data-categories-vendor]").hide(); // nascondi tutti gli elementi con l'attributo
				filtered.show(); // mostra solo gli elementi che soddisfano la condizione
			});

		});


	</script>



<?php

}

function name_filter()
{
?>

<style>
	a.letter-filter, a.number-filter {
		margin-right: 16px;
		color: #7A7A7A;
        
	}
	
	a.letter-filter, a.number-filter:hover {
		color: #7A7A7A !important;
	}

	div.filter-container-iperpro {
		font-size: 20px;
	}
</style>
<?php
	echo '<div class="filter-container-iperpro"><a class="number-filter">0-9 </a>';
	for ($i = 65; $i <= 90; $i++) {

		echo '<a class="letter-filter">'. chr($i) . " " .'</a>';

	}
	echo '<a id="show-all2" style="margin-right: 20px; color:#1D3C6E; font-size: 24px;">
		<svg width="17px" version="1.1" id="Livello_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
		 viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve">
	<style type="text/css">
		.st0{fill:#646371;}
		.st1{fill:#716F7C;}
		.st2{fill:#817D8A;}
		.st3{fill:#B2AEB6;}
	</style>
	<path class="st0" d="M16.9,40c-0.4-0.2-0.9-0.2-1.3-0.2c-4.4-0.7-8.1-2.6-11.2-5.7c-0.9-0.9-0.9-2.3,0-3.3c0.9-0.9,2.3-0.9,3.2,0
		c1.6,1.6,3.4,2.9,5.5,3.6c5.9,2.1,11.3,1.1,15.9-3.1c3.7-3.3,5.3-7.6,4.9-12.6c-0.5-6.5-5.4-12.2-11.8-13.6c-4.5-1-8.6-0.2-12.3,2.4
		c-0.1,0-0.1,0.1-0.3,0.2c0.7,0,1.3,0,1.9,0c1,0,1.9,0.7,2.1,1.7c0.4,1.4-0.6,2.7-2,2.9c-0.1,0-0.2,0-0.4,0c-2.5,0-4.9,0-7.4,0
		c-1.4,0-2.4-1-2.4-2.4c0-2.5,0-5.1,0-7.6c0-1.2,0.8-2.1,2-2.3C4.5,0,5.6,0.7,5.9,1.8C6,2.1,6,2.4,6,2.8C6,3.4,6,4,6,4.6
		c0.4-0.3,0.7-0.5,1.1-0.8c2.9-2,6-3.3,9.5-3.6c7-0.6,12.8,1.8,17.4,7.1c2.6,3.1,4.1,6.7,4.5,10.8c0.6,6.5-1.6,12-6.3,16.5
		c-3.1,3-6.9,4.7-11.2,5.2c-0.3,0-0.5,0-0.8,0.1c-0.2,0-0.4,0-0.6,0c-0.1-0.1-0.3-0.1-0.4,0c-0.3,0-0.6,0-0.9,0
		c-0.2-0.1-0.4-0.1-0.5,0c-0.1,0-0.3,0-0.4,0c-0.1,0-0.1,0-0.2,0C17.2,40,17.1,40,16.9,40z"/>
	<path class="st1" d="M17.8,40c0.2-0.1,0.4-0.1,0.5,0C18.2,40,18,40,17.8,40z"/>
	<path class="st2" d="M19.2,40c0.1-0.1,0.3-0.1,0.4,0C19.5,40,19.3,40,19.2,40z"/>
	<path class="st3" d="M17.3,40c0.1-0.1,0.1-0.1,0.2,0C17.4,40,17.3,40,17.3,40z"/>
	</svg>
</a>';
	echo '</div>';
}




add_shortcode('all_vendor_content', 'vendor_list');

add_shortcode('category_filter_jquery', 'category_filter');

add_shortcode('name_filter_jquery', 'name_filter');

add_shortcode('vendor_searchbar_shortcode', 'vendor_searchbar');