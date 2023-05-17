<?php
class Walker_Category_Custom_lista_venditori extends Walker_Category
{

    function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0)
    {
        $pad = str_repeat('&nbsp;', $depth);
        $cat_name = strlen($category->name) > 20 ? substr($category->name, 0, 20) . '...' : $category->name;
        $output .= '<a href="' . get_permalink() . '/?categoria=' . $category->term_id . '" class="category-posts-filter-link tag-' . $category->slug . '" data-cat-id="' . $category->term_id . '" style="display: block;" title="' . $category->name . '">' .  $cat_name . '</a>';
        return $output;
    }
}

function vendor_list()
{
?>


    <style>
        .btn-drop-iperpro-links {
            width: 100%;
            border-width: 0px;
            background-color: #1D3C6E;
            padding: 9px 15px 8px 15px;
            color: white;
            font-size: 15px;
            border-radius: 15px;
        }

        #collapse-download-iperpro-links {
            padding: 10px 0px 10px 25px;
            max-width: 190px;
        }

        .btn-drop-iperpro-links:focus,
        .btn-drop-iperpro-links:hover {
            color: white;
        }

        .vendors-container-iperpro {
            display: flex;
            column-gap: 25px;
            flex-wrap: wrap;
            min-height: 500px;
        }

        .vendor-card-iperpro {
            width: 140px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all .2s ease-in-out;
        }

        .vendor-card-iperpro:hover {
            transform: scale(1.1);
        }

        .vendor-card-logo-iperpro {
            border: solid #ababab33 1px;
            border-radius: 11px;
            width: 100%;
            height: 140px;
            background-size: contain !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
        }

        .vendor-card-name-iperpro {
            padding: 5px;
            text-align: center;
            color: #303030;
        }


        /*Paginazione */

        .pagination-wrapper {
            display: flex;
            justify-content: flex-end;
        }

        .page_button {
            background-color: #f5f5f5;
            border: solid;
            border-radius: 5px;
            border-color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .page_button:hover {
            background-color: #1D3C6E;
        }

        .page_button .page-link {
            color: #1D3C6E;
            font-size: 20px;
        }

        .page_button:hover .page-link {
            color: #ffffff;
        }


        .pagination-wrapper {
            display: flex;
            justify-content: flex-end;
        }

        .page-numbers {
            background-color: #d9d9d9;
            border: solid;
            border-color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: #1D3C6E;
            display: flex;
            font-size: 16px;
            justify-content: center;
            align-items: center;
        }

        .page-numbers:hover,
        .page-numbers.current {
            background-color: #1D3C6E;
            color: #ffffff;

        }
    </style>

    <?php
    $filteredLetter = isset($_GET['lettera']) ? $_GET['lettera'] : '';
    $filteredCategory = isset($_GET['categoria']) ? $_GET['categoria'] : '';


    $per_page = 12; // Numero di utenti per pagina
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // Numero di pagina corrente

    $args = array(
        'role' => 'venditore',
        'number' => $per_page,
        'paged' => $paged,
    );

    $users_query = new WP_User_Query($args);

    $roles = $users_query->get_results();


    $vendorCards = '';


    foreach ($roles as $role) {

        $brand = get_user_meta($role->ID, 'billing_company', true);
        $firstLetter = strtoupper(substr($brand, 0, 1));

        if ($filteredLetter !== '' && $firstLetter !== strtoupper($filteredLetter)) {

            continue;
        }

        if ($filteredCategory !== '') {


            $args = array(
                'post_type' => 'product',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'term_id',
                        'terms' => $filteredCategory
                    )
                ),
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'vendor_user',
                        'value' => $role->ID,
                        'compare' => '='
                    )
                )
            );


            $products = get_posts($args);

            if (empty($products)) {

                continue;
            }
        }
        $vendorCards .= '<a href="https://iperprogetto.it/vetrina/?venditore=' . $role->user_login . '" class="vendor-card-iperpro" data-company-name="' . $brand . '">
                        <div class="vendor-card-logo-iperpro" style="background: url(' . $campo_personalizzato = get_field('logo_venditore', 'user_' . $role->ID) . ');"></div>
                        <div class="vendor-card-name-iperpro">
                            <p>' . $brand . '</p>
                        </div>
                    </a>';
    }

    $vendorsContainer = '<div class="vendors-container-iperpro">' . $vendorCards . '</div></div>';


    $total_pages = $users_query->get_total() / $per_page;


    $vendorsContainer .= '<div class="pagination-wrapper"><div class="pagination">' . paginate_links(array(
        'total' => ceil($total_pages),
        'current' => $paged,
        'prev_next' => true,
        'prev_text' => '←',
        'next_text' => '→',
        'mid_size'  => 1,
        'end_size' => 1,


    )) . '</div></div>';

    wp_reset_postdata();

    echo $vendorsContainer;
}


// Registra e localizza gli script AJAX necessari
function register_custom_scripts()
{
    wp_register_script('custom-ajax-script', get_template_directory_uri() . '/js/custom-ajax-script.js', array('jquery'), '1.0', true);
    wp_localize_script('custom-ajax-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    wp_enqueue_script('custom-ajax-script');
}
add_action('wp_enqueue_scripts', 'register_custom_scripts');

// Aggiungi la funzione per la chiamata AJAX
function search_sellers_callback()
{
    $search_query = $_POST['search_query'];

    $args = array(
        'role'    => 'venditore',
        'search'  => '*' . esc_attr($search_query) . '*',
    );
    $sellers = get_users($args);

    $response = array();

    foreach ($sellers as $seller) {

        $response[] = array(
            'name' => $seller->display_name,
            'url' => $seller->user_login,
            //'url'  => get_author_posts_url($seller->ID),
        );
    }

    wp_send_json($response);
}
add_action('wp_ajax_search_sellers', 'search_sellers_callback');
add_action('wp_ajax_nopriv_search_sellers', 'search_sellers_callback');


function vendor_searchbar()
{
    ?>

    <style>
        .custom-search-wrapper .custom-search-inputs-wrapper {
            background: whitesmoke;
            display: flex;
            border-radius: 15px;

        }

        .custom-search-wrapper .custom-search-inputs-wrapper #search-sellers-input {
            border: none;
            background: none;
        }

        .custom-search-wrapper .custom-search-inputs-wrapper #custom-search-button {
            min-width: 50px;
            background: none;
            border: none;
        }


        .custom-search-wrapper #search-sellers-results-wrapper {
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

        .custom-search-wrapper #close-search-sellers-results {
            padding: 5px;
            display: flex;
            justify-content: end;
        }

        .custom-search-wrapper #close-search-sellers-results>div {
            background: #1e3c6d;
            height: 25px;
            width: 25px;
            color: white;
            border-radius: 100px;
            font-size: initial;
            cursor: pointer;
            text-align: center;

            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;

        }

        .custom-search-wrapper #search-sellers-results {
            max-height: 400px;
            overflow-y: auto;
        }

        .custom-search-wrapper #search-sellers-results .result-item {
            padding: 10px;
        }

        .custom-search-wrapper #search-sellers-results .result-item .result-item-content {
            padding: 10px;
            border-radius: 15px;

        }

        .custom-search-wrapper #search-sellers-results .result-item .result-item-content a {
            color: #1e3c6d;
        }

        .custom-search-wrapper #search-sellers-results .result-item .result-item-content:hover {
            background: #dae3eb;
        }



        .custom-search-wrapper #search-sellers-results .result-item:not(:last-child) {
            border-bottom: solid #DBE3EB 1px;
        }

        .custom-search-wrapper #search-sellers-results .spinner-wrapper {
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
        #search-sellers-results {
            scrollbar-width: auto;
            scrollbar-color: #1e3c6d #ffffff;
        }

        /* Chrome, Edge, and Safari */
        #search-sellers-results::-webkit-scrollbar {
            width: 16px;
        }

        #search-sellers-results::-webkit-scrollbar-track {
            background: #ffffff;
        }

        #search-sellers-results::-webkit-scrollbar-thumb {
            background-color: #1e3c6d;
            border-radius: 10px;
            border: 3px solid #ffffff;
        }
    </style>

    <!-- <div class="brand-search-container-iperpro">
        <input type="text" id="search-sellers-input" placeholder="Cerca venditori">
    </div>

    <div id="search-sellers-results"></div> -->


    <div class="custom-search-wrapper">
        <div class="custom-search-inputs-wrapper">
            <input type="text" id="search-sellers-input" placeholder="Cerca">
            <button id="custom-search-button"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                    <path d="M23.64 22.176l-5.736-5.712c1.44-1.8 2.232-4.032 2.232-6.336 0-5.544-4.512-10.032-10.032-10.032s-10.008 4.488-10.008 10.008c-0.024 5.568 4.488 10.056 10.032 10.056 2.328 0 4.512-0.792 6.336-2.256l5.712 5.712c0.192 0.192 0.456 0.312 0.72 0.312 0.24 0 0.504-0.096 0.672-0.288 0.192-0.168 0.312-0.384 0.336-0.672v-0.048c0.024-0.288-0.096-0.552-0.264-0.744zM18.12 10.152c0 4.392-3.6 7.992-8.016 7.992-4.392 0-7.992-3.6-7.992-8.016 0-4.392 3.6-7.992 8.016-7.992 4.392 0 7.992 3.6 7.992 8.016z"></path>
                </svg>
            </button>
        </div>
        <div id="search-sellers-results-wrapper">
            <div id="close-search-sellers-results">
                <div>X</div>
            </div>
            <div id="search-sellers-results"></div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function($) {
            var searchInput = $('#search-sellers-input');
            var resultsContainer = $('#search-sellers-results');

            searchInput.on('input', function() {
                var searchQuery = searchInput.val();
                $.ajax({
                    url: ajax_object.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'search_sellers',
                        search_query: searchQuery
                    },
                    beforeSend: function() {

                        $("#search-sellers-results-wrapper").css("display", "block");
                        $("#search-sellers-results").html("<div class=\"spinner-wrapper\"><div class=\"lds-ring\"><div></div><div></div><div></div><div></div></div></div>");
                    },
                    success: function(response) {


                        resultsContainer.empty();
                        $("#search-sellers-results-wrapper").css("display", "block");
                        $("#search-sellers-results").html(response);
                        if (response.length > 0) {
                            $.each(response, function(index, seller) {

                                resultsContainer.append('<div class="result-item"><div class="result-item-content"><a href="https://iperprogetto.it/vetrina/?venditore=' + seller.url + '">' + seller.name + '</a></div></div>');
                            });
                        } else {
                            resultsContainer.append('<p>Nessun venditore trovato.</p>');
                        }
                    }
                });
            });

            $("#close-search-sellers-results").click(function() {
                $("#search-sellers-results-wrapper").css("display", "none");

            });


        });
    </script>
<?php
}


function category_filter()
{
    $args = array(
        'show_option_all' => '',
        'orderby' => 'name',
        'hierarchical' => true,
        'depth' => 0,
        'hide_empty' => 1, // Modifica qui
        'taxonomy' => 'product_cat',
        'walker' => new Walker_Category_Custom_lista_venditori,
        'title_li' => '',
    );
    ob_start();
    wp_list_categories($args);
    $categories = ob_get_clean();

?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        div.category-list-iperpro {
            background: #DBE3EB;
            padding: 25px;
            list-style: none;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            grid-gap: 10px;
        }

        div.category-list-iperpro .collapse-content {
            display: flex;
            flex-direction: column;
            grid-gap: 5px;
        }

        div.category-list-iperpro .collapse-content>a {
            color: #303030;
        }

        .category-column-pd .elementor-widget-wrap {
            padding-left: 15px !important;
        }

        .btn-drop-iperpro-links {
            width: 100%;
            border-width: 0px;
            background-color: #1D3C6E;
            padding: 9px 15px 8px 15px;
            color: white;
            font-size: 15px;
            border-radius: 10px;
        }

        #collapse-download-iperpro-links {
            padding: 10px 0px 10px 25px;
            max-width: 190px;
        }

        .btn-drop-iperpro-links:focus,
        .btn-drop-iperpro-links:hover {
            color: white;
        }

        .category-posts-filter-link,
        .category-posts-filter-link :hover {
            color: #303030 !important;
        }

        .active-strong {
            font-weight: bold;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <div class="category-list-iperpro">

        <a class="btn-drop-iperpro-links d-flex justify-content-between" data-bs-toggle="collapse" href="#collapse-download-iperpro-links" role="button" aria-expanded="false" aria-controls="collapse-iperpro-links">
            <span>Categorie</span>
            <i id="chevron-iperpro-links" class="et-icon et-down-arrow" style="padding-top: 3px;"></i>
        </a>

        <div class="collapse show" id="collapse-download-iperpro-links">
            <div class="collapse-content">
                <?php

                echo '<a id="show-all1" class="category-posts-filter-link" data-cat-id="all" href="' . get_permalink() . '">Tutte le categorie</a>';
                echo $categories;

                ?>
            </div>
        </div>
    </div>

    <script>
        const dropCollapsibleIperproLinks = document.getElementById('collapse-download-iperpro-links')

        const chevronIperproLinks = document.getElementById('chevron-iperpro-links')

        dropCollapsibleIperproLinks.addEventListener('hidden.bs.collapse', event => {
            chevronIperproLinks.classList.remove("et-up-arrow")
            chevronIperproLinks.classList.add("et-down-arrow")
        })

        dropCollapsibleIperproLinks.addEventListener('shown.bs.collapse', event => {
            chevronIperproLinks.classList.remove("et-down-arrow")
            chevronIperproLinks.classList.add("et-up-arrow")
        })

        jQuery(document).ready(function($) {

            $('.category-posts-filter').ready(function() {

                const catLinks = $('.category-posts-filter-link');
                const urlParams = new URLSearchParams(window.location.search);
                const category = urlParams.get('categoria') ? urlParams.get('categoria') : "all";


                $.each(catLinks, function(idx, elm) {



                    if (elm.dataset.catId == category) {

                        $(elm).addClass('active-strong');
                    }

                    if (elm.dataset.catId !== category) {

                        $(elm).removeClass('active-strong');
                    }
                })
            })
        });
    </script>



<?php


}

function name_filter()
{
?>

    <style>
        a.letter-filter,
        a.number-filter {
            margin-right: 16px;
            color: #7A7A7A;

        }

        a.letter-filter,
        a.number-filter:hover {
            color: #7A7A7A !important;
        }

        div.filter-container-iperpro {
            font-size: 20px;
        }
    </style>
<?php
    echo '<div class="filter-container-iperpro"><a class="number-filter">0-9 </a>';
    for ($i = 65; $i <= 90; $i++) {

        echo '<a class="letter-filter" href="' . get_permalink() . '/?lettera=' . chr($i) . '">' . chr($i) . " " . '</a>';
    }
    echo '<a style="margin-right: 20px; color:#1D3C6E; font-size: 24px;" href="' . get_permalink() . '">
            <svg width="17px" version="1.1" id="Livello_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
             viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve">
        <style type="text/css">
            .st0{fill:#646371;}
            .st1{fill:#716F7C;}
            .st2{fill:#817D8A;}
            .st3{fill:#B2AEB6;}
        </style>
        <path class="st0" d="M16.9,40c-0.4-0.2-0.9-0.2-1.3-0.2c-4.4-0.7-8.1-2.6-11.2-5.7c-0.9-0.9-0.9-2.3,0-3.3c0.9-0.9,2.3-0.9,3.2,0
            c1.6,1.6,3.4,2.9,5.5,3.6c5.9,2.1,11.3,1.1,15.9-3.1c3.7-3.3,5.3-7.6,4.9-12.6c-0.5-6.5-5.4-12.2-11.8-13.6c-4.5-1-8.6-0.2-12.3,2.4
            c-0.1,0-0.1,0.1-0.3,0.2c0.7,0,1.3,0,1.9,0c1,0,1.9,0.7,2.1,1.7c0.4,1.4-0.6,2.7-2,2.9c-0.1,0-0.2,0-0.4,0c-2.5,0-4.9,0-7.4,0
            c-1.4,0-2.4-1-2.4-2.4c0-2.5,0-5.1,0-7.6c0-1.2,0.8-2.1,2-2.3C4.5,0,5.6,0.7,5.9,1.8C6,2.1,6,2.4,6,2.8C6,3.4,6,4,6,4.6
            c0.4-0.3,0.7-0.5,1.1-0.8c2.9-2,6-3.3,9.5-3.6c7-0.6,12.8,1.8,17.4,7.1c2.6,3.1,4.1,6.7,4.5,10.8c0.6,6.5-1.6,12-6.3,16.5
            c-3.1,3-6.9,4.7-11.2,5.2c-0.3,0-0.5,0-0.8,0.1c-0.2,0-0.4,0-0.6,0c-0.1-0.1-0.3-0.1-0.4,0c-0.3,0-0.6,0-0.9,0
            c-0.2-0.1-0.4-0.1-0.5,0c-0.1,0-0.3,0-0.4,0c-0.1,0-0.1,0-0.2,0C17.2,40,17.1,40,16.9,40z"/>
        <path class="st1" d="M17.8,40c0.2-0.1,0.4-0.1,0.5,0C18.2,40,18,40,17.8,40z"/>
        <path class="st2" d="M19.2,40c0.1-0.1,0.3-0.1,0.4,0C19.5,40,19.3,40,19.2,40z"/>
        <path class="st3" d="M17.3,40c0.1-0.1,0.1-0.1,0.2,0C17.4,40,17.3,40,17.3,40z"/>
        </svg>
    </a>';
}

add_shortcode('all_vendor_content', 'vendor_list');

add_shortcode('category_filter_jquery', 'category_filter');

add_shortcode('name_filter_jquery', 'name_filter');

add_shortcode('vendor_searchbar_shortcode', 'vendor_searchbar');
