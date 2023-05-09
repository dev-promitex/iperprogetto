<?php

add_filter('woocommerce_placeholder_img_src', 'custom_woocommerce_placeholder_img_src');

function custom_woocommerce_placeholder_img_src( $src ) {
	
	$src = 'https://iperprogetto.it/wp-content/plugins/woocommerce/assets/images/placeholder.png';
    return $src;

}