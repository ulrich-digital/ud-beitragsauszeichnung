<?php
function ud_add_plugin_settings_page() {
    add_options_page(
        'Highlight Einstellungen',
        'Highlight Einstellungen',
        'manage_options',
        'ud-highlight-settings',
        'ud_render_highlight_settings_page'
    );
}
add_action('admin_menu', 'ud_add_plugin_settings_page');

function ud_render_highlight_settings_page() {
    $all_post_types = get_post_types(['public' => true], 'objects');
$enabled = (array) get_option('ud_highlight_enabled_post_types', []);

    echo '<div class="wrap">';
    echo '<h1>Highlight-Funktion Einstellungen</h1>';
    echo '<form method="post" action="options.php">';
    settings_fields('ud_highlight_settings_group');
    do_settings_sections('ud-highlight-settings');

    echo '<table class="form-table"><tr><th scope="row">Aktiv für Post-Types</th><td>';
    foreach ($all_post_types as $post_type) {
        if ($post_type->name === 'attachment') {
            continue; // Medien ausschließen
        }

        $checked = in_array($post_type->name, $enabled) ? 'checked' : '';
        echo "<label><input type='checkbox' name='ud_highlight_enabled_post_types[]' value='{$post_type->name}' $checked> {$post_type->label}</label><br>";
    }
    echo '</td></tr></table>';

    submit_button();
    echo '</form></div>';
}

function ud_register_highlight_settings() {
    register_setting('ud_highlight_settings_group', 'ud_highlight_enabled_post_types');
}
add_action('admin_init', 'ud_register_highlight_settings');
