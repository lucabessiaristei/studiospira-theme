<?php

if (!defined('ABSPATH')) exit;

add_action('init', function() {
    if (get_role('sp_content_editor')) return;

    $editor = get_role('editor');
    if (!$editor) return;

    add_role('sp_content_editor', 'Redattore contenuti', $editor->capabilities);
});

add_action('admin_menu', function() {
    if (!current_user_can('sp_content_editor')) return;

    remove_menu_page('edit.php');           // Articoli (blog)
    remove_menu_page('edit-comments.php');  // Commenti
    remove_menu_page('tools.php');          // Strumenti
}, 999);
