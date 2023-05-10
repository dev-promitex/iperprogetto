<?php

function remove_class_style_default( $atts ) {
    ?>
    <script>
        jQuery(document).ready(function($) {
         $('.category-grid').removeClass('style-default');
        });
    </script>
    <?php
    }
    
    add_shortcode( 'shortcode_remove_class_style_default', 'remove_class_style_default' );
    