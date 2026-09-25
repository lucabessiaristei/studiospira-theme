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
        'supports'          => ['title', 'editor'],
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
        'meta_box_cb'       => false, // managed via the ACF taxonomy field instead, see acf-interventi.php
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

// destinazione_uso is deliberately NOT translatable: each term is shared by both
// languages, with the English name in the "nome_en" term field (see
// i18n-fields.php). Unset rather than just not added, so a stale tick in
// Polylang's settings can't turn it back on.
add_filter('pll_get_taxonomies', function($taxonomies) {
    unset($taxonomies['destinazione_uso']);
    return $taxonomies;
}, 99);

// project metadata (dates, budget, team, relationships) — not language-dependent, always copied identically
add_filter('pll_copy_post_metas', function($metas, $sync) {
    $interventi_metas = [
        'copertina',
        'anno_inizio',
        'anno_fine',
        'posizione',
        'committenza',
        'team',
        'budget',
        'categoria',
        'servizi',
        'articoli',
        'galleria',
    ];
    return array_merge($metas, $interventi_metas);
}, 10, 2);

// destinazione_uso terms are internal-only tags — no need for slug/description
add_action('admin_head-edit-tags.php', 'sp_hide_destinazione_uso_term_fields');
add_action('admin_head-term.php', 'sp_hide_destinazione_uso_term_fields');
function sp_hide_destinazione_uso_term_fields() {
    $screen = get_current_screen();
    if (!$screen || $screen->taxonomy !== 'destinazione_uso') return;
    ?>
    <style>
        .term-slug-wrap,
        .term-description-wrap { display: none; }
    </style>
    <?php
}
