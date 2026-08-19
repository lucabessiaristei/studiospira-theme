<?php

if (!defined('ABSPATH')) exit;

function sp_breadcrumb() {
    $items = [['label' => 'Home', 'url' => home_url('/')]];

    if (is_singular('interventi')) {
        $post_type_obj = get_post_type_object('interventi');
        if ($post_type_obj->has_archive) {
            $items[] = ['label' => $post_type_obj->labels->name, 'url' => get_post_type_archive_link('interventi')];
        }
        $items[] = ['label' => get_the_title(), 'url' => null];
    } elseif (is_post_type_archive('interventi')) {
        $items[] = ['label' => get_post_type_object('interventi')->labels->name, 'url' => null];
    } elseif (is_page()) {
        $ancestors = array_reverse(get_post_ancestors(get_the_ID()));
        foreach ($ancestors as $ancestor_id) {
            $items[] = ['label' => get_the_title($ancestor_id), 'url' => get_permalink($ancestor_id)];
        }
        $items[] = ['label' => get_the_title(), 'url' => null];
    }

    echo '<nav class="breadcrumb text-accent" aria-label="breadcrumb"><ol class="d-flex flex-wrap align-items-center gap-2">';
    foreach ($items as $i => $item) {
        $is_last = ($i === count($items) - 1);
        echo '<li class="d-flex align-items-center gap-2">';
        if (!$is_last && $item['url']) {
            echo '<a class="text-decoration-none" href="' . esc_url($item['url']) . '">' . esc_html($item['label']) . '</a>';
        } else {
            echo '<span aria-current="page">' . esc_html($item['label']) . '</span>';
        }
        echo '</li>';
    }
    echo '</ol></nav>';
}

function sp_get_used_servizi() {
    $intervento_ids = get_posts([
        'post_type'      => 'interventi',
        'post_status'    => 'publish',
        'fields'         => 'ids',
        'posts_per_page' => -1,
    ]);

    $servizio_ids = [];
    foreach ($intervento_ids as $intervento_id) {
        $servizi = get_field('servizi', $intervento_id);
        if (is_array($servizi)) {
            $servizio_ids = array_merge($servizio_ids, $servizi);
        }
    }
    $servizio_ids = array_unique($servizio_ids);

    if (!$servizio_ids) return [];

    return get_posts([
        'post_type'      => 'servizi',
        'post_status'    => 'publish',
        'post__in'       => $servizio_ids,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'posts_per_page' => -1,
    ]);
}

function sp_get_used_posizioni() {
    $intervento_ids = get_posts([
        'post_type'      => 'interventi',
        'post_status'    => 'publish',
        'fields'         => 'ids',
        'posts_per_page' => -1,
    ]);

    $posizioni = [];
    foreach ($intervento_ids as $intervento_id) {
        $posizione = get_field('posizione', $intervento_id);
        if ($posizione) $posizioni[$posizione] = $posizione;
    }

    ksort($posizioni);
    return array_values($posizioni);
}
