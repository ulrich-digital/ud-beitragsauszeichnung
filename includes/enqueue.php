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
        'beitragsauszeichnung-editor-script',
        $base . 'build/editor-script.js',
        ['wp-plugins', 'wp-edit-post', 'wp-components', 'wp-element', 'wp-data'],
        filemtime($path . 'build/editor-script.js'),
        true
    );

wp_localize_script(
	'beitragsauszeichnung-editor-script',
	'udHighlightSettings',
	[
		'enabledPostTypes' => get_option('ud_highlight_enabled_post_types', ['post']), // Fallback
	]
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
        'beitragsauszeichnung-frontend-script',
        $base . 'build/frontend-script.js',
        [],
        filemtime($path . 'build/frontend-script.js'),
        true
    );

    wp_enqueue_style(
        'beitragsauszeichnung-frontend-style',
        $base . 'build/frontend-style.css',
        [],
        filemtime($path . 'build/frontend-style.css')
    );
});
