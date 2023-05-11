<?php
/*
* Plugin Name: IperProgetto
* Plugin URI: https://www.iperprogetto.it/
* Description: Marketplace di iperprogetto.
* Version: 1.0
* Author: Promitex S.R.L.
* Author URI: https://www.promitex.it/
*/

// Ottieni il percorso completo della cartella "assets"
$assets_dir = plugin_dir_path(__FILE__) . 'assets/php/';
//echo $assets_dir;

// Ottieni un elenco di tutti i file nella cartella "assets"
$files = glob($assets_dir . '*.php');
//var_dump($files);

// Includi ogni file trovato
foreach ($files as $file) {
    // Percorso del file

    // Divide il percorso del file in base al separatore "/"
    $path_parts = explode("/", $file);

    // Cerca la posizione della stringa "plugins" nell'array
    $plugins_pos = array_search("assets", $path_parts);

    // Unisci gli elementi dell'array dal "plugins" al nome del plugin
    $plugin_path = implode("/", array_slice($path_parts, $plugins_pos + 1, 2));

    // Stampa il percorso del plugin
    include 'assets/' . $plugin_path;
    //echo 'assets/' . $plugin_path . '<br>';
}
