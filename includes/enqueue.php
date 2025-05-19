<?php
/*
 * Lädt JS und CSS für Editor und Frontend.
 *
 * Wird verwendet, wenn keine automatische Einbindung über block.json erfolgt.
 * - Lädt editor.js und editor.css im Block-Editor
 * - Lädt frontend.js und frontend-style.css im öffentlichen Bereich
 */

defined('ABSPATH') || exit;

add_action('enqueue_block_assets', function () {
    $base = plugin_dir_url(__DIR__); 
    $path = plugin_dir_path(__DIR__);

    wp_enqueue_script(
        'beitragsauszeichnung-editor',
        $base . 'build/editor.js',
        ['wp-plugins', 'wp-edit-post', 'wp-components', 'wp-element', 'wp-data'],
        filemtime($path . 'build/editor.js'),
        true
    );


    wp_enqueue_style(
        'beitragsauszeichnung-editor-style',
        $base . 'build/editor-style.css',
        [],
        filemtime($path . 'build/editor-style.css')
    );
});




// Frontend-Assets
add_action('wp_enqueue_scripts', function () {
    $base = plugin_dir_url(__DIR__);
    $path = plugin_dir_path(__DIR__);

    wp_enqueue_script(
        'beitragsauszeichnung-frontend',
        $base . 'build/frontend.js',
        [],
        filemtime($path . 'build/frontend.js'),
        true
    );

    wp_enqueue_style(
        'beitragsauszeichnung-frontend-style',
        $base . 'build/frontend-style.css',
        [],
        filemtime($path . 'build/frontend-style.css')
    );
});
