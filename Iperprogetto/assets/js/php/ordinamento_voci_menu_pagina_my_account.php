<?php

function custom_my_account_menu_order() {
    $menu_order = array(
        'dashboard'          => __( 'Dashboard', 'woocommerce' ),
		'note-salvate'        => __( 'Note salvate', 'woocommerce' ),
		'bp-messages'     => __( 'Messages', 'woocommerce' ),
		'wishlist'       => __( 'Preferiti', 'woocommerce' ),
		'compare'       => __( 'Confronta articoli/servizi', 'woocommerce' ), 
		'downloads'          => __( 'Downloads', 'woocommerce' ),
        //'edit-address'       => __( 'Indirizzo di fatturazione', 'woocommerce' ),
        'edit-account'       => __( 'Dettagli account', 'woocommerce' ),
        'customer-logout'    => __( 'Logout', 'woocommerce' ),
    );
    return $menu_order;
}
add_filter ( 'woocommerce_account_menu_items', 'custom_my_account_menu_order' );