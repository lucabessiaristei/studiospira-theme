<?php

// "Interventi in evidenza" — the home's featured grid, picked from its own
// admin page (always in the sidebar, right under Interventi) instead of a
// field buried in the home page editor. Stored once as Italian intervento IDs
// in the sp_interventi_evidenza option; each language's home swaps in the
// matching translation. Needs only edit_pages, so the "Redattore contenuti"
// role (see user-roles.php) can use it too.

if (!defined('ABSPATH')) exit;

const SP_EVIDENZA_MAX = 6;

// the chosen Italian intervento IDs, in order
function sp_get_interventi_evidenza_ids() {
    $ids = get_option('sp_interventi_evidenza', false);

    // not saved from the new page yet: fall back to the old home ACF field
    if ($ids === false) {
        $front_id = (int) get_option('page_on_front');
        if (function_exists('pll_get_post') && function_exists('pll_default_language')) {
            $front_id = pll_get_post($front_id, pll_default_language()) ?: $front_id;
        }
        $ids = get_post_meta($front_id, 'interventi_evidenza', true);
    }

    return array_values(array_filter(array_map('intval', (array) $ids)));
}

// featured interventi in the current language, in the chosen order
function sp_get_interventi_evidenza() {
    $posts = [];
    foreach (sp_get_interventi_evidenza_ids() as $id) {
        if (function_exists('pll_get_post')) {
            $id = pll_get_post($id) ?: $id; // no translation yet: show the original
        }
        $post = get_post($id);
        if ($post && $post->post_status === 'publish') $posts[] = $post;
    }

    return array_slice($posts, 0, SP_EVIDENZA_MAX);
}

add_action('admin_menu', function() {
    add_menu_page(
        'Interventi in evidenza',
        'In evidenza',
        'edit_pages',
        'interventi-evidenza',
        'sp_render_interventi_evidenza_page',
        'dashicons-star-filled',
        '26.5' // right after the Interventi CPT (26)
    );
});

add_action('admin_init', function() {
    register_setting('sp_evidenza', 'sp_interventi_evidenza', [
        'sanitize_callback' => function($ids) {
            $ids = array_values(array_unique(array_filter(array_map('intval', (array) $ids))));
            $ids = array_filter($ids, function($id) { return get_post_type($id) === 'interventi'; });
            return array_slice(array_values($ids), 0, SP_EVIDENZA_MAX);
        },
    ]);
});

// options.php requires manage_options by default — let editors save this one
add_filter('option_page_capability_sp_evidenza', function() {
    return 'edit_pages';
});

add_action('admin_enqueue_scripts', function($hook) {
    if ($hook === 'toplevel_page_interventi-evidenza') wp_enqueue_script('jquery-ui-sortable');
});

function sp_render_interventi_evidenza_page() {
    if (!current_user_can('edit_pages')) return;

    $selected = sp_get_interventi_evidenza_ids();

    $all = get_posts([
        'post_type'      => 'interventi',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'lang'           => function_exists('pll_default_language') ? pll_default_language() : '',
    ]);

    // chosen ones first, in their saved order, then the rest alphabetically
    usort($all, function($a, $b) use ($selected) {
        $ia = array_search($a->ID, $selected, true);
        $ib = array_search($b->ID, $selected, true);
        if ($ia !== false && $ib !== false) return $ia - $ib;
        if ($ia !== false) return -1;
        if ($ib !== false) return 1;
        return strcasecmp($a->post_title, $b->post_title);
    });
    ?>
    <div class="wrap">
        <h1>Interventi in evidenza</h1>
        <p>Scegli fino a <?php echo SP_EVIDENZA_MAX; ?> interventi da mostrare nella griglia della home, e trascinali per cambiarne l'ordine.</p>

        <form method="post" action="options.php">
            <?php settings_fields('sp_evidenza'); ?>
            <input type="hidden" name="sp_interventi_evidenza[]" value=""><?php // saves an empty selection too ?>

            <p class="sp-evidenza-count"></p>
            <ul class="sp-evidenza-list">
                <?php foreach ($all as $post) : $checked = in_array($post->ID, $selected, true); ?>
                    <li class="<?php echo $checked ? 'is-checked' : ''; ?>">
                        <span class="dashicons dashicons-menu sp-evidenza-handle" aria-hidden="true"></span>
                        <label>
                            <input type="checkbox" name="sp_interventi_evidenza[]" value="<?php echo esc_attr($post->ID); ?>" <?php checked($checked); ?>>
                            <?php echo esc_html($post->post_title); ?>
                        </label>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?php submit_button('Salva'); ?>
        </form>
    </div>

    <style>
        .sp-evidenza-list { max-width: 720px; margin: 0; }
        .sp-evidenza-list li { display: flex; align-items: center; gap: 8px; margin: 0 0 4px; padding: 8px 10px; background: #fff; border: 1px solid #dcdcde; }
        .sp-evidenza-list li.is-checked { border-color: #2271b1; box-shadow: inset 3px 0 0 #2271b1; }
        .sp-evidenza-list label { flex: 1; }
        .sp-evidenza-handle { color: #8c8f94; cursor: move; }
        .sp-evidenza-list .ui-sortable-placeholder { visibility: visible !important; background: #f0f6fc; border: 1px dashed #2271b1; }
    </style>
    <script>
    jQuery(function($) {
        var max = <?php echo (int) SP_EVIDENZA_MAX; ?>, $list = $('.sp-evidenza-list');

        function refresh() {
            var n = $list.find('input:checked').length;
            $list.find('li').each(function() {
                var $cb = $(this).find('input');
                $(this).toggleClass('is-checked', $cb.prop('checked'));
                $cb.prop('disabled', !$cb.prop('checked') && n >= max);
            });
            $('.sp-evidenza-count').text(n + ' di ' + max + ' selezionati');
        }

        $list.sortable({ handle: '.sp-evidenza-handle', axis: 'y' });
        $list.on('change', 'input', refresh);
        refresh();
    });
    </script>
    <?php
}
