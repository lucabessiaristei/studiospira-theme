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

// Fields that are the same in every language (e.g. the banner photo) are read
// from the default-language (Italian) translation, so a translation always
// inherits them — Polylang's custom-field sync is off, so copying them would
// only happen once, when the translation is created.
function sp_source_post_id($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    if (function_exists('pll_get_post') && function_exists('pll_default_language')) {
        return pll_get_post($post_id, pll_default_language()) ?: $post_id;
    }
    return $post_id;
}

function sp_shared_field($name, $post_id = null) {
    return get_field($name, sp_source_post_id($post_id));
}

// ...and those fields are hidden when editing a translation, where they'd
// have no effect
const SP_SHARED_FIELD_KEYS = ['field_home_banner_photo', 'field_chi_siamo_banner_photo'];

add_filter('acf/prepare_field', function($field) {
    if (!in_array($field['key'] ?? '', SP_SHARED_FIELD_KEYS, true)) return $field;

    global $post;
    if ($post && function_exists('pll_get_post_language') && function_exists('pll_default_language')) {
        $lang = pll_get_post_language($post->ID);
        if ($lang && $lang !== pll_default_language()) return false;
    }
    return $field;
});

// --- Interventi: language-independent data comes from the Italian intervento --
// Cover, project data, destinazione d'uso, services, articoli and gallery are
// the same in every language: an English intervento reads them from its
// Italian original (so editing the Italian one updates both), and its editor
// shows only what is actually translated (title and text).

const SP_INTERVENTI_SHARED_KEYS = [
    'field_interventi_copertina',
    'field_interventi_anno_inizio',
    'field_interventi_anno_fine',
    'field_interventi_posizione',
    'field_interventi_destinazione_uso',
    'field_interventi_committenza',
    'field_interventi_team',
    'field_interventi_budget',
    'field_interventi_categoria',
    'field_interventi_servizi',
    'field_interventi_articoli',
];

// the Italian original of a translated intervento, or null if $post_id is
// already the original (or not an intervento)
function sp_intervento_source($post_id) {
    $post_id = (int) $post_id;
    if (!$post_id || get_post_type($post_id) !== 'interventi') return null;
    $source = sp_source_post_id($post_id);
    return ($source && $source !== $post_id) ? (int) $source : null;
}

// get_field() & co. on a translation: answer with the original's raw value
// (ACF then formats it as usual)
add_filter('acf/pre_load_value', function($value, $post_id, $field) {
    if (!in_array($field['key'], SP_INTERVENTI_SHARED_KEYS, true)) return $value;
    $source = sp_intervento_source($post_id);
    if (!$source) return $value;

    if ($field['type'] === 'taxonomy') {
        $terms = wp_get_post_terms($source, $field['taxonomy'], ['fields' => 'ids']);
        return is_wp_error($terms) ? [] : $terms;
    }
    return get_post_meta($source, $field['name'], true);
}, 10, 3);

// the gallery (custom meta box, not ACF) and destinazione d'uso terms, for templates
function sp_intervento_galleria($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $ids = get_post_meta(sp_intervento_source($post_id) ?: $post_id, 'galleria', true);
    return is_array($ids) ? $ids : [];
}

function sp_intervento_terms($taxonomy, $post_id = null, $args = []) {
    $post_id = $post_id ?: get_the_ID();
    $terms = wp_get_post_terms(sp_intervento_source($post_id) ?: $post_id, $taxonomy, $args);
    return is_wp_error($terms) ? [] : $terms;
}

// English editor: hide the shared fields and the gallery, point to the original
function sp_editing_intervento_translation() {
    global $post;
    return ($post && sp_intervento_source($post->ID)) ? sp_intervento_source($post->ID) : null;
}

add_filter('acf/prepare_field', function($field) {
    if (in_array($field['key'] ?? '', SP_INTERVENTI_SHARED_KEYS, true) && sp_editing_intervento_translation()) return false;
    return $field;
});

add_action('add_meta_boxes_interventi', function($post) {
    if (sp_intervento_source($post->ID)) remove_meta_box('sp_galleria', 'interventi', 'normal');
}, 20);

add_action('edit_form_after_title', function($post) {
    $source = sp_intervento_source($post->ID);
    if (!$source) return;
    ?>
    <div class="notice notice-info inline" style="margin:16px 0 0;">
        <p>Copertina, dati, servizi, articoli e galleria sono gli stessi in tutte le lingue: si modificano
            nell'<a href="<?php echo esc_url(get_edit_post_link($source)); ?>">intervento italiano</a>.
            Qui si traducono solo titolo e testo.</p>
    </div>
    <style>#acf-group_interventi_cover, #acf-group_interventi { display: none; }</style>
    <?php
});
