<?php
/**
 * Plugin Name: Beitragsauszeichnung
 * Description: Ermöglicht es, Beiträge und benutzerdefinierte Inhaltstypen (CPTs) im Editor als „Hervorgehoben“ zu markieren. Fügt automatisch die CSS-Klasse „is-highlighted“ im Frontend-Markup hinzu und zeigt eine visuelle Hervorhebung im Editor.
 * Version: 1.1
 * Author: ulrich.digital
 * Author URI: https://ulrich.digital/
 * Text Domain: breadcrumb-block-ud
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// === Meta-Feld registrieren ===
add_action('init', function () {
    register_post_meta('', '_is_highlighted', [
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'auth_callback' => function () {
            return current_user_can('edit_posts');
        }
    ]);
});

// === Editor-JS & CSS einbinden ===
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_script(
        'beitragsauszeichnung-editor',
        plugin_dir_url(__FILE__) . 'build/index.js',
        ['wp-plugins', 'wp-edit-post', 'wp-components', 'wp-element', 'wp-data'],
        filemtime(plugin_dir_path(__FILE__) . 'build/index.js'),
        true
    );

    wp_enqueue_style(
        'beitragsauszeichnung-editor-style',
        plugin_dir_url(__FILE__) . 'editor.css',
        [],
        filemtime(plugin_dir_path(__FILE__) . 'editor.css')
    );
});

// === Frontend CSS einbinden ===

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'beitragsauszeichnung-frontend-style',
        plugin_dir_url(__FILE__) . 'frontend.css',
        [],
        filemtime(plugin_dir_path(__FILE__) . 'frontend.css')
    );
});


// === CSS-Klasse im Frontend an Beitrags-Wrapper anhängen ===
add_filter('post_class', function ($classes, $class, $post_id) {
    if (get_post_meta($post_id, '_is_highlighted', true) === '1') {
        $classes[] = 'is-highlighted';
    }
    return $classes;
}, 10, 3);

// === Optionale zusätzliche CSS-Klasse im Block-Markup einfügen (z. B. für FSE-Themes) ===
add_filter('render_block', function ($block_content, $block) {
    if (is_admin() || empty($block['blockName']) || empty($block_content)) {
        return $block_content;
    }

    $highlighted = false;

    // Für Archiv-/Loop-Beiträge
    if (in_array($block['blockName'], ['core/post', 'core/post-template', 'core/group', 'core/post-content'])) {
        $post_id = $block['context']['postId'] ?? null;
        if ($post_id && get_post_meta($post_id, '_is_highlighted', true) === '1') {
            $highlighted = true;
        }
    }

    // Für Einzelansicht
    if (is_singular() && $block['blockName'] === 'core/post-content') {
        global $post;
        if ($post && get_post_meta($post->ID, '_is_highlighted', true) === '1') {
            $highlighted = true;
        }
    }

    // CSS-Klasse anhängen
    if ($highlighted) {
        $tags = ['<div', '<article', '<section', '<li'];
        foreach ($tags as $tag) {
            if (strpos($block_content, $tag) !== false && strpos($block_content, 'is-highlighted') === false) {
                $block_content = preg_replace(
                    '/(' . preg_quote($tag, '/') . '[^>]*class=")([^"]*)"/',
                    '$1$2 is-highlighted"',
                    $block_content,
                    1
                );
                break;
            }
        }
    }

    return $block_content;
}, 10, 2);
