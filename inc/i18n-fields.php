<?php

// Destinazioni d'uso, articoli and pubblicazioni are NOT managed by Polylang:
// each exists once and is shared by both languages, with the English text
// kept in "_en" fields on the same edit screen. These helpers pick the right
// value for the current language, falling back to Italian when the English
// one is empty.

if (!defined('ABSPATH')) exit;

function sp_is_en() {
    return function_exists('pll_current_language') && pll_current_language() === 'en';
}

function sp_term_name($term) {
    if (sp_is_en()) {
        $en = get_term_meta($term->term_id, 'nome_en', true);
        if ($en !== '' && $en !== false) return $en;
    }
    return $term->name;
}

function sp_title_i18n($post_id) {
    if (sp_is_en()) {
        $en = get_field('titolo_en', $post_id);
        if ($en) return $en;
    }
    return get_the_title($post_id);
}

function sp_field_i18n($name, $post_id) {
    if (sp_is_en()) {
        $en = get_field($name . '_en', $post_id);
        if ($en) return $en;
    }
    return get_field($name, $post_id);
}
