<?php

if (!defined('ABSPATH')) exit;

add_action('init', function() {
    register_post_type('articoli', [
        'labels' => [
            'name'          => 'Articoli',
            'singular_name' => 'Articolo',
            'add_new_item'  => 'Aggiungi articolo',
            'edit_item'     => 'Modifica articolo',
        ],
        'public'       => true,
        'has_archive'  => false,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-media-document',
        'supports'     => ['title'],
        'rewrite'      => ['slug' => 'articoli'],
    ]);
});

// nasconde i post nativi WP dall'admin (non deregistrabili)
add_action('admin_menu', function() {
    remove_menu_page('edit.php');
});

add_action('admin_bar_menu', function($bar) {
    $bar->remove_node('new-post');
}, 999);
