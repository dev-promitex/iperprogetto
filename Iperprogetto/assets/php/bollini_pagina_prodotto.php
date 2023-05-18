<?php

function bollini_product_page( $atts ) {
    global $product;
   
   
   $ecosostenibile_value = get_field('ecosostenibile');
   $pur_approved_value = get_field('pur_approved');
   $innovativo_value = get_field('innovativo');
   
   $output = '<style>
   
   
       .stamps_wrapper {
       display: flex;
   }

   .stamp_item {
       width: 80px;
   }
   
   </style>';
   
   $output .= '<div class="stamps_wrapper">';
           // logica bollini 
           if(get_field('pur_approved') == true) {
               $output .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_pur-approved.svg"/>';
           }

           if(get_field('ecosostenibile') == true) {
               $output .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_eco.svg"/>';
           }

           if(get_field('innovativo') == true) {
               $output .= '<img class="stamp_item" src="/wp-content/uploads/2023/05/Icone_innovativo.svg"/>';
           }

           $output .= '</div>';
   
    return $output;
}
add_shortcode( 'bollini_product_page_shortcode', 'bollini_product_page' );