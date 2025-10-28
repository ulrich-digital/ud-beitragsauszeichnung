<?php
/*
 * Registriert das Post-Meta-Feld _is_highlighted:
 */

function ud_register_highlight_meta() {
    $enabled_post_types = get_option('ud_highlight_enabled_post_types', []);
    if (!is_array($enabled_post_types) || empty($enabled_post_types)) return;

    foreach ($enabled_post_types as $post_type) {
        register_post_meta($post_type, '_is_highlighted', [
            'show_in_rest' => true,
            'single'       => true,
            'type'         => 'boolean',
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            },
        ]);
    }
}
add_action('init', 'ud_register_highlight_meta');
