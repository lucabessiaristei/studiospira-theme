<?php

if (!defined('ABSPATH')) exit;

add_action('init', function() {
    register_post_type('pubblicazioni', [
        'labels' => [
            'name'          => 'Pubblicazioni',
            'singular_name' => 'Pubblicazione',
            'add_new_item'  => 'Aggiungi pubblicazione',
            'edit_item'     => 'Modifica pubblicazione',
        ],
        'public'       => true,
        'has_archive'  => false,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-book-alt',
        'supports'     => ['title', 'page-attributes', 'thumbnail'],
        'rewrite'      => ['slug' => 'pubblicazioni'],
    ]);
});

// deliberately NOT translatable: one pubblicazione serves both languages, with
// the English texts in the "_en" fields (see i18n-fields.php)
add_filter('pll_get_post_types', function($post_types) {
    unset($post_types['pubblicazioni']);
    return $post_types;
}, 99);
