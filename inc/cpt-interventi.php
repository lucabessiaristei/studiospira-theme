<?php

add_action('init', function() {
    register_post_type('interventi', [
        'labels' => [
            'name'               => 'Interventi',
            'singular_name'      => 'Intervento',
            'add_new_item'       => 'Aggiungi intervento',
            'edit_item'          => 'Modifica intervento',
            'view_item'          => 'Visualizza intervento',
            'search_items'       => 'Cerca interventi',
            'not_found'          => 'Nessun intervento trovato',
        ],
        'public'            => true,
        'has_archive'       => true,
        'show_in_rest'      => true,
        'supports'          => ['title', 'editor', 'thumbnail'],
        'menu_icon'         => 'dashicons-building',
        'rewrite'           => ['slug' => 'interventi'],
    ]);

    register_taxonomy('destinazione_uso', 'interventi', [
        'labels' => [
            'name'          => 'Destinazioni d\'uso',
            'singular_name' => 'Destinazione d\'uso',
            'all_items'     => 'Tutte le destinazioni',
            'add_new_item'  => 'Aggiungi destinazione',
            'edit_item'     => 'Modifica destinazione',
        ],
        'hierarchical'      => false,
        'public'            => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'destinazione-uso'],
    ]);

    register_post_type('servizi', [
        'labels' => [
            'name'               => 'Servizi',
            'singular_name'      => 'Servizio',
            'add_new_item'       => 'Aggiungi servizio',
            'edit_item'          => 'Modifica servizio',
            'view_item'          => 'Visualizza servizio',
        ],
        'public'            => true,
        'has_archive'       => false,
        'show_in_rest'      => true,
        'supports'          => ['title'],
        'menu_icon'         => 'dashicons-list-view',
        'rewrite'           => ['slug' => 'servizi'],
    ]);
});

// register CPTs with Polylang so they are translatable
add_filter('pll_get_post_types', function($post_types) {
    $post_types['interventi'] = 'interventi';
    $post_types['servizi']    = 'servizi';
    return $post_types;
});
