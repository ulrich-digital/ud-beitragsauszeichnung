<?php

/**
 * Ergänzt die Klasse `is-highlighted` je nach Kontext:
 * - klassische Themes über post_class
 * - Block-Themes über render_block (core/post, core/post-content)
 * - eigene Loop-Blöcke über HTML-Manipulation
 */

/* =============================================================== *\ 
   Block-Themes: core/post-content & core/post
\* =============================================================== */
add_filter('render_block', function ($block_content, $block) {
    if (empty($block_content)) {
        return $block_content;
    }

    $post_id = $block['context']['postId'] ?? get_the_ID();
    if (!$post_id || !get_post($post_id)) {
        return $block_content;
    }

    $highlighted = get_post_meta($post_id, '_is_highlighted', true);
    if ($highlighted !== '1') {
        return $block_content;
    }

    /* =============================================================== *\ 
       1. Core-Single – Nur core/post-content
    \* =============================================================== */
    if ($block['blockName'] === 'core/post-content' && is_singular()) {
        $block_content = _add_is_highlighted_to_first_wp_block($block_content);
        return $block_content;
    }

    /* =============================================================== *\ 
       2. Core-Loop / Archiv – core/post
    \* =============================================================== */
    if ($block['blockName'] === 'core/post') {
        $block_content = _add_is_highlighted_to_wrapper_element($block_content);
        return $block_content;
    }
    return $block_content;
}, 10, 2);

// Hilfsfunktion: Klasse zum ersten Block im Post-Inhalt hinzufügen
function _add_is_highlighted_to_first_wp_block($content) {
    return preg_replace(
        '/(<div class="wp-block[^"]*)"/',
        '$1 is-highlighted"',
        $content,
        1
    );
}

// Hilfsfunktion: Wrapper-Element mit Klasse erweitern
function _add_is_highlighted_to_wrapper_element($content) {
    return preg_replace(
        '/(<(article|section|li)[^>]*class=")([^"]*)"/',
        '$1$3 is-highlighted"',
        $content,
        1
    );
}

/* =============================================================== *\
   3. Eigener Block: ud/loop-block mit <li class="post-123"> etc.
\* =============================================================== */
add_filter('render_block', function ($block_content, $block) {
    if (($block['blockName'] ?? '') !== 'ud/loop-block') {
        return $block_content;
    }

    $block_content = preg_replace_callback(
        '/<li([^>]*)class="([^"]*post-(\d+)[^"]*)"(.*?)<\/li>/is',
        function ($matches) {
            $li_attrs = $matches[1];
            $classes  = $matches[2];
            $post_id  = (int) $matches[3];
            $inner    = $matches[4];

            // Nur wenn Beitrag hervorgehoben ist
            if (get_post_meta($post_id, '_is_highlighted', true) === '1') {
                // Ersetze das erste wp-block-group innerhalb des <li>
                $inner = preg_replace(
                    '/<div([^>]*)class="([^"]*wp-block-group[^"]*)"/i',
                    '<div$1class="$2 is-highlighted"',
                    $inner,
                    1
                );
            }

            return '<li' . $li_attrs . 'class="' . $classes . '"' . $inner . '</li>';
        },
        $block_content
    );

    return $block_content;
}, 10, 2);
