<?php

if (!defined('ABSPATH')) exit;

// --- Sitemap ----------------------------------------------------------------------
// only pages and interventi

add_filter('wp_sitemaps_post_types', function($post_types) {
    unset($post_types['articoli'], $post_types['pubblicazioni']);
    return $post_types;
});

add_filter('wp_sitemaps_taxonomies', function($taxonomies) {
    unset($taxonomies['destinazione_uso']);
    return $taxonomies;
});

add_filter('wp_sitemaps_add_provider', function($provider, $name) {
    return $name === 'users' ? false : $provider;
}, 10, 2);

// --- Pages outside the site ------------------------------------------------------
// noindex on articoli, pubblicazioni and destinazione archives; author pages
// redirect home

add_filter('wp_robots', function($robots) {
    if (is_singular(['articoli', 'pubblicazioni']) || is_tax('destinazione_uso')) {
        $robots['noindex'] = true;
        $robots['follow']  = true;
    }
    return $robots;
});

add_action('template_redirect', function() {
    if (is_author()) {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }
}, 1); // before redirect_canonical

// --- SEO fields -------------------------------------------------------------------
// meta title/description per page and intervento; archive fields under
// Interventi > SEO elenco

const SP_SEO_BRAND = 'SPIRA srl';

add_action('acf/init', function() {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'      => 'group_seo',
        'title'    => 'SEO',
        'fields'   => [
            [
                'key'          => 'field_seo_title',
                'name'         => 'seo_title',
                'label'        => 'Meta title',
                'type'         => 'text',
                'instructions' => 'Titolo nei risultati di Google e nelle condivisioni. Massimo 60 caratteri circa.',
            ],
            [
                'key'          => 'field_seo_description',
                'name'         => 'seo_description',
                'label'        => 'Meta description',
                'type'         => 'textarea',
                'rows'         => 3,
                'new_lines'    => '',
                'instructions' => 'Testo sotto il titolo nei risultati di Google. Massimo 155 caratteri circa.',
            ],
        ],
        'location' => [
            [['param' => 'post_type', 'operator' => '==', 'value' => 'page']],
            [['param' => 'post_type', 'operator' => '==', 'value' => 'interventi']],
        ],
        'menu_order' => 100,
        'position'   => 'normal',
    ]);

    acf_add_local_field_group([
        'key'      => 'group_seo_image',
        'title'    => 'SEO – immagine di condivisione',
        'fields'   => [
            [
                'key'           => 'field_seo_image',
                'name'          => 'seo_image',
                'label'         => 'Immagine',
                'type'          => 'image',
                'return_format' => 'id',
                'preview_size'  => 'medium',
                'instructions'  => 'Mostrata quando la pagina viene condivisa (Facebook, LinkedIn, WhatsApp, X). Ideale 1200 × 630 px.',
            ],
        ],
        'location' => [
            [['param' => 'post_type', 'operator' => '==', 'value' => 'page']],
        ],
        'menu_order' => 101,
        'position'   => 'normal',
    ]);
});

// 1200×630 crop for share previews (Facebook/LinkedIn/X recommended size)
add_action('after_setup_theme', function() {
    add_image_size('sp-og', 1200, 630, true);
});

// --- Interventi archive SEO (Interventi > SEO elenco) ------------------------------

function sp_seo_archive_option($key, $lang = null) {
    $lang = $lang ?: (function_exists('pll_current_language') ? pll_current_language() : 'it');
    return trim((string) get_option("sp_seo_interventi_{$key}_{$lang}", ''));
}

add_action('admin_init', function() {
    $langs = function_exists('pll_languages_list') ? pll_languages_list() : ['it'];
    foreach ($langs as $lang) {
        register_setting('sp_seo_archive', "sp_seo_interventi_title_{$lang}", ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('sp_seo_archive', "sp_seo_interventi_description_{$lang}", ['sanitize_callback' => 'sanitize_text_field']);
    }
    register_setting('sp_seo_archive', 'sp_seo_interventi_image', ['sanitize_callback' => 'absint']);
});

add_filter('option_page_capability_sp_seo_archive', function() { return 'edit_pages'; });

add_action('admin_menu', function() {
    add_submenu_page('edit.php?post_type=interventi', 'SEO elenco interventi', 'SEO elenco', 'edit_pages', 'sp-seo-interventi', 'sp_render_seo_archive_page');
});

add_action('admin_enqueue_scripts', function($hook) {
    if ($hook === 'interventi_page_sp-seo-interventi') wp_enqueue_media();
});

function sp_render_seo_archive_page() {
    $langs = function_exists('pll_languages_list') ? pll_languages_list(['fields' => '']) : [];
    $image = (int) get_option('sp_seo_interventi_image');
    ?>
    <div class="wrap">
        <h1>SEO elenco interventi</h1>
        <p>Meta title, description e immagine di condivisione della pagina Interventi.</p>
        <form method="post" action="options.php">
            <?php settings_fields('sp_seo_archive'); ?>
            <table class="form-table" role="presentation">
                <?php foreach ($langs as $l) : $s = $l->slug; ?>
                    <tr>
                        <th scope="row"><label for="sp-seo-t-<?php echo esc_attr($s); ?>">Meta title (<?php echo esc_html($l->name); ?>)</label></th>
                        <td><input type="text" class="large-text" id="sp-seo-t-<?php echo esc_attr($s); ?>" name="sp_seo_interventi_title_<?php echo esc_attr($s); ?>" value="<?php echo esc_attr(get_option("sp_seo_interventi_title_{$s}")); ?>">
                            <p class="description">Massimo 60 caratteri circa.</p></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sp-seo-d-<?php echo esc_attr($s); ?>">Meta description (<?php echo esc_html($l->name); ?>)</label></th>
                        <td><textarea class="large-text" rows="3" id="sp-seo-d-<?php echo esc_attr($s); ?>" name="sp_seo_interventi_description_<?php echo esc_attr($s); ?>"><?php echo esc_textarea(get_option("sp_seo_interventi_description_{$s}")); ?></textarea>
                            <p class="description">Massimo 155 caratteri circa.</p></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <th scope="row">Immagine di condivisione</th>
                    <td>
                        <div id="sp-seo-image-preview" style="margin-bottom:8px;"><?php if ($image) echo wp_get_attachment_image($image, 'medium'); ?></div>
                        <input type="hidden" id="sp-seo-image" name="sp_seo_interventi_image" value="<?php echo esc_attr($image ?: ''); ?>">
                        <button type="button" class="button" id="sp-seo-image-select">Scegli immagine</button>
                        <button type="button" class="button-link-delete" id="sp-seo-image-remove" style="margin-left:8px;<?php echo $image ? '' : 'display:none;'; ?>">Rimuovi</button>
                        <p class="description">Mostrata quando la pagina viene condivisa. Ideale 1200 × 630 px.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <script>
    jQuery(function($) {
        var frame;
        $('#sp-seo-image-select').on('click', function() {
            if (!frame) {
                frame = wp.media({ title: 'Immagine di condivisione', library: { type: 'image' }, multiple: false });
                frame.on('select', function() {
                    var a = frame.state().get('selection').first().toJSON();
                    var src = (a.sizes && a.sizes.medium ? a.sizes.medium : a).url;
                    $('#sp-seo-image').val(a.id);
                    $('#sp-seo-image-preview').html($('<img>', { src: src, style: 'max-width:300px;height:auto;' }));
                    $('#sp-seo-image-remove').show();
                });
            }
            frame.open();
        });
        $('#sp-seo-image-remove').on('click', function() {
            $('#sp-seo-image').val('');
            $('#sp-seo-image-preview').empty();
            $(this).hide();
        });
    });
    </script>
    <?php
}

// --- What the current page says about itself ------------------------------------

function sp_seo_clean($text) {
    return trim(preg_replace('/\s+/', ' ', wp_strip_all_tags((string) $text)));
}

function sp_seo_data() {
    static $data = null;
    if ($data !== null) return $data;

    $data = ['title' => '', 'description' => '', 'image' => 0, 'url' => '', 'type' => 'website'];

    if (is_singular(['page', 'interventi'])) {
        $id = get_queried_object_id();
        $data['title']       = sp_seo_clean(get_field('seo_title', $id)) ?: sp_seo_clean(get_the_title($id)) . ' | ' . SP_SEO_BRAND;
        $data['description'] = sp_seo_clean(get_field('seo_description', $id));
        $data['url']         = is_front_page() && function_exists('pll_home_url') ? pll_home_url() : wp_get_canonical_url($id);

        if (get_post_type($id) === 'interventi') {
            $cover = get_field('copertina', $id); // from the Italian original
            $data['image'] = is_array($cover) ? (int) ($cover['ID'] ?? 0) : (int) $cover;
            $data['type']  = 'article';
        } else {
            $data['image'] = (int) (sp_shared_field('seo_image', $id) ?: 0);
            if (!$data['image']) {
                $banner = sp_shared_field('banner_photo', $id);
                $data['image'] = is_array($banner) ? (int) ($banner['ID'] ?? 0) : (int) $banner;
            }
        }
    } elseif (is_post_type_archive('interventi')) {
        $data['title']       = sp_seo_archive_option('title') ?: post_type_archive_title('', false) . ' | ' . SP_SEO_BRAND;
        $data['description'] = sp_seo_archive_option('description');
        $data['image']       = (int) get_option('sp_seo_interventi_image');
        $data['url']         = get_post_type_archive_link('interventi'); // also for ?servizio= (noindex)
    }

    // last resort for the share image: the home page's
    if (!$data['image'] && ($home = (int) get_option('page_on_front'))) {
        $data['image'] = (int) (get_field('seo_image', $home) ?: 0);
    }

    return $data;
}

add_filter('pre_get_document_title', function($title) {
    $seo = sp_seo_data();
    return $seo['title'] ?: $title;
});

// --- Meta tags: Google, Open Graph (Facebook, LinkedIn, WhatsApp), X ----------------

add_action('wp_head', function() {
    $seo = sp_seo_data();
    if (!$seo['title']) return;

    $locale  = function_exists('pll_current_language') ? pll_current_language('locale') : get_locale();
    $locales = [];
    if (function_exists('pll_languages_list')) {
        foreach (pll_languages_list(['fields' => 'locale']) as $l) if ($l !== $locale) $locales[] = $l;
    }

    $tags = [];
    if ($seo['description']) $tags[] = ['name', 'description', $seo['description']];

    $tags[] = ['property', 'og:site_name', SP_SEO_BRAND];
    $tags[] = ['property', 'og:type', $seo['type']];
    $tags[] = ['property', 'og:title', $seo['title']];
    if ($seo['description']) $tags[] = ['property', 'og:description', $seo['description']];
    if ($seo['url'])         $tags[] = ['property', 'og:url', $seo['url']];
    $tags[] = ['property', 'og:locale', $locale];
    foreach ($locales as $l) $tags[] = ['property', 'og:locale:alternate', $l];

    $tags[] = ['name', 'twitter:card', $seo['image'] ? 'summary_large_image' : 'summary'];
    $tags[] = ['name', 'twitter:title', $seo['title']];
    if ($seo['description']) $tags[] = ['name', 'twitter:description', $seo['description']];

    if ($seo['image'] && ($src = wp_get_attachment_image_src($seo['image'], 'sp-og'))) {
        $alt = sp_seo_clean(get_post_meta($seo['image'], '_wp_attachment_image_alt', true));
        $tags[] = ['property', 'og:image', $src[0]];
        $tags[] = ['property', 'og:image:width', $src[1]];
        $tags[] = ['property', 'og:image:height', $src[2]];
        if ($mime = get_post_mime_type($seo['image'])) $tags[] = ['property', 'og:image:type', $mime];
        if ($alt) $tags[] = ['property', 'og:image:alt', $alt];
        $tags[] = ['name', 'twitter:image', $src[0]];
        if ($alt) $tags[] = ['name', 'twitter:image:alt', $alt];
    }

    echo "\n<!-- SEO (theme) -->\n";
    foreach ($tags as [$attr, $key, $value]) {
        printf('<meta %s="%s" content="%s">' . "\n", $attr, esc_attr($key), esc_attr($value));
    }

    // Google: who the site belongs to, on the home page
    if (is_front_page()) {
        $home = function_exists('pll_home_url') ? pll_home_url() : home_url('/');
        $org  = array_filter([
            '@type'     => 'Organization',
            '@id'       => home_url('/#organization'),
            'name'      => SP_SEO_BRAND,
            'url'       => home_url('/'),
            'logo'      => get_stylesheet_directory_uri() . '/assets/favicons/apple-touch-icon.png',
            'email'     => get_option('sp_contatti_email') ?: null,
            'telephone' => get_option('sp_contatti_telefono') ?: null,
            'address'   => get_option('sp_contatti_indirizzo') ?: null,
            'image'     => $seo['image'] ? wp_get_attachment_image_url($seo['image'], 'sp-og') : null,
        ]);
        $site = [
            '@type'      => 'WebSite',
            '@id'        => $home . '#website',
            'name'       => SP_SEO_BRAND,
            'url'        => $home,
            'inLanguage' => str_replace('_', '-', $locale),
            'publisher'  => ['@id' => home_url('/#organization')],
        ];
        echo '<script type="application/ld+json">' . wp_json_encode(['@context' => 'https://schema.org', '@graph' => [$org, $site]], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "</script>\n";
    }
}, 1);
