<?php
/**
 * Ergänzt die Klasse `is-highlighted__ud` je nach Beitrag (Metafeld `_is_highlighted`)
 * - Funktioniert für: core/post, core/post-content, ud/loop-block
 */
// Speichert ID des aktuell angezeigten Beitrags (z. B. in Einzelansicht)
add_action('the_post', function ($post) {
    $GLOBALS['_ud_current_post_id'] = $post->ID;
});
if (is_admin() && defined('REST_REQUEST') && REST_REQUEST) {
error_log("hello");
    error_log(print_r($block, true));
}

add_filter('the_content', function ($content) {
    global $post;
    if (!$post || get_post_meta($post->ID, '_is_highlighted', true) !== '1') {
        return $content;
    }

    return '<div class="is-highlighted__ud">' . $content . '</div>';
});


add_filter('render_block', function ($block_content, $block) {


if (($block['blockName'] ?? '') !== 'ud/loop-block') {
        return $block_content;
    }

    $target_blocks = ['core/post-content', 'core/post', 'ud/loop-block'];
    if (!in_array($block['blockName'], $target_blocks, true)) {
        return $block_content;
    }

    // robuster Fallback für postId
    $post_id = null;

    if (!empty($block['context']['postId'])) {
        $post_id = $block['context']['postId'];
    } elseif (!empty($GLOBALS['post']->ID)) {
        $post_id = $GLOBALS['post']->ID;
    } elseif (!empty($GLOBALS['_ud_current_post_id'])) {
        $post_id = $GLOBALS['_ud_current_post_id'];
    } elseif (get_the_ID()) {
        $post_id = get_the_ID();
    } else {
        $post_id = get_queried_object_id();
    }
    if (!$post_id || !get_post($post_id)) {
        error_log('⚠️ Kein gültiger post_id im render_block für Block ' . $block['blockName']);
        return $block_content;
    }

    $highlighted = get_post_meta($post_id, '_is_highlighted', true);
    if ($highlighted !== '1') return $block_content;

    // Einfügen der Klasse je nach Blocktyp
    if ($block['blockName'] === 'core/post-content') {
        return _add_is_highlighted_to_first_wp_block($block_content);
    }

    if ($block['blockName'] === 'core/post') {
        return _add_is_highlighted_to_wrapper_element($block_content);
    }

    return $block_content;
}, 10, 2);


/**
 * Fügt die Klasse `is-highlighted__ud` dem ersten WP-Block innerhalb des Inhalts hinzu
 */
function _add_is_highlighted_to_first_wp_block($content) {
    return preg_replace(
        '/(<(?:div|p|section|article)[^>]*class="[^">]*)"/',
        '$1 is-highlighted__ud"',
        $content,
        1
    );
}

/**
 * Fügt `is-highlighted__ud` Wrapper-Elementen wie <article> oder <li> hinzu
 */
function _add_is_highlighted_to_wrapper_element($content) {
    return preg_replace(
        '/(<(article|section|li)[^>]*class=")([^"]*)"/',
        '$1$3 is-highlighted__ud_ud"',
        $content,
        1
    );
}
/**
 * Spezialfall für ud/loop-block – einzelne <li class="post-XYZ">
 */
add_filter('render_block', function ($block_content, $block) {
	if (($block['blockName'] ?? '') !== 'ud/loop-block') return $block_content;

	return preg_replace_callback(
		'/<li([^>]*)class="([^"]*post-(\d+)[^"]*)"(.*?)<\/li>/is',
		function ($matches) {
			$li_attrs = $matches[1];
			$classes  = $matches[2];
			$post_id  = (int) $matches[3];
			$inner    = $matches[4];

			if (get_post_meta($post_id, '_is_highlighted', true) !== '1') {
				return $matches[0];
			}

			// Statt parse_blocks → einfache Textprüfung auf relevante Klassen
			if (
				strpos($inner, 'wp-block-ud-news-loop-content') !== false ||
				strpos($inner, 'wp-block-ud-event-loop-content') !== false ||
				strpos($inner, 'wp-block-group') !== false ||
				strpos($inner, 'wp-block') !== false
			) {
				$classes .= ' is-highlighted__ud';
			}

			return '<li' . $li_attrs . 'class="' . esc_attr($classes) . '"' . $inner . '</li>';
		},
		$block_content
	);
}, 10, 2);



