<?php

if (!defined('ABSPATH')) exit;

add_filter('tiny_mce_before_init', function($settings) {
    global $post;
    if (!$post) return $settings;

    $is_interventi = ($post->post_type === 'interventi');
    $is_chi_siamo  = ($post->post_type === 'page' && get_post_field('post_name', $post->ID) === 'chi-siamo');

    if ($is_interventi || $is_chi_siamo) {
        $settings['block_formats'] = 'Paragrafo=p;Titolo 3=h3';
    }

    return $settings;
});

// Contatti's template renders a contact form, not the_content() — the
// editor box has nothing to do there
add_action('admin_init', function() {
    global $pagenow;
    if (!in_array($pagenow, ['post.php', 'post-new.php'], true)) return;

    $post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
    if (!$post_id) return;
    if (get_post_type($post_id) !== 'page') return;
    if (get_post_field('post_name', $post_id) !== 'contatti') return;

    remove_post_type_support('page', 'editor');
});
