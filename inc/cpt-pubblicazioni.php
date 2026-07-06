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

add_filter('pll_get_post_types', function($post_types) {
    $post_types['pubblicazioni'] = 'pubblicazioni';
    return $post_types;
});

// copia tutti i campi ACF (e meta nativi) alle traduzioni invece di tradurli
add_filter('pll_copy_post_metas', function($metas, $sync) {
    $pubblicazioni_metas = [
        'a_cura_di',
        'editore',
        'anno',
        'isbn',
        'pagine',
        'info',
        'tipo_url',
        'url',
        '_thumbnail_id',
        'menu_order',
    ];
    return array_merge($metas, $pubblicazioni_metas);
}, 10, 2);
