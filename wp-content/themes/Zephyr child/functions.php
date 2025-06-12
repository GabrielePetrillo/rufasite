<?php
function my_child_theme_scripts() {
    // Carica lo script.js dal child theme
    wp_enqueue_script(
        'child-custom-script', // nome handle
        get_stylesheet_directory_uri() . '/scripts.js', // percorso
        array(), // dipendenze (es: array('jquery'))
        null, // versione
        true // in footer
    );
}
add_action('wp_enqueue_scripts', 'my_child_theme_scripts');







