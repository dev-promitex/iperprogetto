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

        echo '<h2 style="font-size: 25px; color:#1d3c6e;">Ultime visite</h2>';

        echo do_shortcode('[product-carousel last_product_visited="' . implode(',', $visited_products) . '"]');

    } 
    
    else
    {
       // echo "nessun id prodotto salvato";
    }
}


add_shortcode('last_visited_product_session', 'get_ultimi_prodotti_visitati');
