<?php

function add_btn_compare_single_product_iperpro() {
    $cookie_compare_name = 'xstore_compare_ids_0';
    $cookie_compare_value = isset($_COOKIE[$cookie_compare_name]) ? $_COOKIE[$cookie_compare_name] : '';
	global $product;
    // Dividi la stringa in base al carattere pipe
    $products_in_compare = explode('|', $cookie_compare_value);

    $opertion = 'add_to_compare';
	// Decodifica ogni stringa in un oggetto JSON
	$products = array();
	foreach ($products_in_compare as $product_in_compare_JSON) {
		$product_in_compare = json_decode(stripslashes($product_in_compare_JSON));
		$products[] = $product_in_compare;
		
		if($product->get_id() === $product_in_compare->id) {
            $opertion = 'remove_compare';
		}
		
	}
    
	$btn_compare = '<a href="/compare/?'.$opertion.'='.$product->get_id().'">'.$opertion.'</a>';

	return $btn_compare;
	
}
add_shortcode('show_cookie_value', 'add_btn_compare_single_product_iperpro');