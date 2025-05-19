<?php
/*
 * Registriert das Post-Meta-Feld _is_highlighted:
 */

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
