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

// hide native posts and comments from the admin
add_action('admin_menu', function() {
    remove_menu_page('edit.php');
    remove_menu_page('edit-comments.php');
});

add_action('admin_bar_menu', function($bar) {
    $bar->remove_node('new-post');
    $bar->remove_node('comments');
}, 999);

// not translatable: English title in "titolo_en"
add_filter('pll_get_post_types', function($post_types) {
    unset($post_types['articoli']);
    return $post_types;
}, 99);
