<?php

function custom_search_filter($query) {
	if ( !is_admin() && $query->is_main_query() ) {
		if ($query->is_search) {
			$query->set('post_type', 'post');
		}
	}
}

add_action('pre_get_posts','custom_search_filter');


function custom_search_shortcode($atts) {
	$atts = shortcode_atts( array(
		'category' => '',
	), $atts );

?>

<style>
	.custom-search-wrapper .custom-search-inputs-wrapper {
		background: whitesmoke;
		display: flex;
		border-radius: 15px;

	}

	.custom-search-wrapper .custom-search-inputs-wrapper #custom-search-value {
		border: none;
		background: none;
	}

	.custom-search-wrapper .custom-search-inputs-wrapper #custom-search-button {
		min-width: 50px;
		background: none;
		border: none;
	}


	.custom-search-wrapper #custom-search-results-wrapper  {
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

	.custom-search-wrapper #close-custom-search-results {
		padding: 5px;
		display: flex;
		justify-content: end;
	}

	.custom-search-wrapper #close-custom-search-results > div {
		background: #1e3c6d;
    height: 25px;
    width: 25px;
    color: white;
    border-radius: 100px;
    font-size: initial;
		cursor: pointer;
		text-align: center;
		
    font-size: 20px;

	}

	.custom-search-wrapper #custom-search-results {
		max-height: 400px;
		overflow-y: auto;
	}

	.custom-search-wrapper #custom-search-results .result-item {
		padding: 10px;
	}

	.custom-search-wrapper #custom-search-results .result-item .result-item-content {
		padding: 10px;
		border-radius: 15px;
		
	}	
	
	.custom-search-wrapper #custom-search-results .result-item .result-item-content a { 
	color: #1e3c6d;
	}

	.custom-search-wrapper #custom-search-results .result-item .result-item-content:hover {
		background: #dae3eb;
	}



	.custom-search-wrapper #custom-search-results .result-item:not(:last-child) {
		border-bottom: solid #DBE3EB 1px;
	}

	.custom-search-wrapper #custom-search-results .spinner-wrapper {
		width: 100%;
		display: flex;
		justify-content: center;
		padding: 20px;
	}
	
	.not-fount-results {
		text-align: center;
    color: #1e3c6d;
    font-size: 18px;
	}
	
	

	/* spinner loading */

	.lds-ring {
		display: inline-block;
		position: relative;
		width: 80px;
		height: 80px;
	}
	.lds-ring div {
		box-sizing: border-box;
		display: block;
		position: absolute;
		width: 64px;
		height: 64px;
		margin: 8px;
		border: 8px solid #1e3c6d;
		border-radius: 50%;
		animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
		border-color: #1e3c6d transparent transparent transparent;
	}
	.lds-ring div:nth-child(1) {
		animation-delay: -0.45s;
	}
	.lds-ring div:nth-child(2) {
		animation-delay: -0.3s;
	}
	.lds-ring div:nth-child(3) {
		animation-delay: -0.15s;
	}
	@keyframes lds-ring {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}

	/* ===== Scrollbar CSS ===== */
	/* Firefox */
	#custom-search-results {
		scrollbar-width: auto;
		scrollbar-color: #1e3c6d #ffffff;
	}

	/* Chrome, Edge, and Safari */
	#custom-search-results::-webkit-scrollbar {
		width: 16px;
	}

	#custom-search-results::-webkit-scrollbar-track {
		background: #ffffff;
	}

	#custom-search-results::-webkit-scrollbar-thumb {
		background-color: #1e3c6d;
		border-radius: 10px;
		border: 3px solid #ffffff;
	}
</style>
<?php

	$output = '<div class="custom-search-wrapper">';
	$output .= '<div class="custom-search-inputs-wrapper"><input type="text" id="custom-search-value" placeholder="Cerca">';
	$output .= '<button id="custom-search-button"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path d="M23.64 22.176l-5.736-5.712c1.44-1.8 2.232-4.032 2.232-6.336 0-5.544-4.512-10.032-10.032-10.032s-10.008 4.488-10.008 10.008c-0.024 5.568 4.488 10.056 10.032 10.056 2.328 0 4.512-0.792 6.336-2.256l5.712 5.712c0.192 0.192 0.456 0.312 0.72 0.312 0.24 0 0.504-0.096 0.672-0.288 0.192-0.168 0.312-0.384 0.336-0.672v-0.048c0.024-0.288-0.096-0.552-0.264-0.744zM18.12 10.152c0 4.392-3.6 7.992-8.016 7.992-4.392 0-7.992-3.6-7.992-8.016 0-4.392 3.6-7.992 8.016-7.992 4.392 0 7.992 3.6 7.992 8.016z"></path></svg></button></div>';
	$output .= '<div id="custom-search-results-wrapper"><div id="close-custom-search-results"><div>X</div></div><div id="custom-search-results"></div></div>';
	$output .= '</div>';

	$output .= '<script type="text/javascript">
	var timer = null;
	$("#custom-search-value").on("input", function() {

		var searchValue = $("#custom-search-value").val();
		var category = "'.esc_attr($atts['category']).'";
		// Se esiste un timer in esecuzione, cancellalo
		if (timer !== null) {
			clearTimeout(timer);
		}

		// Avvia un nuovo timer per avviare la chiamata Ajax dopo un secondo
		timer = setTimeout(function() {
			var query = $("#custom-search-value").val();

			// Effettua la chiamata Ajax solo se la query non è vuota
			if (query !== "") {
				$.ajax({
					type : "post",
					dataType : "html",
					url : "'.admin_url('admin-ajax.php').'",
					data : {action: "custom_search_ajax_handler", category: category, searchValue: searchValue },
					beforeSend: function() {
                        $("#custom-search-results-wrapper").css("display", "block");
						$("#custom-search-results").html("<div class=\"spinner-wrapper\"><div class=\"lds-ring\"><div></div><div></div><div></div><div></div></div></div>");
					},
					success: function(response) {
                        $("#custom-search-results-wrapper").css("display", "block");
						$("#custom-search-results").html(response);
					}
				});
			}
		}, 1000); // Imposta un debounce di 1 secondo
	});

	jQuery(document).ready(function($) {
		$("#custom-search-button").click(function() {
			var searchValue = $("#custom-search-value").val();
			var category = "'.esc_attr($atts['category']).'";
			$.ajax({
				type : "post",
				dataType : "html",
				url : "'.admin_url('admin-ajax.php').'",
				data : {action: "custom_search_ajax_handler", category: category, searchValue: searchValue },
				beforeSend: function() {
                    $("#custom-search-results-wrapper").css("display", "block");
					$("#custom-search-results").html("<div class=\"spinner-wrapper\"><div class=\"lds-ring\"><div></div><div></div><div></div><div></div></div></div>");
				},
				success: function(response) {
				    $("#custom-search-results-wrapper").css("display", "block");
					$("#custom-search-results").html(response);
				}
			});
		});

			$("#close-custom-search-results").click(function() {
				    $("#custom-search-results-wrapper").css("display", "none");

		});
	});</script>';
	return $output;
}

add_shortcode('custom_search_by_category', 'custom_search_shortcode');

function custom_search_ajax_handler() {
	$category = $_POST['category'];
	$searchValue = $_POST['searchValue'];
	$args = array(
		's' => $searchValue,
		'post_type' => 'post',
		'category_name' => $category
	);
	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			echo '<div class="result-item"><div class="result-item-content"><a href="'.get_permalink().'">'.get_the_title().'</a></div></div>';
		}
	} else {
		echo '<div class="not-fount-results">'.__('Nessun risultato trovato').'</div>';
	}

	wp_reset_postdata();
	die();
}
add_action( 'wp_ajax_custom_search_ajax_handler', 'custom_search_ajax_handler' );
add_action( 'wp_ajax_nopriv_custom_search_ajax_handler', 'custom_search_ajax_handler' );



