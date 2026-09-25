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

    // Articoli (blog) and Commenti are hidden for everyone, see cpt-articoli.php
    remove_menu_page('tools.php');          // Strumenti
}, 999);
