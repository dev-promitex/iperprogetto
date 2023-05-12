<?php

function remove_my_account_orders( $menu_links ){
    unset( $menu_links['orders'] );
    return $menu_links;
}

add_filter( 'woocommerce_account_menu_items', 'remove_my_account_orders' );
