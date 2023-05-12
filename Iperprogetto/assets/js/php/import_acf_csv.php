<?php

add_filter( 'woocommerce_csv_product_import_mapping_options', 'custom_acf_mapping_options' );

function custom_acf_mapping_options( $options ) {
    // Recupera il gruppo di campi ACF con ID 5906
    $group = acf_get_field_group(5906);
    // Recupera tutti i campi personalizzati definiti nel gruppo
    $fields = acf_get_fields($group['ID']);

    // Aggiunge ogni campo come opzione di mappatura nell'importatore di Woocommerce
    foreach ( $fields as $field ) {
        $options[ 'meta:' . $field['name'] ] = $field['label'];
    }

    return $options;
}


