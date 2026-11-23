<?php

// Servizi are two levels deep (see cpt-interventi.php): a top-level servizio
// is a phase of the Servizi page, its children are the services listed in it.
// Both get a description; only the services get an icon. Not translated by
// Polylang: the English text sits in the "_en" fields (see i18n-fields.php).

if (!defined('ABSPATH')) exit;

add_action('acf/init', function() {
    if (!function_exists('acf_add_local_field_group')) return;

    $is_servizio = [[['field' => 'field_servizi_tipo', 'operator' => '==', 'value' => 'servizio']]];

    acf_add_local_field_group([
        'key'    => 'group_servizi',
        'title'  => 'Dati servizio',
        'fields' => [
            // "tipo" and "fase" are not stored: they are read from and written
            // to post_parent (see below), which stays the single source of truth
            [
                'key'           => 'field_servizi_tipo',
                'name'          => 'tipo',
                'label'         => 'Tipo',
                'type'          => 'button_group',
                'choices'       => ['fase' => 'Fase', 'servizio' => 'Servizio'],
                'default_value' => 'servizio',
                'layout'        => 'horizontal',
            ],
            [
                'key'               => 'field_servizi_fase',
                'name'              => 'fase',
                'label'             => 'Fase',
                'type'              => 'post_object',
                'post_type'         => ['servizi'],
                'return_format'     => 'id',
                'allow_null'        => 0,
                'multiple'          => 0,
                'ui'                => 1,
                'required'          => 1,
                'conditional_logic' => $is_servizio,
            ],
            [
                'key'   => 'field_servizi_titolo_en',
                'name'  => 'titolo_en',
                'label' => 'Titolo (EN)',
                'type'  => 'text',
            ],
            [
                'key'       => 'field_servizi_descrizione',
                'name'      => 'descrizione',
                'label'     => 'Descrizione',
                'type'      => 'textarea',
                'rows'      => 4,
                'new_lines' => '',
            ],
            [
                'key'       => 'field_servizi_descrizione_en',
                'name'      => 'descrizione_en',
                'label'     => 'Descrizione (EN)',
                'type'      => 'textarea',
                'rows'      => 4,
                'new_lines' => '',
            ],
            [
                // a Phosphor Light icon name (e.g. "ruler"), picked from the
                // visual grid added under the field (sp_servizi_icon_picker)
                'key'               => 'field_servizi_icona',
                'name'              => 'icona',
                'label'             => 'Icona',
                'type'              => 'text',
                'conditional_logic' => $is_servizio,
            ],
        ],
        'location' => [[
            ['param' => 'post_type', 'operator' => '==', 'value' => 'servizi'],
        ]],
        'menu_order'      => 0,
        'position'        => 'acf_after_title',
        'style'           => 'default',
        'label_placement' => 'top',
    ]);
});

// tipo/fase mirror post_parent: shown from it on load...
add_filter('acf/load_value/key=field_servizi_tipo', function($value, $post_id) {
    $post = get_post($post_id);
    if (!$post || $post->post_status === 'auto-draft') return 'servizio';
    return $post->post_parent ? 'servizio' : 'fase';
}, 10, 2);

add_filter('acf/load_value/key=field_servizi_fase', function($value, $post_id) {
    $post = get_post($post_id);
    return $post && $post->post_parent ? $post->post_parent : null;
}, 10, 2);

// ...never saved as meta (null skips the write)...
add_filter('acf/update_value/key=field_servizi_tipo', '__return_null');
add_filter('acf/update_value/key=field_servizi_fase', '__return_null');

// ...and written back to post_parent on save
add_action('acf/save_post', function($post_id) {
    if (get_post_type($post_id) !== 'servizi' || !isset($_POST['acf'])) return;

    $tipo   = $_POST['acf']['field_servizi_tipo'] ?? 'servizio';
    $parent = ($tipo === 'servizio') ? (int) ($_POST['acf']['field_servizi_fase'] ?? 0) : 0;
    if ($parent === (int) $post_id) $parent = 0;

    if ((int) get_post_field('post_parent', $post_id) !== $parent) {
        wp_update_post(['ID' => $post_id, 'post_parent' => $parent]);
    }
}, 5); // before ACF stores the other fields

// the Fase dropdown lists only phases
add_filter('acf/fields/post_object/query/key=field_servizi_fase', function($args, $field, $post_id) {
    $args['post_parent'] = 0;
    $args['post__not_in'] = [(int) $post_id];
    $args['orderby'] = ['menu_order' => 'ASC', 'title' => 'ASC'];
    return $args;
}, 10, 3);

// classic editor for servizi: there is no body text anyway, and it keeps the
// Tipo switch the only place to set the hierarchy
add_filter('use_block_editor_for_post_type', function($use, $post_type) {
    return $post_type === 'servizi' ? false : $use;
}, 10, 2);

// hide the native parent picker in "Attributi", keep only "Ordine"
add_action('admin_head-post.php', 'sp_servizi_hide_parent_picker');
add_action('admin_head-post-new.php', 'sp_servizi_hide_parent_picker');
function sp_servizi_hide_parent_picker() {
    if (get_current_screen()->post_type !== 'servizi') return;
    echo '<style>#pageparentdiv .parent-id-label-wrapper, #pageparentdiv #parent_id { display: none; }</style>';
}

// top-level servizi (the phases), in "Ordine" order
function sp_get_servizi_fasi() {
    return get_posts([
        'post_type'        => 'servizi',
        'post_status'      => 'publish',
        'post_parent'      => 0,
        'orderby'          => ['menu_order' => 'ASC', 'title' => 'ASC'],
        'posts_per_page'   => -1,
    ]);
}

function sp_get_servizi_of_fase($fase_id) {
    return get_posts([
        'post_type'        => 'servizi',
        'post_status'      => 'publish',
        'post_parent'      => $fase_id,
        'orderby'          => ['menu_order' => 'ASC', 'title' => 'ASC'],
        'posts_per_page'   => -1,
    ]);
}

// services (children) of the given IDs, published only, in page order:
// by their phase's "Ordine", then their own. Phases and stray IDs are dropped.
function sp_sort_servizi($ids) {
    $servizi = [];
    foreach (array_filter(array_map('intval', (array) $ids)) as $id) { // no falsy IDs: get_post(0) = current post
        $post = get_post($id);
        if (!$post || $post->post_type !== 'servizi' || $post->post_status !== 'publish' || !$post->post_parent) continue;
        $fase = get_post($post->post_parent);
        if (!$fase || $fase->post_status !== 'publish') continue;
        $servizi[$post->ID] = [$fase->menu_order, $fase->post_title, $post->menu_order, $post->post_title, $post];
    }
    uasort($servizi, function($a, $b) {
        return [$a[0], $a[1], $a[2], $a[3]] <=> [$b[0], $b[1], $b[2], $b[3]];
    });
    return array_values(array_map(function($s) { return $s[4]; }, $servizi));
}

// the phases an intervento belongs to, derived from its ticked services
function sp_intervento_fasi($intervento_id) {
    $fasi = [];
    foreach (sp_sort_servizi(get_field('servizi', $intervento_id)) as $servizio) {
        $fasi[$servizio->post_parent] = get_post($servizio->post_parent);
    }
    return array_values($fasi);
}

// intervento editor: services as checkboxes, grouped under their phase
add_filter('acf/load_field/key=field_interventi_servizi', function($field) {
    $field['choices'] = [];
    foreach (sp_get_servizi_fasi() as $fase) {
        $children = [];
        foreach (sp_get_servizi_of_fase($fase->ID) as $servizio) {
            $children[$servizio->ID] = $servizio->post_title;
        }
        if ($children) $field['choices'][$fase->post_title] = $children;
    }
    return $field;
});

// only administrators add or delete servizi; editors can still edit them
add_filter('register_post_type_args', function($args, $post_type) {
    if ($post_type !== 'servizi') return $args;
    $args['capability_type'] = 'post';
    $args['map_meta_cap']    = true;
    $args['capabilities']    = [
        'create_posts'           => 'manage_options',
        'delete_posts'           => 'manage_options',
        'delete_published_posts' => 'manage_options',
        'delete_others_posts'    => 'manage_options',
        'delete_private_posts'   => 'manage_options',
    ];
    return $args;
}, 10, 2);

// --- Example interventi on the Servizi page (up to 3 per phase) ----------------
// One relationship field per phase, generated from the phases themselves so a
// new phase gets its field automatically. Stored on the Servizi page, which
// redattori can edit, and kept identical across its translations.

const SP_ESEMPI_MAX = 3;

function sp_servizi_page_ids() {
    $page = get_page_by_path('servizi');
    if (!$page) return [];
    if (function_exists('pll_get_post_translations')) {
        return array_values(pll_get_post_translations($page->ID)) ?: [$page->ID];
    }
    return [$page->ID];
}

add_action('acf/init', function() {
    if (!function_exists('acf_add_local_field_group')) return;

    $page_ids = sp_servizi_page_ids();
    if (!$page_ids) return;

    $fields = [];
    foreach (sp_get_servizi_fasi() as $fase) {
        $fields[] = [
            'key'           => 'field_servizi_esempi_' . $fase->ID,
            'name'          => 'esempi_fase_' . $fase->ID,
            'label'         => $fase->post_title,
            'type'          => 'relationship',
            'post_type'     => ['interventi'],
            'filters'       => ['search'],
            'return_format' => 'id',
            'max'           => SP_ESEMPI_MAX,
        ];
    }
    if (!$fields) return;

    acf_add_local_field_group([
        'key'          => 'group_servizi_esempi',
        'title'        => 'Interventi di esempio',
        'instructions' => '',
        'fields'       => $fields,
        'location'     => array_map(function($id) {
            return [['param' => 'page', 'operator' => '==', 'value' => (string) $id]];
        }, $page_ids),
        'menu_order'      => 10,
        'position'        => 'normal',
        'style'           => 'default',
        'label_placement' => 'top',
        'description'     => '',
    ]);
}, 20); // after the CPT and Polylang are ready

// the client's hint, once above the fields
add_filter('acf/prepare_field', function($field) {
    static $done = false;
    if (!$done && strpos($field['key'] ?? '', 'field_servizi_esempi_') === 0) {
        $done = true;
        $field['instructions'] = 'Scegli interventi diversi per ogni fase, quelli in cui il servizio è più evidente.';
    }
    return $field;
});

// query args for the interventi with at least one service of the phase.
// Always the Italian originals: services are stored on them (translations read
// them from there, see i18n-fields.php), and the saved picks are Italian IDs,
// swapped for their translation on display
function sp_esempi_query_args($fase_id, $page_id) {
    $children = wp_list_pluck(sp_get_servizi_of_fase($fase_id), 'ID');

    $args = ['meta_query' => ['relation' => 'OR']];
    foreach ($children as $child_id) {
        $args['meta_query'][] = ['key' => 'servizi', 'value' => '"' . $child_id . '"', 'compare' => 'LIKE'];
    }
    if (!$children) $args['post__in'] = [0];

    if (function_exists('pll_default_language')) $args['lang'] = pll_default_language();
    return $args;
}

// each field lists only interventi that fit its phase
add_filter('acf/fields/relationship/query', function($args, $field, $post_id) {
    if (strpos($field['key'], 'field_servizi_esempi_') !== 0) return $args;
    $fase_id = (int) substr($field['key'], strlen('field_servizi_esempi_'));
    return array_merge($args, sp_esempi_query_args($fase_id, $post_id));
}, 10, 3);

// same selection in both languages (each ID swapped for its translation on display)
add_filter('pll_copy_post_metas', function($metas) {
    foreach (sp_get_servizi_fasi() as $fase) $metas[] = 'esempi_fase_' . $fase->ID;
    return $metas;
});

// the example interventi chosen for a phase on the Servizi page, in the
// current language: published only, in the chosen order. None chosen = none
// shown (the page then keeps just the phase button)
function sp_get_esempi_fase($fase_id, $page_id = null) {
    $out = [];
    // an empty field is false/'' — never let it reach get_post(), which reads
    // a falsy ID as "the current post" (the Servizi page itself)
    $ids = array_filter(array_map('intval', (array) get_field('esempi_fase_' . $fase_id, $page_id ?: get_the_ID())));
    foreach ($ids as $id) {
        if (function_exists('pll_get_post')) $id = pll_get_post($id) ?: $id;
        $post = get_post($id);
        if ($post && $post->post_type === 'interventi' && $post->post_status === 'publish') $out[] = $post;
    }
    return array_slice($out, 0, SP_ESEMPI_MAX);
}

// lowercase only the first letter, for "Interventi di rilievo e diagnostica"
// (a full lowercase would also hit acronyms like "PA")
function sp_lcfirst($text) {
    return mb_strtolower(mb_substr($text, 0, 1)) . mb_substr($text, 1);
}

// slug box: WordPress hides it for post types without public URLs, but the
// phase slugs are the /interventi/?servizio=<slug> values and the Servizi page
// anchors, set from the handoff. Own ID (not "slugdiv") so it isn't hidden by
// default in Screen Options. Admins only, so the links stay stable.
add_action('add_meta_boxes_servizi', function($post) {
    if (!current_user_can('manage_options')) return;
    add_meta_box('sp_slugdiv', 'Slug', 'post_slug_meta_box', 'servizi', 'side', 'high');
});

// --- Phosphor Light icons ------------------------------------------------------
// The set is generated from @phosphor-icons/core by `npm run build:icons`
// (scripts/build-phosphor.mjs) into inc/phosphor-light.php (front end) and
// assets/js/phosphor-light.js (the picker below).

// inline SVG for a Phosphor Light icon name, '' if unknown. Colour follows
// the CSS color (fill="currentColor").
function sp_phosphor_svg($name, $class = '') {
    static $icons = null;
    if ($icons === null) $icons = include __DIR__ . '/phosphor-light.php';
    if (!$name || !isset($icons[$name])) return '';
    return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"'
        . ($class ? ' class="' . esc_attr($class) . '"' : '') . ' aria-hidden="true" focusable="false">'
        . $icons[$name] . '</svg>';
}

add_action('admin_enqueue_scripts', function($hook) {
    if (!in_array($hook, ['post.php', 'post-new.php'], true) || get_current_screen()->post_type !== 'servizi') return;
    $file = get_stylesheet_directory() . '/assets/js/phosphor-light.js';
    wp_enqueue_script('sp-phosphor-light', get_stylesheet_directory_uri() . '/assets/js/phosphor-light.js', [], filemtime($file), true);
});

// visual picker under the Icona field: current icon, search, clickable grid
add_action('acf/render_field/key=field_servizi_icona', function($field) {
    ?>
    <div class="sp-icon-picker">
        <div class="sp-icon-picker__current">
            <span class="sp-icon-picker__preview"><?php echo sp_phosphor_svg($field['value']); ?></span>
            <button type="button" class="button-link sp-icon-picker__clear">Rimuovi icona</button>
        </div>
        <input type="search" class="sp-icon-picker__search" placeholder="Cerca icona (in inglese, es. ruler, tools, house)">
        <div class="sp-icon-picker__grid" role="listbox" aria-label="Icone"></div>
    </div>
    <style>
        .acf-field[data-key="field_servizi_icona"] .acf-input-wrap input { max-width: 260px; font-family: monospace; }
        .sp-icon-picker { margin-top: 10px; }
        .sp-icon-picker__current { display: flex; align-items: center; gap: 12px; margin-bottom: 10px; }
        .sp-icon-picker__preview { display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; border: 1px solid #dcdcde; background: #fff; color: #7C1715; }
        .sp-icon-picker__preview svg { width: 48px; height: 48px; }
        .sp-icon-picker__search { width: 100%; max-width: 420px; margin-bottom: 8px; }
        .sp-icon-picker__grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(76px, 1fr)); gap: 4px; max-height: 340px; overflow-y: auto; padding: 4px; border: 1px solid #dcdcde; background: #fff; }
        .sp-icon-picker__item { display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 8px 2px; border: 1px solid transparent; background: none; cursor: pointer; color: #1d2327; }
        .sp-icon-picker__item svg { width: 32px; height: 32px; color: #7C1715; }
        .sp-icon-picker__item span { font-size: 10px; line-height: 1.2; text-align: center; word-break: break-word; color: #646970; }
        .sp-icon-picker__item:hover { border-color: #c3c4c7; }
        .sp-icon-picker__item.is-selected { border-color: #2271b1; box-shadow: inset 0 0 0 1px #2271b1; }
    </style>
    <script>
    (function () {
        var root = document.currentScript.previousElementSibling.previousElementSibling;
        var input = root.closest('.acf-input').querySelector('input[type="text"]');
        var grid = root.querySelector('.sp-icon-picker__grid');
        var search = root.querySelector('.sp-icon-picker__search');
        var preview = root.querySelector('.sp-icon-picker__preview');

        function svg(inner) {
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor" aria-hidden="true">' + inner + '</svg>';
        }

        function select(name, inner) {
            input.value = name;
            preview.innerHTML = inner ? svg(inner) : '';
            grid.querySelectorAll('.is-selected').forEach(function (b) { b.classList.remove('is-selected'); });
            var btn = name && grid.querySelector('[data-name="' + name + '"]');
            if (btn) btn.classList.add('is-selected');
        }

        function build() {
            var icons = window.SP_PHOSPHOR_LIGHT || [];
            var html = '';
            icons.forEach(function (icon) {
                html += '<button type="button" class="sp-icon-picker__item" role="option" data-name="' + icon[0] + '" data-search="' + (icon[0] + ' ' + icon[1]).toLowerCase() + '" title="' + icon[0] + '">'
                    + svg(icon[2]) + '<span>' + icon[0] + '</span></button>';
            });
            grid.innerHTML = html;
            select(input.value, (icons.find(function (i) { return i[0] === input.value; }) || [])[2]);
        }

        grid.addEventListener('click', function (e) {
            var btn = e.target.closest('.sp-icon-picker__item');
            if (!btn) return;
            var icon = window.SP_PHOSPHOR_LIGHT.find(function (i) { return i[0] === btn.dataset.name; });
            select(icon[0], icon[2]);
        });

        search.addEventListener('input', function () {
            var q = search.value.trim().toLowerCase();
            grid.querySelectorAll('.sp-icon-picker__item').forEach(function (b) {
                b.hidden = q && b.dataset.search.indexOf(q) === -1;
            });
        });

        // Enter in the search box must not submit the post form
        search.addEventListener('keydown', function (e) { if (e.key === 'Enter') e.preventDefault(); });

        root.querySelector('.sp-icon-picker__clear').addEventListener('click', function () { select('', ''); });

        if (window.SP_PHOSPHOR_LIGHT) build();
        else window.addEventListener('load', build);
    })();
    </script>
    <?php
});
