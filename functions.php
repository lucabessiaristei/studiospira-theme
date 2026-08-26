<?php

require_once get_stylesheet_directory() . '/inc/cpt-interventi.php';
require_once get_stylesheet_directory() . '/inc/acf-interventi.php';
require_once get_stylesheet_directory() . '/inc/cpt-pubblicazioni.php';
require_once get_stylesheet_directory() . '/inc/acf-pubblicazioni.php';
require_once get_stylesheet_directory() . '/inc/cpt-articoli.php';
require_once get_stylesheet_directory() . '/inc/acf-articoli.php';
require_once get_stylesheet_directory() . '/inc/acf-pages.php';
require_once get_stylesheet_directory() . '/inc/acf-home.php';
require_once get_stylesheet_directory() . '/inc/acf-chi-siamo.php';
require_once get_stylesheet_directory() . '/inc/footer-settings.php';
require_once get_stylesheet_directory() . '/inc/template-tags.php';
require_once get_stylesheet_directory() . '/inc/user-roles.php';
require_once get_stylesheet_directory() . '/inc/editor-restrictions.php';

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

function sp_logo($variant = 'black') {
    $file = ($variant === 'white') ? 'spira-logo-bianco-thick.svg' : 'spira-logo-nero-thick.svg';
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
