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

// phases with published interventi: the Servizio filter options
function sp_get_used_servizi() {
    $intervento_ids = get_posts([
        'post_type'      => 'interventi',
        'post_status'    => 'publish',
        'fields'         => 'ids',
        'posts_per_page' => -1,
    ]);

    $used = [];
    foreach ($intervento_ids as $intervento_id) {
        foreach (sp_intervento_fasi($intervento_id) as $fase) $used[$fase->ID] = true;
    }

    return array_values(array_filter(sp_get_servizi_fasi(), function($fase) use ($used) {
        return isset($used[$fase->ID]);
    }));
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

// filtered lists: noindex, canonical to the full list
function sp_is_filtered_interventi() {
    return is_post_type_archive('interventi') && isset($_GET['servizio']);
}

add_filter('wp_robots', function($robots) {
    if (sp_is_filtered_interventi()) {
        $robots['noindex'] = true;
        $robots['follow']  = true;
        unset($robots['max-image-preview']);
    }
    return $robots;
});

add_action('wp_head', function() {
    if (sp_is_filtered_interventi()) {
        echo '<link rel="canonical" href="' . esc_url(get_post_type_archive_link('interventi')) . '">' . "\n";
    }
});

// --- Responsive images ----------------------------------------------------------
// every photo gets a srcset; $sizes is its displayed width per breakpoint

// container inner width per breakpoint (Bootstrap container minus px-4 = 3rem)
const SP_SIZES_FULL = '(min-width: 1400px) 1272px, (min-width: 1200px) 1092px, (min-width: 992px) 912px, (min-width: 768px) 672px, (min-width: 576px) 492px, calc(100vw - 3rem)';
// one card of the 3 / 2 / 1-column intervento grids
const SP_SIZES_CARD = '(min-width: 1400px) 380px, (min-width: 992px) 30vw, (min-width: 768px) 45vw, calc(100vw - 3rem)';

// $image: ACF image array or attachment ID. Lazy unless $attrs says otherwise.
function sp_image($image, $size, $sizes, $attrs = []) {
    $id = is_array($image) ? ($image['ID'] ?? 0) : (int) $image;
    if (!$id) return '';
    $attrs = array_merge(['sizes' => $sizes, 'loading' => 'lazy'], $attrs);
    if (is_array($image) && !isset($attrs['alt'])) $attrs['alt'] = $image['alt'] ?? '';
    return wp_get_attachment_image($id, $size, false, $attrs);
}

// page banner/cover: full width, high priority
function sp_banner_image($image) {
    return sp_image($image, '2048x2048', SP_SIZES_FULL, [
        'class'         => 'banner_photo',
        'loading'       => 'eager',
        'fetchpriority' => 'high',
    ]);
}
