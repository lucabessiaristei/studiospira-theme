<?php

require_once get_stylesheet_directory() . '/inc/cpt-interventi.php';
require_once get_stylesheet_directory() . '/inc/acf-interventi.php';
require_once get_stylesheet_directory() . '/inc/cpt-pubblicazioni.php';
require_once get_stylesheet_directory() . '/inc/acf-pubblicazioni.php';
require_once get_stylesheet_directory() . '/inc/cpt-articoli.php';
require_once get_stylesheet_directory() . '/inc/acf-articoli.php';
require_once get_stylesheet_directory() . '/inc/acf-pages.php';
require_once get_stylesheet_directory() . '/inc/acf-home.php';
require_once get_stylesheet_directory() . '/inc/interventi-evidenza.php';
require_once get_stylesheet_directory() . '/inc/acf-chi-siamo.php';
require_once get_stylesheet_directory() . '/inc/footer-settings.php';
require_once get_stylesheet_directory() . '/inc/contact-form.php';
require_once get_stylesheet_directory() . '/inc/polylang-strings.php';
require_once get_stylesheet_directory() . '/inc/i18n-fields.php';
require_once get_stylesheet_directory() . '/inc/template-tags.php';
require_once get_stylesheet_directory() . '/inc/user-roles.php';
require_once get_stylesheet_directory() . '/inc/editor-restrictions.php';
// site-lockdown.php disabled — the site is public again; re-add this line to reinstate the maintenance-mode gate

add_action('after_setup_theme', function() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails', ['post', 'pubblicazioni']);
    add_theme_support('custom-logo');
    add_image_size('logo', 200, 50);
    add_theme_support('html5', ['search-form', 'comment-form', 'gallery', 'caption']);

    register_nav_menus([
        'primary' => 'Primary Menu',
    ]);
});

// derived from the current domain (rather than hardcoded) so it keeps working
// once the site moves from the lucabessiaristei.it subdomain to studiospira.it
add_filter('wp_mail_from', function() {
    return 'contact@' . wp_parse_url(home_url(), PHP_URL_HOST);
});

add_filter('wp_mail_from_name', function() {
    return 'Studio Spira';
});

// a Polylang translation gets its own slug (e.g. "contact" for "contatti"),
// so a plain slug check misses it — this also matches any of its sibling
// translations against $slug
function sp_post_slug_is($slug, $post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    if (!$post_id) return false;

    if (get_post_field('post_name', $post_id) === $slug) return true;

    if (function_exists('pll_get_post') && function_exists('pll_languages_list')) {
        foreach (pll_languages_list() as $lang) {
            $translated_id = pll_get_post($post_id, $lang);
            if ($translated_id && get_post_field('post_name', $translated_id) === $slug) {
                return true;
            }
        }
    }

    return false;
}

// resolves a hardcoded Italian slug (used as a stable anchor) to that page's
// URL in the CURRENT language — e.g. sp_page_url('contatti') returns
// /contatti/ for an Italian visitor but /contact/ for an English one
function sp_page_url($it_slug) {
    $page = get_page_by_path($it_slug);
    if (!$page) return home_url('/' . $it_slug);

    if (function_exists('pll_get_post')) {
        $translated_id = pll_get_post($page->ID);
        if ($translated_id) $page = get_post($translated_id);
    }

    return get_permalink($page);
}

function sp_logo($variant = 'black') {
    $file = ($variant === 'white') ? 'spira-logo-bianco-thick.svg' : 'spira-logo-colore-thick.svg';
    $url = get_stylesheet_directory_uri() . '/assets/logo/' . $file;
    return '<a href="' . esc_url(home_url('/')) . '" class="site-logo site-logo-' . esc_attr($variant) . '">'
         . '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_bloginfo('name')) . '">'
         . '</a>';
}


add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Familjen+Grotesk:ital,wght@0,400..700;1,400..700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'theme-main',
        get_stylesheet_directory_uri() . '/dist/main.css',
        ['google-fonts'],
        filemtime(get_stylesheet_directory() . '/dist/main.css')
    );

    wp_enqueue_style(
        'theme-style',
        get_stylesheet_directory_uri() . '/dist/style.css',
        ['theme-main'],
        filemtime(get_stylesheet_directory() . '/dist/style.css')
    );

    wp_enqueue_script(
        'theme-main-js',
        get_stylesheet_directory_uri() . '/main.js',
        [],
        filemtime(get_stylesheet_directory() . '/main.js'),
        true
    );
});
