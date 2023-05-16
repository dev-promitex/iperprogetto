<?php

function start_session()
{
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'start_session', 1);

// Funzione per aggiungere l'ID del prodotto visitato nella sessione
function aggiungi_prodotto_visitato()
{

    if (is_singular('product')) {
        // Verifica se la pagina corrente è una pagina di prodotto
        global $post;
        $product_id = $post->ID;
        $visited_products = isset($_SESSION['visited_products']) ? $_SESSION['visited_products'] : array();

        // Rimuovi eventuali duplicati
        $visited_products = array_diff($visited_products, array($product_id));

        // Aggiungi l'ID del prodotto visitato all'inizio dell'array
        array_unshift($visited_products, $product_id);

        // Mantieni solo gli ultimi 5 prodotti visitati
        $visited_products = array_slice($visited_products, 0, 8);

        // Salva l'array degli ID dei prodotti visitati nella sessione
        $_SESSION['visited_products'] = $visited_products;

        // echo "test";
    }
}
add_action('template_redirect', 'aggiungi_prodotto_visitato');


// Funzione per ottenere gli ultimi prodotti visitati
function get_ultimi_prodotti_visitati()
{

    include 'function_products/get_product_custom_box.php';

    $visited_products = isset($_SESSION['visited_products']) ? $_SESSION['visited_products'] : array();



    if (!empty($visited_products)) {

        echo '<h2 style="color: #1d3c6e">Ultime visite</h2>';

        get_product_custom_box($visited_products, 'last_visited_products');

        //la parte di script jquery che implementa lo slider sul div degli ultimi prodotti visitati è presente nella paginaecho '</div>';

        $prevArrow = '<button type="button" style="color: red; font-size: 16px;" class="slick-prev">←</button>';
        $nextArrow = '<button type="button" style="color: red; font-size: 16px;" class="slick-next">→</button>';


        


    echo '	
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick-theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
    
    <script>

    
    $(document).ready(function() {

        
        function run_slider() {
            $(".last_visited_products").slick({
                slidesToShow: 4,
                slidesToScroll: 4,
                arrows: true,
                prevArrow: '.$prevArrow.',
                nextArrow: '.$nextArrow.',
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                            infinite: true,
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        }

        run_slider();

    });
        

    
    </script>

    '; 

    } else {
        echo "nessun id prodotto salvato";
    }

    
}


add_shortcode('last_visited_product_session', 'get_ultimi_prodotti_visitati');
